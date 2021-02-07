export enum SettingKeys {
  HomepageTitle = "homepage_title",
  HomepageSubtitle = "homepage_subtitle",
  FooterContent = "footer_content",
  ProfilePictureHomepage = "profile_picture_homepage",
  BackgroundPictureHomepage = "background_picture_homepage",
  DefaultPageTitle = "default_page_title",
  HomepageHeaderSubtitle = "homepage_header_subtitle",
  AppName = "app_name",
  HomepageDescription = "homepage_description",
  SeoDescription = "seo_description",
}
export class SettingMedia {
  name!: string;
  url!: string;
  // eslint-disable-next-line camelcase
  src_set!: string | null;
  width!: number | null;
  height!: number | null;
}

export default class Setting {
  public id!: number;
  public name!: SettingKeys;
  public description!: string;
  public value!: string | SettingMedia | null;
}
