FROM gleroy/keycloak-bcrypt:20.0.2_1.5.1
COPY --chown=1000:0 realm-export.json /tmp/realm.json
COPY --chown=1000:0 ./themes/flasher /opt/jboss/keycloak/themes/flasher
