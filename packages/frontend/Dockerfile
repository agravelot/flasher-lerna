FROM node:14-alpine

ARG NEXT_PUBLIC_API_URL

ENV NEXT_PUBLIC_API_URL=${NEXT_PUBLIC_API_URL}
ENV NEXT_PUBLIC_KEYCLOAK_REALM=${NEXT_PUBLIC_KEYCLOAK_REALM}
ENV NEXT_PUBLIC_KEYCLOAK_URL=${NEXT_PUBLIC_KEYCLOAK_URL}
ENV NEXT_PUBLIC_KEYCLOAK_CLIENT_ID=${NEXT_PUBLIC_KEYCLOAK_CLIENT_ID}
ENV NODE_ENV=production

WORKDIR /app

COPY package.json yarn.lock lerna.json ./
COPY packages/frontend/package.json packages/frontend/package.json
COPY packages/common/package.json packages/common/package.json 
COPY packages/models/package.json packages/models/package.json 

RUN yarn install --frozen-lockfile --production

COPY . . 

RUN yarn workspace @flasher/frontend run build

CMD ["node_modules/.bin/next", "start", "packages/frontend"]