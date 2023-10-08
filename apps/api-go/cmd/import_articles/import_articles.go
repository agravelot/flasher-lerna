package main

import (
	articlesgrpc "api-go/gen/go/articles/v2"
	"api-go/infrastructure/keycloak"
	"bufio"
	"context"
	"fmt"
	"github.com/kr/pretty"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/metadata"
	"google.golang.org/protobuf/types/known/timestamppb"
	"log"
	"os"
	"strings"
	"time"
	"unicode"
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

func truncate(text string, maxLength int) string {
	lastSpaceIx := maxLength
	length := 0
	for i, r := range text {
		if unicode.IsSpace(r) {
			lastSpaceIx = i
		}
		length++
		if length > maxLength {
			return text[:lastSpaceIx] + "..."
		}
	}
	// If here, string is shorter or equal to maxLen
	return text
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
	clientSecret := "2cfb0a3f-2d88-4614-a81d-8bdd2f212f10"

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
			// TODO Add created at?
			PublishedAt: &timestamppb.Timestamp{
				Seconds: int64(post.CreatedAt.Second()),
				Nanos:   int32(post.CreatedAt.Nanosecond()),
			},
			MetaDescription: truncate(post.MetaDescription, 150),
		}

		at, err := kc.GetAccessToken()
		if err != nil {
			log.Fatal(fmt.Errorf("unable to get access token: %w", err))
		}

		ctx = metadata.NewOutgoingContext(ctx, metadata.Pairs("authorization", fmt.Sprintf("Bearer %s", at)))

		r, err := s.Create(ctx, &data)
		if err != nil {
			//if errors.As(err, &article.ErrAlreadyExists) {
			//	_, err := s.Update(ctx, &articlesgrpc.UpdateRequest{
			//		Slug:    post.Slug,
			//		Name:    post.Title,
			//		Content: post.Content,
			//		// TODO Add created at
			//		PublishedAt: &timestamppb.Timestamp{
			//			Seconds: int64(post.CreatedAt.Second()),
			//			Nanos:   int32(post.CreatedAt.Nanosecond()),
			//		},
			//		MetaDescription: truncate(post.MetaDescription, 150),
			//	})
			//	if err != nil {
			//		panic(err)
			//	}
			//	continue
			//}
			log.Fatal(fmt.Errorf("unable to create article: %w", err))
		}

		log.Println(r.Id)

	}
}
