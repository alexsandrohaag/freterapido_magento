<?php

/**
 * @category Freterapido
 * @package Freterapido_Freterapido
 * @author freterapido.com <suporte@freterapido.com>
 * @copyright Frete Rápido (https://freterapido.com)
 * @license https://github.com/freterapido/freterapido_magento/blob/master/LICENSE MIT
 */
class Freterapido_Freterapido_Model_Source_Categoriesfr extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_options = null;

    public function getAllOptions($withEmpty = false)
    {
        if (is_null($this->_options)) {
            $this->_options = [
                ['label' => 'Abrasivos', 'value' => 1],
                ['label' => 'Acessórios de Airsoft / Paintball', 'value' => 69],
                ['label' => 'Acessórios de Arquearia', 'value' => 73],
                ['label' => 'Acessórios de Pesca', 'value' => 70],
                ['label' => 'Acessórios para celular', 'value' => 90],
                ['label' => 'Adubos / Fertilizantes', 'value' => 2],
                ['label' => 'Alimentos não perecíveis', 'value' => 74],
                ['label' => 'Alimentos perecíveis', 'value' => 3],
                ['label' => 'Arquearia', 'value' => 72],
                ['label' => 'Artesanatos', 'value' => 93],
                ['label' => 'Artigos para Camping', 'value' => 82],
                ['label' => 'Artigos para Pesca', 'value' => 4],
                ['label' => 'Auto Peças', 'value' => 5],
                ['label' => 'Bebidas / Destilados', 'value' => 6],
                ['label' => 'Bijuteria', 'value' => 99],
                ['label' => 'Brindes', 'value' => 7],
                ['label' => 'Brinquedos', 'value' => 8],
                ['label' => 'Caixa de embalagem', 'value' => 75],
                ['label' => 'Calçados', 'value' => 9],
                ['label' => 'Cargas refrigeradas/congeladas', 'value' => 62],
                ['label' => 'CD / DVD / Blu-Ray', 'value' => 10],
                ['label' => 'Cocção Industrial', 'value' => 102],
                ['label' => 'Colchão', 'value' => 66],
                ['label' => 'Combustíveis / Óleos', 'value' => 11],
                ['label' => 'Confecção', 'value' => 12],
                ['label' => 'Cosméticos', 'value' => 13],
                ['label' => 'Couro', 'value' => 14],
                ['label' => 'Derivados Petróleo', 'value' => 15],
                ['label' => 'Descartáveis', 'value' => 16],
                ['label' => 'Editorial', 'value' => 17],
                ['label' => 'Eletrodomésticos', 'value' => 19],
                ['label' => 'Eletrônicos', 'value' => 18],
                ['label' => 'Embalagens', 'value' => 20],
                ['label' => 'Equipamentos de cozinha industrial', 'value' => 107],
                ['label' => 'Equipamentos de Segurança / API', 'value' => 88],
                ['label' => 'Estiletes / Materiais Cortantes', 'value' => 84],
                ['label' => 'Estufa térmica', 'value' => 106],
                ['label' => 'Explosivos / Pirotécnicos', 'value' => 21],
                ['label' => 'Extintores', 'value' => 87],
                ['label' => 'Ferragens', 'value' => 23],
                ['label' => 'Ferramentas', 'value' => 24],
                ['label' => 'Fibras Ópticas', 'value' => 25],
                ['label' => 'Fonográfico', 'value' => 26],
                ['label' => 'Fotográfico', 'value' => 27],
                ['label' => 'Fraldas / Geriátricas', 'value' => 28],
                ['label' => 'Higiene', 'value' => 29],
                ['label' => 'Impressos', 'value' => 30],
                ['label' => 'Informática / Computadores', 'value' => 31],
                ['label' => 'Instrumento Musical', 'value' => 32],
                ['label' => 'Joia', 'value' => 100],
                ['label' => 'Limpeza', 'value' => 86],
                ['label' => 'Linha Branca', 'value' => 77],
                ['label' => 'Livro(s)', 'value' => 33],
                ['label' => 'Malas / Mochilas', 'value' => 79],
                ['label' => 'Maquina de algodão doce', 'value' => 104],
                ['label' => 'Maquina de chocolate', 'value' => 105],
                ['label' => 'Materiais Escolares', 'value' => 34],
                ['label' => 'Materiais Esportivos', 'value' => 35],
                ['label' => 'Materiais Frágeis', 'value' => 36],
                ['label' => 'Materiais hidráulicos', 'value' => 97],
                ['label' => 'Material de Construção', 'value' => 37],
                ['label' => 'Material de Irrigação', 'value' => 38],
                ['label' => 'Material Elétrico / Lâmpada(s)', 'value' => 39],
                ['label' => 'Material Gráfico', 'value' => 40],
                ['label' => 'Material Hospitalar', 'value' => 41],
                ['label' => 'Material Odontológico', 'value' => 42],
                ['label' => 'Material Pet Shop', 'value' => 43],
                ['label' => 'Material Plástico', 'value' => 50],
                ['label' => 'Material Veterinário', 'value' => 44],
                ['label' => 'Medicamentos', 'value' => 22],
                ['label' => 'Moto Peças', 'value' => 46],
                ['label' => 'Mudas / Plantas', 'value' => 47],
                ['label' => 'Máquina / Equipamentos', 'value' => 80],
                ['label' => 'Móveis com peças de vidro', 'value' => 68],
                ['label' => 'Móveis desmontados', 'value' => 64],
                ['label' => 'Móveis montados', 'value' => 45],
                ['label' => 'Outros', 'value' => 999],
                ['label' => 'Papelaria / Documentos', 'value' => 48],
                ['label' => 'Papelão', 'value' => 63],
                ['label' => 'Perfumaria', 'value' => 49],
                ['label' => 'Pia / Vasos', 'value' => 98],
                ['label' => 'Pilhas / Baterias', 'value' => 83],
                ['label' => 'Pisos (cerâmica) / Revestimentos', 'value' => 92],
                ['label' => 'Placa de Energia Solar', 'value' => 96],
                ['label' => 'Pneus e Borracharia', 'value' => 51],
                ['label' => 'Porta / Janelas', 'value' => 95],
                ['label' => 'Produto Químico classificado', 'value' => 85],
                ['label' => 'Produtos Cerâmicos', 'value' => 52],
                ['label' => 'Produtos Químicos Não Classificados', 'value' => 53],
                ['label' => 'Produtos Veterinários', 'value' => 54],
                ['label' => 'Quadros / Molduras', 'value' => 94],
                ['label' => 'Rações / Alimento para Animal', 'value' => 81],
                ['label' => 'Refrigeração Industrial', 'value' => 101],
                ['label' => 'Revistas', 'value' => 55],
                ['label' => 'Sementes', 'value' => 56],
                ['label' => 'Simulacro de Arma / Airsoft', 'value' => 71],
                ['label' => 'Sofá', 'value' => 65],
                ['label' => 'Suprimentos Agrícolas / Rurais', 'value' => 57],
                ['label' => 'Tapeçaria / Cortinas / Persianas', 'value' => 108],
                ['label' => 'Toldos', 'value' => 91],
                ['label' => 'Travesseiro', 'value' => 67],
                ['label' => 'TV / Monitores', 'value' => 76],
                ['label' => 'Têxtil', 'value' => 58],
                ['label' => 'Utensílios industriais', 'value' => 103],
                ['label' => 'Utilidades domésticas', 'value' => 89],
                ['label' => 'Vacinas', 'value' => 59],
                ['label' => 'Vestuário', 'value' => 60],
                ['label' => 'Vidros / Frágil', 'value' => 61],
                ['label' => 'Vitaminas / Suplementos nutricionais', 'value' => 78 ]
              ];
        }

        $options = $this->_options;

        if ($withEmpty) {
            array_unshift($options, array('value'=>'', 'label'=>''));
        }

        return $options;
    }

    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);

        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }
}
