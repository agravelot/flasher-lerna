import joi from "joi";

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

const envVarsSchema = joi
  .object()
  .keys({
    NODE_ENV: joi
      .string()
      .valid("production", "development", "test")
      .required(),
    NEXT_PUBLIC_APP_URL: joi
      .string()
      .required()
      .uri()
      .description("Public app url"),
    NEXT_PUBLIC_API_URL: joi
      .string()
      .required()
      .uri()
      .description("Data source api"),
    NEXT_PUBLIC_KEYCLOAK_REALM: joi.string().required(),
    NEXT_PUBLIC_KEYCLOAK_CLIENT_ID: joi.string().required(),
    NEXT_PUBLIC_KEYCLOAK_URL: joi.string().uri().required(),
    NEXT_PUBLIC_ALGOLIA_APP_ID: joi.string().allow(""), // require in prod
    NEXT_PUBLIC_ALGOLIA_API_KEY: joi.string().allow(""), // require in prod
    NEXT_PUBLIC_GOOGLE_ANALYTICS_UA: joi.string().allow(""), // require in prod
    NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG: joi.boolean(),
    NEXT_PUBLIC_ADMINISTRATION_URL: joi.string().uri().required(),
  })
  .unknown();

const { value: envVars, error } = envVarsSchema
  .prefs({ errors: { label: "key" } })
  .validate(process.env);

if (error) {
  throw new Error(`Config validation error: ${error.message}`);
}

export const configuration: ConfigurationInterface = {
  appUrl: envVars.NEXT_PUBLIC_APP_URL,
  baseUrl: envVars.NEXT_PUBLIC_API_URL,
  keycloak: {
    realm: envVars.NEXT_PUBLIC_KEYCLOAK_REALM,
    clientId: envVars.NEXT_PUBLIC_KEYCLOAK_CLIENT_ID,
    url: envVars.NEXT_PUBLIC_KEYCLOAK_URL,
  },
  algolia: {
    appId: envVars.NEXT_PUBLIC_ALGOLIA_APP_ID,
    apiKey: envVars.NEXT_PUBLIC_ALGOLIA_API_KEY,
  },
  googleAnalytics: {
    ua: envVars.NEXT_PUBLIC_GOOGLE_ANALYTICS_UA,
    debug: envVars.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG
      ? envVars.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG === "true"
      : envVars.NODE_ENV !== "production",
  },
  twitter: "JujunneK",
  administration: envVars.NEXT_PUBLIC_ADMINISTRATION_URL,
};
