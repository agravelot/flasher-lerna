module.exports = {
  env: {
    browser: true,
    es6: true,
  },
  extends: [
      'plugin:vue/recommended',
      'eslint:recommended',
      'plugin:@typescript-eslint/eslint-recommended',
      'plugin:@typescript-eslint/recommended',
  ],
  globals: {
    Atomics: 'readonly',
    SharedArrayBuffer: 'readonly',
  },
  'parser': 'vue-eslint-parser',
  parserOptions: {
    'parser': '@typescript-eslint/parser'
  },
  plugins: [
    'vue',
    '@typescript-eslint',
  ],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
  },
};
