<?php

declare(strict_types=1);

return [
  'url' => env('KEYCLOAK_URL'),

  'verify_ssl' => env('KEYCLOAK_VERIFY_SSL', true),

  'realm' => env('KEYCLOAK_REALM', 'default'),

  'client_id' => env('KEYCLOAK_CLIENT_ID', null),

  'realm_public_key' => env('KEYCLOAK_REALM_PUBLIC_KEY', null),

  'load_user_from_database' => env('KEYCLOAK_LOAD_USER_FROM_DATABASE', false),

  'user_provider_credential' => env('KEYCLOAK_USER_PROVIDER_CREDENTIAL', 'name'),

  'token_principal_attribute' => env('KEYCLOAK_TOKEN_PRINCIPAL_ATTRIBUTE', 'preferred_username'),

  'append_decoded_token' => env('KEYCLOAK_APPEND_DECODED_TOKEN', true),

  'allowed_resources' => env('KEYCLOAK_ALLOWED_RESOURCES', 'account'),

  'master' => [
      'username' => env('KEYCLOAK_MASTER_USERNAME'),
      'password' => env('KEYCLOAK_MASTER_PASSWORD'),
  ],
];
