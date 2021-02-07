interface KeycloakConfig {
  realm: string;
  url: string;
  clientId: string;
}

interface AlgoliaConfig {
  appId: string;
  apiKey: string;
}

interface GoogleAnalyticsConfig {
  ua: string;
  debug: boolean;
}

export interface ConfigurationInterface {
  appUrl: string;
  baseUrl: string;
  keycloak: KeycloakConfig;
  algolia: AlgoliaConfig;
  googleAnalytics: GoogleAnalyticsConfig;
  twitter: string;
  administration: string;
}

export const configuration: ConfigurationInterface = {
  appUrl: process.env.APP_URL ?? "",
  baseUrl: process.env.NEXT_PUBLIC_API_URL ?? "",
  keycloak: {
    realm: process.env.NEXT_PUBLIC_KEYCLOAK_REALM ?? "",
    url: process.env.NEXT_PUBLIC_KEYCLOAK_URL ?? "",
    clientId: process.env.NEXT_PUBLIC_KEYCLOAK_CLIENT_ID ?? "",
  },
  algolia: {
    appId: process.env.NEXT_PUBLIC_ALGOLIA_APP_ID ?? "",
    apiKey: process.env.NEXT_PUBLIC_ALGOLIA_API_KEY ?? "",
  },
  googleAnalytics: {
    ua: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_UA ?? "",
    debug: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG
      ? process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG === "true"
      : process.env.NODE_ENV !== "production",
  },
  twitter: "JujunneK",
  administration: process.env.NEXT_PUBLIC_ADMINISTRATION_URL ?? "",
};
