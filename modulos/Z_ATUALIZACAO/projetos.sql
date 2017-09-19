CREATE TABLE `projetos` (
	`Projeto_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Titulo` VARCHAR(250) NOT NULL,
	`Descricao` TEXT NULL,
	`Tabela_Estrangeira` VARCHAR(50) NULL DEFAULT NULL,
	`Projeto_Padrao` INT(11) NULL DEFAULT '0',
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Projeto_ID`),
	INDEX `idx_chamados_workflows_01` (`Projeto_ID`),
	INDEX `idx_chamados_workflows_02` (`Titulo`),
	INDEX `idx_chamados_workflows_03` (`Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tarefas` (
	`Tarefa_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Titulo` VARCHAR(250) NOT NULL,
	`Descricao` TEXT NULL,
	`Tempo_Execucao` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Tarefa_ID`),
	INDEX `idx_chamados_workflows_01` (`Tarefa_ID`),
	INDEX `idx_chamados_workflows_02` (`Titulo`),
	INDEX `idx_chamados_workflows_03` (`Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `projetos_tarefas` (
	`Projeto_Tarefa_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Projeto_ID` INT(10) NOT NULL,
	`Tarefa_ID` INT(10) NOT NULL,
	`Posicao` FLOAT NOT NULL,
	`Tempo_Execucao` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Grupo_Responsavel_ID` INT(11) NULL DEFAULT NULL,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	PRIMARY KEY (`Projeto_Tarefa_ID`),
	INDEX `idx_chamados_workflows_01` (`Projeto_Tarefa_ID`),
	INDEX `idx_chamados_workflows_02` (`Projeto_ID`),
	INDEX `idx_chamados_workflows_03` (`Tarefa_ID`),
	INDEX `idx_chamados_workflows_05` (`Posicao`),
	INDEX `idx_chamados_workflows_06` (`Situacao_ID`),
	INDEX `idx_chamados_workflows_07` (`Projeto_Tarefa_ID`, `Projeto_ID`, `Tarefa_ID`, `Posicao`, `Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projetos_vinculos` (
	`Projeto_Vinculo_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Projeto_ID` INT(10) NULL DEFAULT NULL,
	`Chave_Estrangeira` INT(10) NULL DEFAULT NULL,
	`Tabela_Estrangeira` VARCHAR(100) NULL DEFAULT NULL,
	`Campo_Estrangeiro` VARCHAR(100) NULL DEFAULT NULL,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	PRIMARY KEY (`Projeto_Vinculo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `projetos_vinculos_tarefas` (
	`Projeto_Vinculo_Tarefa_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Projeto_Vinculo_ID` INT(10) NULL DEFAULT NULL,
	`Tarefa_ID` INT(10) NULL DEFAULT NULL,
	`Posicao` INT(10) NULL DEFAULT NULL,
	`Tempo_Execucao` INT(10) NULL DEFAULT NULL,
	`Data_Limite` DATETIME NULL DEFAULT NULL,
	`Grupo_Responsavel_ID` INT(10) NULL DEFAULT NULL,
	`Usuario_Responsavel_ID` INT(10) NULL DEFAULT NULL,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Projeto_Vinculo_Tarefa_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `projetos_vinculos_tarefas_follows` (
	`Projeto_Vinculos_Tarefas_Follow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Projeto_Vinculo_Tarefa_ID` INT(10) NOT NULL,
	`Descricao` TEXT NOT NULL,
	`Hora_Inicio` DATETIME NOT NULL,
	`Hora_Fim` DATETIME NOT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Projeto_Vinculos_Tarefas_Follow_ID`),
	INDEX `Projeto_Vinculo_Tarefa_ID` (`Projeto_Vinculo_Tarefa_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*INSERT IGNORE INTO `tipo_grupo` (Tipo_Grupo_ID,Descr_Tipo_Grupo)values(40,"Tipo de Tarefa"); */
INSERT IGNORE INTO `tipo_grupo` (Tipo_Grupo_ID,Descr_Tipo_Grupo)values(41,"Situacao da Tarefa");
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID,Descr_Tipo,Tipo_Auxiliar)values(83,41,"Aberta", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID,Descr_Tipo,Tipo_Auxiliar)values(84,41,"Finalizada", '');

select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Projetos','projetos','Módulo para controle de projetos e tarefas',@posicao,'1.5');
select Modulo_ID from modulos where Slug = 'projetos' into @moduloID;

insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu)values(@moduloID,'Projeto','Gerenciar Projeto','projetos-projeto','0','1');
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao)values(@moduloID,'Projetos','Projetos','projetos-projeto-localizar','1');
/*insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Tipo_Grupo_ID)values(@moduloID,'Tarefas','Gerenciar Tarefas','projetos-tarefas','2','40');*/
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao)values(@moduloID,'Tarefas','Gerenciar Tarefas','projetos-tarefas','2');
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao)values(@moduloID,'Relatórios','Gerar Relatórios dos Projetos e Tarefas','projetos-relatorios','3');
	select Modulo_Pagina_ID from modulos_paginas where Slug = 'projetos-relatorios' into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Tarefas Dinâmico','RelatÃ³rio Geal Tarefas','projetos-relatorio-geral-tarefas','1', @subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Calendário de Tarefas','Relatório Calendário de Tarefas','projetos-relatorio-calendario-tarefas','2', @subModuloID);



ALTER TABLE `projetos_vinculos_tarefas`
	ADD COLUMN `Descricao_Inicial` TEXT NULL DEFAULT NULL AFTER `Tarefa_ID`;


ALTER TABLE `projetos_tarefas`
	ADD COLUMN `Usuario_Responsavel_ID` INT(11) NULL DEFAULT NULL AFTER `Grupo_Responsavel_ID`;




CREATE TABLE `tarefas_grupos` (
	`Tarefa_Grupo_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Tarefa_ID` INT(11) NOT NULL, 
	`Grupo_ID` INT(11) NOT NULL,
	`Situacao_ID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Tarefa_Grupo_ID`),
	INDEX `idx_tarefas_grupos_01` (`Tarefa_ID`),
	INDEX `idx_tarefas_grupos_02` (`Grupo_ID`),
	INDEX `idx_tarefas_grupos_03` (`Situacao_ID`),
	INDEX `idx_tarefas_grupos_04` (`Tarefa_ID`, `Grupo_ID`, `Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `projetos` (`Projeto_ID`, `Titulo`, `Descricao`, `Tabela_Estrangeira`, `Projeto_Padrao`, `Data_Cadastro`, `Usuario_Cadastro_ID`, `Situacao_ID`) VALUES (-1, 'Agenda Pessoal', NULL, 'cadastros_dados', 1, '2015-04-24 03:56:06', -1, 1);


ALTER TABLE `orcamentos_workflows`
	ADD COLUMN `Oportunidade_ID` INT(10) NULL DEFAULT NULL AFTER `Empresa_ID`;
