import Vue from 'vue';
import * as Sentry from '@sentry/browser';
import * as Integrations from '@sentry/integrations';

Sentry.init({
  dsn: process.env.MIX_SENTRY_DSN_PUBLIC,
  integrations: [new Integrations.Vue({ Vue, attachProps: true })]
});
