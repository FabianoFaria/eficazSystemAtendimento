	CREATE TABLE `orcamentos_propostas_envios` (
	`Orcamento_Proposta_Envio_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Proposta_ID` INT(10) NOT NULL DEFAULT '0',
	`Tipo_Frete` CHAR(3) NOT NULL DEFAULT '',
	`Forma_Envio_ID` INT(11) NOT NULL DEFAULT '0',
	`Endereco_Entrega_ID` INT(11) NOT NULL DEFAULT '0',
	`Valor_Frete` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
	`Valor_Seguro` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Orcamento_Proposta_Envio_ID`),
	INDEX `idx_orcamentos_propostas_envios_01` (`Orcamento_Proposta_Envio_ID`),
	INDEX `idx_orcamentos_propostas_envios_02` (`Proposta_ID`),
	INDEX `idx_orcamentos_propostas_envios_03` (`Tipo_Frete`),
	INDEX `idx_orcamentos_propostas_envios_04` (`Forma_Envio_ID`),
	INDEX `idx_orcamentos_propostas_envios_05` (`Situacao_ID`),
	INDEX `idx_orcamentos_propostas_envios_06` (`Proposta_ID`, `Situacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;



CREATE TABLE `orcamentos_propostas_vencimentos` (
	`Orcamento_Proposta_Vencimento_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Proposta_ID` INT(10) NOT NULL DEFAULT '0',
	`Dias_Vencimento` INT(11) NOT NULL DEFAULT '0',
	`Data_Vencimento` DATE NULL DEFAULT NULL,
	`Valor_Vencimento` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Orcamento_Proposta_Vencimento_ID`),
	INDEX `idx_orcamentos_propostas_vencimentos_01` (`Orcamento_Proposta_Vencimento_ID`),
	INDEX `idx_orcamentos_propostas_vencimentos_02` (`Proposta_ID`),
	INDEX `idx_orcamentos_propostas_vencimentos_03` (`Dias_Vencimento`),
	INDEX `idx_orcamentos_propostas_vencimentos_04` (`Data_Vencimento`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;



INSERT INTO tipo_grupo (Tipo_Grupo_ID, Descr_Tipo_Grupo, Situacao_ID) VALUES (72, 'Forma de Pagamento', 1);

select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'chamados-configuracoes' into @subModuloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Tipo_Grupo_ID, Posicao, Pagina_Pai_ID) values (@moduloID, 'Formas Pagamento','Gerenciar formas de Pagamento','chamados-formas-pagamento','72', '9', @subModuloID);


ALTER TABLE `orcamentos_propostas`
	ADD COLUMN `Forma_Pagamento_ID` INT(11) NULL DEFAULT NULL AFTER `Tabela_Preco_ID`;


CREATE TABLE `cadastros_contas` (
	`Cadastro_Conta_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Cadastro_ID` INT(10) NOT NULL,
	`Tipo_Conta_ID` INT(10) NOT NULL DEFAULT '0',
	`Nome_Conta` VARCHAR(100) NULL DEFAULT NULL,
	`Dados` TEXT NULL,
	`Saldo_Inicial` DECIMAL(16,2) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Cadastro_Conta_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;


delete from modulos_paginas where Slug = 'financeiro-contas-transferencias';
delete from modulos_paginas where Slug = 'financeiro-contas-receber';
delete from modulos_paginas where Slug = 'financeiro-contas-pagar';

select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu) VALUES (@moduloID, 'TransferÃªncias', 'TransferÃªncias', 'financeiro-contas-transferencias', 1, NOW(), -1, 1);
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu) VALUES (@moduloID, 'Contas a Receber', 'Contas a Receber', 'financeiro-contas-receber', 1, NOW(), -2, 1);
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu) VALUES (@moduloID, 'Contas a Pagar', 'Contas a Pagar', 'financeiro-contas-pagar', 1, NOW(), -3, 1);


update modulos_paginas set Slug = 'financeiro-centros-custos' where Slug = 'financeiro-centro-custo';

/*
CREATE TABLE `financeiro_contas_centros_custos` (
	`Conta_Centro_Custo_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Centro_Custo_ID` INT(10) NOT NULL DEFAULT '0',
	`Conta_ID` INT(10) NOT NULL DEFAULT '0',
	`Valor` DECIMAL(16,2) NOT NULL DEFAULT '0',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Conta_Centro_Custo_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
*/

CREATE TABLE `financeiro_contabil` (
	`Contabil_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Centro_Custo_ID` INT(10) NOT NULL DEFAULT '0',
	`Tipo_Conta_ID` INT(10) NOT NULL DEFAULT '0',
	`Conta_ID` INT(10) NOT NULL DEFAULT '0',
	`Valor` DECIMAL(16,2) NOT NULL DEFAULT '0.00',
	`Observacao` VARCHAR(250) NOT NULL DEFAULT '0.00',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Contabil_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;



CREATE TABLE `financeiro_movimentacoes` (
	`Movimentacao_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Conta_ID` INT(10) NOT NULL DEFAULT '0',
	`Titulo_ID` INT(10) NOT NULL DEFAULT '0',
	`Cadastro_Conta_ID` INT(10) NOT NULL DEFAULT '0',
	`Data_Movimentacao` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`Valor` DECIMAL(16,2) NOT NULL DEFAULT '0.00',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Movimentacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;



ALTER TABLE `financeiro_contas`
	ADD COLUMN `Cadastro_Conta_ID_de` INT(10) NOT NULL AFTER `Cadastro_ID_para`,
	ADD COLUMN `Cadastro_Conta_ID_para` INT(10) NOT NULL AFTER `Cadastro_Conta_ID_de`,
	ADD INDEX `idx_financeiro_contas_09` (`Cadastro_Conta_ID_de`),
	ADD INDEX `idx_financeiro_contas_10` (`Cadastro_Conta_ID_para`);

update modulos_paginas set Oculta_Menu = 1 where slug = 'financeiro-relatorio-periodo-entradas-saidas';




update modulos_paginas set Titulo = 'Contas', Descricao = 'Gerenciar Contas', Slug = 'financeiro-gerenciar-contas' where Slug = 'financeiro-contas-bancarias';




ALTER TABLE `financeiro_contas`
	ADD COLUMN `Centro_Custo_ID` INT(10) NOT NULL AFTER `Tipo_Conta_ID`;






update tipo set Descr_Tipo = 'Saída' where tipo_id = 44;
update tipo set Descr_Tipo = 'Entrada' where tipo_id = 45;
update tipo set Descr_Tipo = 'Transferência' where tipo_id = 46;


/* RELATÃ“RIOS DE CENTRO DE CUSTO */

select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'financeiro-relatorio' into @subModuloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Tipo_Grupo_ID, Posicao, Pagina_Pai_ID) values (@moduloID, 'Receitas e despesas','Receitas e despesas','financeiro-relatorio-receitas-despesas','', '-1', @subModuloID);
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Tipo_Grupo_ID, Posicao, Pagina_Pai_ID) values (@moduloID, 'Pesquisar Geral','Pesquisa Geral TÃ­tulos','financeiro-relatorio-pesquisa-titulos-geral','', '0', @subModuloID);




INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (174, 71, 'A Pagar', '', 1);


ALTER TABLE `tele_campanhas`
	ADD COLUMN `Dados_Campanha` TEXT NOT NULL AFTER `Nome`;
	
CREATE TABLE `tele_follows_cancelados` (
	`Follow_Cancelado_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Follow_ID` INT(10) NOT NULL,
	`Workflow_ID` INT(10) NOT NULL,
	`Motivo_ID` INT(10) NULL DEFAULT NULL,
	`Descricao` varchar(500) NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Follow_Cancelado_ID`),
	INDEX `idx_tele_follows_cancelados_01` (`Follow_Cancelado_ID`),
	INDEX `idx_tele_follows_cancelados_02` (`Follow_ID`),
	INDEX `idx_tele_follows_cancelados_03` (`Workflow_ID`),
	INDEX `idx_tele_follows_cancelados_04` (`Motivo_ID`),
	INDEX `idx_tele_follows_cancelados_05` (`Usuario_Cadastro_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

/* MODULO DE LABORATORIO */
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (175, 13, 'Mat&eacute;ria Prima', '', 1);	
// drop table laboratorio_materia_prima;
CREATE TABLE `laboratorio_materia_prima` (
	`Laboratorio_Materia_Prima_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Laboratorio_Produto_ID` INT(10) NOT NULL DEFAULT '0',
	`Produto_Variacao_ID` INT(10) NOT NULL DEFAULT '0',
	`Quantidade` INT(10) NOT NULL DEFAULT '0',
	`Responsavel_ID` INT(11) NOT NULL DEFAULT '0',
	`Descricao` TEXT NULL,
	`Status_ID` INT(3) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Data_Alteracao` DATETIME NOT NULL,
	`Usuario_Alteracao_ID` INT(11) NOT NULL DEFAULT '0',
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '0',
	`Situacao_ID` INT(3) NOT NULL DEFAULT '1',
	PRIMARY KEY (`Laboratorio_Materia_Prima_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

ALTER TABLE `laboratorio_materia_prima`
	ADD COLUMN `Data_Alteracao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `Status_ID`,
	ADD COLUMN `Usuario_Alteracao_ID` INT(11) NOT NULL DEFAULT '0' AFTER `Data_Alteracao`;


ALTER TABLE `laboratorio_produtos`
ADD COLUMN `Data_Finalizado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `Data_Cadastro`;


select Modulo_ID from modulos where Slug = 'laboratorio' into @moduloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao) values (@moduloID, 'Aprovar SolicitaÃ§Ãµes','Aprovar SolicitaÃ§Ãµes de Materiais','laboratorio-controle-materia-prima','2');
update modulos_paginas set Posicao = 10 where Slug = 'laboratorio-gerenciar-modulo';
select Modulo_Pagina_ID from modulos_paginas where Slug = 'laboratorio-gerenciar-modulo' into @moduloPaginaID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values (@moduloID,'ConfiguraÃ§Ãµes','ConfiguraÃ§Ãµes Módulo Laboratório','laboratorio-configuracoes-gerais','10', @moduloPaginaID);



update modulos_paginas set Oculta_Menu = 0 where Slug = 'produtos-movimentacao-material';
update modulos_paginas set Oculta_Menu = 0, Titulo = 'Cadastro Produto' where Slug = 'produtos-cadastrar';


ALTER TABLE `laboratorio_produtos`
ADD COLUMN `Origem_Cadastro_ID` INT(10) NOT NULL DEFAULT '0' AFTER `Produto_Variacao_ID`;

INSERT INTO tipo_grupo (Tipo_Grupo_ID, Descr_Tipo_Grupo, Situacao_ID) VALUES (73, 'Status Solicitação', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (176, 73, 'Solicitado', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (177, 73, 'Aprovado', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (178, 73, 'Não Aprovado', '', 1);	


ALTER TABLE `laboratorio_materia_prima`
CHANGE COLUMN `Status_ID` `Status_ID` INT(3) NOT NULL DEFAULT '176' AFTER `Descricao`;





/* ClassificaÃ§ao de clientes */
INSERT INTO tipo_grupo (Tipo_Grupo_ID, Descr_Tipo_Grupo, Situacao_ID) VALUES (74, 'ClassificaÃ§Ã£o de Cliente', 1);



select Modulo_ID from modulos where Slug = 'cadastros' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'cadastros-tipos' into @subModuloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Tipo_Grupo_ID, Posicao, Pagina_Pai_ID) values (@moduloID, 'ClassificaÃ§Ã£o Clientes','Gerenciar ClassificaÃ§Ã£o Clientes','cadastros-classificacao','74', '10', @subModuloID);



CREATE TABLE `cadastros_classificacoes` (
	`Cadastro_Classificacao_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Cadastro_ID` INT(10) NOT NULL,
	`Classificacao_ID` INT(10) NOT NULL,
	`Situacao_ID` INT(10) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`Cadastro_Classificacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT;



/* AtualizaÃ§Ã£o 16/02/2016 */

select Modulo_ID from modulos where Slug = 'produtos' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'produtos-gerenciar-modulo' into @subModuloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID, 'Importar','Importar produtos em massa','produtos-importar','101',@subModuloID);
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID, ' Exportar','Exportar produtos em massa','produtos-exportar','102',@subModuloID);

RENAME TABLE tele_listas TO modulos_listas;
ALTER TABLE `modulos_listas`
	ADD COLUMN `Slug` VARCHAR(50) NULL AFTER `Descricao`;

RENAME TABLE tele_listas_cadastros TO modulos_listas_cadastros;



/* AtualizaÃ§Ã£o 17/02/2016 */


CREATE TABLE `formularios_respostas` (
	`Resposta_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Formulario_ID` INT(11) NOT NULL,
	`Chave_Estrangeira` INT(11) NOT NULL,
	`Tabela_Estrangeira` VARCHAR(50) NOT NULL,
	`Respostas` TEXT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Resposta_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


/* AtualizaÃ§Ã£o 18/02/2016 */
select Modulo_ID from modulos where Slug = 'pdv' into @moduloID;
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu) VALUES (@moduloID, 'Fechamento de PDV', 'Fechamento de PDV', 'pdv-fechamento-pdv', 1, NOW(), 2, 0);
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu) VALUES (@moduloID, 'Gerenciar Módulo', 'Gerenciar Módulo', 'pdv-gerenciar-modulo', 1, NOW(), 3, 0);
select Modulo_Pagina_ID from modulos_paginas where Slug = 'pdv-gerenciar-modulo' into @subModuloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values (@moduloID,'ConfiguraÃ§Ãµes Módulo','ConfiguraÃ§Ãµes Módulo PDV','pdv-configuracoes-gerais','10', @subModuloID);

//drop table pdv;
//drop table pdv_resumo;
//drop table pdv_produtos;

CREATE TABLE `pdv` (
	`PDV_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Atendente_ID` INT(10) NOT NULL,
	`Caixa_Numero` INT(10) NULL DEFAULT NULL,
	`Cliente_ID` INT(10) NOT NULL DEFAULT '0',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '97',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	PRIMARY KEY (`PDV_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0;


CREATE TABLE `pdv_produtos` (
	`PDV_Produto_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`PDV_ID` INT(10) NOT NULL DEFAULT '0',
	`Atendente_ID` INT(10) NOT NULL,
	`Produto_Variacao_ID` INT(10) NULL DEFAULT NULL,
	`Quantidade` INT(10) NOT NULL DEFAULT '1',
	`Valor_Unitario` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
	`Valor_Desconto` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	PRIMARY KEY (`PDV_Produto_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0;



CREATE TABLE `pdv_pagamentos` (
	`PDV_Pagamento_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`PDV_ID` INT(10) NOT NULL DEFAULT '0',
	`Forma_Pagamento_ID` INT(10) NOT NULL,
	`Data_Vencimento` DATETIME NULL DEFAULT NULL,
	`Valor` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	PRIMARY KEY (`PDV_Pagamento_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;



/* FINANCEIRO - INDICE - INPC */
select Modulo_ID from modulos where Slug = 'financeiro' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'financeiro-gerenciar' into @subModuloID;
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu, Pagina_Pai_ID) 
		     VALUES (@moduloID, 'INPC', 'INPC - Ãndice Nacional de PreÃ§os ao Consumidor', 'financeiro-inpc', 1, NOW(), 5, 0, @subModuloID);




CREATE TABLE `financeiro_cobranca` (
	`Cobranca_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Conta_ID` INT(10) NOT NULL,
	`Titulo_ID` INT(10) NOT NULL,
	`Dados` TEXT NULL,
	`Situacao_ID` INT(5) NOT NULL DEFAULT '0',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	PRIMARY KEY (`Cobranca_ID`),
	INDEX `idx_financeiro_cobranca_01` (`Cobranca_ID`),
	INDEX `idx_financeiro_cobranca_02` (`Conta_ID`),
	INDEX `idx_financeiro_cobranca_03` (`Titulo_ID`),
	INDEX `idx_financeiro_cobranca_04` (`Situacao_ID`),
	INDEX `idx_financeiro_cobranca_05` (`Titulo_ID`, `Situacao_ID`),
	INDEX `idx_financeiro_cobranca_06` (`Conta_ID`, `Titulo_ID`, `Situacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
ROW_FORMAT=COMPACT;




/* INDICE INPC E IPCA*/
INSERT INTO tipo_grupo (Tipo_Grupo_ID, Descr_Tipo_Grupo, Situacao_ID) VALUES (75, 'Indices de AtualizaÃ§Ã£o de PreÃ§os', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (179, 75, 'INPC', 'inpc', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (180, 75, 'IPCA', 'ipca', 1);	


/* PDV PAGAMENTO  */
ALTER TABLE `pdv_pagamentos`
	ADD COLUMN `Condicao_Pagamento` INT(10) NOT NULL DEFAULT '1' AFTER `Forma_Pagamento_ID`;



ALTER TABLE `produtos_movimentacoes`
	ALTER `Nota_Fiscal` DROP DEFAULT;
ALTER TABLE `produtos_movimentacoes`
	CHANGE COLUMN `Nota_Fiscal` `Nota_Fiscal` VARCHAR(20) NOT NULL AFTER `Quantidade`;

ALTER TABLE `produtos_movimentacoes`
	CHANGE COLUMN `Data_Cadastro` `Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `Numero_Serie`;


/***************************/
/* ATUALIZAÃ‡Ã•ES 13/04/2016 */
/***************************/

drop table `oportunidades`;
drop table `oportunidades_responsaveis`;

CREATE TABLE `oportunidades` (
	`Oportunidade_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Cadastro_ID` INT(10) NOT NULL DEFAULT '0',
	`Classificacao` INT(10) NOT NULL DEFAULT '0',	
	`Descricao` TEXT NOT NULL,
	`Tarefa_ID` INT(10) NOT NULL DEFAULT '0',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '0',
	`Data_Retorno` DATETIME NULL DEFAULT NULL,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NOW(),
	PRIMARY KEY (`Oportunidade_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `oportunidades_responsaveis` (
	`Oportunidade_Responsavel_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Oportunidade_ID` INT(10) NOT NULL DEFAULT '0',
	`Responsavel_ID` INT(10) NOT NULL DEFAULT '0',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Oportunidade_Responsavel_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;




select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
/*
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu, Pagina_Pai_ID) 
		     VALUES (@moduloID, 'Pipeline', 'Relatório Pipeline de Vendas', 'relatorio-pipeline', 1, NOW(), -10, 0, @subModuloID);
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu, Pagina_Pai_ID) 
		     VALUES (@moduloID, 'Forecast', 'Relatório Forecast de Vendas', 'relatorio-forecast', 1, NOW(), -9, 0, @subModuloID);
*/
select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu) 
		     VALUES (@moduloID, 'Oportunidades', 'Listagem de Oportunidades', 'oportunidades', 1, NOW(), -10, 0);



/* CORREÃ‡ÃƒO ENVIOS - LOGISTICA */	
ALTER TABLE `envios_workflows`
	CHANGE COLUMN `Data_Cadastro` `Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `Data_Entrega`;


/* ALTERï¿½ï¿½O Mï¿½DULO DE LABORATORIO INCLUIR CAMPO DE JUSTIFICATIVA DE SOLICITACAO */
ALTER TABLE `laboratorio_materia_prima`
	ADD COLUMN `Justificativa` TEXT NULL AFTER `Descricao`;


/* ALTERACOES  19/06/2016  - CRM */
select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu) 
		     VALUES (@moduloID, 'Oportunidade', 'Detalhes da Oportunidade', 'oportunidade', 1, NOW(), -11, 1);


/* CRM ORIGEM 22/06 */
INSERT INTO tipo_grupo (Tipo_Grupo_ID, Descr_Tipo_Grupo, Situacao_ID) VALUES (76, 'CRM Origem', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (182, 76, 'Telemarketing', '', 1);	


/* ARRUMANDO AREA DE CONFIGURACOES */
select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'chamados-configuracoes' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID, Tipo_Grupo_ID)values(@moduloID,'Situações Propostas','Gerenciar Situações de Propostas','chamados-situacoes-propostas','3', @subModuloID, '53');


select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'chamados-configuracoes' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID, Tipo_Grupo_ID)values(@moduloID,'Origens','Gerenciar Origens','oportunidades-origens','7', @subModuloID, '76');


update modulos_paginas set Oculta_Menu = 1 where Slug = 'chamados-tipo-tarefa'
update modulos_paginas set titulo = 'Situações Oportunidades', Posicao = 1  where slug = 'chamados-orcamento-situacoes';
update modulos_paginas set titulo = 'Situações Propostas', Posicao = 2  where slug = 'chamados-situacoes';
update modulos_paginas set titulo = 'Situações O.S. Chamados', Posicao = 3  where slug = 'chamados-situacoes-propostas';
update modulos_paginas set titulo = 'Prioridades' where slug = 'chamados-prioridades';
update tipo set Descr_Tipo = 'Mat&eacute;ria Prima', Tipo_Grupo_ID = 13 where Tipo_ID = 175;


drop table oportunidades_responsaveis;
drop table oportunidades;


CREATE TABLE `oportunidades_workflows` (
	`Oportunidade_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Cadastro_ID` INT(10) NULL DEFAULT NULL,
	`Empresa_ID` INT(10) NULL DEFAULT NULL,
	`Origem_ID` INT(10) NULL DEFAULT NULL,
	`Chave_Estrangeira` INT(10) NULL DEFAULT NULL,
	`Tabela_Estrangeira` VARCHAR(50) NULL DEFAULT NULL,
	`Orcamento_ID` VARCHAR(50) NULL DEFAULT NULL,
	`Proposta_ID` VARCHAR(50) NULL DEFAULT NULL,
	`Titulo` VARCHAR(150) NOT NULL,
	`Descricao` TEXT NOT NULL,
	`Expectativa_Valor` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '0',
	`Status_ID` INT(11) NOT NULL DEFAULT '1' COMMENT 'Determina se o registro foi excluido',
	`Responsavel_ID` INT(11) NOT NULL DEFAULT '0',
	`Data_Previsao` DATETIME NULL DEFAULT NULL,
	`Probabilidade_Fechamento` INT(3) NULL DEFAULT NULL,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Oportunidade_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;




// NOVOS TIPOS TANTO PARA TELEMARKETING COMO ORCAMENTOS
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (183, 51, 'Transformar em orï¿½amento', '', 1);
//INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (184, 76, 'Reuniï¿½o Agendada', '', 1);	



/* ABORDADO
INSERT INTO tipo_grupo (Tipo_Grupo_ID, Descr_Tipo_Grupo, Situacao_ID) VALUES (77, 'CRM Status Funil', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (183, 76, 'Prospectando', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (184, 76, 'Qualifica&ccedil;&atilde;o', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (185, 76, 'Necessita An&aacute;lise', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (186, 76, 'Proposta Valor', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (187, 76, 'Identif. Decisor', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (188, 76, 'An&aacute;lise Percep;&atilde;o', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (189, 76, 'Proposta or Cota;&atilde;o', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (190, 76, 'Negocia;&atilde;o or Revis;&atilde;o', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (191, 76, 'Fechado Vencido', '', 1);	
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (192, 76, 'Fechado Perdido', '', 1);
*/


/* 28/06/2016 */

ALTER TABLE `orcamentos_workflows`
	DROP COLUMN `Oportunidade_ID`;

ALTER TABLE `oportunidades_workflows`
	CHANGE COLUMN `Orcamento_ID` `Orcamento_ID` INT NULL DEFAULT NULL AFTER `Tabela_Estrangeira`,
	DROP COLUMN `Proposta_ID`;


/* RELATÓRIOS */

select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
INSERT INTO modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Situacao_ID, Data_Cadastro, Posicao, Oculta_Menu, Pagina_Pai_ID) 
		     VALUES (@moduloID, 'Propostas e Vendas', 'Relatórios de Orçamentos e Vendas', 'chamados-orcamentos-relatorios', 1, NOW(), -11, 0, @subModuloID);




/* JUSTIFICATIVA ENTRADAS MANUAIS */
select Modulo_ID from modulos where Slug = 'produtos' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'produtos-gerenciar-modulo' into @moduloPaginaID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values (@moduloID,'Configura&ccedil;&otilde;es Gerais','Configura&ccedil;&otilde;es Gerais','produtos-configuracoes-gerais','1000', @moduloPaginaID);

ALTER TABLE `produtos_movimentacoes`
	ADD COLUMN `Justificativa` VARCHAR(1024) NOT NULL AFTER `Numero_Serie`;

	
/* PRODUTOS FATURAMENTO */
ALTER TABLE `produtos_dados`
	ADD COLUMN `Faturamento_Direto` INT(11) NULL DEFAULT '0' AFTER `Industrializado`;

ALTER TABLE `chamados_workflows_produtos` ADD COLUMN `Faturamento_Direto` INT(11) NOT NULL DEFAULT '0' NULL AFTER `Pagamento_Prestador`;
ALTER TABLE `orcamentos_propostas_produtos` ADD COLUMN `Faturamento_Direto` INT(11) NOT NULL DEFAULT '0' NULL AFTER `Pagamento_Prestador`;
	
UPDATE chamados_workflows_produtos set Faturamento_Direto = 0 where Faturamento_Direto is null;
UPDATE orcamentos_propostas_produtos set Faturamento_Direto = 0 where Faturamento_Direto is null;

ALTER TABLE `chamados_workflows_produtos` CHANGE COLUMN `Faturamento_Direto` `Faturamento_Direto` INT(11) NOT NULL DEFAULT '0' AFTER `Pagamento_Prestador`;
ALTER TABLE `orcamentos_propostas_produtos`	CHANGE COLUMN `Faturamento_Direto` `Faturamento_Direto` INT(11) NOT NULL DEFAULT '0' AFTER `Pagamento_Prestador`;
	

delete from modulos_paginas where slug = 'relatorio-pipeline';
delete from modulos_paginas where slug = 'relatorio-forecast';
update modulos_paginas set Titulo = 'Propostas e Vendas' where slug = 'chamados-orcamentos-relatorios';



CREATE TABLE `cadastros_follows` (
	`Follow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Workflow_ID` INT(10) NOT NULL,
	`Descricao` MEDIUMTEXT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Follow_ID`),
	INDEX `idx_cadastros_follows_01` (`Follow_ID`),
	INDEX `idx_cadastros_follows_02` (`Workflow_ID`),
	INDEX `idx_cadastros_follows_03` (`Situacao_ID`),
	INDEX `idx_cadastros_follows_04` (`Usuario_Cadastro_ID`),
	INDEX `idx_cadastros_follows_05` (`Data_Cadastro`),
	INDEX `idx_cadastros_follows_06` (`Follow_ID`, `Workflow_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Data_Cadastro`),
	INDEX `idx_cadastros_follows_07` (`Follow_ID`, `Workflow_ID`, `Situacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;
		
CREATE TABLE `cadastros_representantes` (
	`Cadastro_Representante_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Cadastro_ID` INT(10) NOT NULL,
	`Representante_ID` INT(10) NOT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Cadastro_Representante_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO tipo_grupo (Tipo_Grupo_ID, Descr_Tipo_Grupo, Situacao_ID) VALUES (77, 'Tipo Oportunidade', 1);
select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'chamados-configuracoes' into @subModuloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Tipo_Grupo_ID, Posicao, Pagina_Pai_ID) values (@moduloID, 'Tipos Oportunidades','Tipos Oportunidades','oportunidades-tipos','77', '10', @subModuloID);


/* INICIO - ARRUMANDO TAREFAS DEIXANDO NOS NOVOS PADRÕES */	
/* Apos as alterações abaixo rodar os scripts acertocadastroduplicado.php e acertotarefas.php*/
	
ALTER TABLE `projetos_vinculos_tarefas`
	ADD COLUMN `Chave_Estrangeira` INT(10) NULL DEFAULT NULL AFTER `Posicao`,
	ADD COLUMN `Tabela_Estrangeira` VARCHAR(100) NULL DEFAULT NULL AFTER `Chave_Estrangeira`,
	ADD COLUMN `Cadastro_Alvo_ID` INT NULL DEFAULT NULL AFTER `Tabela_Estrangeira`;

ALTER TABLE `projetos_vinculos_tarefas`
	ADD COLUMN `Campo_Estrangeiro` VARCHAR(100) NULL DEFAULT NULL AFTER `Tabela_Estrangeira`;


update projetos_vinculos_tarefas set Campo_Estrangeiro = 'Oportunidade_ID' where Tabela_Estrangeira = 'oportunidades_workflows';

update projetos_vinculos pv
inner join projetos_vinculos_tarefas pvt on pvt.Projeto_Vinculo_ID = pv.Projeto_Vinculo_ID
set pvt.Tabela_Estrangeira = pv.Tabela_Estrangeira,
pvt.Chave_Estrangeira = pv.Chave_Estrangeira,
pvt.Campo_Estrangeiro = pv.Campo_Estrangeiro;

/* FIM - TAREFAS */

/* INICIO - CRM - FUNCIONALIDADE DE CLIENTES X GERENTE DE CONTA */
select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values (@moduloID, 'Atendimento','Atendimento de Clientes por seus Gerente de Conta','relatorio-acompanhamento-clientes', '4', @subModuloID);

/* CRIANDO VIEW REFERENTE AO FOLLOW */
drop view v_follows;

create view v_follows as 				
	select 'Cadastros' as Modulo, '' as ID, '' as Situacao,
			cf.Data_Cadastro as Data_Cadastro, cf.Descricao as Descricao, 
			c.Cadastro_ID, c.Nome as Cadastro, r.Cadastro_ID as Responsavel_ID, r.Nome as Responsavel
		from cadastros_follows cf
		inner join cadastros_dados r on r.Cadastro_ID = cf.Usuario_Cadastro_ID
		inner join cadastros_dados c on c.Cadastro_ID = cf.Workflow_ID
	union all
	select 'Telemarketing' as Modulo, tf.Workflow_ID as ID, ts.Descr_Tipo as Situacao, 
			tf.Data_Cadastro as Data_Cadastro, tf.Descricao as Descricao,
			c.Cadastro_ID, c.Nome as Cadastro, r.Cadastro_ID as Responsavel_ID, r.Nome as Responsavel
		from tele_follows tf
		inner join tele_workflows tw on tw.Workflow_ID = tf.Workflow_ID
		inner join cadastros_dados r on r.Cadastro_ID = tw.Usuario_Cadastro_ID
		inner join cadastros_dados c on c.Cadastro_ID = tw.Cadastro_ID
		left join tipo ts on ts.Tipo_ID = tf.Situacao_ID
	union all
	select 'Orcamentos' as Modulo, of.Workflow_ID as ID, tf.Descr_Tipo as Situacao, 
			of.Data_Cadastro as Data_Cadastro, of.Descricao as Descricao,
			c.Cadastro_ID, c.Nome as Cadastro, r.Cadastro_ID as Responsavel_ID, r.Nome as Responsavel
		from orcamentos_follows of
		inner join orcamentos_workflows ow on ow.Workflow_ID = of.Workflow_ID
		inner join cadastros_dados r on r.Cadastro_ID = of.Usuario_Cadastro_ID
		inner join cadastros_dados c on c.Cadastro_ID = ow.Solicitante_ID
		left join tipo tf on tf.Tipo_ID = of.Situacao_ID
	union all
	select 'Chamados' as Modulo, cf.Workflow_ID as ID, tf.Descr_Tipo as Situacao,
			 cf.Data_Cadastro as Data_Cadastro, cf.Descricao as Descricao,
			c.Cadastro_ID, c.Nome as Cadastro, r.Cadastro_ID as Responsavel_ID, r.Nome as Responsavel
		from chamados_follows cf
		inner join chamados_workflows cw on cw.Workflow_ID = cf.Workflow_ID
		inner join cadastros_dados r on r.Cadastro_ID = cf.Usuario_Cadastro_ID
		inner join cadastros_dados c on c.Cadastro_ID = cw.Solicitante_ID
		left join tipo tf on tf.Tipo_ID = cf.Situacao_ID
	union all	
	select 'Tarefas' as Modulo, '' as ID, '' as Situacao, 
		pvtf.Data_Cadastro as Data_Cadastro, pvtf.Descricao as Descricao,
		c.Cadastro_ID, c.Nome as Cadastro, r.Cadastro_ID as Responsavel_ID, r.Nome as Responsavel
		from projetos_vinculos_tarefas_follows pvtf
		inner join projetos_vinculos_tarefas pvt on pvt.Projeto_Vinculo_Tarefa_ID = pvtf.Projeto_Vinculo_Tarefa_ID
		inner join cadastros_dados r on r.Cadastro_ID = pvtf.Usuario_Cadastro_ID
		inner join cadastros_dados c on c.Cadastro_ID = pvt.Cadastro_Alvo_ID
		
	order by Data_Cadastro desc
	
	
	
ALTER TABLE `oportunidades_workflows`
	ADD COLUMN `Tipo_ID` INT(10) NULL DEFAULT NULL COMMENT 'Tipo Grupo ID 77' AFTER `Oportunidade_ID`;

	
	
	
ALTER TABLE `nf_dados`
	ADD COLUMN `NF_Dados` TEXT NOT NULL AFTER `NF_Array`;
	