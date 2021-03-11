FROM viaductoss/ksops:v2.3.3

# K9s
# RUN apk add --update ca-certificates 
COPY --from=quay.io/derailed/k9s /bin/k9s /usr/local/bin/k9s
RUN chmod +x /usr/local/bin/k9s

# Kubectl
COPY --from=quay.io/derailed/k9s /usr/local/bin/kubectl /usr/local/bin/kubectl

RUN apt update && apt install nano
# SOPS
RUN wget https://github.com/mozilla/sops/releases/download/v3.6.1/sops_3.6.1_amd64.deb && apt install ./sops_3.6.1_amd64.deb

# Istio
RUN curl -L https://istio.io/downloadIstio | ISTIO_VERSION=1.8.1 TARGET_ARCH=x86_64 sh -

WORKDIR /app

ADD entrypoint.sh /app/entrypoint.sh 

RUN chmod +x /app/entrypoint.sh

ENTRYPOINT [ "/app/entrypoint.sh" ]