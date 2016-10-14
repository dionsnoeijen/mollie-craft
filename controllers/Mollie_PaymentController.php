<?php

namespace Craft;

class Mollie_PaymentController extends BaseController
{

    /**
     * @var bool
     */
    protected $allowAnonymous = true;

    /**
     * This is the enpoint for the webhook. Mollie calls this when a payment status is updated.
     * Mollie sends along the id with the post data. This id is used for the systemwide update.
     */
    public function actionUpdateStatus()
    {
        $this->requirePostRequest();
        $post = craft()->request->getPost();
        $paymentId = $post['id'];

        if (craft()->mollie_payment->update($paymentId)) {

            echo 'updated to paid';
        } else {

            echo 'payment not set to paid';
        }
        exit;
    }
}