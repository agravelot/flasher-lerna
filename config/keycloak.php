<?php

return [
  'url' => env('KEYCLOAK_URL'),

  'verify_ssl' => env('KEYCLOAK_VERIFY_SSL', true),

  'realm' => env('KEYCLOAK_REALM', null),

  'realm_public_key' => env('KEYCLOAK_REALM_PUBLIC_KEY', null),

  'load_user_from_database' => env('KEYCLOAK_LOAD_USER_FROM_DATABASE', false),

  'user_provider_credential' => env('KEYCLOAK_USER_PROVIDER_CREDENTIAL', 'name'),

  'token_principal_attribute' => env('KEYCLOAK_TOKEN_PRINCIPAL_ATTRIBUTE', 'preferred_username'),

  'append_decoded_token' => env('KEYCLOAK_APPEND_DECODED_TOKEN', true),

  'allowed_resources' => env('KEYCLOAK_ALLOWED_RESOURCES', 'account'),
];
