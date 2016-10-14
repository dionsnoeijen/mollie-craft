<?php

namespace Craft;

class MollieController extends BaseController
{
    public function actionSettings()
    {
        craft()->mollie_plugin->requireDependencies();

        $variables = array(
            'provider' => false,
            'account' => false,
            'token' => false,
            'error' => false
        );

        $provider = craft()->oauth->getProvider('mollie');

        if ($provider && $provider->isConfigured()) {

            $token = craft()->mollie_oauth->getToken();

            if ($token) {

                $account = $provider->getAccount($token);
            }

            $variables['token'] = $token;
            $variables['provider'] = $provider;
        }

        $this->renderTemplate('mollie/settings', $variables);
    }
}