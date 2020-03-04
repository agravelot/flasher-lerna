<?php


namespace App;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use Psr\Http\Message\ServerRequestInterface;

class CustomAuthGrant extends AuthCodeGrant
{
    /**
     * Validate the client.
     *
     * @param ServerRequestInterface $request
     *
     * @throws OAuthServerException
     *
     * @return ClientEntityInterface
     */
    protected function validateClient(ServerRequestInterface $request)
    {
        [$clientId, $clientSecret] = $this->getClientCredentials($request);

//        if ($this->clientRepository->validateClient($clientId, $clientSecret, $this->getIdentifier()) === false) {
//            $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
//
//            throw OAuthServerException::invalidClient($request);
//        }

        $client = $this->getClientEntityOrFail($clientId, $request);

        // If a redirect URI is provided ensure it matches what is pre-registered
        $redirectUri = $this->getRequestParameter('redirect_uri', $request, null);

        if ($redirectUri !== null) {
            $this->validateRedirectUri($redirectUri, $client, $request);
        }

        return $client;
    }
}
