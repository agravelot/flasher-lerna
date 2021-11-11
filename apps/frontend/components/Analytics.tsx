import GA4React from "ga-4-react";
import { useEffect } from "react";

interface Config {
  ua: string;
  debug: boolean;
}

const config: Config = {
  ua: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_UA ?? "",
  debug: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG  === "true",
};

export const Analytics = (): null => {
  useEffect(() => {
    if (!config.ua) {
      console.error("Google Analytics UA not set");
      return;
    }

    const ga4react = new GA4React(config.ua);

    ga4react.initialize();
  }, []);

  return null;
};
