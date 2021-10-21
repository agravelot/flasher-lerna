# Flasher infra

This repository contains kubernetes yaml specifications to run flasher on kubernetes.
It use `kustomize` to generate yaml specifications.

## How to run in local

### Requirements 
- Working local kubernetes, I recommend `minikube` https://kubernetes.io/docs/tasks/tools/install-minikube/
- Install cert-manager https://cert-manager.io/docs/installation/kubernetes/
- Deploy Istio on your kubernetes cluster https://istio.io/docs/setup/getting-started/
- kustomize v3 installed https://github.com/kubernetes-sigs/kustomize/blob/master/docs/INSTALL.md
- Kustomize [https://github.com/viaduct-ai/kustomize-sops][KOPS plugin] installed for staging/production environments
- Add docker registry credentials to your secrets `kubectl create secret docker-registry gitlab-registry --docker-server=registry.gitlab.com --docker-username=<username> --docker-password=<password> --docker-email=<email> -n flasher`

Now you will be able to launch local 
```bash
kustomize build --enable_alpha_plugins <env> | kubectl apply -f -
kustomize build --enable_alpha_plugins staging | kubectl apply -f -
kustomize build --enable_alpha_plugins production | kubectl apply -f -
```
**Important:** Make sure to enable Istio sidecar injection on the right namespace.


Using kustomize-sops docker image can be pretty handy.
https://github.com/viaduct-ai/kustomize-sops/blob/master/Dockerfile
```bash
docker run --rm -it -v ~/.kube/config:/root/.kube/config -v (pwd):/app kustomize /bin/bash -c 'cd /app && bash'

docker build . -t k8s-ws
docker run --rm -it -v ~/.kube/config:/root/.kube/config -v (pwd):/app k8s-ws

docker build . -t k8s-ws && docker run --rm -it -v ~/.kube/config:/root/.kube/config -v (pwd):/app k8s-ws
```


[]: https://github.com/viaduct-ai/kustomize-sopsKustomize

## Scaleway

Switch to default storage class to retain policy:
```bash
kubectl patch storageclass scw-bssd -p '{"metadata": {"annotations":{"storageclass.kubernetes.io/is-default-class":"false"}}}'
kubectl patch storageclass scw-bssd-retain -p '{"metadata": {"annotations":{"storageclass.kubernetes.io/is-default-class":"true"}}}'
```
https://kubernetes.io/docs/tasks/administer-cluster/change-default-storage-class/

```bash
kubectl get configmap istio -n istio-system -o yaml | sed 's/mode: REGISTRY_ONLY/mode: ALLOW_ANY/g' | kubectl replace -n istio-system -f -
```



Trigger certificates renew : kubectl cert-manager renew ingress-cert-jkanda.fr -n istio-system



Custom crd :
```yaml
https://github.com/jetstack/cert-manager/releases/download/v0.15.4/cert-manager.crds.yaml
https://raw.githubusercontent.com/istio/istio/master/manifests/charts/base/crds/crd-all.gen.yaml
https://raw.githubusercontent.com/istio/operator/master/data/operator/templates/crd.yaml
```

How to deploy

```bash
kustomize build --enable_alpha_plugins production | kubectl apply -f - 
```
