<?php

namespace Craft;

class Mollie_PaymentService extends BaseApplicationComponent
{

    /**
     * @var $mollie Mollie_API_Client
     */
    private $mollie;

    public function __construct()
    {
        // Get the mollie api instance
        $this->mollie = craft()->mollie_api->getMollie();
    }

    /**
     * Create a fresh payment, it has a non payed status.
     *
     * @param $entry
     * @param $amount
     * @param $description
     * @return mixed
     */
    public function create($entry, $amount, $description)
    {
        // Api call to Mollie
        $payment = $this->mollie->payments->create(
            array(
                'amount'      => $amount,
                'description' => $description,
                'redirectUrl' => craft()->getSiteUrl() . craft()->config->get('mollieThankYouPage'),
                'webhookUrl'  => craft()->getSiteUrl() . 'actions/mollie/payment/updateStatus',
                'metadata'    => array(
                    'order_id' => $entry->id
                ),
                'testmode'  => craft()->config->get('mollieTestmode'),
                'profileId' => craft()->config->get('mollieProfileId')
            )
        );

        // Create a separate buyer record
        $buyerRecord = new Mollie_BuyerRecord();
        $name = explode(' ', $entry->donatorName, 2);
        $buyerRecord->name = $name[0];
        $buyerRecord->surname = isset($name[1]) ? $name[1] : null;
        $buyerRecord->streetNr = $entry->number;
        $buyerRecord->zipCode = $entry->zipcode;
        $buyerRecord->email = $entry->email;
        $buyerRecord->save();

        // Create local payment record
        $paymentRecord = new Mollie_PaymentRecord();
        $paymentRecord->paymentId = $payment->id;
        $paymentRecord->entryId = $entry->id;
        $paymentRecord->paymentCreateResponse = json_encode($payment);
        $paymentRecord->amount = $payment->amount;
        $paymentRecord->description = $payment->description;
        $paymentRecord->paid = false;
        $paymentRecord->refunded = false;
        $paymentRecord->buyerId = $buyerRecord->id;

        $paymentRecord->save();

        return $payment->links->paymentUrl;
    }

    /**
     * Update the payment status in:
     * - The payment record (local database)
     * - Entry created by the form.
     *
     * @param $paymentId
     * @return bool
     */
    public function update($paymentId)
    {
        $payment = $this->mollie->payments->get(
            $paymentId,
            array('testmode' => craft()->config->get('mollieTestmode'))
        );

        $paymentRecord = Mollie_PaymentRecord::model()->findByAttributes(array(
            'paymentId' => $paymentId
        ));

        if ($payment->isPaid()) {

            // Update the payment record
            $paymentRecord->paymentCreateResponse = json_encode($payment);
            $paymentRecord->paid = true;
            $paymentRecord->save();

            // Update the Element belonging to this payment
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
            $criteria->id = $paymentRecord->entryId;

            $entries = $criteria->find();
            $entry = $entries[0];

            $entry->setContentFromPost(array(
                'paymentStatus' => Mollie_StatusFieldType::PAID
            ));
            $success = craft()->entries->saveEntry($entry);

            if (!$success) {

                MolliePlugin::log('Couldn’t update the entry after a Mollie payment"'.$entry->title.'"', LogLevel::Error);
            } else {

                MolliePlugin::log('Updated the payment status after succesfull payment through the Mollie api');
            }

            return true;
        }

        return false;
    }
}