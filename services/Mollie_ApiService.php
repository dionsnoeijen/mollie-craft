<?php

namespace Craft;

use Mollie_API_Client;

/**
 * Create a prepped Mollie_API_Client instance, ready for use.
 *
 * Class Mollie_ApiService
 * @package Craft
 */
class Mollie_ApiService extends BaseApplicationComponent
{
    /**
     * @var Mollie_API_Client
     */
    private $mollie;

    /**
     * Mollie_ApiService constructor.
     */
    public function __construct()
    {
        // client
        $token = craft()->mollie_oauth->getToken();

        $this->mollie = new Mollie_API_Client();
        $this->mollie->setAccessToken($token->accessToken);
    }

    /**
     * @return Mollie_API_Client
     */
    public function getMollie()
    {
        return $this->mollie;
    }
}