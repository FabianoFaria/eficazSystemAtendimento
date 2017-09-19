CREATE TABLE IF NOT EXISTS `financeiro_contas` (
  `Conta_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Tipo_ID` int(10) NOT NULL COMMENT 'TIPO GRUPO 27 -> 44 - Entrada | 45 - Saída | 46 - Transferência',
  `Tipo_Conta_ID` int(10) NOT NULL,
  `Cadastro_ID_de` int(10) NOT NULL COMMENT 'Cadastro que a conta esta vinculada',
  `Cadastro_ID_para` int(10) NOT NULL COMMENT 'Cadastro que a conta detalhado de quem ou para quem será pago ou recebera dinheiro',
  `Codigo` varchar(50) NOT NULL,
  `Tabela_Estrangeira` varchar(100) DEFAULT NULL,
  `Chave_Estrangeira` int(10) DEFAULT NULL,
  `Valor_Total` decimal(14,2) DEFAULT NULL,
  `Observacao` varchar(250) DEFAULT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Conta_ID`),
  KEY `idx_financeiro_contas_01` (`Conta_ID`),
  KEY `idx_financeiro_contas_02` (`Tipo_ID`),
  KEY `idx_financeiro_contas_03` (`Tipo_Conta_ID`),
  KEY `idx_financeiro_contas_04` (`Cadastro_ID_de`),
  KEY `idx_financeiro_contas_05` (`Cadastro_ID_para`),
  KEY `idx_financeiro_contas_06` (`Conta_ID`),
  KEY `idx_financeiro_contas_07` (`Tabela_Estrangeira`,`Chave_Estrangeira`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `financeiro_produtos` (
  `Financeiro_Produto_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Produto_Referencia_ID` int(10) NOT NULL,
  `Conta_ID` int(10) NOT NULL,
  `Tabela_Estrangeira` varchar(100) DEFAULT NULL,
  `Chave_Estrangeira` int(10) DEFAULT NULL,
  `Situacao_ID` int(10) DEFAULT NULL,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  `Data_Alteracao` datetime DEFAULT NULL,
  `Produto_Variacao_ID` int(11) DEFAULT NULL,
  `Quantidade` decimal(15,2) DEFAULT NULL,
  `Valor_Unitario` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`Financeiro_Produto_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `financeiro_titulos` (
  `Titulo_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Conta_ID` int(10) NOT NULL,
  `Forma_Pagamento_ID` int(10) NOT NULL COMMENT 'TIPO GRUPO 25',
  `Codigo` varchar(50) NOT NULL,
  `Valor_Titulo` decimal(14,2) DEFAULT NULL,
  `Data_Vencimento` date NOT NULL,
  `Valor_Pago` decimal(14,2) DEFAULT NULL,
  `Data_Pago` date DEFAULT NULL,
  `Situacao_Pagamento_ID` int(11) NOT NULL,
  `Observacao` varchar(250) DEFAULT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  `Data_Alteracao` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Usuario_Alteracao_ID` int(11) NOT NULL,
  PRIMARY KEY (`Titulo_ID`),
  KEY `idx_financeiro_titulos_01` (`Titulo_ID`),
  KEY `idx_financeiro_titulos_02` (`Conta_ID`),
  KEY `idx_financeiro_titulos_03` (`Codigo`),
  KEY `idx_financeiro_titulos_04` (`Situacao_Pagamento_ID`),
  KEY `idx_financeiro_titulos_05` (`Titulo_ID`,`Conta_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (25, 'Forma de Pagamento', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (26, 'Centros de Custo', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (27, 'Tipos de Conta', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (28, 'Tipos de Títulos', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (29, 'Situação Pagamento', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (44, 27, 'A Pagar', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (45, 27, 'A Receber', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (46, 27, 'Transferência', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (47, 25, 'Boleto', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (48, 29, 'Aberto', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (49, 29, 'Pago', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (50, 29, 'Cancelado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (51, 28, 'Contribuição Voluntária Site', NULL, 3);
INSERT IGNORE INTO tipo (Tipo_ID,Tipo_Grupo_ID, Descr_Tipo, Situacao_ID) VALUES (90, 25, 'Dinhero', 1), (91, 25, 'Débito', 1), (92, 25, 'Crédito', 1), (93, 25, 'Cheque', 1), (94, 25, 'Depósito', 1), (95, 25, 'DOC', 1), (96, 25, 'TED', 1);


select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao) values('Financeiro','financeiro','Módulo para controle financeiro e contas a pagar e receber',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Contas','Gerenciamento de contas a pagar e a receber','financeiro-contas','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Lançamento','Lançamentos de contas a pagar e a receber','financeiro-lancamento','2');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Aguardando Faturamento','Aguardando Faturamento','financeiro-aguardando-faturamento','2');

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Relatórios','Relatórios Financeiros','financeiro-relatorio','3');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Resumo','Relatório Resumo Geral por período','financeiro-relatorio-resumo-geral','1',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Totais Entradas X Saídas','Relatório por período de Entradas X Saídas','financeiro-relatorio-periodo-entradas-saidas','2',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Entradas e Saídas','Relatório Analítico de Entradas e Saídas','financeiro-relatorio-entradas-saidas','2',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Transferências','Relatório de Controle de Transferências','financeiro-relatorio-transferencias','5',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Demonstração Financeira','Relatório de Demonstração Financeira','financeiro-relatorio-demonstracao-financeira','6',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Fluxo de Caixa','Relatório de Fluxo de Caixa','financeiro-relatorio-fluxo-caixa','7',@subModuloID);

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo','financeiro-gerenciar','4');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values(@moduloID,'Tipos de Conta','Gerenciar Tipos de Conta','financeiro-tipo-conta','1',@subModuloID,'28');
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Contas Bancárias','Gerenciar Contas Bancárias','financeiro-contas-bancarias','3',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values(@moduloID,'Centros de Custo','Gerenciar Centros de Custo','financeiro-centro-custo','2',@subModuloID,'26');
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values(@moduloID,'Formas de Pagamento','Gerenciar Formas de Pagamento','financeiro-formas-pagamento','4',@subModuloID,'25');


/* ATUALIZADO 09/07/2014*/
ALTER TABLE `financeiro_titulos` ADD INDEX `idx_financeiro_titulos_06` (`Data_Vencimento`);
ALTER TABLE `financeiro_titulos` ADD INDEX `idx_financeiro_titulos_07` (`Forma_Pagamento_ID`);
ALTER TABLE `financeiro_titulos` ADD INDEX `idx_financeiro_titulos_08` (`Data_Vencimento`,`Situacao_Pagamento_ID`);
ALTER TABLE `financeiro_titulos` ADD INDEX `idx_financeiro_titulos_09` (`Data_Vencimento`,`Forma_Pagamento_ID`);
ALTER TABLE `financeiro_titulos` ADD INDEX `idx_financeiro_titulos_10` (`Data_Vencimento`,`Forma_Pagamento_ID`,`Situacao_Pagamento_ID`);
ALTER TABLE `financeiro_titulos` ADD INDEX `idx_financeiro_titulos_11` (`Conta_ID`, `Data_Vencimento`, `Forma_Pagamento_ID`, `Situacao_Pagamento_ID`);

ALTER TABLE `financeiro_contas` ADD INDEX `idx_financeiro_contas_08` (`Conta_ID`, `Cadastro_ID_de`, `Cadastro_ID_para`, `Tipo_Conta_ID`);

ALTER TABLE `financeiro_produtos`
	ADD COLUMN `Info_NFE` TEXT NULL AFTER `Valor_Unitario`;
	
	
