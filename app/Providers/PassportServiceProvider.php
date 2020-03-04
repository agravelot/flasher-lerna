<?php


namespace App\Providers;


use App\CustomAuthGrant;
use DateInterval;
use Laravel\Passport\Bridge\AuthCodeRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\PassportServiceProvider as PassportServiceProviderBase;

class PassportServiceProvider extends PassportServiceProviderBase
{
    /**
     * Build the Auth Code grant instance.
     *
     * @return \League\OAuth2\Server\Grant\AuthCodeGrant
     * @throws \Exception
     */
    protected function buildAuthCodeGrant()
    {
        return new CustomAuthGrant(
            $this->app->make(AuthCodeRepository::class),
            $this->app->make(RefreshTokenRepository::class),
            new DateInterval('PT10M')
        );
    }
}
