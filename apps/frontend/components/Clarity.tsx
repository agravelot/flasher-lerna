import Script from "next/script";

interface Config {
  id: string;
  enable: boolean;
}

const config: Config = {
  id: process.env.NEXT_PUBLIC_CLARITY_ID ?? "",
  enable: process.env.NEXT_PUBLIC_CLARITY_ENABLE === "true",
};

export const Clarity = (): JSX.Element => {
  return config.enable ? (<Script src={`https://www.clarity.ms/tag/${config.id}`} strategy="afterInteractive" async />) : (<></>);
};
