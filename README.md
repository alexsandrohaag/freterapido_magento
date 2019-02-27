![Frete Rápido - Sistema Inteligente de Gestão Logística](https://freterapido.com/imgs/frete_rapido.png)

<hr>

### **Módulo para plataforma Magento**

Versão do módulo: 1.3.1

Compatibilidade com Magento: **1.9.x**

Links úteis:

- [Magento Marketplace][1]
- [Painel administrativo][2]
- [suporte@freterapido.com][3]


-------------

### IMPORTANTE

Este módulo é apenas um referencial de integração e cabe ao cliente a função de configurá-lo e adaptá-lo a sua respectiva loja, levando em conta as particularidades e conflitos que podem surgir durante o processo de integração.

A Frete Rápido não mantem e/ou oferece suporte para a integração com o **Magento**, disponibilizamos o módulo padrão que atente a modalidade de envio simples.

**Este módulo não opera Dropshipphig!**

Caso seja necessário adaptações deste módulo para atender a sua loja, é possível alterar o código fonte, desde que atenda a [API da Frete Rápido][9]. E [neste link][8] você encontra a documentação de orientações do **Magento**.

A Frete Rápido não se responsabiliza por eventualidades advindas deste módulo.

--------------

### Instalação

>**<i class="icon-attention"></i> ATENÇÃO!** Recomendamos que seja feito backup da sua loja antes de realizar qualquer instalação. A instalação desse módulo é de inteira responsabilidade do lojista.


- [Baixe a última versão aqui][4],  descompacte o conteúdo do arquivo zip dentro da pasta "app" da sua loja Magento.
- Verifique se o arquivo **local.xml** está habilitado na pasta "app/etc" da sua loja Magento. Caso não esteja, é só renomear o arquivo **local.xml.sample** para **local.xml**.
- Acesse a área administrativa de sua loja e limpe o cache em: Sistema > Gerenciamento de Cache.

![Mensagem de atenção para backup da loja](app/code/community/Freterapido/Freterapido/docs/img/attention_2.png "#FicaDica ;&#41;")

----------

## Configurações iniciais

Após a instalação é necessário realizar algumas configurações iniciais para obter total usabilidade desse módulo.

### 1. Configuração do módulo:

- Configure a nova forma de entrega em:
	-  [EN] **System** > **Settings** > **Shipping Methods** > **Frete Rápido** (conforme imagem abaixo).
	-  [PT] **Sistema** > **Configurações** > **Métodos de envio** > **Frete Rápido** (conforme imagem abaixo).

<img src="https://freterapido.com/wiki/magento_1.9/images/extension_settings.PNG" alt="cotações de frete Magento, logística para magento, gateway de frete" title="Configurando o módulo do Frete Rápido" style="display: block; margin-left: auto; margin-right: auto;">

- **Habilitar:** Habilita ou desabilita o módulo em sua loja.
- **Título:** Define um título para a seção de resultados.
- **CNPJ:** CNPJ da sua empresa, o mesmo registrado na Frete Rápido (**somente números, sem pontos**).
- **Formato do Peso:** Formato do peso utilizado em sua loja (Quilos ou Gramas).
- **Altura padrão (cm):** Define Altura padrão para os produtos que não tiverem sido informados.
- **Largura padrão (cm):** Define Largura padrão para os produtos que não tiverem sido informados.
- **Comprimento padrão (cm):** Define Comprimento padrão para os produtos que não tiverem sido informados.
- **Frete Grátis:**  Habilita o frete mais barato como **frete grátis**.
- **Valor Mínimo Frete Grátis:**  Define o valor mínimo para aplicar a regra de **Frete grátis**.
- **Resultados:** Define quais resultados apresentar.
- **Limite:** Limitar a quantidade de cotações que deseja apresentar ao visitante.
- **Token:** Token de integração da sua empresa disponível no [Painel administrativo do Frete Rápido][2] > Configurações.


### 2. Origem remetente

- As informações de Origem das mercadorias são **importantes** para definirmos a origem dos seus fretes. 
- Acesse a área administrativa da sua loja e informe os dados de origem em:
	- [EN] **System** > **Settings** > **Shipping Settings** > **Origin**.
	- [PT] **Sistema** > **Configurações** > **Configurações de envio** > **Origem**.

<img style="display: block; margin-left: auto; margin-right: auto;" src="https://freterapido.com/wiki/magento_1.9/images/origin_settings.PNG" alt="dashboard logístico, transportadora para ecommerce magento" title="Dados de origem">

> **Obs:** É importante informar todos os campos corretamente.

### 3. Medidas e Prazo de envio:
- Para cálculo de frete com precisão é necessário ter as medidas de envio dos produtos informadas. Basta informá-las em: 
	- [EN] **Catalog** > **Manage Products** > **[select product]** > menu **General**.
	- [PT] **Catálogo** > **Produtos** > **[Selecionar produto]** > menu **Geral**.

<img style="display: block; margin-left: auto; margin-right: auto;" src="https://freterapido.com/wiki/magento_1.9/images/iten_setting.png" alt="integrações de frete magento, frete magento" title="Configuração de medidas dos produtos">

- É possível informar também um **Prazo de Fabricação** (em dias) do produto, caso necessário. Esse será adicionado ao prazo de entrega apresentado em sua loja.

#### **Atenção:** 
- É importante considerar as **dimensões** e **peso** do produto embalado pronto para envio/postagem.

- É obrigatório ter esses dados configurados em cada produto para que seja possível calcular o frete de forma eficiente.

- Caso as dimensões não forem informadas, serão utilizadas as medidas padrões informadas na [Configuração do módulo](#_1-configuracao-do-modulo). Contudo, recomendamos que cada produto tenha suas próprias configurações de **dimensões** e **peso** separadamente, para cálculo de frete mais preciso.

#### 4. Categorias

- Caso tenha interesse em aplicar campanhas de regra de frete por categoria de produtos, será importante relacionar as categorias da sua loja com as categorias da Frete Rápido. Para isso, basta relacioná-las em:
	- [EN] **Catalog** > **Manage Categories** > [**Select a category**] >  **Categoria no Frete Rápido**.
	- [PT] **Catálogo** > **Categorias** > [**Seleciona categoria**] > **Categoria**

<img style="display: block; margin-left: auto; margin-right: auto; max-width: 101%;" src="https://freterapido.com/wiki/magento_1.9/images/categories_settings.PNG" alt="transportadoras para e-commerce magento, cotações de frete, cotar frete" title="Configuração de categorias">

> **Obs:** Nem todas as categorias da sua loja podem coincidir com a relação de categorias do Frete Rápido, mas é possível relacioná-las de forma ampla. Exemplos:
>
> **Moda feminina** -> **Vestuário**
>
> **CDs** -> **CD / DVD / Blu-Ray**
>
> **Violões** -> **Instrumento Musical**

--------

## Contratação do Frete
É possível fechar o fluxo e contratar o frete e gerar uma solicitação de coleta diretamente da área administrativa da sua loja, no detalhamento do pedido do cliente.

* Abra o pedido e clique no botão **"Ship"** / **"Envio"**.

<img style="display: block; margin-left: auto; margin-right: auto;" src="https://freterapido.com/wiki/magento_1.9/images/order.png" alt="contratar transportadoras para e-commerce, gateway frete, api de frete json" title="Detalhamento do pedido">

<br>

* Você será redirecionado para a tela de confirmação do frete. Após conferir as informações, clique no botão **"Submit Shipment"** / **"Enviar remessa"**

<img style="display: block; margin-left: auto; margin-right: auto;" src="https://freterapido.com/wiki/magento_1.9/images/confirm_order.png" alt="integração de frete, integrações de frete" title="Confirmação da contratação do frete">

* Neste momento a Frete Rápido irá receber a confirmação e solicitar a coleta à transportadora escolhida.
--------

## Cálculo do frete na página do produto

- O módulo também permite habilitar o cálculo de frete na páginga do produto, para cotar opções de frete para um produto sem precisar colocá-lo no carrinho.
- Basta apenas habilitar e configurar conforme exemplo abaixo: 

<img style="display: block; margin-left: auto; margin-right: auto;" src="https://freterapido.com/wiki/magento_1.9/images/product_page_config.png" alt="contratar transportadoras, api de frete, api gateway transportadora" title="Configuração do bloco de cálculo do frete">

- **Habilitar:** [Yes / No] Habilita ou desabilita o bloco de cálculo de frete na página do produto.
- **Posição de exibição:** Permite escolher a posição do bloco no layout da página do produto (**o funcionamento dependerá da disposição do layout da sua loja**). As opções são:
	- **Coluna da direita**: adiciona na coluna da direita (caso haja).
	- **Coluna da esquerda**: adiciona na coluna da esquerda (caso haja).
	- **Bloco de informação adicional**: adiciona após o bloco de descrição.
	- **Layout personalizado**: permite personalizar a posição no campo **Alias de posicionamento do bloco**.

- **Posicionamento relativo:** Permite escolher a posição do bloco em relação aos outros blocos da página.
- **Alias de posicionamento do bloco:** Permite especificar um bloco para ser substituído pelo bloco de cálculo do frete.

----

**Atenção!**
A Frete Rápido irá realizar cotações de frete para os pedidos com base nas informações que você configurou na integração e conforme as informações dos pedidos que são disponibilizados.

Nós não nos responsabilizamos por eventuais dados inconsistentes que possam ser enviados a nós através da integração, tais como: peso do produto fora da realidade, dimensões erradas, quantidade de itens errada, dados faltantes, etc...

Para haver a certa contratação de fretes para os pedidos, certifique-se que os dados estejam corretos antes e durante a integração com a Frete Rápido. Toda configuração do módulo é de responsabilidade do embarcador.

--------

## Considerações finais:
1. Para obter cotações de frete dos Correios é necessário configurar o seu contrato com os Correios no [Painel administrativo do Frete Rápido][2] > Integrações > Correios.

2. Esse módulo atende solicitações de coleta para destinatários Pessoa Física. Para atender Pessoas Jurídicas, o módulo pode ser adaptado por você de acordo com a [API da Frete Rápido][9].

--------

### Contribuições
Encontrou algum bug ou tem sugestões de melhorias no código? Sensacional! Não se acanhe, nos envie um pull request com a sua alteração e ajude este projeto a ficar ainda melhor.

1. Faça um "Fork"
2. Crie seu branch para a funcionalidade: ` $ git checkout -b feature/nova-funcionalidade`
3. Faça o commit suas modificações: ` $ git commit -am "adiciona nova funcionalidade"`
4. Faça o push para a branch: ` $ git push origin feature/nova-funcionalidade`
5. Crie um novo Pull Request

--------

### Licença

[MIT][5]

[1]: https://marketplace.magento.com/freterapido-frete-rapido.html "Magento Connect"
[2]: https://painel.freterapido.com/?origin=doc_magento "Painel do Frete Rápido"
[3]: mailto:suporte@freterapido.com "Contato uma galera super gente fina :)"
[4]: https://github.com/freterapido/freterapido_magento/archive/master.zip
[5]: https://github.com/freterapido/freterapido_magento/blob/master/LICENSE
[6]: https://github.com/freterapido/freterapido_magento/blob/master/README.md
[7]: https://github.com/freterapido/freterapido_magento/blob/master/README_EN.md
[8]: http://devdocs.magento.com/
[9]: https://dev.freterapido.com
