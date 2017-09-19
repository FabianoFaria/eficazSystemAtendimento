CREATE TABLE IF NOT EXISTS `modulos` (
  `Modulo_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(150) DEFAULT NULL,
  `Descricao` varchar(150) DEFAULT NULL,
  `Situacao_ID` varchar(150) DEFAULT '1',
  `Slug` varchar(150) DEFAULT NULL,
  `Data_Ativacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Posicao` int(11) DEFAULT NULL,
  `Versao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Modulo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `modulos_acessos` (
  `Modulo_Acesso_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(150) DEFAULT NULL,
  `Acessos` text,
  `Situacao_ID` int(11) DEFAULT '1',
  PRIMARY KEY (`Modulo_Acesso_ID`),
  UNIQUE KEY `Titulo` (`Titulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `modulos_anexos` (
  `Anexo_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Documento_ID` int(10) DEFAULT NULL COMMENT 'Caso o anexo foi gerado a partir de um documento modelo',
  `Cabecalho_Rodape` varchar(10) DEFAULT NULL,
  `Chave_Estrangeira` int(10) NOT NULL DEFAULT '0',
  `Tabela_Estrangeira` varchar(100) NOT NULL DEFAULT '0',
  `Nome_Arquivo` varchar(300) NOT NULL DEFAULT '0',
  `Nome_Arquivo_Original` varchar(300) NOT NULL DEFAULT '0',
  `Observacao` text NOT NULL,
  `Situacao_ID` int(10) NOT NULL DEFAULT '1',
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Anexo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `modulos_paginas` (
  `Modulo_Pagina_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Modulo_ID` int(10) DEFAULT NULL,
  `Titulo` varchar(250) DEFAULT NULL,
  `Descricao` text,
  `Slug` varchar(150) DEFAULT NULL,
  `Situacao_ID` int(10) DEFAULT '1',
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Pagina_Pai_ID` int(11) DEFAULT NULL,
  `Posicao` int(11) NOT NULL,
  `Tipo_Grupo_ID` int(11) DEFAULT NULL,
  `Oculta_Menu` int(1) DEFAULT '0',
  PRIMARY KEY (`Modulo_Pagina_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `tipo` (
  `Tipo_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Tipo_Grupo_ID` int(10) DEFAULT NULL,
  `Descr_Tipo` text,
  `Tipo_Auxiliar` text,
  `Situacao_ID` int(10) DEFAULT '1',
  PRIMARY KEY (`Tipo_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000;

CREATE TABLE IF NOT EXISTS `tipo_grupo` (
  `Tipo_Grupo_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Descr_Tipo_Grupo` varchar(150) DEFAULT NULL,
  `Situacao_ID` int(10) DEFAULT '1',
  PRIMARY KEY (`Tipo_Grupo_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `log_acessos` (
	`Log_Acesso_ID` INT(10) NULL AUTO_INCREMENT,
	`Usuario_ID` INT(10) NULL,
	`Pagina_ID` INT(10) NULL,
	`Modulo_ID` int NULL,
	`Chave_Estrangeira` INT NULL,
	`Tipo_Acesso_ID` INT NULL,
	`IP_Acesso` VARCHAR(50) NULL,
	`Data_Acesso` TIMESTAMP  NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Log_Acesso_ID`),
	INDEX `Usuario_ID_Pagina_ID_Modulo_ID_Tipo_Acesso_ID_IP_Acesso` (`Usuario_ID`, `Pagina_ID`, `Modulo_ID`, `Tipo_Acesso_ID`, `IP_Acesso`),
	INDEX `Usuario_ID` (`Usuario_ID`),
	INDEX `Pagina_ID` (`Pagina_ID`),
	INDEX `Modulo_ID` (`Modulo_ID`),
	INDEX `Tipo_Acesso_ID` (`Tipo_Acesso_ID`),
	INDEX `Data_Acesso` (`Data_Acesso`),
	INDEX `Usuario_ID_Data_Acesso` (`Usuario_ID`, `Data_Acesso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (1, 'Situação', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (2, 'Permissão de Usuários', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (3, 'Pagina Inicial', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (4, 'Configuração de Envio', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (5, 'Campos', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (6, 'Mascaras', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (7, 'Envios', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (36, 'Configuração do layout', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (37, 'PDV', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (38, 'Situação Responsabilidade Follow', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (39, 'Log de acessos', 1);


INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (1, 1, 'Ativo', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (2, 1, 'Inativo', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (3, 1, 'Lixeira', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (4, 2, 'Leitura', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (5, 2, 'Completo', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (6, 3, '', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (7, 4, '', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (8, 5, 'Texto Curto', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (9, 5, 'Texto Longo', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (10, 5, 'Drop Down', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (11, 5, 'Radio ', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (12, 5, 'Checked', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (14, 6, 'CPF', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (15, 6, 'Telefone', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (16, 6, 'Somente Números', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (17, 6, 'Texto Livre', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (18, 6, 'CNPJ', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (19, 6, 'Cep', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (20, 7, 'Sem envio', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (21, 7, 'Enviar SMS', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (22, 7, 'Enviar E-mail', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (23, 7, 'Enviar E-mail e SMS', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (70, 36, 'Layout', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (74, 39, 'Consulta', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (75, 39, 'Inclusão', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (76, 39, 'Exclusão', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (77, 39, 'Alteração', NULL, 1);

INSERT INTO `modulos` (`Modulo_ID`, `Nome`, `Descricao`, `Situacao_ID`, `Slug`, `Data_Ativacao`, `Posicao`, `Versao`) VALUES (1, 'Administrativo', 'Módulo principal de administração para gestão administrativa do sistema', '1', 'administrativo', '2013-09-30 05:43:16', 1000, '1.5');
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (-1, 1, 'Home', 'Página Inicial', 'home', 1, '2013-09-30 05:48:17', NULL, 0, NULL, 1);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (1, 1, 'Configurações Gerais', 'Pagina inicial para configuração Gerais do Sistema', 'configuracoes-gerais', 1, '2013-09-30 05:48:17', NULL, 2, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (2, 1, 'Relatorio de Acessos', 'Relatorio de Acessos ao Sistema', 'relatorio-acessos', 1, '2013-09-30 05:48:17', NULL, 4, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (3, 1, 'Gerenciar Módulos', 'Gerenciamento de Módulos do Sistema', 'gerenciar-modulos', 1, '2013-10-04 10:50:24', NULL, 3, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (4, 1, 'Gerenciar Permissões', 'Gerenciamento e criação dos grupos de permissões de acesso para os módulos', 'gerenciar-permissao-grupos', 1, '2013-10-07 09:25:15', 1, 2, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (5, 1, 'Gerenciar Usuários', 'Pagina para inclusão, alteração e exclusão de usuários e permissões de acesso', 'gerenciar-usuarios', 1, '2013-10-07 12:00:15', 1, 1, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (6, 1, 'Configurar Página Inicial', 'Gerenciar página inicial do sistema após login', 'gerenciar-pagina-inicial', 1, '2013-10-07 12:00:15', 1, 3, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (7, 1, 'Configurar Envios', 'Configurações SMTP para envios de e-mail e SMS', 'gerenciar-envios', 1, '2013-10-07 12:00:15', 1, 4, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (8, 1, 'Configurar layout', 'Configurar layout do sistema', 'configurar-layout', 1, '2013-10-07 12:00:15', 1, 5, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (9, 1, 'Gerenciar Documentos', 'Modulo para criação de documentos', 'documentos-gerenciar-documentos', 1, '2013-10-07 12:00:15', NULL, 6, NULL, 0);
INSERT INTO `modulos_paginas` (`Modulo_Pagina_ID`, `Modulo_ID`, `Titulo`, `Descricao`, `Slug`, `Situacao_ID`, `Data_Cadastro`, `Pagina_Pai_ID`, `Posicao`, `Tipo_Grupo_ID`, `Oculta_Menu`) VALUES (10, 1, 'Gerenciar Formulários', 'Modulo para criação de formularios', 'formularios-dinamicos', 1, '2013-10-07 12:00:15', NULL, 7, NULL, 0);

INSERT INTO `modulos_acessos` (`Modulo_Acesso_ID`, `Titulo`, `Acessos`, `Situacao_ID`) VALUES (-3, 'Usuario Externo Cliente','', 1);
INSERT INTO `modulos_acessos` (`Modulo_Acesso_ID`, `Titulo`, `Acessos`, `Situacao_ID`) VALUES (-2, 'Usuario Externo Representante', '', 1);
INSERT INTO `modulos_acessos` (`Modulo_Acesso_ID`, `Titulo`, `Acessos`, `Situacao_ID`) VALUES (1, 'Administrador', '', 1);

CREATE TABLE `formularios` (
	`Formulario_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Titulo` VARCHAR(250) NULL DEFAULT NULL,
	`Modulo_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`Situacao_ID` INT(11) NULL DEFAULT '1',
	PRIMARY KEY (`Formulario_ID`)
);

create INDEX `idx_tipo_01` on tipo(`Tipo_ID`);
create INDEX `idx_tipo_02` on tipo(`Tipo_Grupo_ID`);
create INDEX `idx_tipo_03` on tipo(`Tipo_ID`, `Tipo_Grupo_ID`);

create INDEX `idx_modulos_anexos_01` on modulos_anexos(`Anexo_ID`);
create INDEX `idx_modulos_anexos_02` on modulos_anexos(`Chave_Estrangeira`);
create INDEX `idx_modulos_anexos_03` on modulos_anexos(`Tabela_Estrangeira`);
create INDEX `idx_modulos_anexos_04` on modulos_anexos(`Chave_Estrangeira`,`Tabela_Estrangeira`);
create INDEX `idx_modulos_anexos_05` on modulos_anexos(`Situacao_ID`);
create INDEX `idx_modulos_anexos_06` on modulos_anexos(`Chave_Estrangeira`,`Tabela_Estrangeira`,`Situacao_ID`);

delete from modulos_paginas where Slug = 'gerenciar-usuarios';
delete from modulos_paginas where Slug = 'gerenciar-empresas';
delete from modulos_paginas where Slug = 'gerenciar-modulos';

select Modulo_ID from modulos where Slug = 'administrativo' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Empresas','Gerenciar Empresas','gerenciar-empresas','2');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Usuários','Gerenciar Usuários','gerenciar-usuarios','3');

/*
ALTER TABLE `modulos_anexos`
	ALTER `Tabela_Estrangeira` DROP DEFAULT,
	ALTER `Nome_Arquivo` DROP DEFAULT,
	ALTER `Nome_Arquivo_Original` DROP DEFAULT;
ALTER TABLE `modulos_anexos`
	CHANGE COLUMN `Tabela_Estrangeira` `Tabela_Estrangeira` VARCHAR(100) NOT NULL AFTER `Chave_Estrangeira`,
	CHANGE COLUMN `Nome_Arquivo` `Nome_Arquivo` VARCHAR(300) NOT NULL AFTER `Tabela_Estrangeira`,
	CHANGE COLUMN `Nome_Arquivo_Original` `Nome_Arquivo_Original` VARCHAR(300) NOT NULL AFTER `Nome_Arquivo`,
	ADD COLUMN `Complemento` TEXT NOT NULL AFTER `Nome_Arquivo_Original`;

ALTER TABLE `modulos_anexos`
	ALTER `Tabela_Estrangeira` DROP DEFAULT,
	ALTER `Nome_Arquivo` DROP DEFAULT,
	ALTER `Nome_Arquivo_Original` DROP DEFAULT;
ALTER TABLE `modulos_anexos`
	CHANGE COLUMN `Tabela_Estrangeira` `Tabela_Estrangeira` VARCHAR(100) NULL AFTER `Chave_Estrangeira`,
	CHANGE COLUMN `Nome_Arquivo` `Nome_Arquivo` VARCHAR(300) NULL AFTER `Tabela_Estrangeira`,
	CHANGE COLUMN `Nome_Arquivo_Original` `Nome_Arquivo_Original` VARCHAR(300) NULL AFTER `Nome_Arquivo`,
	CHANGE COLUMN `Complemento` `Complemento` TEXT NULL AFTER `Nome_Arquivo_Original`,
	CHANGE COLUMN `Observacao` `Observacao` TEXT NULL AFTER `Complemento`;
*/

CREATE TABLE `modulos_vinculos` (
	`Vinculo_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Nome_Tabela` VARCHAR(250) NOT NULL,
	`Tipo_Principal_ID` INT(10) NOT NULL,
	`Tipo_Secundario_ID` INT(10) NOT NULL,
	`Valor_Vinculo` VARCHAR(250) NOT NULL,
	`Situacao_ID` INT(10) NOT NULL DEFAULT '1',
	`Data_Situacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Vinculo_ID`),
	UNIQUE INDEX `idx_modulos_vinculos` (`Tipo_Principal_ID`, `Tipo_Secundario_ID`, `Valor_Vinculo`, `Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE `modulos_paginas`
	ADD COLUMN `Campos_Obrigatorios` TEXT NOT NULL AFTER `Oculta_Menu`;

create INDEX `idx_modulos_01` on modulos(Modulo_ID);
create INDEX `idx_modulos_02` on modulos(Slug);
create INDEX `idx_modulos_03` on modulos(Situacao_ID);
create INDEX `idx_modulos_04` on modulos(Posicao);
create INDEX `idx_modulos_05` on modulos(Modulo_ID, Situacao_ID);
create INDEX `idx_modulos_06` on modulos(Modulo_ID, Situacao_ID, Posicao);



create INDEX `idx_modulos_paginas_01` on modulos_paginas(Modulo_Pagina_ID);
create INDEX `idx_modulos_paginas_02` on modulos_paginas(Modulo_ID);
create INDEX `idx_modulos_paginas_03` on modulos_paginas(Slug);
create INDEX `idx_modulos_paginas_04` on modulos_paginas(Situacao_ID);
create INDEX `idx_modulos_paginas_05` on modulos_paginas(Pagina_Pai_ID);
create INDEX `idx_modulos_paginas_06` on modulos_paginas(Posicao);
create INDEX `idx_modulos_paginas_07` on modulos_paginas(Tipo_Grupo_ID);										
create INDEX `idx_modulos_paginas_08` on modulos_paginas(Modulo_ID, Modulo_Pagina_ID, Situacao_ID, Posicao);
create INDEX `idx_modulos_paginas_09` on modulos_paginas(Modulo_ID, Modulo_Pagina_ID, Situacao_ID);
