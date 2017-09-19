
CREATE TABLE `tele_workflows` (
	`Workflow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Campanha_ID` INT(10) NOT NULL DEFAULT '0',
	`Cadastro_ID` INT(10) NOT NULL DEFAULT '0',
	`Codigo` VARCHAR(50) NOT NULL DEFAULT '',
	`Responsavel_ID` INT(11) NOT NULL DEFAULT '0',
	`Resumo` TINYTEXT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '0',
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`Workflow_ID`),
	INDEX `idx_tele_workflows_01` (`Workflow_ID`),
	INDEX `idx_tele_workflows_03` (`Cadastro_ID`),
	INDEX `idx_tele_workflows_04` (`Responsavel_ID`),
	INDEX `idx_tele_workflows_05` (`Situacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM;


CREATE TABLE `tele_follows` (
	`Follow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Workflow_ID` INT(10) NOT NULL,
	`Descricao` MEDIUMTEXT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Follow_ID`),
	INDEX `idx_tele_follows_01` (`Workflow_ID`),
	INDEX `idx_tele_follows_02` (`Follow_ID`),
	INDEX `idx_tele_follows_03` (`Situacao_ID`),
	INDEX `idx_tele_follows_04` (`Workflow_ID`, `Situacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;



select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Telemarketing','tele','Módulo para controle de operações do telemarketing',@posicao,'1.5');
select Modulo_ID from modulos where Slug = 'tele' into @moduloID;

insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao) values (@moduloID, 'Operação','Operação','tele-operacao','1');
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao) values (@moduloID, 'Campanhas','Localizar Campanhas','tele-campanhas','5');
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu) values (@moduloID,'Gerenciar Campanha','Gerenciar Campanhas','tele-campanhas-gerenciar','0','1');

insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao) values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo de Telemarketing','tele-gerenciar-modulo',10);
select Modulo_Pagina_ID from modulos_paginas where Slug = 'tele-gerenciar-modulo' into @subModuloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID, 'Operações','Listar Operacoes','tele-operacoes','1',@subModuloID);
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID, Oculta_Menu) values(@moduloID,'Gerenciar Operação','Gerenciar Operação','tele-operacoes-gerenciar','0',@subModuloID,'1');

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (67, 'Tipo Campanha', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (154, 67, 'Cobran&ccedil;a', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (155, 67, 'Suporte', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (156, 67, 'SAC', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (157, 67, 'Prospecção', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (158, 67, 'Pesquisa', '', 1);


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (68, 'Situações Campanha', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (160, 68, 'Inativa', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (161, 68, 'Ativa', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (162, 68, 'Finalizada', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (163, 68, 'Cancelada', '', 1);


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (69, 'Situações Workflows Telemarketing', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (164, 69, 'Em aberto', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (165, 69, 'Em andamento', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (166, 69, 'Cancelado', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (167, 69, 'Finalizado', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (168, 69, 'Re-aberto', '', 1);

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (70, 'Motivos Cancelamento Telemarketing', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (169, 70, 'Outros', '', 1);
