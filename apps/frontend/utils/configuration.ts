// import joi from "joi";

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
    apiUrl: string;
    apiInternalUrl?: string;
    keycloak: KeycloakConfig;
    algolia: AlgoliaConfig;
    googleAnalytics: GoogleAnalyticsConfig;
    twitter: string;
    administration: string;
}

// const envVarsSchema = joi
//   .object()
//   .keys({
//     // NODE_ENV: joi
//     //   .string()
//     //   .valid("production", "development", "test")
//     //   .required(),
//     NEXT_PUBLIC_APP_URL: joi
//       .string()
//       .required()
//       .uri()
//       .description("Public app url"),
//     NEXT_PUBLIC_API_URL: joi
//       .string()
//       .required()
//       .uri()
//       .description("Data source api"),
//     NEXT_PUBLIC_KEYCLOAK_REALM: joi.string().required(),
//     NEXT_PUBLIC_KEYCLOAK_CLIENT_ID: joi.string().required(),
//     NEXT_PUBLIC_KEYCLOAK_URL: joi.string().uri().required(),
//     NEXT_PUBLIC_ALGOLIA_APP_ID: joi.string().allow(""), // require in prod
//     NEXT_PUBLIC_ALGOLIA_API_KEY: joi.string().allow(""), // require in prod
//     NEXT_PUBLIC_GOOGLE_ANALYTICS_UA: joi.string().allow(""), // require in prod
//     NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG: joi.boolean(),
//     NEXT_PUBLIC_ADMINISTRATION_URL: joi.string().uri().required(),
//   })
//   .unknown();

// const { error } = envVarsSchema
//   .prefs({ errors: { label: "key" } })
//   .validate(process.env);

// if (error) {
//   throw new Error(`Config validation error: ${error.message}`);
// }

export const configuration: ConfigurationInterface = {
    appUrl: process.env.NEXT_PUBLIC_APP_URL ?? "",
    apiUrl: process.env.NEXT_PUBLIC_API_URL ?? "",
    apiInternalUrl: process.env.INTERNAL_API_URL,
    keycloak: {
        realm: process.env.NEXT_PUBLIC_KEYCLOAK_REALM ?? "",
        clientId: process.env.NEXT_PUBLIC_KEYCLOAK_CLIENT_ID ?? "",
        url: process.env.NEXT_PUBLIC_KEYCLOAK_URL ?? "",
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
    twitter: "Jkandaphoto",
    administration: process.env.NEXT_PUBLIC_ADMINISTRATION_URL ?? "",
};
