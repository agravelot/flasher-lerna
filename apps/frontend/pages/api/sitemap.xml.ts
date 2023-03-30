import { join } from "path";
import { generateNextImageUrl } from "./../../utils/util";
import { apiRepository } from "./../../../common/src/useRepository";
import { NextApiRequest, NextApiResponse } from "next";
import { configuration } from "utils/configuration";
import { getAllPosts } from "utils/markdown";
import fs from "fs";
import { setBaseUrl } from "@flasher/common";

interface Image {
  loc: string;
}

interface Page {
  url: string;
  lastMod: string;
  changefreq: string;
  priority: string;
  images: Image[];
}

const prefix =
  // eslint-disable-next-line quotes
  '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
const suffix = "</urlset>";
const perPage = 10;

setBaseUrl(configuration.baseUrl);

const getGaleries = async (): Promise<Page[]> => {
  const repo = apiRepository();
  const pages: Page[] = [];
  for (
    let page = 0;
    page < (await repo.albums.list({ page: 1, perPage: 10 })).meta.last_page;
    page++
  ) {
    const galeries = await repo.albums.list({ page, perPage });

    for (const galerie of galeries.data) {
      pages.push({
        url: `/galerie/${galerie.slug}`,
        lastMod: galerie.updated_at ?? (galerie.published_at as string),
        changefreq: "monthly",
        priority: "0.8",
        images:
          galerie.medias?.map((m) => ({ loc: generateNextImageUrl(m.url) })) ??
          (galerie.media?.url
            ? [{ loc: generateNextImageUrl(galerie.media?.url) }]
            : []),
      });
    }
  }
  return pages;
};

const getCategories = async (): Promise<Page[]> => {
  const repo = apiRepository();
  const pages: Page[] = [];
  for (
    let page = 0;
    page <
    (await repo.categories.list({ page: 1, perPage: 10 })).meta.last_page;
    page++
  ) {
    const categories = await repo.categories.list({ page, perPage });

    for (const category of categories.data) {
      pages.push({
        url: `/categories/${category.slug}`,
        lastMod: category.updated_at ?? category.created_at,
        changefreq: "monthly",
        priority: "0.5",
        images: category.cover
          ? [{ loc: generateNextImageUrl(category.cover?.url) }]
          : [],
      });
    }
  }
  return pages;
};

const getCosplayers = async () => {
  const repo = apiRepository();
  const pages: Page[] = [];
  for (
    let page = 0;
    page <
    (await repo.cosplayers.list({ page: 1, perPage: 10 })).meta.last_page;
    page++
  ) {
    const cosplayers = await repo.cosplayers.list({ page, perPage });

    for (const cosplayer of cosplayers.data) {
      pages.push({
        url: `/cosplayers/${cosplayer.slug}`,
        lastMod: cosplayer.updated_at ?? cosplayer.created_at,
        changefreq: "monthly",
        priority: "0.6",
        images: cosplayer.avatar
          ? [{ loc: generateNextImageUrl(cosplayer.avatar?.url) }]
          : [],
      });
    }
  }
  return pages;
};

const getBlogs = async (): Promise<Page[]> => {
  const posts = await getAllPosts("published");

  return posts.map((p) => ({
    url: `/blog/${p.slug}`,
    lastMod: new Date(p.updatedAt ?? p.createdAt).toISOString(),
    changefreq: "monthly",
    priority: "0.9",
    images: [],
  }));
};

interface RoutePrerenderManifest {
  [key: string]: unknown;
}

interface PrerenderManifest {
  version: number;
  routes: RoutePrerenderManifest[];
}

const getDefaultRoutes = (): Page[] => {
  try {
    const manifest: PrerenderManifest = JSON.parse(
      fs.readFileSync(
        join(process.cwd(), ".next", "prerender-manifest.json"),
        "utf8"
      )
    );
    const paths = Object.keys(manifest.routes);
    const mapPaths = paths.map((p) => ({
      url: p,
      lastMod: new Date().toISOString(),
      changefreq: "monthly",
      priority: "0.7",
      images: [],
    }));
    const regExpProfilePaths = "/(/invitations/validate|/me/albums)/gm";
    return mapPaths.filter((map) => !map.url.match(regExpProfilePaths));
  } catch (err) {
    console.error(err);
  }
  return [];
};

const generateSitemap = async (
  req: NextApiRequest,
  res: NextApiResponse
): Promise<NextApiResponse | void> => {
  const resources = await Promise.all([
    getBlogs(),
    getGaleries(),
    getCategories(),
    getCosplayers(),
    getDefaultRoutes(),
  ]);

  const pages = new Map<string, Page>();
  for (const resource of resources) {
    for (const page of resource) {
      if (!pages.has(page.url)) {
        pages.set(page.url, page);
      }
    }
  }

  res.setHeader("content-type", "application/xml");
  return res.send(
    prefix +
      Array.from(pages)
        .map((p) => serialize(p[1]))
        .join("") +
      suffix
  );
};

export const serialize = (page: Page): string => {
  const host = configuration.appUrl;
  return `<url>
          <loc>${host}${page.url}</loc>
          <lastmod>${page.lastMod}</lastmod>
          <changefreq>${page.changefreq}</changefreq>
          <priority>${page.priority}</priority>
          ${
            page.images?.map(
              (i) => `<image:image>
          <image:loc>${host}${encodeHTML(i.loc)}</image:loc>
        </image:image>`
            ) ?? ""
          }
    </url>
    `;
};

const encodeHTML = (s: string): string => {
  return s
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&apos;");
};

export default generateSitemap;
