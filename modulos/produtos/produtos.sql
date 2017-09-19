CREATE TABLE IF NOT EXISTS `produtos_categorias` (
  `Categoria_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(250) DEFAULT NULL,
  `Slug` varchar(250) DEFAULT NULL,
  `Categoria_Pai_ID` int(11) DEFAULT NULL,
  `Descricao` text,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Situacao_ID` int(3) DEFAULT '1',
  PRIMARY KEY (`Categoria_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `produtos_dados` (
  `Produto_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(200) DEFAULT NULL,
  `Nome` varchar(2000) DEFAULT NULL,
  `Descricao_Resumida` varchar(5000) DEFAULT NULL,
  `Descricao_Completa` longtext,
  `Tipo_Produto` int(11) DEFAULT NULL,
  `Marca` int(11) DEFAULT NULL,
  `Destaque` int(11) DEFAULT NULL,
  `Lancamento` int(11) DEFAULT NULL,
  `Categorias` text,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Situacao_ID` int(11) DEFAULT '1',
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`Produto_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `produtos_estoque` (
  `Produto_Estoque_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Produto_Variacao_ID` int(11) NOT NULL,
  `Estoque_Minimo` decimal(15,2) NOT NULL,
  `Compra_Minima` decimal(15,2) NOT NULL,
  `Utilizacao_Media` decimal(15,2) NOT NULL,
  `Prazo_Medio_Entrega` int(11) NOT NULL,
  `Quantidade_Embalagem` int(11) NOT NULL,
  PRIMARY KEY (`Produto_Estoque_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `produtos_fornecedores` (
  `Produto_Cadastro_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Produto_ID` int(10) DEFAULT NULL,
  `Cadastro_ID` int(10) DEFAULT NULL,
  `Situacao_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Produto_Cadastro_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `produtos_imagens` (
  `Imagem_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Produto_ID` int(10) DEFAULT NULL,
  `Posicao` int(10) DEFAULT NULL,
  `Nome_Imagem` varchar(200) DEFAULT NULL,
  `Imagem1` varchar(200) DEFAULT NULL,
  `Imagem2` varchar(200) DEFAULT NULL,
  `Imagem3` varchar(200) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT '1',
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`Imagem_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `produtos_movimentacoes` (
  `Produto_Movimentacao_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Chave_Estrangeira` int(10) NOT NULL DEFAULT '0',
  `Tabela_Estrangeira` varchar(100) NOT NULL DEFAULT '',
  `Produto_Variacao_ID` int(11) NOT NULL,
  `Tipo_Movimentacao_ID` int(11) NOT NULL DEFAULT '66',
  `Quantidade` int(11) NOT NULL DEFAULT '1',
  `Nota_Fiscal` int(10) NOT NULL DEFAULT '-1',
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Produto_Movimentacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `produtos_tabelas_precos` (
  `Tabela_Preco_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Titulo_Tabela` varchar(50) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Situacao_ID` int(11) DEFAULT '1',
  PRIMARY KEY (`Tabela_Preco_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `produtos_tabelas_precos_detalhes` (
  `Tabelas_Precos_Detalhe_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Tabela_Preco_ID` int(10) DEFAULT NULL,
  `Produto_Variacao_ID` int(10) DEFAULT NULL,
  `Valor_Custo` varchar(50) DEFAULT NULL,
  `Valor_Venda` varchar(50) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT '1',
  PRIMARY KEY (`Tabelas_Precos_Detalhe_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `produtos_variacoes` (
  `Produto_Variacao_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Produto_ID` int(10) NOT NULL,
  `Descricao` varchar(2000) DEFAULT NULL,
  `Forma_Cobranca_ID` int(11) NOT NULL,
  `Codigo` varchar(50) DEFAULT NULL,
  `Imagem_ID` int(11) DEFAULT NULL,
  `Valor_Custo` decimal(10,2) DEFAULT NULL,
  `Valor_Venda` decimal(10,2) DEFAULT NULL,
  `Percentual_Venda` decimal(10,2) NOT NULL,
  `Valor_Promocao` decimal(10,2) DEFAULT NULL,
  `Data_Inicio_Promocao` datetime DEFAULT NULL,
  `Data_Fim_Promocao` datetime DEFAULT NULL,
  `Altura` decimal(10,2) DEFAULT NULL,
  `Largura` decimal(10,2) DEFAULT NULL,
  `Comprimento` decimal(10,2) DEFAULT NULL,
  `Peso` decimal(10,2) DEFAULT NULL,
  `Saldo_Estoque` decimal(10,2) DEFAULT NULL,
  `Situacao_ID` int(11) NOT NULL DEFAULT '1',
  `Imagem` varchar(150) DEFAULT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Produto_Variacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `produtos_variacoes_campos` (
  `Produto_Variacao_Campo_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Produto_Variacao_ID` int(10) DEFAULT NULL,
  `Grupo` varchar(150) DEFAULT NULL,
  `Descricao` varchar(150) DEFAULT NULL,
  `Valor` varchar(150) DEFAULT NULL,
  `Situacao_ID` int(1) DEFAULT '1',
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Produto_Variacao_Campo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (13, 'Tipo Produto', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (14, 'Marcas', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (20, 'Forma de Cobrança', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (33, 'Controle de Estoque', 1);

INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (30, 13, 'Produto', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (31, 13, 'Serviço', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (35, 20, 'Valores Fixos', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (36, 20, 'Porcentagem do Custo', NULL, 3);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (58, 20, 'Valores Abertos', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (66, 33, 'Entrada', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (67, 33, 'Saida', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (100, 13, 'Composto', NULL, 1);


select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Produtos','produtos','Módulo para cadastro e gerenciamento de Produtos e Serviços',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;


insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Produtos Cadastrados','Localizar Produtos Cadastrados','produtos-cadastrados','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Cadastrar Produto','Cadastrar novo produto','produtos-cadastrar','2','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Categorias','Gerenciar categorias ','produtos-categorias','3');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID)values(@moduloID,'Marcas','Gerenciar marcas','produtos-marcas','4','14');

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Dados Técnicos','Gerenciar Dados Técnicos','produtos-dados-tecnicos','6');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Relatorio Dinâmico','Relatórios Dinâmicos','produtos-relatorio-dinamico','1',@subModuloID);

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Relatórios','Gerar Relatórios dos Produtos cadastrados','produtos-relatorios','8');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Relatório Dinâmico','Relatórios Dinâmicos','produtos-relatorio-dinamico','1',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Controle de Estoque','Controle de Estoque','produtos-relatorio-estoque','2',@subModuloID);

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Editar Estoque','Editar de Estoque','produtos-estoque-edita','1', '1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Controle de Estoque','Controle de Estoque ','produtos-controle-estoque', '7');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Tabela de preços','Tabela de preços ','produtos-tabela-preco','8');


/* ATUALIZADO 01/09/2014 */

ALTER TABLE `produtos_variacoes` ADD COLUMN `Unidade` VARCHAR(6) NULL DEFAULT NULL AFTER `Peso`;
ALTER TABLE `produtos_variacoes` ADD COLUMN `CEAN` INT NULL DEFAULT NULL AFTER `Unidade`;

ALTER TABLE `produtos_dados`
	ADD COLUMN `NCM` VARCHAR(8) NULL DEFAULT NULL AFTER `Marca`;

ALTER TABLE `produtos_dados`
	ADD COLUMN `Origem` INT(11) NULL DEFAULT '0' AFTER `NCM`;

ALTER TABLE `produtos_dados`
	ADD COLUMN `Industrializado` INT(11) NULL DEFAULT '0' AFTER `Origem`;
	

update tipo set Situacao_ID = 1 where Tipo_ID = 36;

select Modulo_ID from modulos where Slug = 'produtos' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Movimentação de Materiais','Movimentação de Materiais','produtos-movimentacao-material',0,1);



ALTER TABLE produtos_variacoes ADD INDEX `idx_produtos_variacoes_09` (`Produto_ID`, `Produto_Variacao_ID`, `Descricao`(255), `Situacao_ID`);
ALTER TABLE produtos_variacoes ADD INDEX `idx_produtos_variacoes_10` (`Produto_ID`, `Descricao`(255), `Situacao_ID`);



ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_01` (Produto_Movimentacao_ID);
ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_02` (Chave_Estrangeira);
ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_03` (Tabela_Estrangeira);
ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_04` (Produto_Variacao_ID);
ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_05` (Tipo_Movimentacao_ID);
ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_06` (Data_Cadastro);
ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_07` (Produto_Movimentacao_ID, Chave_Estrangeira, Tabela_Estrangeira, Produto_Variacao_ID, Tipo_Movimentacao_ID);
ALTER TABLE produtos_movimentacoes ADD INDEX `idx_produtos_movimentacoes_08` (Chave_Estrangeira, Tabela_Estrangeira, Produto_Variacao_ID);





ALTER TABLE `produtos_variacoes`
	CHANGE COLUMN `Peso` `Peso` DECIMAL(10,3) NULL DEFAULT NULL AFTER `Comprimento`;







CREATE TABLE `produtos_tabelas_precos_faixas` (
	`Tabelas_Precos_Faixa_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Tipo_Faixa` INT(10) NULL DEFAULT NULL,
	`Produto_Variacao_ID` INT(10) NULL DEFAULT NULL,
	`Caracteristica_ID` INT(10) NULL DEFAULT NULL,
	`Quantidade` INT(10) NULL DEFAULT NULL,
	`Valor_Custo` VARCHAR(50) NULL DEFAULT NULL,
	`Valor_Venda` VARCHAR(50) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	PRIMARY KEY (`Tabelas_Precos_Faixa_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;




