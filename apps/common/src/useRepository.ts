import { api, PaginatedReponse, WrappedResponse } from "./api";
import {
  Album,
  Article,
  Category,
  Cosplayer,
  DashboardData,
} from "@flasher/models";

type Pagination = {
  page: number;
  perPage?: number;
};

export type CategoryListParams = {
  filter?: {
    name?: string;
  };
} & Pagination;

// TODO Callback to return token on request ?
export const apiClient = (getToken: () => string | undefined) => {
  const authHeader = getToken
    ? () => ({
        headers: { Authorization: `Bearer ${getToken()}` },
      })
    : () => ({});

  return {
    articles: {
      list: ({ page = 1, perPage = 10 }: Pagination) =>
        api<PaginatedReponse<Article[]>>(
          `/v2/articles?page=${page}&per_page=${perPage}`,
          authHeader(),
        ).then((res) => res.json()),
      retrieve: (slug: string) =>
        api<WrappedResponse<Article>>(
          `/v2/articles/${slug}`,
          authHeader(),
        ).then((res) => res.json()),
      create: async (article: Partial<Article>) => {
        const res = await api<WrappedResponse<Article>>(`/v2/articles`, {
          method: "POST",
          body: JSON.stringify(article),
          ...authHeader(),
        });
        // TODO only return res to handle error on caller
        return await res.json();
      },
    },

    albums: {
      list: ({ page = 1, perPage = 10 }: Pagination) =>
        api<PaginatedReponse<Album[]>>(
          `/albums?page=${page}&per_page=${perPage}`,
        ).then((res) => res.json()),

      retrieve: (slug: string) =>
        api<WrappedResponse<Album>>(`/albums/${slug}`).then((res) =>
          res.json(),
        ),
    },

    categories: {
      list: ({ page = 1, perPage = 10, filter }: CategoryListParams) =>
        api<PaginatedReponse<Category[]>>(
          `/categories?page=${page}&per_page=${perPage}&filter[name]=${filter?.name}`,
        ).then((res) => res.json()),
      retrieve: (slug: string) =>
        api<WrappedResponse<Category>>(`/categories/${slug}`).then((res) =>
          res.json(),
        ),
    },

    cosplayers: {
      list: ({ page = 1, perPage = 10 }: Pagination) =>
        api<PaginatedReponse<Cosplayer[]>>(
          `/cosplayers?page=${page}&per_page=${perPage}`,
        ).then((res) => res.json()),
      retrieve: (slug: string) =>
        api<WrappedResponse<Cosplayer>>(`/cosplayers/${slug}`).then((res) =>
          res.json(),
        ),
    },

    admin: {
      dashboard: () =>
        api<DashboardData>(`/admin/dashboard`, authHeader()).then((res) =>
          res.json(),
        ),

      albums: {
        list: ({ page = 1, perPage = 10 }: Pagination) =>
          api<PaginatedReponse<Album[]>>(
            `/admin/albums?page=${page}&per_page=${perPage}`,
            authHeader(),
          ).then((res) => res.json()),
        retrieve: (slug: string) =>
          api<WrappedResponse<Album>>(
            `/admin/albums/${slug}`,
            authHeader(),
          ).then((res) => res.json()),

        create: (album: Partial<Album>) => {
          if (!getToken) return;
          return api<WrappedResponse<Album>>(`/admin/albums`, {
            method: "POST",
            body: JSON.stringify(album),
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${getToken()}`,
            },
          }).then((res) => res.json());
        },

        update: (slug: string, album: Partial<Album>) => {
          if (!getToken) return;
          return api<WrappedResponse<Album>>(`/admin/albums/${slug}`, {
            method: "PATCH",
            body: JSON.stringify(album),
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${getToken()}`,
            },
          }).then((res) => res.json());
        },

        delete: (slug: string) => {
          if (!getToken) return;
          return api<WrappedResponse<Album>>(`/admin/albums/${slug}`, {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${getToken()}`,
            },
          }).then((res) => res);
        },
      },

      categories: {
        list: ({ page = 1, perPage = 10, filter }: CategoryListParams) =>
          api<PaginatedReponse<Category[]>>(
            `/admin/categories?page=${page}&per_page=${perPage}&filter[name]=${filter?.name}`,
            authHeader(),
          ).then((res) => res.json()),
        retrieve: (slug: string) =>
          api<WrappedResponse<Category>>(
            `/admin/categories/${slug}`,
            authHeader(),
          ).then((res) => res.json()),
      },

      cosplayers: {
        list: ({ page = 1, perPage = 10 }: Pagination) =>
          api<PaginatedReponse<Cosplayer[]>>(
            `/admin/cosplayers?page=${page}&per_page=${perPage}`,
            authHeader(),
          ).then((res) => res.json()),
        retrieve: (slug: string) =>
          api<WrappedResponse<Cosplayer>>(
            `/admin/cosplayers/${slug}`,
            authHeader(),
          ).then((res) => res.json()),
      },

      medias: {
        order: (ids: number[], albumSlug: string) => {
          if (!getToken) return;
          return api(`/admin/albums/${albumSlug}/media-ordering`, {
            method: "PATCH",
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${getToken()}`,
            },
            body: JSON.stringify({ media_ids: ids }),
          }).then((res) => res.json());
        },
      },
    },
  };
};
