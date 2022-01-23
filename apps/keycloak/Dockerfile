FROM jboss/keycloak:16.1.0
RUN mkdir -p /opt/jboss/keycloak/modules/at/favre/lib/jbcrypt/main/
COPY --chown=1000:0 realm-export.json /tmp/realm.json
ADD --chown=1000:0 https://repo1.maven.org/maven2/at/favre/lib/bcrypt/0.9.0/bcrypt-0.9.0.jar /opt/jboss/keycloak/modules/at/favre/lib/jbcrypt/main/
ADD --chown=1000:0 https://github.com/leroyguillaume/keycloak-bcrypt/releases/download/1.5.0/keycloak-bcrypt-1.5.0.jar /opt/jboss/keycloak/standalone/deployments/
COPY --chown=1000:0 ./themes/flasher /opt/jboss/keycloak/themes/flasher
