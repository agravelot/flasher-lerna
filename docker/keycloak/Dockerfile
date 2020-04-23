FROM jboss/keycloak:9.0.3

COPY --chown=1000:0 bcrypt/dependency/jbcrypt /opt/jboss/keycloak/modules/org/mindrot/jbcrypt/main/
ADD --chown=1000:0 https://repo1.maven.org/maven2/org/mindrot/jbcrypt/0.4/jbcrypt-0.4.jar /opt/jboss/keycloak/modules/org/mindrot/jbcrypt/main/
ADD --chown=1000:0 https://github.com/leroyguillaume/keycloak-bcrypt/releases/download/1.2.0/keycloak-bcrypt-1.2.0.jar /opt/jboss/keycloak/standalone/deployments/
RUN ls -ahl /opt/jboss/keycloak/modules/org/mindrot/jbcrypt/main/
