<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */
class Freterapido_Freterapido_Model_Carrier_Freterapido extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    const CODE = 'freterapido';

    const TITLE = 'Frete Rápido';

    protected $_code = self::CODE;

    protected $_result = null;

    protected $_title = self::TITLE;

    protected $_sender = array(
        'cnpj' => null,
        'endereco' => array(
            'cep' => null
        )
    );

    protected $_receiver = array(
        'endereco' => array(
            'cep' => null
        )
    );

    protected $_freight_type = 1;

    protected $_carriers = array();

    protected $_platform_code = null;

    protected $_manufacturing_time = 0; // Adiciona o tempo de fabricação do produto selecionado

    protected $_free_shipping = false;

    protected $_free_shipping_minimum = 0;

    protected $_products_total_value = 0;

    protected $_limit = 5;

    protected $_filter = 0;

    protected $_token = null;

    protected $_quote_id = 0;

    protected $_volumes = array();

    protected $_offer_token = null;

    // Função chamada pelo Magento para calcular frete
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        try {
            // Realiza a checagem inicial
            if (!$this->_initialCheck($request)) {
                return false;
            }

            $this->_result = Mage::getModel('shipping/rate_result');

            $this->_free_shipping = $this->getConfigData('free_shipping');
            $this->_free_shipping_minimum = $this->getConfigData('free_shipping_minimum_value');
            $this->_filter = $this->getConfigData('filter');
            $this->_limit = $this->getConfigData('limit');
            $this->_platform_code = $this->getConfigData('platform_code');
            $this->_token = $this->getConfigData('token');

            // Obtém o id da cotação atual no Magento
            $itens = [Mage::getSingleton('checkout/session')->getQuoteId()];
            foreach ($request->getAllItems() as $item) {
                $itens[] = "{$item->getProductId()}|{$item->getSku()}|{$item->getName()}|{$item->getQty()}";
            }

            $this->_quote_id = md5(implode("-", $itens));

            // Obtém os volumes
            $this->_getVolumes($request);

            // Realiza a cotação
            $this->_getQuoteApi();

            // Retorna o XML com o resultado para o Magento.
            return $this->_result;
        } catch (Exception $e) {
            $this->_throwError('apierror', $e->getMessage() . ' - [' . __LINE__ . '] ' . $e->getFile());
        }
    }

    public function getAllowedMethods()
    {
        return array($this->_code => $this->_title);
    }

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return bool
     */
    protected function _initialCheck(Mage_Shipping_Model_Rate_Request $request)
    {
        // Verifica se o módulo está ativo
        if (!$this->getConfigFlag('active')) {
            $this->_log('Desabilitado');
            return false;
        }

        // Verifica se origem e destino estão dentro da área de atuação do Frete Rápido
        $origin_country = Mage::getStoreConfig('shipping/origin/country_id', $this->getStore());
        $destination_country = $request->getDestCountryId();

        if ($origin_country != 'BR' || $destination_country != 'BR') {
            $this->_log('Fora da área de atuação do Frete Rápido');
            return false;
        }

        if (!$this->_checkZipCode($request)) {
            return false;
        }

        return true;
    }

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return bool
     */
    protected function _checkZipCode(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->_getSender()) {
            $this->_log('Não foi possível recuperar os dados de origem.');

            return false;
        }

        if (!$this->_getReceiver($request)) {
            $this->_log('Não foi possível recuperar os dados do destinatário.');

            return false;
        }

        return true;
    }

    /**
     * Obtém os dados da origem
     */
    protected function _getSender()
    {
        $this->_sender = array();
        $this->_sender['cnpj'] = preg_replace("/\D/", '', $this->getConfigData('shipper_cnpj'));

        return true;
    }

    protected function _getReceiver(Mage_Shipping_Model_Rate_Request $request)
    {
        $order = false;
        $cnpj_cpf = '';
        $ref_attr = Mage::helper($this->_code)->getConfigData('ref_attr_state_registration_type');

        foreach ($request->getAllItems() as $item) {
            $order = $item->getQuote();
            break;
        }

        //Se conseguir localizar o pedido, pega os dados por ele
        if ($order) {
            $cnpj_cpf = $order->getShippingAddress()->getData('vat_id');
            $state_registration = $order->getShippingAddress()->getData($ref_attr);
            $post_code = $order->getShippingAddress()->getPostcode();

            if (empty($cnpj_cpf)) {
                $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
                $cnpj_cpf = $customer->getData('taxvat');
            }
            if (empty($state_registration)) {
                $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
                $state_registration = $customer->getData($ref_attr);
            }
        }

        $cnpj_cpf = preg_replace("/\D/", '', $cnpj_cpf);
        $state_registration = preg_replace("/\D/", '', $state_registration);

        $post_code = !empty($post_code) ? $post_code : $request->getDestPostcode();

        // Seta como pessoa física ou jurídica conforme o cpf/cnpj retornado
        $this->_receiver = array();
        $this->_receiver['tipo_pessoa'] = strlen($cnpj_cpf) == 14 ? 2 : 1;
        $this->_receiver['cnpj_cpf'] = $cnpj_cpf;

        if ($this->_receiver['tipo_pessoa'] == 2) {
            $this->_receiver['inscricao_estadual'] = !empty($state_registration) ? $state_registration : 'ISENTO';
        }

        // Recupera o CEP digitado pelo usuário
        $this->_receiver['endereco']['cep'] = $this->_formatZipCode($post_code);

        return true;
    }

    /**
     * Formata e valida o CEP informado
     *
     * @param string $zipcode
     * @return boolean|string
     */
    protected function _formatZipCode($zipcode)
    {
        $new_zipcode = preg_replace("/\D/", '', trim($zipcode));

        if (strlen($new_zipcode) !== 8) {
            //CEP está errado
            $this->_log('O CEP digitado é inválido');

            return false;
        }

        return $new_zipcode;
    }

    /**
     * Realiza a cotação na API do Frete Rápido
     */
    protected function _getQuoteApi()
    {
        // Realiza a chamada POST
        $response = $this->_requestApi();

        if ($response->getStatus() != 200) {
            $this->_throwError('apierror', 'Erro ao tentar se comunicar com a API - Code: ' . $response->getStatus() .
                '. Error: ' . $response->getMessage() . ' ' . $response->getBody());
            return $this->_result;
        }

        $response = json_decode($response->getBody(), true);

        $this->_carriers = isset($response['transportadoras']) ? $response['transportadoras'] : array();

        $this->_log('Foram retornadas ' . count($this->_carriers) . ' Transportadoras na consulta');

        // Seta o token da oferta
        $this->_offer_token = $response['token_oferta'];

        // Se não retornar nenhuma transportadora na chamada, retorna o resultado vazio
        if (empty($this->_carriers)) {
            return $this->_result;
        }

        // Separa as colunas de preço e prazo para realizar a ordenação
        $price_column = array_column($this->_carriers, 'preco_frete');
        $deadline_column = array_column($this->_carriers, 'prazo_entrega');

        // Ordena as transportadoras por preço e prazo
        array_multisort($price_column, SORT_ASC, $deadline_column, SORT_ASC, $this->_carriers);

        // Se estiver marcada a opção de 'Frete Grátis' na configuração, altera o frete mais barato para 'Frete Grátis'
        if (!empty($this->_carriers[0]) && $this->_free_shipping && $this->_free_shipping_minimum < $this->_products_total_value) {
            $this->_carriers[0]['preco_frete'] = 0;
        }

        foreach ($this->_carriers as $key => $carrier) {
            if (empty($carrier)) {
                continue;
            }

            $this->_appendShippingReturn((object) $carrier);
        }
    }

    protected function _requestApi()
    {
        // Dados que serão enviados para a API do Frete Rápido
        $request_data = array(
            'remetente'         => $this->_sender,
            'destinatario'      => $this->_receiver,
            'volumes'           => $this->_volumes,
            'tipo_frete'        => $this->_freight_type,
            'token'             => $this->_token,
            'codigo_plataforma' => $this->_platform_code
        );

        $_channel = Mage::helper(self::CODE)->getConfigData('sales_channel');

        if (!empty($_channel)) {
            $request_data['canal'] = $_channel;
        }

        if (!is_null($this->_quote_id)) {
            $request_data['cotacao_plataforma'] = $this->_quote_id;
        }

        // Adiciona o filtro caso tenhas sido selecionado
        if ($this->_filter) {
            $request_data['filtro'] = (int) $this->_filter;
        }

        // Adiciona o limite de ofertas disponíveis caso tenhas sido selecionado
        if ($this->_limit) {
            $request_data['limite'] = (int) $this->_limit;
        }

        $config = array(
            'adapter' => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false
            ),
        );

        // Configura o cliente http passando a URL da API e a configuração
        $client = new Zend_Http_Client($this->getConfigData('api_simulation_url'), $config);

        // Adiciona os parâmetros à requisição
        $client->setRawData(json_encode($request_data), 'application/json');

        $this->_log('Realizando cotação...');

        return $client->request('POST');
    }

    protected function _methodTitle($carrier)
    {
        $description_offer = (int) $this->getConfigData('generic_description_offer');

        switch ($description_offer) {
            case 1: //Nome da transportadora
                $title = $carrier->nome;
                break;
            case 2: // Nome da transportadora - Serviço
                $title = "{$carrier->nome} - {$carrier->servico}";
                break;
            case 3: // Nome da transportadora - Descricao serviço
                $title = "{$carrier->nome} - {$carrier->descricao_servico}";
                break;
            case 4: // Serviço
                $title = $carrier->servico;
                break;
            case 5: // Descricao serviço
                $title = $carrier->descricao_servico;
                break;
            case 6: // serviço - Descricao serviço
                $title = "{$carrier->servico} - {$carrier->descricao_servico}";
                break;
            default: // Nome da transportadora [ - Serviço (correios)]
                $title = $carrier->nome;
                if (strtolower($carrier->nome) == 'correios') {
                    $title .= " - {$carrier->servico}";
                }
                break;
        }

        $function = function_exists('mb_strtoupper') ? 'mb_strtoupper' : 'strtoupper';
        return $function(trim($title, " -"));
    }

    /**
     * Adiciona o retorno de cada Transportadora no resultado
     *
     * @param $carrier
     */
    protected function _appendShippingReturn($carrier)
    {
        $carrier->method_title = $this->_methodTitle($carrier);

        // Seta o nome do método
        $shipping_method = $this->_offer_token . '_' . $carrier->oferta;

        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier($this->_code);

        $method->setMethod($shipping_method);

        $deadline = $carrier->prazo_entrega + $this->_manufacturing_time;

        $deadline_msg = $deadline > 1 ? 'dias úteis' : 'dia útil';

        if ($carrier->preco_frete == 0) {
            $carrier->method_title = 'FRETE GRÁTIS';
        }

        $method->setMethodTitle(sprintf(
            $this->getConfigData('msgprazo'),
            $carrier->method_title,
            $deadline,
            $deadline_msg
        ));

        // Diz ao Magento qual será o valor do frete
        $method->setPrice($carrier->preco_frete);

        // Diz qual será o custo do frete para a loja. Esta informação não é exibida
        $method->setCost($carrier->custo_frete);

        $this->_result->append($method);
    }

    /**
     * Prepara os volumes para serem enviados à API
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     */
    protected function _getVolumes(Mage_Shipping_Model_Rate_Request $request)
    {
        foreach ($request->getAllItems() as $item) {
            if ($item->getProductType() == 'bundle') {
                continue;
            }
            $product = $item->getProduct();
            $sku = $product->getSku();

            if ($item->getParentItemId()) {
                $parent = $item->getParentItem();
                if ($parent->getProductType() != 'bundle') {
                    continue;
                }
            }

            if ($product->isVirtual()) {
                continue;
            }

            $sku = $product->getSku();

            // Recupera os ids das categorias relacionadas ao produto
            $categories_ids = $product->getCategoryIds();
            $categories = [];

            foreach ($categories_ids as $id) {
                $_category = Mage::getModel('catalog/category')->load($id);
                $_category_fr = $_category->getData('fr_category');

                // Verifica se o Model de categoria não está vazio e se a categoria do FR foi definida para o produto
                if (!empty($_category) && !empty($_category_fr)) {
                    $_level = $_category->getData('level');
                    $categories[$_level] = $_category->getData('fr_category');
                }
            }

            // Ordena para que a categoria com nível maior (mais específica) fique na primeira posição
            krsort($categories);
            $categories = array_values($categories);

            // Verifica se a categoria foi encontrada e inserida no array, remove as chaves e extrai o primeiro item do array
            $type = is_array($categories) && !empty($categories[0]) ? $categories[0] : 999;

            if ($item->getParentItemId() && $item->getParentItem()->getProductType() == 'bundle') {
                $qtde_bundle = $item->getParentItem()->getQty();
            } else {
                $qtde_bundle = 1;
            }

            $quantity = $item->getQty() * $qtde_bundle;
            $weight = (float) $item->getWeight() * $quantity;
            $value = (float) $item->getBasePrice() * $quantity;

            if (!isset($this->_volumes[$sku])) {
                $this->_volumes[$sku] = ['quantidade' => 0, 'peso' => 0, 'valor' => 0];
            }

            $this->_volumes[$sku]['tipo']        = (int) $type;
            $this->_volumes[$sku]['quantidade'] += (int) $quantity;
            $this->_volumes[$sku]['peso']       += $this->_weightVerify($weight);
            $this->_volumes[$sku]['valor']      += $value;

            $v = $this->_volumes[$sku];

            /**
             * O mesmo produto pode ter item pai e item filho. Neste caso, no item pai não existe informações sobre as medidas e
             * no item filho não possui informações como preço e quantidade no carrinho.
             * Assim, Verifica se é item pai ou filho para pegar as informações necessárias de cada tipo e montar o volume.
             * No entanto se o produto não possuir item filho, todas as informações são extraídas do item pai.
             */
            if (!$item->getParentItemId()) {
                // Soma ao valor total do carrinho
                $this->_products_total_value += $value;

                // Verifica se não possui item filho
                if (!$item->hasChild()) {
                    $this->_getFrFields($item, $sku);
                }
            } else {
                $this->_getFrFields($item, $sku);
            }
        }

        // Define o array sem os Sku como chave
        $this->_volumes = array_values($this->_volumes);
    }

    /**
     * Tenta obter as medidas do produto, se for 0 ou vazio tenta obter as medidas genéricas preenchidas na configuração
     * caso também não esteja preenchido ou seja = 0, retorna valor informado como $def
     */
    protected function _getProductConfigFr($product, $fr_volume, $generic = null, $def = null)
    {
        //Consulta um valor baseado nas configurações do atributo
        $ref_attr = $this->getConfigData("ref_attr_{$generic}_type");
        if (!empty($ref_attr)) {
            $attribute = $product->getData($ref_attr);
            if (!empty($attribute)) {
                return $attribute;
            }
        }

        // Consuta informação nos atributos da Frete Rápido
        $product_value = $product->getData("fr_volume_{$fr_volume}");
        if (!empty($product_value)) {
            return $product_value;
        }

        // Consulta informaçao do volume com padrão de outros modulos já instalado
        $product_value = $product->getData("volume_{$fr_volume}");
        if (!empty($product_value)) {
            return $product_value;
        }

        if (!is_null($generic)) {
            $generic_value = $this->getConfigData("generic_{$generic}");
            if (!empty($generic_value)) {
                return $generic_value;
            }
        }


        return $def;
    }

    /**
     * Recupera os campos personalizados do Frete Rápido
     *
     * @param $item
     */
    protected function _getFrFields($item, $sku)
    {
        $product = $item->getProduct();

        $height           = $this->_getProductConfigFr($product, 'altura', 'height', 0);
        $width            = $this->_getProductConfigFr($product, 'largura', 'width', 0);
        $length           = $this->_getProductConfigFr($product, 'comprimento', 'length', 0);
        $prazo_fabricacao = $this->_getProductConfigFr($product, 'prazo_fabricacao');
        $consolidar       = $this->_getProductConfigFr($product, 'consolidar', 'consolidate', false);
        $sobreposto       = $this->_getProductConfigFr($product, 'sobreposto', 'overlaid', false);
        $tombar           = $this->_getProductConfigFr($product, 'tombar', 'topple', false);

        if (!empty($prazo_fabricacao) && $prazo_fabricacao > $this->_manufacturing_time) {
            $this->_manufacturing_time = $prazo_fabricacao;
        }

        $this->_volumes[$sku]['sku']         = $sku;
        $this->_volumes[$sku]['altura']      = (float) $height / 100; // Converte para metros
        $this->_volumes[$sku]['largura']     = (float) $width / 100;  // Converte para metros
        $this->_volumes[$sku]['comprimento'] = (float) $length / 100; // Converte para metros
        $this->_volumes[$sku]['consolidar']  = (bool) $consolidar;
        $this->_volumes[$sku]['sobreposto']  = (bool) $sobreposto;
        $this->_volumes[$sku]['tombar']      = (bool) $tombar;
    }

    /**
     * Verifica se o peso está definido em gramas ou quilos e converte, se necessário
     *
     * @param $weight
     * @return float|int
     */
    protected function _weightVerify($weight)
    {
        if ($this->getConfigData('weight_type') == Freterapido_Freterapido_Model_Source_WeightType::WEIGHT_IN_GR) {
            $new_weight = $weight * 1000;
        } else {
            $new_weight = $weight;
        }

        return (float) number_format($new_weight, 2);
    }

    // ---- Métodos para verificar o tracking dos pedidos ----

    /**
     * Verifica se o módulo permite o tracking
     *
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return true;
    }

    /**
     * Obtém as informações do tracking
     *
     * @param mixed $tracking Tracking
     *
     * @return mixed
     */
    public function getTrackingInfo($tracking)
    {
        $fr_tracking = new Freterapido_Freterapido_Model_Tracking();

        $result = $fr_tracking->getTracking($tracking);
        if ($result instanceof Mage_Shipping_Model_Tracking_Result) {
            if ($trackings = $result->getAllTrackings()) {
                return $trackings[0];
            }
        } elseif (is_string($result) && !empty($result)) {
            return $result;
        }

        return false;
    }

    // ---- Métodos para Log do módulo ----

    /**
     * Armazena no log a mensagem informada
     *
     * @param string $mensagem
     */
    protected function _log($mensagem)
    {
        Mage::log('Frete Rápido: ' . $mensagem);
    }

    /**
     * Prepara mensagem de erro para ser exibida
     *
     * @param $message
     * @param null $log
     */
    protected function _throwError($message, $log = null)
    {
        $this->_result = null;
        $this->_result = Mage::getModel('shipping/rate_result');

        // Recupera o model de erro da transportadora
        $error = Mage::getModel('shipping/rate_result_error');
        $error->setCarrier($this->_code);
        $error->setCarrierTitle($this->getConfigData('title'));

        // Armazena o erro no log do sistema
        $this->_log($log);
        $error->setErrorMessage($this->getConfigData($message));

        // Exibe o erro
        $this->_result->append($error);
    }
}
