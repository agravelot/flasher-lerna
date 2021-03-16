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
WORKDIR /istio
RUN curl -L https://istio.io/downloadIstio | ISTIO_VERSION=1.9.1 TARGET_ARCH=x86_64 sh - && ln -s /istio/istio-1.9.1/bin/istioctl /usr/local/bin

WORKDIR /app

ADD entrypoint.sh /app/entrypoint.sh 

RUN chmod +x /app/entrypoint.sh

RUN echo 'alias prod="kustomize build --enable_alpha_plugins production | kubectl apply -f -"' >> ~/.bashrc
RUN echo 'alias staging="kustomize build --enable_alpha_plugins staging | kubectl apply -f -"' >> ~/.bashrc

ENTRYPOINT [ "/app/entrypoint.sh" ]