import { readdirSync, readFileSync } from "fs";
import { join } from "path";
import matter from "gray-matter";
import { MDXRemoteSerializeResult } from "next-mdx-remote";

const postsDirectory = join(process.cwd(), "content", "blog");

export const getPostSlugs = (): string[] => {
  return readdirSync(postsDirectory);
};

export async function getPostBySlug(slug: string): Promise<BlogPost> {
  const realSlug = slug.replace(/\.mdx$/, "");
  const fullPath = join(postsDirectory, `${realSlug}.mdx`);
  const fileContents = readFileSync(fullPath, "utf8");
  const { data, content } = matter(fileContents);

  return {
    slug: realSlug,
    content,
    contentSerialized: null,
    title: data.title,
    author: data.author,
    status: data.status,
    metaDescription: data.metaDescription,
    createdAt: data.createdAt.toString(),
    updatedAt: null,
  };
}

export interface BlogPost {
  title: string;
  author: string;
  slug: string;
  content: string;
  contentSerialized: MDXRemoteSerializeResult | null;
  metaDescription: string;
  status: "published" | "draft";
  createdAt: string;
  updatedAt: string | null;
}

export async function getAllPosts(
  status: "all" | "published" = "all"
): Promise<BlogPost[]> {
  const slugs = getPostSlugs();
  const posts: BlogPost[] = [];

  for (const slug of slugs) {
    posts.push(await getPostBySlug(slug));
  }

  return posts
    .filter((p) => status === "all" || p.status === "published")
    .sort((post1, post2) =>
      new Date(post1.createdAt) > new Date(post2.createdAt) ? -1 : 1
    );
}
