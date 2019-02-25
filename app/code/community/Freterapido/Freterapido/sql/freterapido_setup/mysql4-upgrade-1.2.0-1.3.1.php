<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
*/
$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

// atualiza atributo fr_volume_comprimento
$setup->updateAttribute('catalog_product', 'fr_volume_comprimento', 'is_required', false);
$setup->updateAttribute('catalog_product', 'fr_volume_comprimento', 'note', 'Comprimento da embalagem do produto (Frete Rápido)');

// atualiza atributo fr_volume_altura
$setup->updateAttribute('catalog_product', 'fr_volume_altura', 'is_required', false);
$setup->updateAttribute('catalog_product', 'fr_volume_altura', 'note', 'Altura da embalagem do produto (Frete Rápido)');

// atualiza atributo fr_volume_largura
$setup->updateAttribute('catalog_product', 'fr_volume_largura', 'is_required', false);
$setup->updateAttribute('catalog_product', 'fr_volume_largura', 'note', 'Largura da embalagem do produto (Frete Rápido)');

// atualiza atributo fr_volume_prazo_fabricacao
$setup->updateAttribute('catalog_product', 'fr_volume_prazo_fabricacao', 'note', 'Será acrescido no prazo do frete (Frete Rápido)');

// atualiza atributo fr_category
$setup->updateAttribute('catalog_category', 'fr_category', 'is_required', false);

// atualiza grupo dos atributos
$attributes = ['fr_volume_comprimento', 'fr_volume_altura', 'fr_volume_largura', 'fr_volume_prazo_fabricacao'];
foreach ($setup->getAllAttributeSetIds('catalog_product') as $setId) {
    $groupId    = $setup->getAttributeGroupId("catalog_product", $setId, 'General');

    foreach ($attributes as $attribute) {
        $attributeId = $setup->getAttributeId('catalog_product', $attribute);
        $setup->addAttributeToGroup('catalog_product', $setId, $groupId, $attributeId);
    }
}

$installer->endSetup();
