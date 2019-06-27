# Changelog


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
