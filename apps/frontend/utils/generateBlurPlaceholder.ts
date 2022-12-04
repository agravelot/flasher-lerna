import type { ImageProps } from "./types";

const cache = new Map<ImageProps, string>();

export default async function getBase64ImageUrl(
  image: ImageProps
): Promise<string> {
  let url = cache.get(image);
  if (url) {
    return url;
  }
  const response = await fetch(image.url);
  const buffer = await response.arrayBuffer();
  const base64 = Buffer.from(buffer).toString("base64");
  url = `data:image/jpeg;base64,${base64}`;
  cache.set(image, url);
  return url;
}
