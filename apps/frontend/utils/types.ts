/* eslint-disable no-unused-vars */
import {Media} from "@flasher/models";

export interface ImageProps {
  id: number;
  height: number;
  width: number;
  url: string;
  // format: string
  blurDataUrl?: string;
}

export interface SharedModalProps {
  index: number;
  images?: ImageProps[];
  currentPhoto?: ImageProps;
  next: () => void;
  previous: () => void;
  closeModal: () => void;
  navigation: boolean;
  direction?: number;
  setIndex?: (i: number) => void;
}

export const transformImage = (m: Media): ImageProps => {
  return {
    url: m.url,
    id: m.id,
    height: m.height,
    width: m.width,
  };
};
