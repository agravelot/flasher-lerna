import * as Sentry from "@sentry/browser";

Sentry.init({
    dsn: process.env.MIX_SENTRY_DSN_PUBLIC
});
