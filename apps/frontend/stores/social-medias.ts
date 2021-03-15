import { api, PaginatedReponse } from "@flasher/common/src";
import { SocialMedia } from "@flasher/models";

export const getSocialMedias = (): Promise<SocialMedia[]> =>
  api<PaginatedReponse<SocialMedia[]>>("/social-medias")
    .then((res) => res.json())
    .then((res) => res.data);
