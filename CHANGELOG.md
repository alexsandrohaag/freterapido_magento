# Changelog

## [1.3.6] - 2019-09-13
### Adicionado

#### **Status customizáveis**

Quando configurados no módulo, ao contratar ou quand um frete for dado como entregue pela Frete Rápido, o módulo irá atualizar os respectivos pedidos para os status configurados.

#### **Canal de vendas para regra de frete**

A partir desta versão, é possível informar o canal de vendas na configuração do módulo, permitindo assim, a aplicação de regras de frete mais específicas.


#### **Categorias**

	- Acessório para decoração (com vidro)
	- Acessório para decoração (sem vidro)
	- Acessórios automotivos
	- Acessórios para bicicleta
	- Artesanatos (com vidro)
	- Bicicletas (desmontada)
	- Cama / Mesa / Banho
	- Chapas de madeira
	- Manequins
	- Portas / Janelas (com vidro)
	- Torneiras
	- Vasos de polietileno

#### **Webhook**

	- Inclusão dos status customizáveis para a atualização dos pedidos;

#### **Contratação do frete**

	- Inclusão dos status customizáveis para a atualização dos pedidos;

#### **Cotação do frete**

	- Envio do canal de venda;

### Alterado

#### **Categorias**

	- Artesanatos, -> Artesanatos (sem vidro),            
    - Portas / Janelas, -> Portas / Janelas (sem vidro),       
    - Materiais hidráulicos -> Materiais hidráulicos / Encanamentos

## [1.3.5] - 2019-08-29
### Adicionado

#### **Categorias**	

	- Utilidades domésticas;
	- Acessórios para celular;
	- Toldos;
	- Pisos cerâm / Revestimentos;
	- Artesanatos;
	- Quadros / Molduras;
	- Porta / Janelas;
	- Placa de Energia Solar;
	- Materiais hidráulicos;
	- Pia / Vasos;
	- Bijuteria;
	- Joia;
	- Refrigeração Industrial;
	- Cocção Industrial;
	- Utensílios industriais;
	- Maquina de algodão doce;
	- Maquina de chocolate;
	- Estufa térmica;
	- Equipamentos de cozinha industrial;
	- Tapeçaria / Cortinas / Persianas;

#### **Envio de NFe**
 	- A partir da versão 1.3.5 é possivel realizar o envio das NFes para que o rastreio possa ocorrer de forma efetiva pela Frete rápido.
		Para tal, se faz necessário realizar algumas ações:
		- Ao adicionar o faturamento no pedido, basta informar a data de emissão e chave de acesso em um comentário de faturamento seguindo o seguinte padrão: *nfe:17110752684209056803016340068050771046234656, emissao:29/08/2019 14:17:01*
		- Após adicionado o comentário seguindo a regra acima, tanto ao contratar quanto ao rastrear do frete, a nota fiscal informada será enviada automaticamente para a Frete Rápido 

### Alterado

#### **Webhook de ocorrências**
	- Implementação de retorno para testes do serviço
	- Correção do status HTTP de retorno


## [1.3.4] - 2019-06-27
### Adicionado

#### **Atributos**

Estes atributos servirão para indicar qual atributo será utilizado pelo módulo no momento da obtenção dos seus respectivos valores;

	- `ref_attr_height_type`-> Atributo referente à altura;
	- `ref_attr_width_type` -> Atributo referente à largura;
	- `ref_attr_length_type`-> Atributo referente ao comprimento;
	- `ref_attr_state_registration_type` -> Atributo referente à Insc. Estadual do destinatário;
	- Implementação de função para retorno dos valores dos produtos referenciados pelos atrbutos acima;
	- Implementação das classes necessárias para que os atributos sejam exibidos no front;

#### **Categorias**

	- Produto Químico Não Classificado;
	- Pilhas / Baterias;
	- Estiletes / Materiais cortantes;
	- Produto Químico Classificado;
	- Limpeza;
	- Extintores;
	- Equipamentos de segurança / EPI;

#### **Webhook**

	- Inclusão no arquivo de configuração do módulo, dos dados necessários para configurar a rota do webhook no qual a Frete Rápido irá utilizar para atualizar os status dos pedidos;
	- Inclusão do controller para manipular as requisições recebidas pela Frete Rápido;

### Alterado

#### **Categorias**

	- Produtos Químicos -> Produto Químico Não Classificado;

#### **Cotação / Contratação de frete**
	- Alterado o método de recuperação dos dados do destinatário do frete para obter, caso exista, dados como CNPJ e Inscrição Eestadual (este último quando informado o atributo customizável)
	- Implementação de filtros que permitam apenas números nos campos de CNPJ/CPF e Inscrição estadual do destinatário/remetente;
	- Alteração da imagem do MySql de mysql:5.6.23 para mariadb:10.1


## [1.3.3] - 2019-04-29
### Adicionado

#### **Categorias**

	- Alimentos não perecíveis;
    - Caixa de embalagem;
    - TV / Monitores;
    - Linha Branca;
    - Vitaminas / Suplementos nutricionais;
    - Malas / Mochilas;
    - Máquina / Equipamentos;
    - Rações / Alimento para Animal;
    - Artigos para Camping;

### Alterado

#### **Categorias**

	- Alimentos -> Alimentos perecíveis;
	- Cosméticos / Perfumaria-> Cosméticos;
	- Farmacêutico / Medicamentos -> Medicamentos;
	- Material Pet Shop / Rações -> Material Pet Shop;
