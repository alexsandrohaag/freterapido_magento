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

// adiciona o atributo 'fr_volume_consolidar' no cadastro de produtos da loja, para realização do cálculo do frete
$setup->addAttribute('catalog_product', 'fr_volume_consolidar', [
    'position' => 5,
    'required' => false,
    'label'    => 'Consolidar',
    'type'     => 'int',
    'input'    => 'select',
    'source'   => 'eav/entity_attribute_source_boolean',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'apply_to' => 'simple,bundle,grouped,configurable',
    'note'     => 'Permitir a sobreposição do produto na consolidação (Frete Rápido)'
]);

// adiciona o atributo 'fr_volume_sobreposto' no cadastro de produtos da loja, para realização do cálculo do frete
$setup->addAttribute('catalog_product', 'fr_volume_sobreposto', [
    'position' => 6,
    'required' => false,
    'label'    => 'Sobreposto',
    'type'     => 'int',
    'input'    => 'select',
    'source'   => 'eav/entity_attribute_source_boolean',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'apply_to' => 'simple,bundle,grouped,configurable',
    'note'     => 'Permitir a sobreposição do produto na consolidação (Frete Rápido)'
]);

// adiciona o atributo 'fr_volume_tombar' no cadastro de produtos da loja, para realização do cálculo do frete
$setup->addAttribute('catalog_product', 'fr_volume_tombar', [
    'position' => 6,
    'required' => false,
    'label'    => 'Tombar',
    'type'     => 'int',
    'input'    => 'select',
    'source'   => 'eav/entity_attribute_source_boolean',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'apply_to' => 'simple,bundle,grouped,configurable',
    'note'     => 'Permitir tombar o produto na consolidação (Frete Rápido)'
]);

// adiciona a tab 'General' no cadastro de produtos da loja
$attributes = ['fr_volume_consolidar', 'fr_volume_sobreposto', 'fr_volume_tombar'];

foreach ($setup->getAllAttributeSetIds('catalog_product') as $setId) {
    $groupId    = $setup->getAttributeGroupId("catalog_product", $setId, 'General');

    foreach ($attributes as $attribute) {
        $attributeId = $setup->getAttributeId('catalog_product', $attribute);
        $setup->addAttributeToGroup('catalog_product', $setId, $groupId, $attributeId);
    }
}

$installer->endSetup();
