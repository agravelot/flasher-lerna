import { api, PaginatedReponse } from "@flasher/common";
import { Setting, SettingKeys, SettingMedia } from "@flasher/models";

export interface Settings {
  homepageTitle: string;
  homepageSubtitle: string;
  footerContent: string;
  profilePictureHomepage: SettingMedia | null;
  backgroundPictureHomepage: SettingMedia | null;
  defaultPageTitle: string;
  homepageHeaderSubtitle: string;
  appName: string;
  homepageDescription: string;
  seoDescription: string;
}

export const getSettings = async (): Promise<Settings> => {
  return await api<PaginatedReponse<Setting[]>>("/settings?page=1")
    .then((res) => res.json())
    .then((json) => json.data)
    .then((settings) => {
      const getSetting = (key: SettingKeys): Setting => {
        const setting = settings.find((s) => s.name === key);
        if (setting === undefined) {
          throw new Error("Unable to get setting with key : " + key);
        }
        return setting;
      };

      return {
        homepageTitle: getSetting(SettingKeys.HomepageTitle).value as string,
        homepageSubtitle: getSetting(SettingKeys.HomepageSubtitle)
          .value as string,
        footerContent: getSetting(SettingKeys.FooterContent).value as string,
        profilePictureHomepage: getSetting(SettingKeys.ProfilePictureHomepage)
          .value as SettingMedia,
        backgroundPictureHomepage: getSetting(
          SettingKeys.BackgroundPictureHomepage
        ).value as SettingMedia,
        defaultPageTitle: getSetting(SettingKeys.DefaultPageTitle)
          .value as string,
        homepageHeaderSubtitle: getSetting(SettingKeys.HomepageHeaderSubtitle)
          .value as string,
        appName: getSetting(SettingKeys.AppName).value as string,
        homepageDescription: getSetting(SettingKeys.HomepageDescription)
          .value as string,
        seoDescription: getSetting(SettingKeys.SeoDescription).value as string,
      };
    })
    .catch((e) => {
      throw e;
    });
};
