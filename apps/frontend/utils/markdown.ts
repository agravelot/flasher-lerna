import { MDXRemoteSerializeResult } from "next-mdx-remote";
import { GrpcTransport } from "@protobuf-ts/grpc-transport";
import { ChannelCredentials } from "@grpc/grpc-js";
import { ArticleServiceClient } from "../gen/articles/v2/articles_pb.client";
import { ArticleResponse } from "../gen/articles/v2/articles_pb";
import { Timestamp } from "gen/google/protobuf/timestamp_pb";

export interface BlogPost {
  title: string;
  author: string;
  slug: string;
  content: string;
  contentSerialized: MDXRemoteSerializeResult | null;
  metaDescription: string;
  status: "published" | "draft";
  publishedAt: string | null;
  createdAt: string;
  updatedAt: string | null;
}

const transformArticle = (p: ArticleResponse) => {
  console.log(
    p.publishedAt ? Timestamp.toDate(p.publishedAt).toISOString() : null,
    p.publishedAt,
  );
  return {
    title: p.name,
    author: p.authorId, // TODO add name
    content: p.content,
    slug: p.slug,
    contentSerialized: null,
    metaDescription: p.metaDescription,
    status: p.publishedAt ? "published" : "draft",
    publishedAt: p.publishedAt
      ? Timestamp.toDate(p.publishedAt).toISOString()
      : null,
    createdAt: new Date().toString(), // TODO
    updatedAt: new Date().toString(),
  } satisfies BlogPost;
};

const grpcTransport = new GrpcTransport({
  host: "localhost:3100",
  channelCredentials: ChannelCredentials.createInsecure(),
});
const client = new ArticleServiceClient(grpcTransport);

export async function getPostBySlug(slug: string): Promise<BlogPost> {
  const posts = await client.getBySlug({ slug });

  return transformArticle(posts.response);
}

export async function getAllPosts(): Promise<BlogPost[]> {
  const posts = await client.index({
    limit: 100,
  });

  return posts.response.data.map(transformArticle);
}
