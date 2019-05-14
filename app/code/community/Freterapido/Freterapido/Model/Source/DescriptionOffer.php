<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */
class Freterapido_Freterapido_Model_Source_DescriptionOffer
{
    /**
     * Get options for description
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => Mage::helper('adminhtml')->__('Padrão')),
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('Transportadora')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('Transportadora - Serviço')),
            array('value' => 3, 'label' => Mage::helper('adminhtml')->__('Transportadora - Descricao serviço')),
            array('value' => 4, 'label' => Mage::helper('adminhtml')->__('Serviço')),
            array('value' => 5, 'label' => Mage::helper('adminhtml')->__('Descricao serviço')),
            array('value' => 6, 'label' => Mage::helper('adminhtml')->__('Serviço - Descricao serviço')),
        );
    }
}
