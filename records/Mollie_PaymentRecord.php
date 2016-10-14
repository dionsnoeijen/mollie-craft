<?php

namespace Craft;

/**
 * Class Mollie_PaymentRecord
 * @package Craft
 */
class Mollie_PaymentRecord extends BaseRecord
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'mollie_payments';
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'paymentId'             => [AttributeType::String,  'required' => true],
            'entryId'               => [AttributeType::Number,  'required' => false],
            'paymentCreateResponse' => [AttributeType::String,  'column' => ColumnType::Text,  'required' => true],
            'amount'                => [AttributeType::String,  'required' => true],
            'description'           => [AttributeType::String,  'column' => ColumnType::Text, 'required' => true],
            'paid'                  => [AttributeType::Bool,    'default' => false],
            'refunded'              => [AttributeType::Bool,    'default' => false],
            'buyerId'               => [AttributeType::Number,  'required' => true]
        );
    }

    /**
     * @return array
     */
    public function defineIndexes()
    {
        return array(
            array('columns' => array('paymentId'), 'unique' => true),
        );
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'buyerId' => [static::HAS_ONE, 'Mollie_BuyerRecord', 'id']
        );
    }
}