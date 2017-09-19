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

	
	
	

      /*
      * Script created by Quest Schema Compare at 08/12/2016 17:47:09.
      * Please back up your database before running this script.
      *
      * Synchronizing objects from sys_prod to eficazsystem31.
      */
    USE `eficazsystem31`;

/* Header line. Object: modulos_ajax. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `modulos_ajax` will be dropped.
DROP TABLE `eficazsystem31`.`modulos_ajax`;

/* Header line. Object: modulos_paginas_usuarios. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `modulos_paginas_usuarios` will be dropped.
DROP TABLE `eficazsystem31`.`modulos_paginas_usuarios`;

/* Header line. Object: modulos_vinculos_tipos. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `modulos_vinculos_tipos` will be dropped.
DROP TABLE `eficazsystem31`.`modulos_vinculos_tipos`;

/* Header line. Object: oportunidades. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `oportunidades` will be dropped.
DROP TABLE `eficazsystem31`.`oportunidades`;

/* Header line. Object: oportunidades_responsaveis. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `oportunidades_responsaveis` will be dropped.
DROP TABLE `eficazsystem31`.`oportunidades_responsaveis`;

/* Header line. Object: pdv. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `pdv` will be dropped.
DROP TABLE `eficazsystem31`.`pdv`;

/* Header line. Object: pdv_produtos. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `pdv_produtos` will be dropped.
DROP TABLE `eficazsystem31`.`pdv_produtos`;

/* Header line. Object: regionais. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `regionais` will be dropped.
DROP TABLE `eficazsystem31`.`regionais`;

/* Header line. Object: tmp_importacao. Script date: 08/12/2016 17:47:09. */
-- Attention: Table `tmp_importacao` will be dropped.
DROP TABLE `eficazsystem31`.`tmp_importacao`;

/* Header line. Object: cadastros_contas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_cadastros_contas`;

CREATE TABLE `eficazsystem31`.`_temp_cadastros_contas` (
	`Cadastro_Conta_ID` int(10) NOT NULL auto_increment,
	`Cadastro_ID` int(10) NOT NULL,
	`Tipo_Conta_ID` int(10) NOT NULL default '0',
	`Nome_Conta` varchar(100) default NULL,
	`Dados` text default NULL,
	`Saldo_Inicial` decimal(16,2) default NULL,
	`Situacao_ID` int(11) default '1',
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Data_Cadastro` timestamp,
	PRIMARY KEY  ( `Cadastro_Conta_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 2
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_cadastros_contas`
( `Cadastro_Conta_ID`, `Cadastro_ID`, `Dados`, `Data_Cadastro`, `Nome_Conta`, `Saldo_Inicial`, `Situacao_ID`, `Tipo_Conta_ID`, `Usuario_Cadastro_ID` )
SELECT
`Cadastro_Conta_ID`, `Cadastro_ID`, `Dados`, `Data_Cadastro`, `Nome_Conta`, `Saldo_Inicial`, `Situacao_ID`, `Tipo_Conta_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`cadastros_contas`;

DROP TABLE `eficazsystem31`.`cadastros_contas`;

ALTER TABLE `eficazsystem31`.`_temp_cadastros_contas` RENAME `cadastros_contas`;

/* Header line. Object: cadastros_enderecos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_cadastros_enderecos`;

CREATE TABLE `eficazsystem31`.`_temp_cadastros_enderecos` (
	`Cadastro_Endereco_ID` int(10) NOT NULL auto_increment,
	`Cadastro_ID` int(10) NOT NULL default '0',
	`Tipo_Endereco_ID` int(10) default NULL,
	`CEP` varchar(10) NOT NULL,
	`Logradouro` varchar(250) NOT NULL,
	`Numero` varchar(10) NOT NULL,
	`Complemento` varchar(100) NOT NULL,
	`Bairro` varchar(100) NOT NULL,
	`Cidade` varchar(50) NOT NULL,
	`UF` varchar(2) NOT NULL,
	`Referencia` varchar(150) NOT NULL,
	`Situacao_ID` varchar(150) NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	KEY `idx_cadastros_enderecos_01` ( `Cadastro_Endereco_ID` ),
	KEY `idx_cadastros_enderecos_02` ( `Cadastro_ID` ),
	KEY `idx_cadastros_enderecos_03` ( `Tipo_Endereco_ID` ),
	KEY `idx_cadastros_enderecos_04` ( `Situacao_ID` ),
	KEY `idx_cadastros_enderecos_05` ( `CEP` ),
	KEY `idx_cadastros_enderecos_06` ( `Cadastro_ID`, `Situacao_ID` ),
	KEY `idx_cadastros_enderecos_07` ( `Cadastro_ID`, `Situacao_ID`, `Tipo_Endereco_ID` ),
	PRIMARY KEY  ( `Cadastro_Endereco_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 9976
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_cadastros_enderecos`
( `Bairro`, `Cadastro_Endereco_ID`, `Cadastro_ID`, `CEP`, `Cidade`, `Complemento`, `Data_Cadastro`, `Logradouro`, `Numero`, `Referencia`, `Situacao_ID`, `Tipo_Endereco_ID`, `UF`, `Usuario_Cadastro_ID` )
SELECT
`Bairro`, `Cadastro_Endereco_ID`, `Cadastro_ID`, `CEP`, `Cidade`, `Complemento`, `Data_Cadastro`, `Logradouro`, `Numero`, `Referencia`, `Situacao_ID`, `Tipo_Endereco_ID`, `UF`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`cadastros_enderecos`;

DROP TABLE `eficazsystem31`.`cadastros_enderecos`;

ALTER TABLE `eficazsystem31`.`_temp_cadastros_enderecos` RENAME `cadastros_enderecos`;

/* Header line. Object: cadastros_follows. Script date: 08/12/2016 17:47:09. */
CREATE TABLE `eficazsystem31`.`cadastros_follows` (
	`Follow_ID` int(10) NOT NULL auto_increment,
	`Workflow_ID` int(10) NOT NULL,
	`Descricao` mediumtext default NULL,
	`Situacao_ID` int(10) default NULL,
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(10) default NULL,
	KEY `idx_cadastros_follows_01` ( `Follow_ID` ),
	KEY `idx_cadastros_follows_02` ( `Workflow_ID` ),
	KEY `idx_cadastros_follows_03` ( `Situacao_ID` ),
	KEY `idx_cadastros_follows_04` ( `Usuario_Cadastro_ID` ),
	KEY `idx_cadastros_follows_05` ( `Data_Cadastro` ),
	KEY `idx_cadastros_follows_06` ( `Follow_ID`, `Workflow_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Data_Cadastro` ),
	KEY `idx_cadastros_follows_07` ( `Follow_ID`, `Workflow_ID`, `Situacao_ID` ),
	PRIMARY KEY  ( `Follow_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

/* Header line. Object: cadastros_telefones. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_cadastros_telefones`;

CREATE TABLE `eficazsystem31`.`_temp_cadastros_telefones` (
	`Cadastro_Telefone_ID` int(10) NOT NULL auto_increment,
	`Cadastro_ID` int(10) NOT NULL default '0',
	`Telefone` varchar(20) default NULL,
	`Tipo_Telefone_ID` varchar(20) default NULL,
	`Observacao` varchar(50) default NULL,
	`Situacao_ID` varchar(20) NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	KEY `idx_cadastros_telefones_01` ( `Cadastro_Telefone_ID` ),
	KEY `idx_cadastros_telefones_02` ( `Cadastro_ID` ),
	KEY `idx_cadastros_telefones_03` ( `Telefone` ),
	KEY `idx_cadastros_telefones_04` ( `Tipo_Telefone_ID` ),
	KEY `idx_cadastros_telefones_05` ( `Situacao_ID` ),
	KEY `idx_cadastros_telefones_06` ( `Cadastro_ID`, `Situacao_ID` ),
	KEY `idx_cadastros_telefones_07` ( `Cadastro_ID`, `Situacao_ID`, `Tipo_Telefone_ID` ),
	PRIMARY KEY  ( `Cadastro_Telefone_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 18503
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_cadastros_telefones`
( `Cadastro_ID`, `Cadastro_Telefone_ID`, `Data_Cadastro`, `Observacao`, `Situacao_ID`, `Telefone`, `Tipo_Telefone_ID`, `Usuario_Cadastro_ID` )
SELECT
`Cadastro_ID`, `Cadastro_Telefone_ID`, `Data_Cadastro`, `Observacao`, `Situacao_ID`, `Telefone`, `Tipo_Telefone_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`cadastros_telefones`;

DROP TABLE `eficazsystem31`.`cadastros_telefones`;

ALTER TABLE `eficazsystem31`.`_temp_cadastros_telefones` RENAME `cadastros_telefones`;

/* Header line. Object: chamados_workflows_produtos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_chamados_workflows_produtos`;

CREATE TABLE `eficazsystem31`.`_temp_chamados_workflows_produtos` (
	`Workflow_Produto_ID` int(10) NOT NULL auto_increment,
	`Workflow_ID` int(10) NOT NULL,
	`Produto_Variacao_ID` int(10) NOT NULL,
	`Descricao_Produto` varchar(250) default NULL,
	`Quantidade` decimal(10,2) default NULL,
	`Valor_Custo_Unitario` decimal(10,2) default NULL,
	`Valor_Venda_Unitario` decimal(10,2) default NULL,
	`Cobranca_Cliente` int(11) default NULL,
	`Pagamento_Prestador` int(11) default NULL,
	`Faturamento_Direto` int(11) NOT NULL default '0',
	`Prestador_ID` int(10) default NULL,
	`Cliente_Final_ID` int(10) default NULL,
	`Situacao_ID` int(10) default NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(10) default NULL,
	`Usuario_Alteracao_ID` int(10) default NULL,
	KEY `idx_chamados_workflows_produtos_01` ( `Workflow_Produto_ID` ),
	KEY `idx_chamados_workflows_produtos_02` ( `Workflow_ID` ),
	KEY `idx_chamados_workflows_produtos_03` ( `Produto_Variacao_ID` ),
	KEY `idx_chamados_workflows_produtos_04` ( `Usuario_Cadastro_ID` ),
	KEY `idx_chamados_workflows_produtos_05` ( `Situacao_ID` ),
	KEY `idx_chamados_workflows_produtos_06` ( `Workflow_ID`, `Workflow_Produto_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID` ),
	KEY `idx_chamados_workflows_produtos_07` ( `Workflow_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID` ),
	KEY `idx_chamados_workflows_produtos_08` ( `Workflow_ID`, `Produto_Variacao_ID`, `Situacao_ID` ),
	PRIMARY KEY  ( `Workflow_Produto_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 18876
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_chamados_workflows_produtos`
( `Cliente_Final_ID`, `Cobranca_Cliente`, `Data_Cadastro`, `Descricao_Produto`, `Faturamento_Direto`, `Pagamento_Prestador`, `Prestador_ID`, `Produto_Variacao_ID`, `Quantidade`, `Situacao_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`, `Valor_Custo_Unitario`, `Valor_Venda_Unitario`, `Workflow_ID`, `Workflow_Produto_ID` )
SELECT
`Cliente_Final_ID`, `Cobranca_Cliente`, `Data_Cadastro`, `Descricao_Produto`, `Faturamento_Direto`, `Pagamento_Prestador`, `Prestador_ID`, `Produto_Variacao_ID`, `Quantidade`, `Situacao_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`, `Valor_Custo_Unitario`, `Valor_Venda_Unitario`, `Workflow_ID`, `Workflow_Produto_ID`
FROM `eficazsystem31`.`chamados_workflows_produtos`;

DROP TABLE `eficazsystem31`.`chamados_workflows_produtos`;

ALTER TABLE `eficazsystem31`.`_temp_chamados_workflows_produtos` RENAME `chamados_workflows_produtos`;

/* Header line. Object: faq_perguntas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_faq_perguntas`;

CREATE TABLE `eficazsystem31`.`_temp_faq_perguntas` (
	`Pergunta_ID` int(10) NOT NULL auto_increment,
	`Descricao` varchar(2000) NOT NULL,
	`Situacao_ID` int(11) NOT NULL,
	`Ordem` int(11) NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	PRIMARY KEY  ( `Pergunta_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_faq_perguntas`
( `Data_Cadastro`, `Descricao`, `Ordem`, `Pergunta_ID`, `Situacao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Data_Cadastro`, `Descricao`, `Ordem`, `Pergunta_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`faq_perguntas`;

DROP TABLE `eficazsystem31`.`faq_perguntas`;

ALTER TABLE `eficazsystem31`.`_temp_faq_perguntas` RENAME `faq_perguntas`;

/* Header line. Object: faq_respostas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_faq_respostas`;

CREATE TABLE `eficazsystem31`.`_temp_faq_respostas` (
	`Resposta_ID` int(10) NOT NULL auto_increment,
	`Pergunta_ID` int(10) NOT NULL,
	`Descricao` varchar(2000) NOT NULL,
	`Ordem` int(11) NOT NULL,
	`Situacao_ID` int(11) NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	PRIMARY KEY  ( `Resposta_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_faq_respostas`
( `Data_Cadastro`, `Descricao`, `Ordem`, `Pergunta_ID`, `Resposta_ID`, `Situacao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Data_Cadastro`, `Descricao`, `Ordem`, `Pergunta_ID`, `Resposta_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`faq_respostas`;

DROP TABLE `eficazsystem31`.`faq_respostas`;

ALTER TABLE `eficazsystem31`.`_temp_faq_respostas` RENAME `faq_respostas`;

/* Header line. Object: financeiro_contabil. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_financeiro_contabil`;

CREATE TABLE `eficazsystem31`.`_temp_financeiro_contabil` (
	`Contabil_ID` int(10) NOT NULL auto_increment,
	`Centro_Custo_ID` int(10) NOT NULL default '0',
	`Tipo_Conta_ID` int(10) NOT NULL default '0',
	`Conta_ID` int(10) NOT NULL default '0',
	`Valor` decimal(16,2) NOT NULL default '0.00',
	`Observacao` varchar(250) NOT NULL default '0.00',
	`Situacao_ID` int(11) NOT NULL default '1',
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Data_Cadastro` timestamp,
	PRIMARY KEY  ( `Contabil_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 17
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_financeiro_contabil`
( `Centro_Custo_ID`, `Conta_ID`, `Contabil_ID`, `Data_Cadastro`, `Observacao`, `Situacao_ID`, `Tipo_Conta_ID`, `Usuario_Cadastro_ID`, `Valor` )
SELECT
`Centro_Custo_ID`, `Conta_ID`, `Contabil_ID`, `Data_Cadastro`, `Observacao`, `Situacao_ID`, `Tipo_Conta_ID`, `Usuario_Cadastro_ID`, `Valor`
FROM `eficazsystem31`.`financeiro_contabil`;

DROP TABLE `eficazsystem31`.`financeiro_contabil`;

ALTER TABLE `eficazsystem31`.`_temp_financeiro_contabil` RENAME `financeiro_contabil`;

/* Header line. Object: financeiro_movimentacoes. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_financeiro_movimentacoes`;

CREATE TABLE `eficazsystem31`.`_temp_financeiro_movimentacoes` (
	`Movimentacao_ID` int(10) NOT NULL auto_increment,
	`Conta_ID` int(10) NOT NULL default '0',
	`Titulo_ID` int(10) NOT NULL default '0',
	`Cadastro_Conta_ID` int(10) NOT NULL default '0',
	`Data_Movimentacao` datetime NOT NULL default '0000-00-00 00:00:00',
	`Valor` decimal(16,2) NOT NULL default '0.00',
	`Situacao_ID` int(11) NOT NULL default '1',
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Data_Cadastro` timestamp,
	PRIMARY KEY  ( `Movimentacao_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 5
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_financeiro_movimentacoes`
( `Cadastro_Conta_ID`, `Conta_ID`, `Data_Cadastro`, `Data_Movimentacao`, `Movimentacao_ID`, `Situacao_ID`, `Titulo_ID`, `Usuario_Cadastro_ID`, `Valor` )
SELECT
`Cadastro_Conta_ID`, `Conta_ID`, `Data_Cadastro`, `Data_Movimentacao`, `Movimentacao_ID`, `Situacao_ID`, `Titulo_ID`, `Usuario_Cadastro_ID`, `Valor`
FROM `eficazsystem31`.`financeiro_movimentacoes`;

DROP TABLE `eficazsystem31`.`financeiro_movimentacoes`;

ALTER TABLE `eficazsystem31`.`_temp_financeiro_movimentacoes` RENAME `financeiro_movimentacoes`;

/* Header line. Object: formularios. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_formularios`;

CREATE TABLE `eficazsystem31`.`_temp_formularios` (
	`Formulario_ID` int(11) NOT NULL auto_increment,
	`Nome` varchar(50) NOT NULL,
	`Tabela_Estrangeira` varchar(250) NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	`Usuario_Cadastro_ID` int(11) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	PRIMARY KEY  ( `Formulario_ID` ),
	UNIQUE INDEX `Titulo` ( `Nome` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_formularios`
( `Data_Cadastro`, `Formulario_ID`, `Nome`, `Situacao_ID`, `Tabela_Estrangeira`, `Usuario_Cadastro_ID` )
SELECT
`Data_Cadastro`, `Formulario_ID`, `Nome`, `Situacao_ID`, `Tabela_Estrangeira`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`formularios`;

DROP TABLE `eficazsystem31`.`formularios`;

ALTER TABLE `eficazsystem31`.`_temp_formularios` RENAME `formularios`;

/* Header line. Object: formularios_campos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_formularios_campos`;

CREATE TABLE `eficazsystem31`.`_temp_formularios_campos` (
	`Campo_ID` int(10) NOT NULL auto_increment,
	`Nome` varchar(250) NOT NULL,
	`Descricao` varchar(5000) NOT NULL,
	`Tipo_Campo` varchar(10) NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	`Usuario_Cadastro_ID` int(11) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	UNIQUE INDEX `Campos_Nome` ( `Nome` ),
	PRIMARY KEY  ( `Campo_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_formularios_campos`
( `Campo_ID`, `Data_Cadastro`, `Descricao`, `Nome`, `Situacao_ID`, `Tipo_Campo`, `Usuario_Cadastro_ID` )
SELECT
`Campo_ID`, `Data_Cadastro`, `Descricao`, `Nome`, `Situacao_ID`, `Tipo_Campo`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`formularios_campos`;

DROP TABLE `eficazsystem31`.`formularios_campos`;

ALTER TABLE `eficazsystem31`.`_temp_formularios_campos` RENAME `formularios_campos`;

/* Header line. Object: formularios_campos_opcoes. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_formularios_campos_opcoes`;

CREATE TABLE `eficazsystem31`.`_temp_formularios_campos_opcoes` (
	`Campo_Opcao_ID` int(10) NOT NULL auto_increment,
	`Campo_ID` int(10) NOT NULL,
	`Descricao` varchar(250) NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	`Posicao` int(11) NOT NULL default '1',
	`Usuario_Cadastro_ID` int(11) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	UNIQUE INDEX `Campos_Descricao` ( `Descricao` ),
	PRIMARY KEY  ( `Campo_Opcao_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_formularios_campos_opcoes`
( `Campo_ID`, `Campo_Opcao_ID`, `Data_Cadastro`, `Descricao`, `Posicao`, `Situacao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Campo_ID`, `Campo_Opcao_ID`, `Data_Cadastro`, `Descricao`, `Posicao`, `Situacao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`formularios_campos_opcoes`;

DROP TABLE `eficazsystem31`.`formularios_campos_opcoes`;

ALTER TABLE `eficazsystem31`.`_temp_formularios_campos_opcoes` RENAME `formularios_campos_opcoes`;

/* Header line. Object: formularios_formulario_campo. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_formularios_formulario_campo`;

CREATE TABLE `eficazsystem31`.`_temp_formularios_formulario_campo` (
	`Formulario_Campo_ID` int(10) NOT NULL auto_increment,
	`Formulario_ID` int(10) NOT NULL,
	`Campo_ID` int(10) NOT NULL,
	`Posicao` int(11) default NULL,
	`Largura` varchar(20) default NULL,
	`Altura` varchar(20) default NULL,
	`Obrigatorio` int(11) default NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	`Usuario_Cadastro_ID` int(11) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	PRIMARY KEY  ( `Formulario_Campo_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_formularios_formulario_campo`
( `Altura`, `Campo_ID`, `Data_Cadastro`, `Formulario_Campo_ID`, `Formulario_ID`, `Largura`, `Obrigatorio`, `Posicao`, `Situacao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Altura`, `Campo_ID`, `Data_Cadastro`, `Formulario_Campo_ID`, `Formulario_ID`, `Largura`, `Obrigatorio`, `Posicao`, `Situacao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`formularios_formulario_campo`;

DROP TABLE `eficazsystem31`.`formularios_formulario_campo`;

ALTER TABLE `eficazsystem31`.`_temp_formularios_formulario_campo` RENAME `formularios_formulario_campo`;

/* Header line. Object: help. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_help`;

CREATE TABLE `eficazsystem31`.`_temp_help` (
	`Help_ID` int(11) NOT NULL auto_increment,
	`Slug_Pagina` varchar(150) NOT NULL,
	`Titulo` varchar(150) NOT NULL,
	`Descricao` text NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	PRIMARY KEY  ( `Help_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_help`
( `Data_Cadastro`, `Descricao`, `Help_ID`, `Situacao_ID`, `Slug_Pagina`, `Titulo` )
SELECT
`Data_Cadastro`, `Descricao`, `Help_ID`, `Situacao_ID`, `Slug_Pagina`, `Titulo`
FROM `eficazsystem31`.`help`;

DROP TABLE `eficazsystem31`.`help`;

ALTER TABLE `eficazsystem31`.`_temp_help` RENAME `help`;

/* Header line. Object: laboratorio_materia_prima. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_laboratorio_materia_prima`;

CREATE TABLE `eficazsystem31`.`_temp_laboratorio_materia_prima` (
	`Laboratorio_Materia_Prima_ID` int(10) NOT NULL auto_increment,
	`Laboratorio_Produto_ID` int(10) NOT NULL default '0',
	`Produto_Variacao_ID` int(10) NOT NULL default '0',
	`Quantidade` int(10) NOT NULL default '0',
	`Responsavel_ID` int(11) NOT NULL default '0',
	`Descricao` text default NULL,
	`Justificativa` text default NULL,
	`Status_ID` int(3) NOT NULL default '176',
	`Data_Alteracao` timestamp NOT NULL,
	`Usuario_Alteracao_ID` int(11) NOT NULL default '0',
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL default '0',
	`Situacao_ID` int(3) NOT NULL default '1',
	PRIMARY KEY  ( `Laboratorio_Materia_Prima_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1726
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_laboratorio_materia_prima`
( `Data_Alteracao`, `Data_Cadastro`, `Descricao`, `Justificativa`, `Laboratorio_Materia_Prima_ID`, `Laboratorio_Produto_ID`, `Produto_Variacao_ID`, `Quantidade`, `Responsavel_ID`, `Situacao_ID`, `Status_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Data_Alteracao`, `Data_Cadastro`, `Descricao`, `Justificativa`, `Laboratorio_Materia_Prima_ID`, `Laboratorio_Produto_ID`, `Produto_Variacao_ID`, `Quantidade`, `Responsavel_ID`, `Situacao_ID`, `Status_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`laboratorio_materia_prima`;

DROP TABLE `eficazsystem31`.`laboratorio_materia_prima`;

ALTER TABLE `eficazsystem31`.`_temp_laboratorio_materia_prima` RENAME `laboratorio_materia_prima`;

/* Header line. Object: laboratorio_produtos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_laboratorio_produtos`;

CREATE TABLE `eficazsystem31`.`_temp_laboratorio_produtos` (
	`Laboratorio_Produto_ID` int(10) NOT NULL auto_increment,
	`Produto_Movimentacao_ID` int(10) NOT NULL default '0',
	`Produto_Variacao_ID` int(10) NOT NULL default '0',
	`Origem_Cadastro_ID` int(10) NOT NULL default '0',
	`Status_ID` int(11) NOT NULL default '131',
	`Tecnico_Responsavel_ID` int(11) NOT NULL default '0',
	`Descricao` text default NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Data_Finalizado` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL default '0',
	`Situacao_ID` int(3) NOT NULL default '1',
	PRIMARY KEY  ( `Laboratorio_Produto_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1448
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_laboratorio_produtos`
( `Data_Cadastro`, `Data_Finalizado`, `Descricao`, `Laboratorio_Produto_ID`, `Origem_Cadastro_ID`, `Produto_Movimentacao_ID`, `Produto_Variacao_ID`, `Situacao_ID`, `Status_ID`, `Tecnico_Responsavel_ID`, `Usuario_Cadastro_ID` )
SELECT
`Data_Cadastro`, `Data_Finalizado`, `Descricao`, `Laboratorio_Produto_ID`, `Origem_Cadastro_ID`, `Produto_Movimentacao_ID`, `Produto_Variacao_ID`, `Situacao_ID`, `Status_ID`, `Tecnico_Responsavel_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`laboratorio_produtos`;

DROP TABLE `eficazsystem31`.`laboratorio_produtos`;

ALTER TABLE `eficazsystem31`.`_temp_laboratorio_produtos` RENAME `laboratorio_produtos`;

/* Header line. Object: leiloes_dados. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_leiloes_dados`;

CREATE TABLE `eficazsystem31`.`_temp_leiloes_dados` (
	`Leilao_ID` int(11) NOT NULL auto_increment,
	`Empresa_ID` int(11) NOT NULL,
	`Titulo` varchar(250) NOT NULL,
	`Plano_ID` int(11) NOT NULL,
	`Lance_Aberto` binary(1) NOT NULL default '0',
	`Descricao` text NOT NULL,
	`Data_Leilao` datetime default NULL,
	`Tempo_Duracao_Inicial` time default '00:00:00',
	`Tempo_Renovacao_Lance` time default '00:00:00',
	`Valor_Lance` decimal(15,2) default '0.00',
	`Situacao_ID` int(11) NOT NULL default '134',
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	PRIMARY KEY  ( `Leilao_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_leiloes_dados`
( `Data_Cadastro`, `Data_Leilao`, `Descricao`, `Empresa_ID`, `Lance_Aberto`, `Leilao_ID`, `Plano_ID`, `Situacao_ID`, `Tempo_Duracao_Inicial`, `Tempo_Renovacao_Lance`, `Titulo`, `Usuario_Cadastro_ID`, `Valor_Lance` )
SELECT
`Data_Cadastro`, `Data_Leilao`, `Descricao`, `Empresa_ID`, `Lance_Aberto`, `Leilao_ID`, `Plano_ID`, `Situacao_ID`, `Tempo_Duracao_Inicial`, `Tempo_Renovacao_Lance`, `Titulo`, `Usuario_Cadastro_ID`, `Valor_Lance`
FROM `eficazsystem31`.`leiloes_dados`;

DROP TABLE `eficazsystem31`.`leiloes_dados`;

ALTER TABLE `eficazsystem31`.`_temp_leiloes_dados` RENAME `leiloes_dados`;

/* Header line. Object: leiloes_lotes. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_leiloes_lotes`;

CREATE TABLE `eficazsystem31`.`_temp_leiloes_lotes` (
	`Leilao_Lote_ID` int(11) NOT NULL auto_increment,
	`Leilao_ID` int(11) NOT NULL,
	`Produto_Variacao_ID` int(11) NOT NULL,
	`Quantidade` int(11) NOT NULL default '0',
	`Valor_Inicial` decimal(15,2) NOT NULL default '0.00',
	`Valor_Lance` decimal(15,2) NOT NULL default '0.00',
	`Descricao` text NOT NULL,
	`Data_Inicio` datetime default NULL,
	`Data_Fim` datetime default NULL,
	`Ordem` int(11) NOT NULL default '0',
	`Situacao_ID` int(11) NOT NULL default '134',
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	PRIMARY KEY  ( `Leilao_Lote_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_leiloes_lotes`
( `Data_Cadastro`, `Data_Fim`, `Data_Inicio`, `Descricao`, `Leilao_ID`, `Leilao_Lote_ID`, `Ordem`, `Produto_Variacao_ID`, `Quantidade`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Valor_Inicial`, `Valor_Lance` )
SELECT
`Data_Cadastro`, `Data_Fim`, `Data_Inicio`, `Descricao`, `Leilao_ID`, `Leilao_Lote_ID`, `Ordem`, `Produto_Variacao_ID`, `Quantidade`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Valor_Inicial`, `Valor_Lance`
FROM `eficazsystem31`.`leiloes_lotes`;

DROP TABLE `eficazsystem31`.`leiloes_lotes`;

ALTER TABLE `eficazsystem31`.`_temp_leiloes_lotes` RENAME `leiloes_lotes`;

/* Header line. Object: leiloes_lotes_lances. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_leiloes_lotes_lances`;

CREATE TABLE `eficazsystem31`.`_temp_leiloes_lotes_lances` (
	`Lance_ID` int(11) NOT NULL auto_increment,
	`Leilao_Lote_ID` int(11) NOT NULL,
	`Valor_Lance` decimal(15,2) NOT NULL default '0.00',
	`Usuario_Lance_ID` int(11) NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	PRIMARY KEY  ( `Lance_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_leiloes_lotes_lances`
( `Data_Cadastro`, `Lance_ID`, `Leilao_Lote_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Usuario_Lance_ID`, `Valor_Lance` )
SELECT
`Data_Cadastro`, `Lance_ID`, `Leilao_Lote_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Usuario_Lance_ID`, `Valor_Lance`
FROM `eficazsystem31`.`leiloes_lotes_lances`;

DROP TABLE `eficazsystem31`.`leiloes_lotes_lances`;

ALTER TABLE `eficazsystem31`.`_temp_leiloes_lotes_lances` RENAME `leiloes_lotes_lances`;

/* Header line. Object: modulos_formularios. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_modulos_formularios`;

CREATE TABLE `eficazsystem31`.`_temp_modulos_formularios` (
	`Formulario_ID` int(11) NOT NULL auto_increment,
	`Modulo` varchar(50) NOT NULL,
	`Slug` varchar(150) NOT NULL,
	`Tabela_Estrangeira` varchar(150) NOT NULL,
	`Chave_Estrangeira` varchar(150) NOT NULL,
	`Dados` text NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	PRIMARY KEY  ( `Formulario_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_modulos_formularios`
( `Chave_Estrangeira`, `Dados`, `Data_Cadastro`, `Formulario_ID`, `Modulo`, `Situacao_ID`, `Slug`, `Tabela_Estrangeira`, `Usuario_Cadastro_ID` )
SELECT
`Chave_Estrangeira`, `Dados`, `Data_Cadastro`, `Formulario_ID`, `Modulo`, `Situacao_ID`, `Slug`, `Tabela_Estrangeira`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`modulos_formularios`;

DROP TABLE `eficazsystem31`.`modulos_formularios`;

ALTER TABLE `eficazsystem31`.`_temp_modulos_formularios` RENAME `modulos_formularios`;

/* Header line. Object: oportunidades_workflows. Script date: 08/12/2016 17:47:09. */
CREATE TABLE `eficazsystem31`.`oportunidades_workflows` (
	`Oportunidade_ID` int(10) NOT NULL auto_increment,
	`Tipo_ID` int(10) default NULL,
	`Cadastro_ID` int(10) default NULL,
	`Empresa_ID` int(10) default NULL,
	`Origem_ID` int(10) default NULL,
	`Chave_Estrangeira` int(10) default NULL,
	`Tabela_Estrangeira` varchar(50) default NULL,
	`Orcamento_ID` varchar(50) default NULL,
	`Proposta_ID` varchar(50) default NULL,
	`Titulo` varchar(150) NOT NULL,
	`Descricao` text NOT NULL,
	`Expectativa_Valor` decimal(15,2) NOT NULL default '0.00',
	`Situacao_ID` int(11) NOT NULL default '0',
	`Status_ID` int(11) NOT NULL default '1',
	`Responsavel_ID` int(11) NOT NULL default '0',
	`Data_Previsao` datetime default NULL,
	`Probabilidade_Fechamento` int(3) default NULL,
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Data_Cadastro` timestamp,
	PRIMARY KEY  ( `Oportunidade_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 66
ROW_FORMAT = Compact
;

/* Header line. Object: orcamentos_chamados. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_orcamentos_chamados`;

CREATE TABLE `eficazsystem31`.`_temp_orcamentos_chamados` (
	`Orcamento_Chamado_ID` int(10) NOT NULL auto_increment,
	`Orcamento_ID` int(10) NOT NULL,
	`Chamado_ID` int(10) NOT NULL,
	`Situacao_ID` int(10) default NULL,
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(10) default NULL,
	KEY `idx_orcamentos_chamados_01` ( `Orcamento_Chamado_ID` ),
	KEY `idx_orcamentos_chamados_02` ( `Orcamento_ID` ),
	KEY `idx_orcamentos_chamados_03` ( `Chamado_ID` ),
	KEY `idx_orcamentos_chamados_04` ( `Situacao_ID` ),
	KEY `idx_orcamentos_chamados_05` ( `Orcamento_ID`, `Chamado_ID`, `Situacao_ID` ),
	PRIMARY KEY  ( `Orcamento_Chamado_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_orcamentos_chamados`
( `Chamado_ID`, `Data_Cadastro`, `Orcamento_Chamado_ID`, `Orcamento_ID`, `Situacao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Chamado_ID`, `Data_Cadastro`, `Orcamento_Chamado_ID`, `Orcamento_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`orcamentos_chamados`;

DROP TABLE `eficazsystem31`.`orcamentos_chamados`;

ALTER TABLE `eficazsystem31`.`_temp_orcamentos_chamados` RENAME `orcamentos_chamados`;

/* Header line. Object: orcamentos_propostas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_orcamentos_propostas`;

CREATE TABLE `eficazsystem31`.`_temp_orcamentos_propostas` (
	`Proposta_ID` int(10) NOT NULL auto_increment,
	`Workflow_ID` int(10) NOT NULL,
	`Titulo` varchar(250) default NULL,
	`Tabela_Preco_ID` int(11) default NULL,
	`Forma_Pagamento_ID` int(11) default NULL,
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Status_ID` int(11) default '114',
	`Situacao_ID` int(11) default NULL,
	KEY `idx_orcamentos_workflows_01` ( `Proposta_ID` ),
	KEY `idx_orcamentos_workflows_02` ( `Workflow_ID` ),
	KEY `idx_orcamentos_workflows_03` ( `Titulo` ),
	KEY `idx_orcamentos_workflows_04` ( `Situacao_ID` ),
	KEY `idx_orcamentos_workflows_09` ( `Proposta_ID`, `Workflow_ID`, `Titulo`, `Situacao_ID` ),
	PRIMARY KEY  ( `Proposta_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_orcamentos_propostas`
( `Data_Cadastro`, `Forma_Pagamento_ID`, `Proposta_ID`, `Situacao_ID`, `Status_ID`, `Tabela_Preco_ID`, `Titulo`, `Usuario_Cadastro_ID`, `Workflow_ID` )
SELECT
`Data_Cadastro`, `Forma_Pagamento_ID`, `Proposta_ID`, `Situacao_ID`, `Status_ID`, `Tabela_Preco_ID`, `Titulo`, `Usuario_Cadastro_ID`, `Workflow_ID`
FROM `eficazsystem31`.`orcamentos_propostas`;

DROP TABLE `eficazsystem31`.`orcamentos_propostas`;

ALTER TABLE `eficazsystem31`.`_temp_orcamentos_propostas` RENAME `orcamentos_propostas`;

/* Header line. Object: orcamentos_propostas_envios. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_orcamentos_propostas_envios`;

CREATE TABLE `eficazsystem31`.`_temp_orcamentos_propostas_envios` (
	`Orcamento_Proposta_Envio_ID` int(10) NOT NULL auto_increment,
	`Proposta_ID` int(10) NOT NULL default '0',
	`Tipo_Frete` char(3) NOT NULL,
	`Forma_Envio_ID` int(11) NOT NULL default '0',
	`Endereco_Entrega_ID` int(11) NOT NULL default '0',
	`Valor_Frete` decimal(12,2) NOT NULL default '0.00',
	`Valor_Seguro` decimal(12,2) NOT NULL default '0.00',
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Situacao_ID` int(11) default NULL,
	KEY `idx_orcamentos_propostas_envios_01` ( `Orcamento_Proposta_Envio_ID` ),
	KEY `idx_orcamentos_propostas_envios_02` ( `Proposta_ID` ),
	KEY `idx_orcamentos_propostas_envios_03` ( `Tipo_Frete` ),
	KEY `idx_orcamentos_propostas_envios_04` ( `Forma_Envio_ID` ),
	KEY `idx_orcamentos_propostas_envios_05` ( `Situacao_ID` ),
	KEY `idx_orcamentos_propostas_envios_06` ( `Proposta_ID`, `Situacao_ID` ),
	PRIMARY KEY  ( `Orcamento_Proposta_Envio_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_orcamentos_propostas_envios`
( `Data_Cadastro`, `Endereco_Entrega_ID`, `Forma_Envio_ID`, `Orcamento_Proposta_Envio_ID`, `Proposta_ID`, `Situacao_ID`, `Tipo_Frete`, `Usuario_Cadastro_ID`, `Valor_Frete`, `Valor_Seguro` )
SELECT
`Data_Cadastro`, `Endereco_Entrega_ID`, `Forma_Envio_ID`, `Orcamento_Proposta_Envio_ID`, `Proposta_ID`, `Situacao_ID`, `Tipo_Frete`, `Usuario_Cadastro_ID`, `Valor_Frete`, `Valor_Seguro`
FROM `eficazsystem31`.`orcamentos_propostas_envios`;

DROP TABLE `eficazsystem31`.`orcamentos_propostas_envios`;

ALTER TABLE `eficazsystem31`.`_temp_orcamentos_propostas_envios` RENAME `orcamentos_propostas_envios`;

/* Header line. Object: orcamentos_propostas_eventos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_orcamentos_propostas_eventos`;

CREATE TABLE `eficazsystem31`.`_temp_orcamentos_propostas_eventos` (
	`Proposta_Evento_ID` int(11) NOT NULL auto_increment,
	`Proposta_Produto_ID` int(11) NOT NULL default '0',
	`Proposta_ID` int(11) default NULL,
	`Participantes` int(11) default NULL,
	`Data_Evento` datetime default NULL,
	`Situacao_ID` int(11) default NULL,
	`Data_Cadastro` timestamp,
	PRIMARY KEY  ( `Proposta_Evento_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_orcamentos_propostas_eventos`
( `Data_Cadastro`, `Data_Evento`, `Participantes`, `Proposta_Evento_ID`, `Proposta_ID`, `Proposta_Produto_ID`, `Situacao_ID` )
SELECT
`Data_Cadastro`, `Data_Evento`, `Participantes`, `Proposta_Evento_ID`, `Proposta_ID`, `Proposta_Produto_ID`, `Situacao_ID`
FROM `eficazsystem31`.`orcamentos_propostas_eventos`;

DROP TABLE `eficazsystem31`.`orcamentos_propostas_eventos`;

ALTER TABLE `eficazsystem31`.`_temp_orcamentos_propostas_eventos` RENAME `orcamentos_propostas_eventos`;

/* Header line. Object: orcamentos_propostas_produtos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_orcamentos_propostas_produtos`;

CREATE TABLE `eficazsystem31`.`_temp_orcamentos_propostas_produtos` (
	`Proposta_Produto_ID` int(10) NOT NULL auto_increment,
	`Proposta_ID` int(10) NOT NULL,
	`Produto_Variacao_ID` int(10) NOT NULL,
	`Produto_Categoria_ID` int(10) default NULL,
	`Descricao` varchar(2000) default NULL,
	`Quantidade` decimal(10,2) default NULL,
	`Valor_Custo_Unitario` decimal(10,2) default NULL,
	`Valor_Venda_Unitario` decimal(10,2) default NULL,
	`Cobranca_Cliente` int(11) default NULL,
	`Pagamento_Prestador` int(11) default NULL,
	`Faturamento_Direto` int(11) NOT NULL default '0',
	`Prestador_ID` int(11) default NULL,
	`Cliente_Final_ID` int(11) NOT NULL default '0',
	`Situacao_ID` int(10) default NULL,
	`Status_ID` int(10) default NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(10) default NULL,
	`Usuario_Alteracao_ID` int(10) default NULL,
	KEY `idx_orcamentos_propostas_produtos_01` ( `Proposta_Produto_ID` ),
	KEY `idx_orcamentos_propostas_produtos_02` ( `Proposta_ID` ),
	KEY `idx_orcamentos_propostas_produtos_03` ( `Produto_Variacao_ID` ),
	KEY `idx_orcamentos_propostas_produtos_04` ( `Usuario_Cadastro_ID` ),
	KEY `idx_orcamentos_propostas_produtos_05` ( `Situacao_ID` ),
	KEY `idx_orcamentos_propostas_produtos_06` ( `Proposta_ID`, `Proposta_Produto_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID` ),
	KEY `idx_orcamentos_propostas_produtos_07` ( `Proposta_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID` ),
	KEY `idx_orcamentos_propostas_produtos_08` ( `Proposta_ID`, `Produto_Variacao_ID`, `Situacao_ID` ),
	KEY `idx_orcamentos_propostas_produtos_09` ( `Produto_Categoria_ID`, `Situacao_ID` ),
	KEY `idx_orcamentos_propostas_produtos_10` ( `Proposta_ID`, `Proposta_Produto_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID`, `Produto_Categoria_ID` ),
	PRIMARY KEY  ( `Proposta_Produto_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_orcamentos_propostas_produtos`
( `Cliente_Final_ID`, `Cobranca_Cliente`, `Data_Cadastro`, `Descricao`, `Faturamento_Direto`, `Pagamento_Prestador`, `Prestador_ID`, `Produto_Categoria_ID`, `Produto_Variacao_ID`, `Proposta_ID`, `Proposta_Produto_ID`, `Quantidade`, `Situacao_ID`, `Status_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`, `Valor_Custo_Unitario`, `Valor_Venda_Unitario` )
SELECT
`Cliente_Final_ID`, `Cobranca_Cliente`, `Data_Cadastro`, `Descricao`, `Faturamento_Direto`, `Pagamento_Prestador`, `Prestador_ID`, `Produto_Categoria_ID`, `Produto_Variacao_ID`, `Proposta_ID`, `Proposta_Produto_ID`, `Quantidade`, `Situacao_ID`, `Status_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`, `Valor_Custo_Unitario`, `Valor_Venda_Unitario`
FROM `eficazsystem31`.`orcamentos_propostas_produtos`;

DROP TABLE `eficazsystem31`.`orcamentos_propostas_produtos`;

ALTER TABLE `eficazsystem31`.`_temp_orcamentos_propostas_produtos` RENAME `orcamentos_propostas_produtos`;

/* Header line. Object: orcamentos_propostas_vencimentos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_orcamentos_propostas_vencimentos`;

CREATE TABLE `eficazsystem31`.`_temp_orcamentos_propostas_vencimentos` (
	`Orcamento_Proposta_Vencimento_ID` int(10) NOT NULL auto_increment,
	`Proposta_ID` int(10) NOT NULL default '0',
	`Dias_Vencimento` int(11) NOT NULL default '0',
	`Data_Vencimento` date default NULL,
	`Valor_Vencimento` decimal(12,2) NOT NULL default '0.00',
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Situacao_ID` int(11) default NULL,
	KEY `idx_orcamentos_propostas_vencimentos_01` ( `Orcamento_Proposta_Vencimento_ID` ),
	KEY `idx_orcamentos_propostas_vencimentos_02` ( `Proposta_ID` ),
	KEY `idx_orcamentos_propostas_vencimentos_03` ( `Dias_Vencimento` ),
	KEY `idx_orcamentos_propostas_vencimentos_04` ( `Data_Vencimento` ),
	PRIMARY KEY  ( `Orcamento_Proposta_Vencimento_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_orcamentos_propostas_vencimentos`
( `Data_Cadastro`, `Data_Vencimento`, `Dias_Vencimento`, `Orcamento_Proposta_Vencimento_ID`, `Proposta_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Valor_Vencimento` )
SELECT
`Data_Cadastro`, `Data_Vencimento`, `Dias_Vencimento`, `Orcamento_Proposta_Vencimento_ID`, `Proposta_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Valor_Vencimento`
FROM `eficazsystem31`.`orcamentos_propostas_vencimentos`;

DROP TABLE `eficazsystem31`.`orcamentos_propostas_vencimentos`;

ALTER TABLE `eficazsystem31`.`_temp_orcamentos_propostas_vencimentos` RENAME `orcamentos_propostas_vencimentos`;

/* Header line. Object: produtos_tabelas_precos_faixas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_produtos_tabelas_precos_faixas`;

CREATE TABLE `eficazsystem31`.`_temp_produtos_tabelas_precos_faixas` (
	`Tabelas_Precos_Faixa_ID` int(10) NOT NULL auto_increment,
	`Descricao_Faixa` varchar(250) NOT NULL,
	`Tipo_Faixa` int(10) NOT NULL,
	`Tipo_Cobranca` int(10) NOT NULL,
	`Produto_Variacao_ID` int(10) NOT NULL,
	`Caracteristica_ID` int(10) NOT NULL,
	`Quantidade_Inicial` decimal(15,2) NOT NULL,
	`Quantidade_Final` decimal(15,2) NOT NULL,
	`Valor_Custo` decimal(15,2) NOT NULL,
	`Valor_Venda` decimal(15,2) NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	PRIMARY KEY  ( `Tabelas_Precos_Faixa_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_produtos_tabelas_precos_faixas`
( `Caracteristica_ID`, `Descricao_Faixa`, `Produto_Variacao_ID`, `Quantidade_Final`, `Quantidade_Inicial`, `Situacao_ID`, `Tabelas_Precos_Faixa_ID`, `Tipo_Cobranca`, `Tipo_Faixa`, `Valor_Custo`, `Valor_Venda` )
SELECT
`Caracteristica_ID`, `Descricao_Faixa`, `Produto_Variacao_ID`, `Quantidade_Final`, `Quantidade_Inicial`, `Situacao_ID`, `Tabelas_Precos_Faixa_ID`, `Tipo_Cobranca`, `Tipo_Faixa`, `Valor_Custo`, `Valor_Venda`
FROM `eficazsystem31`.`produtos_tabelas_precos_faixas`;

DROP TABLE `eficazsystem31`.`produtos_tabelas_precos_faixas`;

ALTER TABLE `eficazsystem31`.`_temp_produtos_tabelas_precos_faixas` RENAME `produtos_tabelas_precos_faixas`;

/* Header line. Object: projetos_vinculos_tarefas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_projetos_vinculos_tarefas`;

CREATE TABLE `eficazsystem31`.`_temp_projetos_vinculos_tarefas` (
	`Projeto_Vinculo_Tarefa_ID` int(10) NOT NULL auto_increment,
	`Projeto_Vinculo_ID` int(10) default NULL,
	`Tarefa_ID` int(10) default NULL,
	`Descricao_Inicial` text default NULL,
	`Posicao` int(10) default NULL,
	`Chave_Estrangeira` int(10) default NULL,
	`Tabela_Estrangeira` varchar(100) default NULL,
	`Campo_Estrangeiro` varchar(100) default NULL,
	`Cadastro_Alvo_ID` int(11) default NULL,
	`Tempo_Execucao` int(10) default NULL,
	`Data_Limite` datetime default NULL,
	`Grupo_Responsavel_ID` int(10) default NULL,
	`Usuario_Responsavel_ID` int(10) default NULL,
	`Usuario_Cadastro_ID` int(10) default NULL,
	`Situacao_ID` int(10) default '1',
	`Data_Cadastro` timestamp NOT NULL,
	PRIMARY KEY  ( `Projeto_Vinculo_Tarefa_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1841
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_projetos_vinculos_tarefas`
( `Data_Cadastro`, `Data_Limite`, `Descricao_Inicial`, `Grupo_Responsavel_ID`, `Posicao`, `Projeto_Vinculo_ID`, `Projeto_Vinculo_Tarefa_ID`, `Situacao_ID`, `Tarefa_ID`, `Tempo_Execucao`, `Usuario_Cadastro_ID`, `Usuario_Responsavel_ID` )
SELECT
`Data_Cadastro`, `Data_Limite`, `Descricao_Inicial`, `Grupo_Responsavel_ID`, `Posicao`, `Projeto_Vinculo_ID`, `Projeto_Vinculo_Tarefa_ID`, `Situacao_ID`, `Tarefa_ID`, `Tempo_Execucao`, `Usuario_Cadastro_ID`, `Usuario_Responsavel_ID`
FROM `eficazsystem31`.`projetos_vinculos_tarefas`;

DROP TABLE `eficazsystem31`.`projetos_vinculos_tarefas`;

ALTER TABLE `eficazsystem31`.`_temp_projetos_vinculos_tarefas` RENAME `projetos_vinculos_tarefas`;

/* Header line. Object: sites_dados. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_sites_dados`;

CREATE TABLE `eficazsystem31`.`_temp_sites_dados` (
	`Site_ID` int(11) NOT NULL auto_increment,
	`Empresa_ID` int(11) NOT NULL,
	`URL` varchar(500) NOT NULL,
	`Dados` text NOT NULL,
	`Situacao_ID` int(11) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL,
	PRIMARY KEY  ( `Site_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_sites_dados`
( `Dados`, `Data_Cadastro`, `Empresa_ID`, `Site_ID`, `Situacao_ID`, `URL`, `Usuario_Cadastro_ID` )
SELECT
`Dados`, `Data_Cadastro`, `Empresa_ID`, `Site_ID`, `Situacao_ID`, `URL`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`sites_dados`;

DROP TABLE `eficazsystem31`.`sites_dados`;

ALTER TABLE `eficazsystem31`.`_temp_sites_dados` RENAME `sites_dados`;

/* Header line. Object: tarefas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tarefas`;

CREATE TABLE `eficazsystem31`.`_temp_tarefas` (
	`Tarefa_ID` int(10) NOT NULL auto_increment,
	`Titulo` varchar(250) NOT NULL,
	`Descricao` text default NULL,
	`Dados` text default NULL,
	`Tempo_Execucao` int(11) NOT NULL,
	`Data_Cadastro` timestamp NOT NULL,
	`Usuario_Cadastro_ID` int(11) default NULL,
	`Situacao_ID` int(11) default NULL,
	KEY `idx_chamados_workflows_01` ( `Tarefa_ID` ),
	KEY `idx_chamados_workflows_02` ( `Titulo` ),
	KEY `idx_chamados_workflows_03` ( `Situacao_ID` ),
	PRIMARY KEY  ( `Tarefa_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 14
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tarefas`
( `Dados`, `Data_Cadastro`, `Descricao`, `Situacao_ID`, `Tarefa_ID`, `Tempo_Execucao`, `Titulo`, `Usuario_Cadastro_ID` )
SELECT
`Dados`, `Data_Cadastro`, `Descricao`, `Situacao_ID`, `Tarefa_ID`, `Tempo_Execucao`, `Titulo`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`tarefas`;

DROP TABLE `eficazsystem31`.`tarefas`;

ALTER TABLE `eficazsystem31`.`_temp_tarefas` RENAME `tarefas`;

/* Header line. Object: tele_campanhas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tele_campanhas`;

CREATE TABLE `eficazsystem31`.`_temp_tele_campanhas` (
	`Campanha_ID` int(10) NOT NULL auto_increment,
	`Operacao_ID` int(10) NOT NULL default '0',
	`Tipo_Campanha_ID` int(11) NOT NULL default '0',
	`Lista_ID` int(11) NOT NULL default '0',
	`Formulario_ID` int(11) NOT NULL default '0',
	`Campanha_Conta_ID` int(11) NOT NULL default '0',
	`Nome` varchar(150) default NULL,
	`Dados_Campanha` text NOT NULL,
	`Situacao_ID` int(10) NOT NULL default '160',
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) default NULL,
	PRIMARY KEY  ( `Campanha_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tele_campanhas`
( `Campanha_Conta_ID`, `Campanha_ID`, `Dados_Campanha`, `Data_Cadastro`, `Formulario_ID`, `Lista_ID`, `Nome`, `Operacao_ID`, `Situacao_ID`, `Tipo_Campanha_ID`, `Usuario_Cadastro_ID` )
SELECT
`Campanha_Conta_ID`, `Campanha_ID`, `Dados_Campanha`, `Data_Cadastro`, `Formulario_ID`, `Lista_ID`, `Nome`, `Operacao_ID`, `Situacao_ID`, `Tipo_Campanha_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`tele_campanhas`;

DROP TABLE `eficazsystem31`.`tele_campanhas`;

ALTER TABLE `eficazsystem31`.`_temp_tele_campanhas` RENAME `tele_campanhas`;

/* Header line. Object: tele_campanhas_listas. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tele_campanhas_listas`;

CREATE TABLE `eficazsystem31`.`_temp_tele_campanhas_listas` (
	`Campanha_Lista_ID` int(10) NOT NULL auto_increment,
	`Campanha_ID` int(10) NOT NULL default '0',
	`Lista_ID` int(10) NOT NULL default '0',
	`Situacao_ID` int(10) NOT NULL default '0',
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) default NULL,
	PRIMARY KEY  ( `Campanha_Lista_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tele_campanhas_listas`
( `Campanha_ID`, `Campanha_Lista_ID`, `Data_Cadastro`, `Lista_ID`, `Situacao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Campanha_ID`, `Campanha_Lista_ID`, `Data_Cadastro`, `Lista_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`tele_campanhas_listas`;

DROP TABLE `eficazsystem31`.`tele_campanhas_listas`;

ALTER TABLE `eficazsystem31`.`_temp_tele_campanhas_listas` RENAME `tele_campanhas_listas`;

/* Header line. Object: tele_campanhas_operadores. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tele_campanhas_operadores`;

CREATE TABLE `eficazsystem31`.`_temp_tele_campanhas_operadores` (
	`Campanha_Usuario_ID` int(10) NOT NULL auto_increment,
	`Campanha_ID` int(10) NOT NULL default '0',
	`Operador_ID` int(10) NOT NULL default '0',
	`Situacao_ID` int(10) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	`Data_Alteracao` datetime default NULL,
	`Usuario_Cadastro_ID` int(11) NOT NULL default '0',
	`Usuario_Alteracao_ID` int(11) default NULL,
	PRIMARY KEY  ( `Campanha_Usuario_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tele_campanhas_operadores`
( `Campanha_ID`, `Campanha_Usuario_ID`, `Data_Alteracao`, `Data_Cadastro`, `Operador_ID`, `Situacao_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Campanha_ID`, `Campanha_Usuario_ID`, `Data_Alteracao`, `Data_Cadastro`, `Operador_ID`, `Situacao_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`tele_campanhas_operadores`;

DROP TABLE `eficazsystem31`.`tele_campanhas_operadores`;

ALTER TABLE `eficazsystem31`.`_temp_tele_campanhas_operadores` RENAME `tele_campanhas_operadores`;

/* Header line. Object: tele_follows. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tele_follows`;

CREATE TABLE `eficazsystem31`.`_temp_tele_follows` (
	`Follow_ID` int(10) NOT NULL auto_increment,
	`Workflow_ID` int(10) NOT NULL,
	`Descricao` mediumtext default NULL,
	`Situacao_ID` int(10) default NULL,
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(10) default NULL,
	KEY `idx_tele_follows_01` ( `Workflow_ID` ),
	KEY `idx_tele_follows_02` ( `Follow_ID` ),
	KEY `idx_tele_follows_03` ( `Situacao_ID` ),
	KEY `idx_tele_follows_04` ( `Workflow_ID`, `Situacao_ID` ),
	PRIMARY KEY  ( `Follow_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tele_follows`
( `Data_Cadastro`, `Descricao`, `Follow_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Workflow_ID` )
SELECT
`Data_Cadastro`, `Descricao`, `Follow_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Workflow_ID`
FROM `eficazsystem31`.`tele_follows`;

DROP TABLE `eficazsystem31`.`tele_follows`;

ALTER TABLE `eficazsystem31`.`_temp_tele_follows` RENAME `tele_follows`;

/* Header line. Object: tele_follows_cancelados. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tele_follows_cancelados`;

CREATE TABLE `eficazsystem31`.`_temp_tele_follows_cancelados` (
	`Follow_Cancelado_ID` int(10) NOT NULL auto_increment,
	`Follow_ID` int(10) NOT NULL,
	`Workflow_ID` int(10) NOT NULL,
	`Motivo_ID` int(10) default NULL,
	`Descricao` varchar(500) default NULL,
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(10) default NULL,
	KEY `idx_tele_follows_cancelados_01` ( `Follow_Cancelado_ID` ),
	KEY `idx_tele_follows_cancelados_02` ( `Follow_ID` ),
	KEY `idx_tele_follows_cancelados_03` ( `Workflow_ID` ),
	KEY `idx_tele_follows_cancelados_04` ( `Motivo_ID` ),
	KEY `idx_tele_follows_cancelados_05` ( `Usuario_Cadastro_ID` ),
	PRIMARY KEY  ( `Follow_Cancelado_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tele_follows_cancelados`
( `Data_Cadastro`, `Descricao`, `Follow_Cancelado_ID`, `Follow_ID`, `Motivo_ID`, `Usuario_Cadastro_ID`, `Workflow_ID` )
SELECT
`Data_Cadastro`, `Descricao`, `Follow_Cancelado_ID`, `Follow_ID`, `Motivo_ID`, `Usuario_Cadastro_ID`, `Workflow_ID`
FROM `eficazsystem31`.`tele_follows_cancelados`;

DROP TABLE `eficazsystem31`.`tele_follows_cancelados`;

ALTER TABLE `eficazsystem31`.`_temp_tele_follows_cancelados` RENAME `tele_follows_cancelados`;

/* Header line. Object: tele_operacoes. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tele_operacoes`;

CREATE TABLE `eficazsystem31`.`_temp_tele_operacoes` (
	`Operacao_ID` int(10) NOT NULL auto_increment,
	`Empresa_ID` int(10) NOT NULL default '0',
	`Nome` varchar(150) NOT NULL,
	`Fluxo_Operacao` char(2) NOT NULL default 'A',
	`Tipo_Operacao_ID` int(11) NOT NULL default '0',
	`Situacao_ID` int(10) NOT NULL default '1',
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) default NULL,
	PRIMARY KEY  ( `Operacao_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tele_operacoes`
( `Data_Cadastro`, `Empresa_ID`, `Fluxo_Operacao`, `Nome`, `Operacao_ID`, `Situacao_ID`, `Tipo_Operacao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Data_Cadastro`, `Empresa_ID`, `Fluxo_Operacao`, `Nome`, `Operacao_ID`, `Situacao_ID`, `Tipo_Operacao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`tele_operacoes`;

DROP TABLE `eficazsystem31`.`tele_operacoes`;

ALTER TABLE `eficazsystem31`.`_temp_tele_operacoes` RENAME `tele_operacoes`;

/* Header line. Object: tele_workflows. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_tele_workflows`;

CREATE TABLE `eficazsystem31`.`_temp_tele_workflows` (
	`Workflow_ID` int(10) NOT NULL auto_increment,
	`Campanha_ID` int(10) NOT NULL default '0',
	`Cadastro_ID` int(10) NOT NULL default '0',
	`Codigo` varchar(50) NOT NULL,
	`Chave` int(11) NOT NULL default '0',
	`Responsavel_ID` int(11) NOT NULL default '0',
	`Resumo` tinytext default NULL,
	`Situacao_ID` int(11) NOT NULL default '0',
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) NOT NULL default '0',
	KEY `idx_tele_workflows_01` ( `Workflow_ID` ),
	KEY `idx_tele_workflows_03` ( `Cadastro_ID` ),
	KEY `idx_tele_workflows_04` ( `Responsavel_ID` ),
	KEY `idx_tele_workflows_05` ( `Situacao_ID` ),
	PRIMARY KEY  ( `Workflow_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_tele_workflows`
( `Cadastro_ID`, `Campanha_ID`, `Chave`, `Codigo`, `Data_Cadastro`, `Responsavel_ID`, `Resumo`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Workflow_ID` )
SELECT
`Cadastro_ID`, `Campanha_ID`, `Chave`, `Codigo`, `Data_Cadastro`, `Responsavel_ID`, `Resumo`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Workflow_ID`
FROM `eficazsystem31`.`tele_workflows`;

DROP TABLE `eficazsystem31`.`tele_workflows`;

ALTER TABLE `eficazsystem31`.`_temp_tele_workflows` RENAME `tele_workflows`;

/* Header line. Object: telemarketing_follows. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_telemarketing_follows`;

CREATE TABLE `eficazsystem31`.`_temp_telemarketing_follows` (
	`Follow_ID` int(10) NOT NULL auto_increment,
	`Workflow_ID` int(10) NOT NULL,
	`Descricao` mediumtext default NULL,
	`Dados` mediumtext default NULL,
	`Situacao_ID` int(10) default NULL,
	`Motivo_ID` int(10) default NULL,
	`Data_Cadastro` timestamp,
	`Responsabilidade_ID` int(10) default NULL,
	`Usuario_Cadastro_ID` int(10) default NULL,
	PRIMARY KEY  ( `Follow_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_telemarketing_follows`
( `Dados`, `Data_Cadastro`, `Descricao`, `Follow_ID`, `Motivo_ID`, `Responsabilidade_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Workflow_ID` )
SELECT
`Dados`, `Data_Cadastro`, `Descricao`, `Follow_ID`, `Motivo_ID`, `Responsabilidade_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Workflow_ID`
FROM `eficazsystem31`.`telemarketing_follows`;

DROP TABLE `eficazsystem31`.`telemarketing_follows`;

ALTER TABLE `eficazsystem31`.`_temp_telemarketing_follows` RENAME `telemarketing_follows`;

/* Header line. Object: telemarketing_workflows. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_telemarketing_workflows`;

CREATE TABLE `eficazsystem31`.`_temp_telemarketing_workflows` (
	`Workflow_ID` int(10) NOT NULL auto_increment,
	`Tipo_Workflow_ID` int(10) default NULL,
	`Codigo` varchar(50) default NULL,
	`Solicitante_ID` int(10) default NULL,
	`Fornecedor_ID` int(10) default NULL,
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(11) default NULL,
	PRIMARY KEY  ( `Workflow_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_telemarketing_workflows`
( `Codigo`, `Data_Cadastro`, `Fornecedor_ID`, `Solicitante_ID`, `Tipo_Workflow_ID`, `Usuario_Cadastro_ID`, `Workflow_ID` )
SELECT
`Codigo`, `Data_Cadastro`, `Fornecedor_ID`, `Solicitante_ID`, `Tipo_Workflow_ID`, `Usuario_Cadastro_ID`, `Workflow_ID`
FROM `eficazsystem31`.`telemarketing_workflows`;

DROP TABLE `eficazsystem31`.`telemarketing_workflows`;

ALTER TABLE `eficazsystem31`.`_temp_telemarketing_workflows` RENAME `telemarketing_workflows`;

/* Header line. Object: telemarketing_workflows_produtos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_telemarketing_workflows_produtos`;

CREATE TABLE `eficazsystem31`.`_temp_telemarketing_workflows_produtos` (
	`Workflow_Produto_ID` int(10) NOT NULL auto_increment,
	`Workflow_ID` int(10) NOT NULL,
	`Produto_Variacao_ID` int(10) NOT NULL,
	`Quantidade` int(10) default '0',
	`Valor_Custo_Unitario` int(10) default '0',
	`Valor_Venda_Unitario` int(10) default '0',
	`Situacao_ID` int(10) NOT NULL default '1',
	`Usuario_Cadastro_ID` int(10) default NULL,
	`Usuario_Alteracao_ID` int(10) default NULL,
	`Auxiliar` int(10) default NULL,
	`Data_Cadastro` timestamp NOT NULL,
	PRIMARY KEY  ( `Workflow_Produto_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_telemarketing_workflows_produtos`
( `Auxiliar`, `Data_Cadastro`, `Produto_Variacao_ID`, `Quantidade`, `Situacao_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`, `Valor_Custo_Unitario`, `Valor_Venda_Unitario`, `Workflow_ID`, `Workflow_Produto_ID` )
SELECT
`Auxiliar`, `Data_Cadastro`, `Produto_Variacao_ID`, `Quantidade`, `Situacao_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`, `Valor_Custo_Unitario`, `Valor_Venda_Unitario`, `Workflow_ID`, `Workflow_Produto_ID`
FROM `eficazsystem31`.`telemarketing_workflows_produtos`;

DROP TABLE `eficazsystem31`.`telemarketing_workflows_produtos`;

ALTER TABLE `eficazsystem31`.`_temp_telemarketing_workflows_produtos` RENAME `telemarketing_workflows_produtos`;

/* Header line. Object: turmas_dados. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_turmas_dados`;

CREATE TABLE `eficazsystem31`.`_temp_turmas_dados` (
	`Turma_ID` int(10) NOT NULL auto_increment,
	`Cadastro_ID` int(11) NOT NULL,
	`Codigo` varchar(30) NOT NULL,
	`Nome_Turma` varchar(250) NOT NULL,
	`Instituicao_ID` int(10) NOT NULL,
	`Campus_ID` int(10) default NULL,
	`Curso_ID` int(10) default NULL,
	`Periodo_ID` int(10) default NULL,
	`Turno_ID` int(10) default NULL,
	`Quantidade` int(10) default NULL,
	`Responsavel_ID` int(10) default NULL,
	`Usuario_ID` int(10) default NULL,
	`Situacao_ID` int(10) NOT NULL default '1',
	`Data_Cadastro` timestamp NOT NULL,
	KEY `Cadastro_ID` ( `Cadastro_ID` ),
	KEY `Cadastro_ID_Instituicao_ID_Campus_ID_Curso_ID_Periodo_ID` ( `Cadastro_ID`, `Instituicao_ID`, `Campus_ID`, `Curso_ID`, `Periodo_ID` ),
	KEY `idx_turmas_01` ( `Instituicao_ID`, `Campus_ID`, `Curso_ID`, `Periodo_ID` ),
	PRIMARY KEY  ( `Turma_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_turmas_dados`
( `Cadastro_ID`, `Campus_ID`, `Codigo`, `Curso_ID`, `Data_Cadastro`, `Instituicao_ID`, `Nome_Turma`, `Periodo_ID`, `Quantidade`, `Responsavel_ID`, `Situacao_ID`, `Turma_ID`, `Turno_ID`, `Usuario_ID` )
SELECT
`Cadastro_ID`, `Campus_ID`, `Codigo`, `Curso_ID`, `Data_Cadastro`, `Instituicao_ID`, `Nome_Turma`, `Periodo_ID`, `Quantidade`, `Responsavel_ID`, `Situacao_ID`, `Turma_ID`, `Turno_ID`, `Usuario_ID`
FROM `eficazsystem31`.`turmas_dados`;

DROP TABLE `eficazsystem31`.`turmas_dados`;

ALTER TABLE `eficazsystem31`.`_temp_turmas_dados` RENAME `turmas_dados`;

/* Header line. Object: turmas_dados_alunos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_turmas_dados_alunos`;

CREATE TABLE `eficazsystem31`.`_temp_turmas_dados_alunos` (
	`Turma_Aluno_ID` int(10) NOT NULL auto_increment,
	`Turma_ID` int(10) NOT NULL,
	`Cadastro_ID` int(10) NOT NULL,
	`Situacao_ID` int(10) NOT NULL,
	`Data_Cadastro` timestamp,
	`Usuario_ID` int(10) NOT NULL,
	`Data_Alteracao` datetime default NULL,
	`Usuario_Alteracao_ID` int(10) default NULL,
	KEY `idx_turmas_alunos_01` ( `Turma_ID` ),
	PRIMARY KEY  ( `Turma_Aluno_ID` )
)
ENGINE = InnoDB
CHARACTER SET = utf8
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_turmas_dados_alunos`
( `Cadastro_ID`, `Data_Alteracao`, `Data_Cadastro`, `Situacao_ID`, `Turma_Aluno_ID`, `Turma_ID`, `Usuario_Alteracao_ID`, `Usuario_ID` )
SELECT
`Cadastro_ID`, `Data_Alteracao`, `Data_Cadastro`, `Situacao_ID`, `Turma_Aluno_ID`, `Turma_ID`, `Usuario_Alteracao_ID`, `Usuario_ID`
FROM `eficazsystem31`.`turmas_dados_alunos`;

DROP TABLE `eficazsystem31`.`turmas_dados_alunos`;

ALTER TABLE `eficazsystem31`.`_temp_turmas_dados_alunos` RENAME `turmas_dados_alunos`;

/* Header line. Object: turmas_eventos. Script date: 08/12/2016 17:47:09. */
DROP TABLE IF EXISTS `eficazsystem31`.`_temp_turmas_eventos`;

CREATE TABLE `eficazsystem31`.`_temp_turmas_eventos` (
	`Evento_ID` int(10) NOT NULL auto_increment,
	`Turma_ID` int(10) NOT NULL,
	`Tipo_Evento_ID` int(10) NOT NULL,
	`Local_Evento_ID` int(10) NOT NULL,
	`Descricao` varchar(250) NOT NULL,
	`Participantes` int(10) NOT NULL,
	`Data_Evento` datetime NOT NULL,
	`Situacao_ID` int(10) NOT NULL,
	`Data_Cadastro` timestamp,
	`Usuario_Cadastro_ID` int(10) default NULL,
	`Data_Alteracao` datetime default NULL,
	`Usuario_Alteracao_ID` int(10) default NULL,
	KEY `idx_turmas_eventos_01` ( `Evento_ID`, `Turma_ID`, `Tipo_Evento_ID`, `Situacao_ID` ),
	PRIMARY KEY  ( `Evento_ID` )
)
ENGINE = InnoDB
CHARACTER SET = latin1
AUTO_INCREMENT = 1
ROW_FORMAT = Compact
;

INSERT INTO `eficazsystem31`.`_temp_turmas_eventos`
( `Data_Alteracao`, `Data_Cadastro`, `Data_Evento`, `Descricao`, `Evento_ID`, `Local_Evento_ID`, `Participantes`, `Situacao_ID`, `Tipo_Evento_ID`, `Turma_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID` )
SELECT
`Data_Alteracao`, `Data_Cadastro`, `Data_Evento`, `Descricao`, `Evento_ID`, `Local_Evento_ID`, `Participantes`, `Situacao_ID`, `Tipo_Evento_ID`, `Turma_ID`, `Usuario_Alteracao_ID`, `Usuario_Cadastro_ID`
FROM `eficazsystem31`.`turmas_eventos`;

DROP TABLE `eficazsystem31`.`turmas_eventos`;

ALTER TABLE `eficazsystem31`.`_temp_turmas_eventos` RENAME `turmas_eventos`;

	