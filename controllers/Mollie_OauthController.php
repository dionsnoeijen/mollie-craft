<?php

namespace Craft;

class Mollie_OauthController extends BaseController
{
    /**
     * Connect to the Mollie Api and get a token.
     */
    public function actionConnect() {

        $referrer = craft()->httpSession->get('mollie.referrer');

        if (!$referrer) {

            $referrer = craft()->request->getUrlReferrer();

            craft()->httpSession->add('mollie.referrer', $referrer);

            MolliePlugin::log('Mollie OAuth Connect Step 1: '."\r\n".print_r(['referrer' => $referrer], true), LogLevel::Info);
        }

        // connect
        if ($response = craft()->oauth->connect(array(
            'plugin'   => 'mollie',
            'provider' => 'mollie',
            'scope'    => array('organizations.read','payments.read','payments.write')
        ))) {

            if ($response['success']) {

                // token
                $token = $response['token'];

                // save token
                craft()->mollie_oauth->saveToken($token);

                MolliePlugin::log('Mollie OAuth Connect Step 2: '."\r\n".print_r(['token' => $token], true), LogLevel::Info);

                // session notice
                craft()->userSession->setNotice(Craft::t('Connected to Mollie.'));

            } else {

                // session error
                craft()->userSession->setError(Craft::t($response['errorMsg']));
            }
        } else {

            // session error
            craft()->userSession->setError(Craft::t('Couldn\'t connect'));
        }

        craft()->httpSession->remove('mollie.referrer');

        $this->redirect($referrer);
    }

    /**
     * Disconnect
     *
     * @return null
     */
    public function actionDisconnect()
    {
        if (craft()->mollie_oauth->deleteToken()) {

            craft()->userSession->setNotice(Craft::t('Disconnected from Mollie.'));
        } else {

            craft()->userSession->setError(Craft::t('Couldn\'t disconnect from Mollie'));
        }

        // redirect
        $redirect = craft()->request->getUrlReferrer();
        $this->redirect($redirect);
    }
}