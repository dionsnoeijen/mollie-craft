<?php

namespace Craft;

class Mollie_BuyerRecord extends BaseRecord
{

    /**
     * @return string
     */
    public function getTableName()
    {
        return 'mollie_buyers';
    }

    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array(
            'userId'               => [AttributeType::Number, 'required' => false],
            'name'                 => [AttributeType::String, 'required' => false],
            'surname'              => [AttributeType::String, 'required' => false],
            'street'               => [AttributeType::String, 'required' => false],
            'streetNr'             => [AttributeType::String, 'required' => false],
            'zipCode'              => [AttributeType::String, 'required' => false],
            'city'                 => [AttributeType::String, 'required' => false],
            'provinceState'        => [AttributeType::String, 'required' => false],
            'email'                => [AttributeType::Email,  'required' => false],
            'deliverName'          => [AttributeType::String, 'required' => false],
            'deliverSurname'       => [AttributeType::String, 'required' => false],
            'deliverStreet'        => [AttributeType::String, 'required' => false],
            'deliverCity'          => [AttributeType::String, 'required' => false],
            'deliverZipCode'       => [AttributeType::String, 'required' => false],
            'deliverStreetNr'      => [AttributeType::String, 'required' => false],
            'deliverProvinceState' => [AttributeType::String, 'required' => false]
        );
    }

    /**
     * @return array
     */
    public function defineRelations()
    {
        return array(
            'userId' => [static::HAS_ONE, 'UserRecord', 'id']
        );
    }
}