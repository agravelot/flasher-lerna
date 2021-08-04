export  class SocialMedia {
  id!: number;
  name!: string;
  url!: string;
  icon!: 'facebook-f' | 'instagram' | 'twitter' | 'discord';
  color!: string;
  active!: boolean;
  created_at!: Date;
  updated_at!: Date;
}
