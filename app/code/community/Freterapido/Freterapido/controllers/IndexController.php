<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */

use ___PHPSTORM_HELPERS\object;

class Freterapido_Freterapido_IndexController extends Mage_Core_Controller_Front_Action
{
    const CODE = 'freterapido';
    const FR_STATUS_ENTREGUE = 3;

    private function _response($status, $message = '', $error = '')
    {
        header('Content-Type: application/json');
        http_response_code(400);

        $data = [];
        $data['error'] = !empty($error) ? true : false;
        $data['message'] = empty($message) ? $error : $message;

        exit(json_encode($data));
    }

    public function indexAction()
    {
        /** @var object $occurrence */
        $occurrence = null;

        $body = $this->getRequest()->getRawBody();

        try {
            $occurrence = json_decode($body);
        } catch (\Exception $e) { }

        if (!empty($occurrence)) {
            $required_fields = [
                'id_frete',
                'numero_pedido',
                'codigo',
                'nome',
                'data_ocorrencia',
            ];

            $attributes = array_keys(get_object_vars($occurrence));

            $valid = true;
            foreach ($required_fields as $required) {
                $valid = $valid && in_array($required, $attributes);
            }

            if (!$valid) {
                $this->_response(400, '', "Todos os dados devem ser informados");
            }

            //Verifica se encontrou o pedido
            $order = Mage::getModel('sales/order')->loadByIncrementId($occurrence->numero_pedido);
            if (empty($order->getIncrementID())) {
                $this->_response(404, '', "Nenhum pedido localizado - ID pedido: {$occurrence->numero_pedido}");
            }

            $shipment = reset(Mage::getResourceModel('sales/order_shipment_track_collection')
                ->setOrderFilter($order)
                ->getData());

            //Verifica se foi encontrado rastreio para o pedido
            if (empty($shipment)) {
                $this->_response(404, '', "Nenhum frete localizado - ID pedido: {$occurrence->numero_pedido}");
            }

            $shipment = (object) $shipment;
            $shipment->track_number = str_replace('#', '', $shipment->track_number);
            $occurrence->id_frete = str_replace('#', '', $occurrence->id_frete);

            if ($shipment->track_number != $occurrence->id_frete) {
                $this->_response(404, '', "O pedido informado não possui envio pela Frete Rápido - ID pedido: {$occurrence->numero_pedido}");
            }

            $update_date = (new DateTime($occurrence->data_ocorrencia));
            $update_date = $update_date->format('d/m/Y') . ' às ' . $update_date->format('H:i');

            $message = '';
            if ((isset($occurrence->mensagem)) && (!empty($occurrence->mensagem))){
                $message = "[{$occurrence->mensagem}]";
            }

            //Atualiza o pedido para finalizado quando entreue para Frete Rápido
            if ($order->getData('state') != 'complete') {
                try {
                    if ($occurrence->codigo == self::FR_STATUS_ENTREGUE) {
                        $order->setData('state', "complete");
                        $order->setStatus("complete");
                        $history = $order->addStatusHistoryComment("Entrega realizada em {$update_date}", false);
                    } else {
                        $history = $order->addStatusHistoryComment("{$occurrence->nome} em {$update_date} {$message}", false);
                    }
                    $history->setIsCustomerNotified(false);
                    $order->save();
                    $this->_response(200, 'Pedido atualizado com sucesso', '');
                } catch (\Exception $e) {
                    $this->_response(500, '', "Não foi possível atualizar o status - ID pedido: {$occurrence->numero_pedido}");
                }
            }
        }
    }
}
