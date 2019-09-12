<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */

class Freterapido_Freterapido_Model_Source_OrderStatusOnHire
{
    /**
     * Return the available statuses for apply in orders is orders freight is hired
     *
     * @return array
     */
    public function toOptionArray()
    {
        //Lista todos os status cadastrados no Magento
        $statuses = Mage::getResourceModel('sales/order_status_collection')
            ->joinStates()
            ->getData();

        //Lista apenas aqueles que tem um 'state' associado a ele
        $statuses = array_filter($statuses, function ($status) {
            return !empty($status['state']);
        });

        //Faz o parse para o formato do Magento
        $statuses = array_map(function ($status) {
            return array('value' => $status['status'], 'label' => $status['label'].' ('.$status['state'].')');
        }, $statuses);

        $statuses[] = [
            'value' => '-',
            'label' => '-'
        ];

        //Ordena as informações em ordem alfabética
        usort($statuses, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        return $statuses;
    }
}
