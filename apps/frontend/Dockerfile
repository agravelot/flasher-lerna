FROM node:14-alpine

ARG NEXT_PUBLIC_API_URL

ENV NEXT_PUBLIC_API_URL=${NEXT_PUBLIC_API_URL}
ENV NEXT_PUBLIC_KEYCLOAK_REALM=${NEXT_PUBLIC_KEYCLOAK_REALM}
ENV NEXT_PUBLIC_KEYCLOAK_URL=${NEXT_PUBLIC_KEYCLOAK_URL}
ENV NEXT_PUBLIC_KEYCLOAK_CLIENT_ID=${NEXT_PUBLIC_KEYCLOAK_CLIENT_ID}
ENV NODE_ENV=production

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

CMD ["node_modules/.bin/next", "start", "apps/frontend"]