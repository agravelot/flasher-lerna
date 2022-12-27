package main

import (
	articlesgrpc "api-go/gen/go/proto/articles/v2"
	"api-go/infrastructure/keycloak"
	"bufio"
	"context"
	"fmt"
	"github.com/kr/pretty"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/metadata"
	"log"
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

func getPostFromFile(entry os.DirEntry) Post {
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
	conn, err := grpc.Dial("localhost:3100", grpc.WithTransportCredentials(insecure.NewCredentials()))
	if err != nil {
		log.Fatal(err)
	}

	// TODO extract in env
	url := "https://accounts.jkanda.fr"
	realm := "jkanda"
	clientID := "service-account"
	clientSecret := "2d5e4db4-9785-4919-9dbf-39964aebcc4f"

	kc, err := keycloak.New(url, realm, clientID, clientSecret)
	s := articlesgrpc.NewArticleServiceClient(conn)

	files, err := os.ReadDir(blogContentPath)
	if err != nil {
		panic(err)
	}

	for _, entry := range files {
		if entry.IsDir() {
			continue
		}

		if !strings.HasSuffix(entry.Name(), ".mdx") {
			continue
		}

		post := getPostFromFile(entry)

		ctx, cancel := context.WithTimeout(context.Background(), 100*time.Second)

		// TODO Fix defer in loop
		defer cancel()

		pretty.Log(post.Slug)
		data := articlesgrpc.CreateRequest{
			Slug:    post.Slug,
			Name:    post.Title,
			Content: post.Content,
			//PublishedAt:     post.CreatedAt,
			MetaDescription: post.MetaDescription,
		}

		at, err := kc.GetAccessToken()
		if err != nil {
			log.Fatal(fmt.Errorf("unable to get access token: %w", err))
		}

		ctx = metadata.NewOutgoingContext(ctx, metadata.Pairs("authorization", fmt.Sprintf("Bearer %s", at)))

		r, err := s.Create(ctx, &data)
		if err != nil {
			log.Fatal(fmt.Errorf("unable to create article: %w", err))
		}

		log.Println(r.Id)

	}
}
