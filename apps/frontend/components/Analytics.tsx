import GA4React from "ga-4-react";
import { useEffect } from "react";

interface Config {
  ua: string;
  debug: boolean;
}

const config: Config = {
  ua: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_UA ?? "",
  debug: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG
    ? process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG === "true"
    : process.env.NODE_ENV !== "production",
};

export const Analytics = (): null => {
  useEffect(() => {
    const ga4react = new GA4React(config.ua);

    ga4react.initialize();
  }, []);

  return null;
};
