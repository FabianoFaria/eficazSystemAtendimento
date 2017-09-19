ALTER TABLE `produtos_movimentacoes`
	CHANGE COLUMN `Quantidade` `Quantidade` DECIMAL(15,2) NOT NULL DEFAULT '1' AFTER `Tipo_Movimentacao_ID`;


select m.Modulo_Pagina_ID from modulos_paginas m where m.Slug = 'produtos-dados-tecnicos' into @moduloPaginaPaiID;
delete from modulos_paginas where Slug = 'produtos-dados-tecnicos';
delete from modulos_paginas where Slug = 'produtos-relatorio-dinamico' and Pagina_Pai_ID =  @moduloPaginaPaiID;
delete from modulos_paginas where Slug = 'produtos-relatorio-estoque';


select Modulo_ID from modulos where Slug = 'produtos' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo de Produtos','produtos-gerenciar-modulo','7');
select Modulo_Pagina_ID from modulos_paginas where Slug = 'produtos-gerenciar-modulo' into @subModuloID;
update modulos_paginas set Pagina_Pai_ID = @subModuloID where slug = 'produtos-categorias' or slug = 'produtos-marcas';



INSERT INTO `produtos_dados` (`Produto_ID`, `Codigo`, `Nome`, `Descricao_Resumida`, `Descricao_Completa`, `Tipo_Produto`, `Marca`, `NCM`, `Origem`, `Industrializado`, `Destaque`, `Lancamento`, `Categorias`, `Data_Cadastro`, `Situacao_ID`, `Usuario_Cadastro_ID`) VALUES (-1, '', 'Produto Genérico', '', '', 30, 0, '', 0, 0, 0, 0, 'N;', '2014-11-11 15:23:15', 1, -1);
INSERT INTO `produtos_dados` (`Produto_ID`, `Codigo`, `Nome`, `Descricao_Resumida`, `Descricao_Completa`, `Tipo_Produto`, `Marca`, `NCM`, `Origem`, `Industrializado`, `Destaque`, `Lancamento`, `Categorias`, `Data_Cadastro`, `Situacao_ID`, `Usuario_Cadastro_ID`) VALUES (-2, '', 'Serviço Genérico', '', '', 31, 0, '', 0, 0, 0, 0, 'N;', '2014-11-11 15:23:44', 1, -1);

INSERT INTO `produtos_variacoes` (`Produto_Variacao_ID`, `Produto_ID`, `Descricao`, `Forma_Cobranca_ID`, `Codigo`, `Imagem_ID`, `Valor_Custo`, `Valor_Venda`, `Percentual_Venda`, `Valor_Promocao`, `Data_Inicio_Promocao`, `Data_Fim_Promocao`, `Altura`, `Largura`, `Comprimento`, `Peso`, `Unidade`, `CEAN`, `Saldo_Estoque`, `Situacao_ID`, `Imagem`, `Data_Cadastro`, `Usuario_Cadastro_ID`) VALUES (-1, -1, 'Produto Genérico', 58, '', 0, 0.00, 0.00, 0.00, 0.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, 0.00, 0.00, 'UN', NULL, 0.00, 1, NULL, '2014-11-11 15:23:15', -1);
INSERT INTO `produtos_variacoes` (`Produto_Variacao_ID`, `Produto_ID`, `Descricao`, `Forma_Cobranca_ID`, `Codigo`, `Imagem_ID`, `Valor_Custo`, `Valor_Venda`, `Percentual_Venda`, `Valor_Promocao`, `Data_Inicio_Promocao`, `Data_Fim_Promocao`, `Altura`, `Largura`, `Comprimento`, `Peso`, `Unidade`, `CEAN`, `Saldo_Estoque`, `Situacao_ID`, `Imagem`, `Data_Cadastro`, `Usuario_Cadastro_ID`) VALUES (-2, -2, 'Serviço Genérico', 58, '', 0, 0.00, 0.00, 0.00, 0.00, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0.00, 0.00, 0.00, 0.00, 'UN', NULL, 0.00, 1, NULL, '2014-11-11 15:23:44', -1);



CREATE TABLE `produtos_dados_categorias` (
	`Produto_Categoria_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Produto_ID` INT(10) NOT NULL,
	`Categoria_ID` INT(10) NOT NULL,
	`Situacao_ID` INT(10) NOT NULL DEFAULT '1',
	PRIMARY KEY (`Produto_Categoria_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `produtos_compostos` (
	`Produto_Composto_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Produto_Variacao_Pai_ID` INT(11) NULL DEFAULT NULL,
	`Produto_Pai_ID` INT(10) NULL DEFAULT NULL,
	`Produto_Variacao_ID` INT(10) NULL DEFAULT NULL,
	`Quantidade` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	PRIMARY KEY (`Produto_Composto_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `produtos_dados`
	ADD COLUMN `Numero_Serie` INT(1) NULL DEFAULT '0' AFTER `Marca`;



ALTER TABLE `produtos_movimentacoes`
	ADD COLUMN `Chave_Estrangeira_Produto` INT NOT NULL DEFAULT '0' AFTER `Chave_Estrangeira`;


ALTER TABLE `produtos_movimentacoes`
	ADD COLUMN `Numero_Serie` VARCHAR(250) NOT NULL DEFAULT '' AFTER `Data_Cadastro`;
ALTER TABLE `produtos_movimentacoes`
	CHANGE COLUMN `Numero_Serie` `Numero_Serie` VARCHAR(250) NOT NULL DEFAULT '' AFTER `Nota_Fiscal`;


ALTER TABLE `produtos_movimentacoes`
	ADD COLUMN `Situacao_ID` INT(10) NOT NULL DEFAULT '1' AFTER `Usuario_Cadastro_ID`;


/*
só para eficaz
update produtos_movimentacoes set Situacao_ID = 2;
*/


CREATE TABLE `produtos_tabelas_precos_faixas` (
	`Tabelas_Precos_Faixa_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Descricao_Faixa` VARCHAR(250) NOT NULL DEFAULT '',
	`Tipo_Faixa` INT(10) NOT NULL COMMENT 'Grupo 62',
	`Tipo_Cobranca` INT(10) NOT NULL COMMENT 'Grupo 63 ',
	`Produto_Variacao_ID` INT(10) NOT NULL,
	`Caracteristica_ID` INT(10) NOT NULL,
	`Quantidade_Inicial` DECIMAL(15,2) NOT NULL,
	`Quantidade_Final` DECIMAL(15,2) NOT NULL,
	`Valor_Custo` DECIMAL(15,2) NOT NULL,
	`Valor_Venda` DECIMAL(15,2) NOT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	PRIMARY KEY (`Tabelas_Precos_Faixa_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (140, 13, 'Locações', NULL, 1);


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (62, 'Tipo Faixa', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (63, 'Tipo Cobrança', 1);

INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (143, 62, 'Por Quantidade', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (144, 62, 'Por Característica', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (145, 63, 'Valor Unitário', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (146, 63, 'Valor Agrupado', '', 1);


select Modulo_ID from modulos where Slug = 'produtos' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'produtos-gerenciar-modulo' into @moduloPaginaPaiID;
insert into modulos_paginas(Modulo_ID, Pagina_Pai_ID, Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID)values(@moduloID, @moduloPaginaPaiID, 'Características','Gerenciar Características','produtos-gerenciar-caracteristicas','8','64');



ALTER TABLE `produtos_dados`
	ADD COLUMN `Slug` VARCHAR(500) NULL DEFAULT NULL AFTER `Nome`;


ALTER TABLE `produtos_variacoes`
	ALTER `CEAN` DROP DEFAULT;
ALTER TABLE `produtos_variacoes`
	CHANGE COLUMN `CEAN` `CEAN` VARCHAR(50) NOT NULL AFTER `Unidade`;


ALTER TABLE `produtos_movimentacoes`
	ADD COLUMN `CD_ID` INT(10) NOT NULL DEFAULT '0' AFTER `Produto_Movimentacao_ID`;



ALTER TABLE `produtos_dados`
	ADD COLUMN `Formulario_ID` INT(10) NOT NULL DEFAULT '0' AFTER `Marca`;
