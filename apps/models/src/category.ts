import {FilterableById} from './interfaces/filterableById';
import {Media} from './media';

export  class Category implements FilterableById {
  public id!: number;
  public slug!: string;
  public name!: string;
  // eslint-disable-next-line camelcase
  public meta_description!: string;
  public description!: string | null;
  // eslint-disable-next-line camelcase
  public created_at!: string;
  // eslint-disable-next-line camelcase
  public updated_at!: string;
  public cover!: Media | null;
}
