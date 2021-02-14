# Flasher monorepo


## Docker 

````bash
docker build -f apps/frontend/Dockerfile $(cat apps/frontend/.env .secrets | awk NF | sed 's@^@--build-arg @g' | paste -s -d " ") .
```

**NOTE:** `cat apps/frontend/.env .secrets | awk NF | sed 's@^@--build-arg @g' | paste -s -d " "` combine all envornoment variables available in `.env` of the project and inject `.secrets` used for pipeline. `NEXT_PUBLIC_KEYCLOAK_REALM=realm NEXT_PUBLIC_KEYCLOAK_URL=https://accounts.example.com/auth` will generate `--build-arg NEXT_PUBLIC_KEYCLOAK_REALM=realm --build-arg NEXT_PUBLIC_KEYCLOAK_URL=https://accounts.example.com/auth`

