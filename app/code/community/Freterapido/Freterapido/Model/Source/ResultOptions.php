<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */
class Freterapido_Freterapido_Model_Source_ResultOptions
{
    /**
     * Get options for weight
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => Mage::helper('adminhtml')->__('Sem filtro (todas as ofertas)')),
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('Somente a oferta com menor preço')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('Somente a oferta com menor prazo de entrega'))
        );
    }
}
