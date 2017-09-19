
select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Plenario','plenario','Módulo para gestão plenario / mesas digitais',@posicao,'1.5');
select Modulo_ID from modulos where Slug = 'plenario' into @moduloID;

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (65, 'Tipos de Sessão', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (66, 'Situação Sessão', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (147, 66, 'Pendente', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (148, 66, 'Em andamento', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (149, 66, 'Cancelada', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (150, 66, 'Finalizada', '', 1);



insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu)values(@moduloID,'Sessões','Gerenciar Sessões','plenario-geral','1','0');
	select Modulo_Pagina_ID from modulos_paginas where Slug = 'plenario-geral' into @subModuloID;
	insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Gerenciar Sessão','Gerenciar Sessão','plenario-gerenciar-sessao','1','0', @subModuloID);
	insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Visualizar Sessão','Visualizar Sessão','plenario-visualizar-sessao','2','0', @subModuloID);

insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu)values(@moduloID,'Relatórios','Relatórios','plenario-relatorios','2','0');
	select Modulo_Pagina_ID from modulos_paginas where Slug = 'plenario-relatorios' into @subModuloID;
	insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Geral','Geral','plenario-relatorio-geral','1','0', @subModuloID);
	insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Estatísticas','Relatório Estatísticas','plenario-relatorio-estatisticas','2','0', @subModuloID);

insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu)values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo','plenario-gerenciar-modulo','3','0');
	select Modulo_Pagina_ID from modulos_paginas where Slug = 'plenario-gerenciar-modulo' into @subModuloID;
	insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID)values(@moduloID,'Configuração Gerais','Configuração Gerais','plenario-configuracoes-gerais','1', @subModuloID);
	insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Tipo_Grupo_ID, Pagina_Pai_ID) values (@moduloID,'Fases Sessão','Gerenciar Fases de uma Sessão','plenario-tipos-sessao','2', '65', @subModuloID);


CREATE TABLE `sessao_fase` (
	`Fase_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Sessao_ID` VARCHAR(250) NOT NULL,
	`Tipo_Fase_ID` INT(11) NOT NULL,
	`Situacao_ID` INT(11) NOT NULL,
	`Data_Inicio` DATETIME NULL DEFAULT NULL,
	`Data_Fim` DATETIME NULL DEFAULT NULL,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Fase_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `sessao_follows` (
	`Sessao_Follow_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Sessao_ID` VARCHAR(250) NOT NULL,
	`Fase_ID` VARCHAR(250) NOT NULL,
	`Data_Hora_Inicio` DATETIME NULL DEFAULT NULL,
	`Data_Hora_Fim` DATETIME NULL DEFAULT NULL,
	`Tipo` CHAR(1) NULL DEFAULT NULL,
	`Descricao` VARCHAR(500) NOT NULL,
	`Responsavel_Follow_ID` INT(11) NOT NULL,
	`Canal` INT(11) NOT NULL,
	`Posicao` INT(11) NOT NULL,
	`Tempo` INT(20) NOT NULL COMMENT 'Tempo em SEGUNDOS destinado para palavra',
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Sessao_Follow_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `sessao_posicao` (
	`Sessao_Posicao_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Sessao_ID` INT(11) NOT NULL,
	`Cadastro_ID` INT(11) NOT NULL,
	`Posicao` INT(11) NOT NULL,
	`Tipo` CHAR(1) NOT NULL COMMENT 'T = Tribuna / C - Cadastros',
	PRIMARY KEY (`Sessao_Posicao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `sessao_workflows` (
	`Sessao_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Titulo` VARCHAR(250) NOT NULL,
	`Data_Inicio` DATETIME NULL DEFAULT NULL,
	`Data_Fim` TIME NULL DEFAULT '00:00:00',
	`Situacao_ID` INT(11) NOT NULL DEFAULT '147',
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Sessao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


