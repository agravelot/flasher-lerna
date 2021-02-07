import FilterableById from "~/models/interfaces/filterableById";
import Media from "~/models/media";

export default class Category implements FilterableById {
  public id!: number;
  public slug!: string;
  public name!: string;
  // eslint-disable-next-line camelcase
  public meta_description!: string;
  public description!: string | null;
  // eslint-disable-next-line camelcase
  public created_at!: Date;
  // eslint-disable-next-line camelcase
  public updated_at!: Date;
  public cover!: Media | null;
}
