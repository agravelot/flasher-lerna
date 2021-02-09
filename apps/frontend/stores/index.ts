import { SocialMedia } from "@flasher/models";
import { getSettings, Settings } from "./settings";
import { getSocialMedias } from "./social-medias";

export type GlobalProps = Settings & { socialMedias: SocialMedia[] };

export const getGlobalProps = async (): Promise<GlobalProps> => {
  const settings = await getSettings();

  const socialMedias = await getSocialMedias();

  return { ...settings, socialMedias };
};
