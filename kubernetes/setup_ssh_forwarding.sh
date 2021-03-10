#!/bin/bash

echo "Getting host and ports"
INGRESS_PORT=$(kubectl -n istio-system get service istio-ingressgateway -o jsonpath='{.spec.ports[?(@.name=="http2")].nodePort}')
SECURE_INGRESS_PORT=$(kubectl -n istio-system get service istio-ingressgateway -o jsonpath='{.spec.ports[?(@.name=="https")].nodePort}')
INGRESS_HOST=$(minikube ip)

#minikube dashboard --url
#$ISTIO_VERSION/bin/istioctl dashboard kiali &

#echo "Setting up port-forwarding in local"
#kubectl port-forward service/istio-ingressgateway -n istio-system 8443:443 --address 0.0.0.0 &
#kubectl port-forward service/istio-ingressgateway -n istio-system 8080:80 --address 0.0.0.0 &

echo "Starting ssh proxy"
sudo ssh -fN -i ~/.minikube/machines/minikube/id_rsa -o GatewayPorts=true -o StrictHostKeyChecking=accept-new \
  docker@$INGRESS_HOST -L 443:0.0.0.0:$SECURE_INGRESS_PORT -L 80:0.0.0.0:$INGRESS_PORT
