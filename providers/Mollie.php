<?php

namespace Mollie\Providers;

use Craft\UrlHelper;
use Dukt\OAuth\Providers\BaseProvider;
use Mollie\OAuth2\Client\Provider\Mollie as MollieOauth;

class Mollie extends BaseProvider
{
    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return 'Mollie';
    }

    /**
     * Get Icon URL
     *
     * @return string
     */
    public function getIconUrl()
    {
        return UrlHelper::getResourceUrl('admin/resources/images/default_plugin.svg?x=tephFHNy6');
    }

    /**
     * Get OAuth Version
     *
     * @return int
     */
    public function getOauthVersion()
    {
        return 2;
    }

    /**
     * Get API Manager URL
     *
     * @return string
     */
    public function getManagerUrl()
    {
        return 'https://www.mollie.com/dashboard/applications';
    }

    /**
     * Get Scope Docs URL
     *
     * @return string
     */
    public function getScopeDocsUrl()
    {
        return '#';
    }

    /**
     * Create Mollie Provider
     *
     * @return Mollie
     */
    public function createProvider()
    {
        $config = [
            'clientId' => $this->providerInfos->clientId,
            'clientSecret' => $this->providerInfos->clientSecret,
            'redirectUri' => $this->getRedirectUri()
        ];

        return new MollieOauth($config);
    }
}