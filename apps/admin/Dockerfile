FROM node:15-alpine as builder

ENV NODE_ENV=production
ENV PORT=80 

WORKDIR /app

# Copy all files, ignore apps folder
COPY [^a]* . 
COPY apps/admin/package.json apps/admin/package.json
COPY apps/common/package.json apps/common/package.json 
COPY apps/models/package.json apps/models/package.json 

RUN yarn install --frozen-lockfile && yarn cache clean

COPY apps/admin apps/admin
COPY apps/common apps/common 
COPY apps/models apps/models 

RUN yarn workspace admin run build

FROM nginx:1.21

COPY --from=builder /app/apps/admin/build /usr/share/nginx/html