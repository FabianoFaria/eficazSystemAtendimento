CREATE TABLE IF NOT EXISTS `chamados_follows` (
  `Follow_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Workflow_ID` int(10) NOT NULL,
  `Descricao` mediumtext,
  `Dados` mediumtext,
  `Situacao_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Responsabilidade_ID` int(10) DEFAULT NULL,
  `Responsabilidade_Situacao_ID` int(10) DEFAULT '71',
  `Data_Hora_Retorno` datetime DEFAULT NULL,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Follow_ID`),
  KEY `idx_chamados_follows_01` (`Workflow_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `chamados_workflows` (
  `Workflow_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Cadastro_ID` int(10) DEFAULT NULL COMMENT 'Cadastro da empresa para qual esta se abrindo o workflow',
  `Solicitante_ID` int(10) DEFAULT NULL,
  `Prestador_ID` int(10) DEFAULT NULL,
  `Tipo_Workflow_ID` int(10) DEFAULT NULL,
  `Prioridade_ID` int(11) DEFAULT NULL,
  `Codigo` varchar(250) DEFAULT NULL,
  `Titulo` tinytext,
  `Data_Abertura` datetime DEFAULT NULL,
  `Data_Finalizado` datetime DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`Workflow_ID`),
  KEY `idx_chamados_workflows_02` (`Solicitante_ID`),
  KEY `idx_chamados_workflows_03` (`Prestador_ID`),
  KEY `idx_chamados_workflows_04` (`Tipo_Workflow_ID`),
  KEY `idx_chamados_workflows_05` (`Prioridade_ID`),
  KEY `idx_chamados_workflows_06` (`Usuario_Cadastro_ID`),
  KEY `idx_chamados_workflows_07` (`Solicitante_ID`,`Prestador_ID`,`Tipo_Workflow_ID`,`Prioridade_ID`),
  KEY `idx_chamados_workflows_01` (`Workflow_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `chamados_workflows_produtos` (
  `Workflow_Produto_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Workflow_ID` int(10) NOT NULL,
  `Produto_Variacao_ID` int(10) NOT NULL,
  `Quantidade` decimal(10,2) DEFAULT NULL,
  `Valor_Custo_Unitario` decimal(10,2) DEFAULT NULL,
  `Valor_Venda_Unitario` decimal(10,2) DEFAULT NULL,
  `Cobranca_Cliente` int(11) DEFAULT NULL,
  `Pagamento_Prestador` int(11) DEFAULT NULL,
  `Situacao_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Workflow_Produto_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (18, 'Situações Processos', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (19, 'Tipo Processo', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (21, 'Prioridades', 1);

INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (32, 18, 'Aberto', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (33, 18, 'Cancelado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (34, 18, 'Finalizado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (71, 38, 'Aberto', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (72, 38, 'Finalizado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (73, 18, 'Agendado', NULL, 3);



select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Controle de Processos','chamados','Módulo para controle de processos',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Cadastrar Processo','Cadastrar Processo','chamados-cadastro-chamado','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Processos','Localizar Processos Cadastrados','chamados-localizar-chamado','1');

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Relatórios','Gerar Relatórios dos Processos','relatorio-chamados','2');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Geral','Geral','chamados-relatorio-dinamico','1', @subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Situação Geral','Relatório sintético com a situação geral de todos os processos cadastrados','chamados-relatorio-geral','2', @subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Regional','Relatório Processos por Estado','chamados-relatorio-regional','3', @subModuloID);

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Configurações Processos','Configurações Processos','chamados-configuracoes','3');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Gerenciar Situações','Gerenciar Situações de Chamado','chamados-situacoes','1','18', @subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Tipo Chamado','Gerenciar Tipo de Chamado','chamados-tipos','2','19', @subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Gerenciar Prioridades','Gerenciar Prioridades','chamados-prioridades','3','21', @subModuloID);

select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
INSERT IGNORE INTO `tipo_grupo` (Tipo_Grupo_ID,Descr_Tipo_Grupo)values(40,"Tipo de Tarefa");
INSERT IGNORE INTO `tipo_grupo` (Tipo_Grupo_ID,Descr_Tipo_Grupo)values(41,"Situacao da Tarefa");
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID,Descr_Tipo,Tipo_Auxiliar)values(83,41,"Aberta", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID,Descr_Tipo,Tipo_Auxiliar)values(84,41,"Finalizada", '');

select Modulo_Pagina_ID from modulos_paginas where Slug = 'chamados-configuracoes' into @subModuloID;
INSERT INTO `modulos_paginas` (Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Tipo Tarefa','Gerenciar Tipo de Tarefa','chamados-tipo-tarefa',4,'40',@subModuloID);
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Faturamento','Faturamento','chamados-relatorio-faturamento','1', @subModuloID);

CREATE TABLE IF NOT EXISTS `chamados_workflows_tarefas` (
	`Workflow_Tarefa_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Workflow_ID` INT(10) NOT NULL,
	`Tipo_Tarefa_ID` INT(10) NOT NULL,
	`Descricao` text NOT NULL,
	`Grupo_Responsavel_ID` int(10) NULL DEFAULT NULL,
	`Responsavel_ID` int(10) NULL DEFAULT NULL,
	`Data_Retorno` datetime NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Workflow_Tarefa_ID`),
	INDEX `Workflow_ID` (`Workflow_ID`)
);

CREATE TABLE IF NOT EXISTS `chamados_workflows_tarefas_follows` (
	`Workflow_Tarefa_Follow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Workflow_Tarefa_ID` INT(10) NOT NULL,
	`Descricao` text NOT NULL,
	`Hora_Inicio` DATETIME NOT NULL,
	`Hora_Fim` DATETIME NOT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Workflow_Tarefa_Follow_ID`),
	INDEX `Workflow_ID` (`Workflow_Tarefa_ID`)
);


select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Tarefas','Relatório Dinâmico de Tarefas','chamados-relatorio-tarefas','5', @subModuloID);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Calendario de Tarefas','Calendario de Tarefas','chamados-relatorio-calendario-tarefas','5', @subModuloID);




ALTER TABLE `chamados_workflows`
	ADD COLUMN `Data_Limite` DATETIME NULL DEFAULT NULL AFTER `Data_Cadastro`,
	ADD COLUMN `Responsavel_ID` INT(11) NULL DEFAULT NULL AFTER `Usuario_Cadastro_ID`,
	ADD COLUMN `Grupo_Responsavel_ID` INT(11) NULL DEFAULT NULL AFTER `Responsavel_ID`;





ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_01` (Workflow_Produto_ID);
ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_02` (Workflow_ID);
ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_03` (Produto_Variacao_ID);
ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_04` (Usuario_Cadastro_ID);
ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_05` (Situacao_ID);
ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_06` (Workflow_ID, Workflow_Produto_ID, Produto_Variacao_ID, Usuario_Cadastro_ID, Situacao_ID);
ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_07` (Workflow_ID, Produto_Variacao_ID, Usuario_Cadastro_ID, Situacao_ID);
ALTER TABLE chamados_workflows_produtos ADD INDEX `idx_chamados_workflows_produtos_08` (Workflow_ID, Produto_Variacao_ID, Situacao_ID);


ALTER TABLE chamados_follows ADD INDEX `idx_chamados_follows_02` (Follow_ID);
ALTER TABLE chamados_follows ADD INDEX `idx_chamados_follows_03` (Situacao_ID);
ALTER TABLE chamados_follows ADD INDEX `idx_chamados_follows_04` (Workflow_ID, Situacao_ID);


ALTER TABLE `orcamentos_propostas_produtos`
	CHANGE COLUMN `Categoria_ID` `Produto_Categoria_ID` INT(10) NULL DEFAULT NULL AFTER `Produto_Variacao_ID`;


CREATE TABLE `orcamentos_propostas_eventos` (
	`Proposta_Evento_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Proposta_Produto_ID` INT(11) NOT NULL DEFAULT '0',
	`Proposta_ID` INT(11) NULL DEFAULT NULL,
	`Participantes` INT(11) NULL DEFAULT NULL,
	`Data_Evento` DATETIME NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`Proposta_Evento_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

ALTER TABLE `orcamentos_propostas_produtos`
	ADD COLUMN `Cliente_Final_ID` INT(11) NOT NULL DEFAULT '0' AFTER `Prestador_ID`;
