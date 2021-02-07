import Category from "~/models/category";
import Cosplayer from "~/models/cosplayer";
import Media from "~/models/media";

interface LinksInterface {
  view: string;
  download?: string;
  edit?: string;
}

export default class Album {
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
