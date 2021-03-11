#!/bin/bash
PWD=$(pwd)
BIN_DIR=$PWD/.bin
ISTIO_VERSION=1.6.2
ISTIO_FOLDER=istio-$ISTIO_VERSION
ISTIOCTL=$BIN_DIR/${ISTIO_FOLDER}/bin/istioctl
mkdir -p $BIN_DIR

echo "Checking dependencies"
cd "$BIN_DIR" || exit

if [ ! -d "$ISTIO_FOLDER" ]; then
  echo "Downloading Istio"
  curl -L https://istio.io/downloadIstio | ISTIO_VERSION=$ISTIO_VERSION sh -
fi

if [ ! -f "kustomize" ]; then
  echo "Downloading Kustomize"
  curl -s "https://raw.githubusercontent.com/kubernetes-sigs/kustomize/master/hack/install_kustomize.sh" | bash
fi

if [ ! -f "kind" ]; then
 curl -Lo ./kind https://kind.sigs.k8s.io/dl/v0.8.1/kind-$(uname)-amd64
  chmod +x ./kind
fi

sudo install kustomize /usr/local/bin/
sudo install $ISTIOCTL /usr/local/bin/
sudo install kind /usr/local/bin/

cd - || exit

####

echo "Setting up kind"
kind create cluster --config dev/cluster.yml


istioctl verify-install

if [ $? -ne 0 ]; then
  echo "Installing Istio"
  istioctl manifest apply \
    --skip-confirmation \
    --set values.gateways.istio-ingressgateway.sds.enabled=true \
    --set values.gateways.istio-ingressgateway.type=LoadBalancer \
    --set values.kiali.enabled=true
#    --set values.prometheus.enabled=false \ # reduce memory usage
#    --set MeshConfig.OutboundTrafficPolicy.Mode=ALLOW_ANY \  # Allow any egress traffic

  istioctl analyze -n istio-system
fi

# MetalLB
kubectl apply -f https://raw.githubusercontent.com/metallb/metallb/v0.9.3/manifests/namespace.yaml
kubectl apply -f https://raw.githubusercontent.com/metallb/metallb/v0.9.3/manifests/metallb.yaml
# On first install only
kubectl create secret generic -n metallb-system memberlist --from-literal=secretkey="$(openssl rand -base64 128)"

# Take IPs from the end of the docker bridge network subnet to use for MetalLB IPs
DOCKER_BRIDGE_SUBNET="$(docker inspect bridge | jq .[0].IPAM.Config[0].Subnet -r)"

# TODO add kiali credentials

echo "Setting up namespaces"
kubectl label namespace default istio-injection=enabled
kubectl apply -f dev/namespace.yml

echo "Installing cert-manager"
kubectl apply --validate=false -f https://github.com/jetstack/cert-manager/releases/download/v0.15.1/cert-manager.yaml

echo "Installing project"
kustomize build dev | kubectl apply -f -

#source ./setup_ssh_forwarding.sh

echo "HTTP : http://127.0.0.1.nip.io"
echo "HTTPS (frontend/api): https://127.0.0.1.nip.io"
echo "HTTPS (accounts): https://accounts.127.0.0.1.nip.io"
echo "HTTPS (admin): https://admin.127.0.0.1.nip.io"
