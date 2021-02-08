FROM node:14-alpine as deps 

COPY package.json yarn.lock lerna.json ./

COPY packages/frontend/package.json packages/frontend/package.json
COPY packages/common/package.json packages/common/package.json 
COPY packages/models/package.json packages/models/package.json 

RUN yarn install --frozen-lockfile


####

FROM node:14-alpine as builder

ARG NEXT_PUBLIC_API_URL

ENV NEXT_PUBLIC_API_URL=${NEXT_PUBLIC_API_URL}
ENV NEXT_PUBLIC_KEYCLOAK_REALM=${NEXT_PUBLIC_KEYCLOAK_REALM}
ENV NEXT_PUBLIC_KEYCLOAK_URL=${NEXT_PUBLIC_KEYCLOAK_URL}
ENV NEXT_PUBLIC_KEYCLOAK_CLIENT_ID=${NEXT_PUBLIC_KEYCLOAK_CLIENT_ID}
ENV NODE_ENV=production

RUN env 
WORKDIR /app

COPY . . 
COPY --from=deps . .

RUN yarn workspace @flasher/frontend run build

###

FROM node:14-alpine

#TODO Env 

COPY --from=builder . .
CMD ["node_modules/.bin/next", "start", "packages/frontend"]