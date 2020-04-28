FROM maven:3.3-jdk-8 as builder
WORKDIR /build
COPY . /build/
RUN mvn clean package

FROM jboss/keycloak:9.0.3
RUN mkdir -p /opt/jboss/keycloak/modules/at/favre/lib/jbcrypt/main/
COPY --chown=1000:0 module.xml /opt/jboss/keycloak/modules/at/favre/lib/jbcrypt/main/
ADD --chown=1000:0 https://repo1.maven.org/maven2/at/favre/lib/bcrypt/0.9.0/bcrypt-0.9.0.jar /opt/jboss/keycloak/modules/at/favre/lib/jbcrypt/main/
COPY --from=builder --chown=1000:0 /build/target/keycloak-bcrypt-1.3.0.jar /opt/jboss/keycloak/standalone/deployments/
