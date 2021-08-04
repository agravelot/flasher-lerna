import { Album } from "@flasher/models";

export const range = (start: number, end: number): number[] =>
  Array(end + 1 - start)
    .fill(1)
    .map((_, y) => y + 1);

export const sizes = (divider: number, size: "container" | "full"): string => {
  if (divider === 3 && size === "container") {
    return "(min-width: 1536px) 521px, (min-width: 1280px) 431px, (min-width: 1024px) 342px, (min-width: 768px) 240px, 100vw";
  }

  if (divider === 2 && size === "container") {
    return "(min-width: 1536px) 776px, (min-width: 1280px) 648px, (min-width: 1024px) 520px, (min-width: 768px) 392px, 100vw";
  }

  throw new Error("Nop");
};

export const resolveOriginalImage = (url: string): string =>
  url.replace("-thumb", "").replace("/conversions", "");

export const generateNextImageUrl = (imageUrl: string, width = 828): string =>
  "/_next/image?url=" + encodeURIComponent(imageUrl) + `&w=${width}&q=75`;

export const resolveAlbumStatus = (album: Album): 'published'| 'private' | 'draft' => {
    if (album.private && album.published_at !== null) {
      return 'private'
    } 
    if (album.published_at !== null) {
      return 'published'
    }

    return 'draft'
  }