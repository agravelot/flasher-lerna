import { Category } from './category';
import { Cosplayer } from './cosplayer';
import { Media } from './media';

interface LinksInterface {
  view: string;
  download?: string;
  edit?: string;
}

export class Album {
  public id!: number;
  public slug!: string;
  public meta_description!: string;
  public title!: string;
  public body?: string | null;
  public published_at!: string | null;
  public private!: boolean;
  public media: Media | undefined;
  public medias: Media[] | undefined;
  public user_id!: string;
  public cosplayers?: Cosplayer[];
  public created_at!: string;
  public updated_at!: string | null;
  public links!: LinksInterface;
  public categories?: Category[];
}

export enum Status {
  Published = 'published',
  Draft = 'draft',
  Private = 'private',
}

export const getStatus = (album: Album): Status => {
  if (album.private && album.published_at !== null) {
    return Status.Private;
  }

  if (album.published_at !== null) {
    return Status.Published;
  }

  return Status.Draft;
};

export const reverseGetStatus = (
  status: Status
): { private: boolean; published_at: string|null } => {
  if (status === Status.Private) {
    return { private: true, published_at: new Date().toISOString() };
  }

  if (status === Status.Published) {
    return { private: false, published_at: new Date().toISOString() };
  }

  return { private: false, published_at: null };
};
