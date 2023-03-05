# Flasher

A website specialized for photographers, targeting high image quality delivery.

**Important notice**: This repository is a personal playground to try out new things. It is not intended for public production use and can introduce breaking changes at any time without notice. Public website is accessible at [jkanda.fr](https://jkanda.fr).

## Structure
This repository is a monorepo which contains most of the code for this project. Some legacy components are archived or kept private.

The projects within the repository can be found in the `/apps` directory, including:

- `admin`: a work-in-progress React SPA administration v2.
- `api-go`: a work-in-progress gRPC/REST API v2 built with Golang.
  - `api-laravel`: the production REST API built with PHP and Laravel.
- `common`: common TypeScript code used by the frontend projects.
- `frontend`: the production frontend built with Next.js.
- `http-client`: a generated TypeScript REST client for communicating with api-go.
- `keycloak`: a docker image for running keycloak with bcrypt support for older accounts.
- [admin v1](https://github.com/FlasherProject/flasher-admin): the production administration built with NuxtJS.
- A self-hosted HA kubernetes cluster
- GitOps workflow with flux.cd
- a work-in-progress image processing service, not OSS for now

## Docker

All project can be built on his respective directory, except `frontend` which has dependencies on other projects.

```bash
docker build -f apps/frontend/Dockerfile $(cat apps/frontend/.env .secrets | awk NF | sed 's@^@--build-arg @g' | paste -s -d " ") .
```
**NOTE:** `$(cat apps/frontend/.env .secrets | awk NF | sed 's@^@--build-arg @g' | paste -s -d " ")`combine all environment variables available in `.env` of frontend project and inject `.secrets`.