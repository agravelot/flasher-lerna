import {Media} from './media';

export  class Cosplayer {
  public id!: number;
  public name!: string;
  public slug!: string;
  public description!: string | null;
  public avatar!: Media | null;
  public user_id!: string | null;
  public created_at!: Date;
  public updated_at!: Date;
}
