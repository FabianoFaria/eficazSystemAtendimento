
CREATE TABLE `modulos_vinculos` (
	`Vinculo_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Nome_Tabela` VARCHAR(250) NOT NULL,
	`Tipo_Principal_ID` INT(10) NOT NULL,
	`Tipo_Secundario_ID` INT(10) NOT NULL,
	`Valor_Vinculo` VARCHAR(250) NOT NULL,
	`Situacao_ID` INT(10) NOT NULL DEFAULT '1',
	`Data_Situacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Vinculo_ID`),
	UNIQUE INDEX `idx_modulos_chamados_vinculos` (`Tipo_Principal_ID`, `Tipo_Secundario_ID`, `Valor_Vinculo`, `Situacao_ID`)
)

ALTER TABLE `modulos_paginas`
	ADD COLUMN `Campos_Obrigatorios` TEXT NOT NULL DEFAULT '' AFTER `Oculta_Menu`;



INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (142, 1, 'Pendente', '', 1);



ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_01` (`Modulo_Pagina_ID`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_02` (`Modulo_ID`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_03` (`Slug`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_04` (`Situacao_ID`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_05` (`Pagina_Pai_ID`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_06` (`Posicao`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_07` (`Tipo_Grupo_ID`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_08` (`Modulo_ID`, `Modulo_Pagina_ID`, `Situacao_ID`, `Posicao`);
ALTER TABLE `modulos_paginas` ADD INDEX `idx_modulos_paginas_09` (`Modulo_ID`, `Modulo_Pagina_ID`, `Situacao_ID`);
	
ALTER TABLE `modulos` ADD INDEX `idx_modulos_01` (`Modulo_ID`);
ALTER TABLE `modulos` ADD INDEX `idx_modulos_02` (`Slug`);
ALTER TABLE `modulos` ADD INDEX `idx_modulos_03` (`Situacao_ID`);
ALTER TABLE `modulos` ADD INDEX `idx_modulos_04` (`Posicao`);
ALTER TABLE `modulos` ADD INDEX `idx_modulos_05` (`Modulo_ID`, `Situacao_ID`);
ALTER TABLE `modulos` ADD INDEX `idx_modulos_06` (`Modulo_ID`, `Situacao_ID`, `Posicao`);



CREATE TABLE `modulos_formularios` (
	`Formulario_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Modulo` VARCHAR(50) NOT NULL,
	`Slug` VARCHAR(500) NOT NULL,
	`Dados` TEXT NOT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	PRIMARY KEY (`Formulario_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;



/* Vinculo colaborador e fornecedores FIXO! */
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (151, 12, 'Colaboradores', 'Colaborador', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (152, 9, 'Fornecedor', '', 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (153, 9, 'Cliente', '', 1);


/* FORMULARIOS DINAMICOS*/


ALTER TABLE `modulos_paginas`
	CHANGE COLUMN `Campos_Obrigatorios` `Campos_Obrigatorios` TEXT NULL AFTER `Oculta_Menu`;


select Modulo_ID from modulos where Slug = 'administrativo' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Oculta_Menu)values(@moduloID,'Formulários Dinâmicos','Gerenciar Formulários','formularios','10','', 1);
select Modulo_Pagina_ID from modulos_paginas where Slug = 'formularios' into @moduloPaginaID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Formulários','Listar Formulários','formularios-dinamicos','1','', 0,@moduloPaginaID);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Formulário','Gerenciar Formulário','formularios-dinamicos-gerenciar','0','',1,@moduloPaginaID);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Campos','Listar Campos','formularios-campos','2','', 0,@moduloPaginaID);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Oculta_Menu, Pagina_Pai_ID)values(@moduloID,'Campo','Gerenciar Campo','formularios-campos-gerenciar','0','', 1,@moduloPaginaID);


CREATE TABLE `formularios` (
	`Formulario_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Nome` VARCHAR(50) NOT NULL,
	`Tabela_Estrangeira` VARCHAR(250) NOT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Formulario_ID`),
	UNIQUE INDEX `Titulo` (`Nome`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `formularios_campos` (
	`Campo_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Nome` VARCHAR(250) NOT NULL,
	`Descricao` VARCHAR(5000) NOT NULL,
	`Tipo_Campo` VARCHAR(10) NOT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Campo_ID`),
	UNIQUE INDEX `Campos_Nome` (`Nome`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `formularios_campos_opcoes` (
	`Campo_Opcao_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Campo_ID` INT(10) NOT NULL,
	`Descricao` VARCHAR(250) NOT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Posicao` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Campo_Opcao_ID`),
	UNIQUE INDEX `Campos_Descricao` (`Descricao`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `formularios_formulario_campo` (
	`Formulario_Campo_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Formulario_ID` INT(10) NOT NULL,
	`Campo_ID` INT(10) NOT NULL,
	`Posicao` INT(11) NULL DEFAULT NULL,
	`Largura` VARCHAR(20) NULL DEFAULT NULL,
	`Altura` VARCHAR(20) NULL DEFAULT NULL,
	`Obrigatorio` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`Formulario_Campo_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

