import { api, PaginatedReponse, WrappedResponse } from './api';
import { Album, Category, Cosplayer, DashboardData } from '@flasher/models';
import { KeycloakInstance } from 'keycloak-js';

type Pagination = {
  page: number;
  perPage?: number;
}

export type CategoryListParams = {
  filter?: {
    name?: string;
  };
} & Pagination

// TODO Callback to return token on request ?
export const useRepository = (keycloak: KeycloakInstance | undefined) => {
  const authHeader = () => ({
    headers: { Authorization: `Bearer ${keycloak?.token}` }
  });

  return {
    admin: {
      dashboard: () =>
        api<DashboardData>(`/admin/dashboard`, authHeader()).then((res) =>
          res.json()
        ), 

      albums: {
        list: ({ page = 1, perPage = 10 }: Pagination) =>
          api<PaginatedReponse<Album[]>>(
            `/admin/albums?page=${page}&per_page=${perPage}`,
            authHeader()
          ).then((res) => res.json()),
        retrieve: (slug: string) =>
          api<WrappedResponse<Album>>(
            `/admin/albums/${slug}`,
            authHeader()
          ).then((res) => res.json()),
      },

      categories: {
        list: ({ page = 1, perPage = 10, filter }: CategoryListParams) =>
          api<PaginatedReponse<Category[]>>(
            `/admin/categories?page=${page}&per_page=${perPage}&filter[name]=${filter?.name}`,
            authHeader()
          ).then((res) => res.json()),
        retrieve: (slug: string) =>
          api<WrappedResponse<Category>>(
            `/admin/categories/${slug}`,
            authHeader()
          ).then((res) => res.json()),
      },

      cosplayers: {
        list: ({ page = 1, perPage = 10 }: Pagination) =>
          api<PaginatedReponse<Cosplayer[]>>(
            `/admin/cosplayers?page=${page}&per_page=${perPage}`,
            authHeader()
          ).then((res) => res.json()),
        retrieve: (slug: string) =>
          api<WrappedResponse<Cosplayer>>(
            `/admin/cosplayers/${slug}`,
            authHeader()
          ).then((res) => res.json()),
      },
    },
  };
};
