package main

import (
	"bufio"
	"fmt"
	"github.com/kr/pretty"
	"os"
	"strings"
	"time"
)

type Post struct {
	Slug            string
	Title           string
	Author          string
	Published       bool
	MetaDescription string
	CreatedAt       time.Time
	Content         string
}

const blogContentPath = "../frontend/content/blog/"

func processFile(entry os.DirEntry) Post {
	slug := strings.ReplaceAll(entry.Name(), ".mdx", "")

	file, err := os.Open(fmt.Sprintf("%s%s", blogContentPath, entry.Name()))
	if err != nil {
		panic(err)
	}
	defer file.Close()

	scanner := bufio.NewScanner(file)
	beginHeaderRead := false
	endHeaderRead := false

	post := Post{Slug: slug}

	for scanner.Scan() {
		line := scanner.Text()
		isHeaderSeparator := strings.Contains(line, "---")

		if isHeaderSeparator && !beginHeaderRead {
			beginHeaderRead = true
			continue
		}

		if isHeaderSeparator && !endHeaderRead {
			endHeaderRead = true
			continue
		}

		if endHeaderRead {
			post.Content += line + "\n"
		}

		if !strings.Contains(line, ":") {
			continue
		}

		split := strings.Split(line, ":")
		key := split[0]
		value := strings.TrimSpace(split[1])

		if key == "title" {
			post.Title = value
			continue
		}

		if key == "author" {
			post.Author = value
			continue
		}

		if key == "status" {
			post.Published = value == "published"
			continue
		}

		if key == "metaDescription" {
			post.MetaDescription = value
			continue
		}

		if key == "cratedAt" {
			t, err := time.Parse(time.RFC3339, value)
			if err != nil {
				panic(err)
			}
			post.CreatedAt = t
			continue
		}
	}
	return post
}

func main() {

	files, err := os.ReadDir(blogContentPath)
	if err != nil {
		panic(err)
	}

	for _, entry := range files {
		if entry.IsDir() {
			continue
		}

		if !strings.HasSuffix(entry.Name(), ".mdx") {
			return
		}

		post := processFile(entry)

		pretty.Log(post)

	}
}
