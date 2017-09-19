select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao) values('NF','nfe','Módulo emissor de Notas Fiscais',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Notas Fiscais','Pagina com listagem de notas Fiscais emitidas','nfe-emitidas','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Modulo','Gerenciar Modulo de notas fiscais','nfe-gerenciar-modulo','2');
select LAST_INSERT_ID() into @subModuloID;
insert into modulos_paginas(Modulo_ID, Pagina_Pai_ID, Titulo, Descricao, Slug, Posicao)values(@moduloID, @subModuloID, 'Importar XML','Importar os XML ja gerados','nfe-importar-xml','1');

CREATE TABLE `nf_dados` (
	`NF_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Conta_ID` INT(11) NOT NULL,
	`Numero_NF` INT(11) NOT NULL,
	`Serie` INT(11) NOT NULL,
	`Empresa_ID` INT(11) NOT NULL,
	`Ambiente` INT(11) NOT NULL,
	`Chave_Acesso` VARCHAR(50) NOT NULL,
	`Recibo` VARCHAR(50) NOT NULL,
	`Protocolo` VARCHAR(50) NOT NULL,
	`Status_NF` VARCHAR(10) NOT NULL,
	`NF_XML` TEXT NOT NULL,
	`NF_Array` TEXT NOT NULL,
	`Situacao_ID` INT(11) NOT NULL,
	`Data_Emissao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Emissao_ID` INT(11) NOT NULL,
	PRIMARY KEY (`NF_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;




CREATE TABLE `nf_config` (
	`NF_config_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Empresa_ID` INT(11) NOT NULL,
	`Config` TEXT NOT NULL,
	`Situacao_ID` INT(11) NOT NULL,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`NF_config_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `nf_canceladas` (
	`NF_Cancelamento_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`NF_ID` INT(11) NOT NULL,
	`Justificativa` VARCHAR(250) NOT NULL,
	`Retorno` TEXT NULL,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Erro` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`NF_Cancelamento_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
