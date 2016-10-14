<?php

namespace Craft;

class Mollie_StatusFieldType extends BaseFieldType
{
    const OPEN = 'open';
    const PAID = 'paid';

    public function getName()
    {
        return Craft::t('Mollie Payment Status');
    }

    public function getInputHtml($name, $value)
    {
        return craft()->templates->render('mollie/_fieldtypes/input_payment_status', array(
            'name'  => $name,
            'value' => $value
        ));
    }
}