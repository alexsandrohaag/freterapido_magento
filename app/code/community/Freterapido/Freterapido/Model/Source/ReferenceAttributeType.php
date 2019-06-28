<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */

class Freterapido_Freterapido_Model_Source_ReferenceAttributeType
{
    /**
     * Get the options of reference attribute
     *
     * @return array
     */
    public function toOptionArray()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')->getItems();

        $attributes  = array_map(function ($attribute) {
            $value  = trim($attribute->getAttributecode());
            $label = Mage::helper('adminhtml')->__($attribute->getFrontendLabel());

            $label = empty($label)?$value:"{$label} ($value)";

            return array('value' => $value, 'label' => $label);
        }, $attributes);

        array_push($attributes, array('value' => '-', 'label' => '-'));

        usort($attributes, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        return array_filter($attributes, function ($attribute) {
            return !empty($attribute['value']) && !empty($attribute['label']);
        });
    }
}
