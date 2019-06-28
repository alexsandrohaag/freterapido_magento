<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */

class Freterapido_Freterapido_Model_Source_ReferenceAttributeStateRegistrationType
{
    /**
     * Prepare the options of the attributes of a model
     *
     * @param  string $model - Model no qual se deseja retornar os parâmetros
     * @return array
     */
    private function _getAttributes(string $model)
    {
        $model_attributes = Mage::getModel($model)->getAttributes();

        $attributes = array_map(function ($attribute) {
            $value = trim($attribute->getAttributecode());
            $label = Mage::helper('adminhtml')->__($attribute->getFrontendLabel());

            $label = empty($label) ? $value : "{$label} ($value)";

            return array('value' => $value, 'label' => $label);
        }, $model_attributes);

        $attributes = !empty($attributes) ? $attributes : array(array('value' => null, 'label' => null));

        usort($attributes, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        return $attributes;
    }

    /**
     * Get the options of reference attribute of State registration [Inscrição estadual]
     *
     * @return array
     */
    public function toOptionArray()
    {

        //Retorna os atributos
        $customer_attributes       = $this->_getAttributes('customer/customer');
        $order_attributes          = $this->_getAttributes('sales/order');
        $order_shipment_attributes = $this->_getAttributes('sales/order_shipment');

        $attributes = array_merge($order_shipment_attributes, $customer_attributes, $order_attributes);

        array_unshift($attributes, ['value' => '-', 'label' => '-']);

        return array_filter($attributes, function ($attribute) {
            return !empty($attribute['value']);
        });
    }
}
