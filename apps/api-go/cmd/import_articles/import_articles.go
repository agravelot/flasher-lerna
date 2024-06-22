package main

import (
	"api-go/config"
	"api-go/infrastructure/keycloak"
	"bufio"
	"context"
	"fmt"
	"log"
	"os"
	"slices"
	"strings"
	"time"

	articlesgrpc "api-go/gen/go/articles/v2"

	"google.golang.org/grpc"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/metadata"
	"google.golang.org/grpc/status"
	"google.golang.org/protobuf/types/known/timestamppb"
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

func processFile(entry os.DirEntry) (Post, error) {
	slug := strings.ReplaceAll(entry.Name(), ".mdx", "")

	file, err := os.Open(fmt.Sprintf("%s%s", blogContentPath, entry.Name()))
	if err != nil {
		return Post{}, fmt.Errorf("error opening file: %w", err)
	}

	defer func(file *os.File) {
		err := file.Close()
		if err != nil {
			log.Printf("error closing file: %w", err)
		}
	}(file)

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

		split := strings.SplitN(line, ":", 2)
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

		if key == "createdAt" {
			t, err := time.Parse(time.RFC3339, value)
			if err != nil {
				panic(fmt.Errorf("error parsing date: %s %s %s", value, slug, err))
			}
			post.CreatedAt = t
			continue
		}
	}

	return post, nil
}

func saveArticle(ctx context.Context, service articlesgrpc.ArticleServiceClient, p Post, token string) {
	log.Printf("article: %s", p.Slug)

	ctx = metadata.AppendToOutgoingContext(ctx, "authorization", "Bearer "+token)

	a, err := service.GetBySlug(ctx, &articlesgrpc.GetBySlugRequest{})
	if err != nil {
		st, ok := status.FromError(err)

		if !ok || st.Code() != codes.NotFound {
			panic(fmt.Errorf("error getting article: %s", err))
		}

		if a != nil && ok && st.Code() == codes.NotFound {
			_, err = service.Delete(ctx, &articlesgrpc.DeleteRequest{
				Id: int32(a.Id),
			})
			if err != nil {
				panic(fmt.Errorf("error deleting article: %s", err))
			}
		}
	}

	r := []rune(p.MetaDescription)
	l := len(r)
	if len(r) > 155 {
		l = 155
	}

	res, err := service.Create(ctx, &articlesgrpc.CreateRequest{
		Name:            p.Title,
		Content:         p.Content,
		Slug:            p.Slug,
		PublishedAt:     timestamppb.New(p.CreatedAt),
		MetaDescription: string(r[:l]),
	})
	if err != nil {
		st, ok := status.FromError(err)
		if ok && st.Code() == codes.AlreadyExists {
			return
		}
		panic(fmt.Errorf("error creating article: %s", err))
	}
	log.Println(res.Slug)
}

func main() {
	cfg, err := config.FromDotEnv(".env")
	if err != nil {
		log.Fatalf("error getting config: %s", err)
	}

	files, err := os.ReadDir(blogContentPath)
	if err != nil {
		panic(err)
	}

	posts := make([]Post, 0, len(files))

	for _, entry := range files {
		if entry.IsDir() {
			continue
		}

		if !strings.HasSuffix(entry.Name(), ".mdx") {
			return
		}

		p, err := processFile(entry)
		if err != nil {
			panic(fmt.Sprintf("error processing file: %s", err))
		}

		posts = append(posts, p)
	}

	slices.SortFunc(posts, func(a, b Post) int {
		if a.CreatedAt.Unix() == b.CreatedAt.Unix() {
			return 0
		}

		if a.CreatedAt.Unix() > b.CreatedAt.Unix() {
			return 1
		}

		return -1
	})

	log.Println("Importing articles...")

	kc, err := keycloak.New(cfg.Keycloak.Url, cfg.Keycloak.Realm, cfg.Keycloak.ServiceAccount.User, cfg.Keycloak.ServiceAccount.Password)
	if err != nil {
		panic(fmt.Errorf("error creating keycloak client: %s", err))
	}

	token, err := kc.GetAccessToken()
	if err != nil {
		panic(fmt.Errorf("error getting access token: %s", err))
	}

	// TODO Extract
	conn, err := grpc.Dial("localhost:3100", grpc.WithTransportCredentials(insecure.NewCredentials()))
	if err != nil {
		panic(fmt.Errorf("error creating grpc client: %s", err))
	}

	defer func() {
		err := conn.Close()
		if err != nil {
			panic(err)
		}
	}()

	service := articlesgrpc.NewArticleServiceClient(conn)

	ctx, cancel := context.WithTimeout(context.Background(), 20*time.Second)
	defer cancel()

	// TODO refresh token if needed ?
	for i := range posts {
		saveArticle(ctx, service, posts[i], token)
	}
}
