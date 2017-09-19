select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao) values('Igreja','igreja','Módulo para igrejas',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;
/*insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Lançamento','Pagina para lançamento de número de pessoas que frequentam a igreja','igreja-lancamento','1');*/

CREATE TABLE `igreja_lancamento` (
	`Igreja_Lancamento_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Igreja_ID` INT(11) NOT NULL,
	`Quantidade` INT(11) NULL DEFAULT NULL,
	`Data_Lancamento` DATETIME NULL DEFAULT NULL,
	`Situacao_ID` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	PRIMARY KEY (`Igreja_Lancamento_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE igreja_lancamento ADD INDEX `idx_igreja_lancamento_01` (Igreja_Lancamento_ID);
ALTER TABLE igreja_lancamento ADD INDEX `idx_igreja_lancamento_02` (Igreja_ID);
ALTER TABLE igreja_lancamento ADD INDEX `idx_igreja_lancamento_03` (Situacao_ID);
ALTER TABLE igreja_lancamento ADD INDEX `idx_igreja_lancamento_04` (Quantidade);
ALTER TABLE igreja_lancamento ADD INDEX `idx_igreja_lancamento_05` (Data_Lancamento);
ALTER TABLE igreja_lancamento ADD INDEX `idx_igreja_lancamento_06` (Igreja_Lancamento_ID, Igreja_ID, Data_Lancamento, Quantidade);
ALTER TABLE igreja_lancamento ADD INDEX `idx_igreja_lancamento_07` (Igreja_Lancamento_ID, Igreja_ID);


CREATE TABLE `igreja_cadastros_dados` (
	`Cadastro_ID` INT(11) NOT NULL,
	`Estado_Civil` INT(11) NULL DEFAULT NULL,
	`Procedencia` INT(11) NOT NULL,
	`Data_Batismo` DATETIME NULL DEFAULT NULL,
	`Nome_Pai` VARCHAR(150) NOT NULL,
	`Nome_Mae` VARCHAR(150) NOT NULL,
	PRIMARY KEY (`Cadastro_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (52, 'Estado Civil', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (53, 'Procedência', 1);

select modulo_ID from modulos where slug = 'igreja' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo','igreja-gerenciar-modulo','1');
select Modulo_Pagina_ID from modulos_paginas where slug = 'igreja-gerenciar-modulo' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values(@moduloID,'Estado Civil','Gerenciar Estado Civil','igreja-estado-civil','1',@subModuloID,52);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values(@moduloID,'Procedência','Gerenciar Procedencia','igreja-procedencia','2',@subModuloID,53);


ALTER TABLE `igreja_cadastros_dados`
	ADD COLUMN `Cidade_Natural` VARCHAR(150) NULL AFTER `Nome_Mae`,
	ADD COLUMN `UF_Natural` VARCHAR(2) NULL AFTER `Cidade_Natural`;



ALTER TABLE `igreja_cadastros_dados`
	ADD COLUMN `Congregacao_ID` INT(10) NULL AFTER `UF_Natural`;


ALTER TABLE `igreja_cadastros_dados`
	ADD COLUMN `Data_Ordenacao` DATETIME NULL DEFAULT NULL AFTER `Data_Batismo`;
