FROM node:15-alpine

ARG NEXT_PUBLIC_APP_URL
ARG NEXT_PUBLIC_API_URL
ARG NEXT_PUBLIC_KEYCLOAK_REALM
ARG NEXT_PUBLIC_KEYCLOAK_URL
ARG NEXT_PUBLIC_KEYCLOAK_CLIENT_ID
ARG NEXT_PUBLIC_ADMINISTRATION_URL
ARG NEXT_PUBLIC_ALGOLIA_APP_ID
ARG NEXT_PUBLIC_ALGOLIA_API_KEY
ARG NEXT_PUBLIC_GOOGLE_ANALYTICS_UA

ENV NODE_ENV=production
ENV PORT=80 

WORKDIR /app

# Copy all files, ignore apps folder
COPY [^a]* . 
COPY apps/frontend/package.json apps/frontend/package.json
COPY apps/common/package.json apps/common/package.json 
COPY apps/models/package.json apps/models/package.json 

RUN yarn install --frozen-lockfile --production && yarn cache clean

COPY apps/frontend apps/frontend
COPY apps/common apps/common 
COPY apps/models apps/models 

RUN yarn workspace @flasher/frontend run build

WORKDIR /app/apps/frontend

CMD ["../../node_modules/.bin/next", "start"]