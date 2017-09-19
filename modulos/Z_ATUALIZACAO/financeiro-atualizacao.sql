select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'financeiro-relatorio' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Calendario','Calendario de Pagamentos','financeiro-relatorio-calendario','8',@subModuloID);


CREATE TABLE `financeiro_faturar` (
	`Faturar_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Tipo_ID` INT(10) NOT NULL COMMENT 'TIPO GRUPO 27 -> 44 - Entrada | 45 - Saída | 46 - Transferência',
	`Empresa_ID` INT(10) NOT NULL COMMENT 'Cadastro que a conta esta vinculada',
	`Cliente_Fornecedor_ID` INT(10) NOT NULL COMMENT 'Cadastro que a conta detalhado de quem ou para quem será pago ou recebera dinheiro',
	`Tabela_Estrangeira` VARCHAR(100) NULL DEFAULT NULL,
	`Chave_Estrangeira` INT(10) NULL DEFAULT NULL,
	`Valor_Unitario` DECIMAL(14,2) NULL DEFAULT NULL,
	`Quantidade` DECIMAL(14,2) NULL DEFAULT NULL,
	`Observacao` VARCHAR(250) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	`Usuario_Cancelamento_ID` INT(11) NOT NULL,
	`Data_Cancelamento` DATETIME NOT NULL,
	`Situacao_ID` INT(11) NOT NULL,
	PRIMARY KEY (`Faturar_ID`),
	INDEX `idx_financeiro_contas_01` (`Faturar_ID`),
	INDEX `idx_financeiro_contas_02` (`Tipo_ID`),
	INDEX `idx_financeiro_contas_03` (`Empresa_ID`),
	INDEX `idx_financeiro_contas_04` (`Cliente_Fornecedor_ID`),
	INDEX `idx_financeiro_contas_05` (`Tabela_Estrangeira`),
	INDEX `idx_financeiro_contas_06` (`Chave_Estrangeira`),
	INDEX `idx_financeiro_contas_07` (`Situacao_ID`),
	INDEX `idx_financeiro_contas_08` (`Tabela_Estrangeira`, `Chave_Estrangeira`),
	INDEX `idx_financeiro_contas_09` (`Faturar_ID`, `Tipo_ID`, `Empresa_ID`, `Cliente_Fornecedor_ID`, `Tabela_Estrangeira`, `Chave_Estrangeira`, `Situacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;



ALTER TABLE `financeiro_produtos`
	ADD COLUMN `Produto_Descricao` VARCHAR(500) NULL DEFAULT NULL AFTER `Produto_Variacao_ID`;




select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'financeiro-gerenciar' into @moduloPaiID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Pagina_Pai_ID, Posicao)values(@moduloID, 'Comissionamento','Gerenciar Tabelas de Comissionamento','financeiro-comissionamento', @moduloPaiID, '6');

CREATE TABLE `financeiro_tabelas_comissoes` (
	`Tabela_Comissao_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Titulo_Tabela` VARCHAR(50) NULL DEFAULT NULL,
	`Tipo_Comissionamento` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	PRIMARY KEY (`Tabela_Comissao_ID`)
)

CREATE TABLE `financeiro_tabelas_comissoes_produtos` (
	`Tabela_Comissao_Produto_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Tabela_Comissao_ID` INT(10) NULL DEFAULT NULL,
	`Produto_Variacao_ID` INT(10) NULL DEFAULT NULL,
	`Percentual_Comissao` DECIMAL(5,2) NOT NULL DEFAULT 0,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	PRIMARY KEY (`Tabela_Comissao_Produto_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `financeiro_tabelas_comissoes_faixas` (
	`Tabela_Comissao_Faixa_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Tabela_Comissao_ID` INT(10) NULL DEFAULT NULL,
	`Descricao` VARCHAR(250) NULL DEFAULT NULL,
	`Valor_Inicial` DECIMAL(18,2) NOT NULL DEFAULT '0.00',
	`Valor_Final` DECIMAL(18,2) NOT NULL DEFAULT '0.00',
	`Percentual_Comissao` DECIMAL(5,2) NOT NULL DEFAULT '0.00',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	PRIMARY KEY (`Tabela_Comissao_Faixa_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (56, 'Tipo de Comissionamento', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (126, 56, 'Por Produto', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (127, 56, 'Faixa de Preço Periodo ', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (128, 56, 'Faixa de Preço Proposta', '', 1);



/*
select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao,Pagina_Pai_ID)values(@moduloID,'Comissionamento','Gerar Comissionamento','financeiro-gerar-comissionamento','5');
*/


ALTER TABLE `financeiro_contas`
	ADD COLUMN `Situacao_ID` INT NOT NULL DEFAULT '1' AFTER `Usuario_Cadastro_ID`;


select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'financeiro-gerenciar' into @moduloPaiID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Pagina_Pai_ID, Posicao)values(@moduloID, 'Comissionamento','Gerenciar Tabelas de Comissionamento','financeiro-comissionamento', @moduloPaiID, '6');



select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'financeiro-gerenciar' into @subModuloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID, 'Configurações','Configurações','financeiro-configuracoes','100',@subModuloID);



INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (71, 'Tipo de Conta', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (170, 71, 'Cartão de crédito', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (171, 71, 'Conta Corrente', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (172, 71, 'Dinheiro', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (173, 71, 'Poupança', '', 1);



	
	
drop table if exists cadastros_contas;

CREATE TABLE `cadastros_contas` (
	`Cadastro_Conta_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Cadastro_ID` INT(10) NOT NULL,
	`Tipo_Conta_ID` INT(10) NOT NULL DEFAULT '0',
	`Dados` TEXT NULL,
	`Agencia` VARCHAR(10) NULL DEFAULT NULL,
	`Banco` VARCHAR(20) NULL DEFAULT NULL,
	`Conta_Corrente` VARCHAR(20) NULL DEFAULT NULL,
	`Saldo_Inicial` DECIMAL(16,2) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Cadastro_Conta_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

