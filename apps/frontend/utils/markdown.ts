import fs from "fs";
import { join } from "path";
import matter from "gray-matter";
import { MdxRemote } from "next-mdx-remote/types";

const postsDirectory = join(process.cwd(), "blog");

export const getPostSlugs = (): string[] => {
  return fs.readdirSync(postsDirectory);
};

export function getPostBySlug(slug: string): BlogPost {
  const realSlug = slug.replace(/\.mdx$/, "");
  const fullPath = join(postsDirectory, `${realSlug}.mdx`);
  const fileContents = fs.readFileSync(fullPath, "utf8");
  const { data, content } = matter(fileContents);

  return {
    slug: realSlug,
    content,
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
  content: MdxRemote.Source | string;
  metaDescription: string;
  status: "published" | "draft";
  createdAt: string;
  updatedAt: string | null;
}

export function getAllPosts(status: "all" | "published" = "all"): BlogPost[] {
  const slugs = getPostSlugs();
  const posts = slugs
    .map((slug) => getPostBySlug(slug))
    .filter((p) => status === "all" || p.status === "published")
    .sort((post1, post2) => (post1.createdAt < post2.createdAt ? -1 : 1));

  return posts;
}
