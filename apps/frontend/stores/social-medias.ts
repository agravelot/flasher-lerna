import { SocialMedia } from "@flasher/models";
import { api, PaginatedReponse } from "../utils/api";

export const getSocialMedias = (): Promise<SocialMedia[]> =>
  api<PaginatedReponse<SocialMedia[]>>("/social-medias")
    .then((res) => res.json())
    .then((res) => res.data);