CREATE TABLE IF NOT EXISTS `cadastros_dados` (
  `Cadastro_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Grupo_ID` int(10) DEFAULT NULL,
  `Centro_Custo_ID` int(10) DEFAULT NULL,
  `Codigo` varchar(250) DEFAULT NULL,
  `Tipo_Pessoa` int(10) NOT NULL,
  `Tipo_Cadastro` varchar(2000) DEFAULT NULL,
  `Nome` varchar(250) NOT NULL,
  `Nome_Fantasia` varchar(250) DEFAULT NULL,
  `Senha` varchar(50) DEFAULT NULL,
  `Data_Nascimento` date DEFAULT NULL,
  `Cpf_Cnpj` varchar(20) DEFAULT NULL,
  `RG` varchar(20) DEFAULT NULL,
  `Inscricao_Municipal` varchar(20) DEFAULT NULL,
  `Inscricao_Estadual` varchar(20) DEFAULT NULL,
  `Tipo_Vinculo` varchar(250) DEFAULT NULL,
  `Email` varchar(2000) DEFAULT NULL,
  `Foto` varchar(200) DEFAULT NULL,
  `Observacao` varchar(2000) DEFAULT NULL,
  `Usuario_Cadastro_ID` int(10) NOT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  `Data_Inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Data_Alteracao` datetime DEFAULT NULL,
  `Ultimo_Login` datetime NULL,
  `Tabela_Preco_ID` int(10) DEFAULT NULL,
  `Areas_Atuacoes` varchar(2000) DEFAULT NULL,
  `Situacao_ID` int(10) NOT NULL,
  PRIMARY KEY (`Cadastro_ID`),
  KEY `idx_cadastros_dados_codigos` (`Codigo`),
  KEY `idx_grupos_cadastros` (`Grupo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `cadastros_dados_bancarios` (
  `Dados_Bancarios_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Cadastro_ID` int(10) NOT NULL,
  `Banco` varchar(20) DEFAULT NULL,
  `Agencia` varchar(10) DEFAULT NULL,
  `Conta_Corrente` varchar(20) DEFAULT NULL,
  `Situacao_ID` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Dados_Bancarios_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `cadastros_documentos` (
  `Documento_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Titulo` varchar(250) DEFAULT NULL,
  `Texto_Documento` text,
  `Cabecalho_Rodape` varchar(10) DEFAULT NULL,
  `Slug_Modulo` varchar(50) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT '1',
  PRIMARY KEY (`Documento_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `cadastros_enderecos` (
  `Cadastro_Endereco_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Cadastro_ID` int(10) NOT NULL DEFAULT '0',
  `Tipo_Endereco_ID` int(10) DEFAULT NULL,
  `CEP` varchar(10) NOT NULL,
  `Logradouro` varchar(250) NOT NULL,
  `Numero` varchar(10) NOT NULL,
  `Complemento` varchar(100) NOT NULL,
  `Bairro` varchar(100) NOT NULL,
  `Cidade` varchar(50) NOT NULL,
  `UF` varchar(2) NOT NULL,
  `Referencia` varchar(150) NOT NULL,
  `Situacao_ID` varchar(150) NOT NULL,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Cadastro_Endereco_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `cadastros_telefones` (
  `Cadastro_Telefone_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Cadastro_ID` int(10) NOT NULL DEFAULT '0',
  `Telefone` varchar(20) DEFAULT NULL,
  `Tipo_Telefone_ID` varchar(20) DEFAULT NULL,
  `Observacao` varchar(50) DEFAULT NULL,
  `Situacao_ID` varchar(20) NOT NULL,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Cadastro_Telefone_ID`),
  KEY `idx_cadastros_telefones_01` (`Cadastro_Telefone_ID`),
  KEY `idx_cadastros_telefones_02` (`Cadastro_ID`),
  KEY `idx_cadastros_telefones_03` (`Telefone`),
  KEY `idx_cadastros_telefones_04` (`Tipo_Telefone_ID`),
  KEY `idx_cadastros_telefones_05` (`Situacao_ID`),
  KEY `idx_cadastros_telefones_06` (`Cadastro_ID`,`Situacao_ID`),
  KEY `idx_cadastros_telefones_07` (`Cadastro_ID`,`Situacao_ID`,`Tipo_Telefone_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `cadastros_vinculos` (
  `Cadastro_Vinculo_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Tipo_Vinculo_ID` int(10) DEFAULT NULL,
  `Cadastro_ID` int(10) DEFAULT NULL,
  `Cadastro_Filho_ID` int(10) DEFAULT NULL,
  `Situacao_ID` int(10) DEFAULT '0',
  PRIMARY KEY (`Cadastro_Vinculo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DELETE FROM `cadastros_dados`;
INSERT INTO `cadastros_dados` (`Cadastro_ID`, `Grupo_ID`, `Tipo_Pessoa`, `Tipo_Cadastro`, `Nome`, `Nome_Fantasia`, `Senha`, `Email`, `Usuario_Cadastro_ID`, `Situacao_ID`) VALUES
	(-1, 1, 25, '', 'Administrador Geral', 'Administrador Geral', 'mwn@1209', 'admin', -1, -1);	


INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (8, 'Tipo Pessoa', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (9, 'Tipo Cadastro', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (10, 'Tipo Endereço', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (11, 'Tipo Telefone', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (12, 'Tipo Vínculo', 1);

INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (15, 'Unidades', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (16, 'Departamentos', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (17, 'Setores', 1);
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (34, 'Áreas de Atuação', 1);


INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (24, 8, 'Pessoa Física', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (25, 8, 'Pessoa Jurídica', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (26, 10, 'Principal', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (27, 11, 'Comercial', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (28, 11, 'Celular', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (29, 11, 'Residencial', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (38, 10, 'Instalação', NULL, 3);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (102, 11, '0800', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (103, 11, 'Nextel', NULL, 1);


select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
INSERT ignore INTO modulos(Nome, Slug, Descricao, Posicao, Versao) values('Cadastros','cadastros','Módulo para Cadastros de Clientes, Fornecedores, Prestadores e Usuários',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;

INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Cadastros','Localizar cadastros de Clientes, fornecedores e prestadores','cadastro-localiza','1');
INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_menu)values(@moduloID,'Cadastro','Cadastro de Clientes, fornecedores e prestadores','cadastro-dados','2','1');

INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Cadastros Tipos','Gerenciar Tipos dos Cadastros','cadastros-tipos','3');
	select LAST_INSERT_ID() into @subModuloID;
/*
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Unidades','Gerenciar Unidade','cadastro-unidade','1','15',@subModuloID );
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Departamentos','Gerenciar Departamentos','cadastro-departamento','2','16',@subModuloID );
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Setores','Gerenciar Setores','cadastro-setor','3','17',@subModuloID );
*/
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Tipo Vinculo','Gerenciar Tipos de Vinculos','cadastro-vinculo','4','12',@subModuloID );
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Tipo Cadastro','Gerenciar Tipos de Cadastros','cadastro-tipo','5','9',@subModuloID );
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Tipo Endereço','Gerenciar Tipos de Endereços','cadastro-endereco','6','10',@subModuloID );
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Tipo Telefone','Gerenciar Tipos de Telefones','cadastro-telefone','7','11',@subModuloID );
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Áreas de Atuação','Gerenciar Áreas de Atuação','cadastro-areas-atuacoes','8','34',@subModuloID );

INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Relatórios','Relatórios','cadastros-relatorios','4');
	select LAST_INSERT_ID() into @subModuloID;
	INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Pagina_Pai_ID)values(@moduloID,'Relatório Regional','Relatório Regional','cadastro-relatorio-regional','1',@subModuloID );

INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (101, 12, 'Clientes', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (102, 11, '0800', NULL, 1);
INSERT ignore INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (103, 11, 'Nextel', NULL, 1);



/* Atualizado 09/07/2014 em todos os sistemas */
INSERT ignore INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (42, 'Cargos', 1);
update modulos_paginas set Titulo = 'Gerenciar Módulo', Posicao = 4 where Slug = 'cadastros-tipos';
update modulos_paginas set Posicao = 3 where Slug = 'cadastros-relatorios';
select Modulo_ID from modulos where Slug = 'cadastros' into @moduloID;

select Modulo_Pagina_ID from modulos_paginas where Slug = 'cadastros-tipos' into @subModuloID;
INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Pagina_Pai_ID)values(@moduloID,'Cargos','Cadastro de Cargos','cadastro-cargos','9','42', @subModuloID);

select Modulo_Pagina_ID from modulos_paginas where Slug = 'cadastros-relatorios' into @subModuloID;
INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Aniversariantes','Relatório de Aniversariantes','cadastro-relatorio-aniversariantes','2', @subModuloID);

ALTER TABLE `cadastros_dados`
	ADD COLUMN `Cargo_ID` INT(10) NULL AFTER `Areas_Atuacoes`;

/**/


/* ATUALIZADO 01/09/2014 */

select Modulo_ID from modulos where Slug = 'cadastros' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'cadastros-relatorios' into @subModuloID;
INSERT ignore INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Geral','Relatório Dinâmico Geral','cadastro-relatorio-geral','2', @subModuloID);

UPDATE cadastros_dados SET Centro_Custo_ID = 0 WHERE Centro_Custo_ID IS NULL;


ALTER TABLE `cadastros_dados`
	ADD COLUMN `Empresa` INT(10) NOT NULL DEFAULT '0' AFTER `Cargo_ID`;

ALTER TABLE `cadastros_dados`
	CHANGE COLUMN `Centro_Custo_ID` `Centro_Custo_ID` INT(10) NOT NULL DEFAULT '0' AFTER `Grupo_ID`;

update cadastros_dados set Empresa = 1 where Centro_Custo_ID <> 0;

delete from modulos_paginas where slug = 'cadastro-unidade';
delete from modulos_paginas where slug = 'cadastro-departamento';
delete from modulos_paginas where slug = 'cadastro-setor';


update modulos_paginas set Titulo = 'Gerenciar Módulo' where slug = 'chamados-configuracoes';


ALTER TABLE `cadastros_dados`
	ADD COLUMN `Sexo` CHAR(1) NOT NULL DEFAULT '' AFTER `Data_Nascimento`;
	
	
ALTER TABLE `cadastros_dados` ADD INDEX `idx_cadastros_dados_01` (`Nome`,`Situacao_ID`);
ALTER TABLE `cadastros_dados` ADD INDEX `idx_cadastros_dados_02` (`Nome_Fantasia`,`Situacao_ID`);
ALTER TABLE `cadastros_dados` ADD INDEX `idx_cadastros_dados_03` (`Nome`,`Nome_Fantasia`,`Situacao_ID`);
ALTER TABLE `cadastros_dados` ADD INDEX `idx_cadastros_dados_04` (`Nome`);
ALTER TABLE `cadastros_dados` ADD INDEX `idx_cadastros_dados_05` (`Nome_Fantasia`);
ALTER TABLE `cadastros_dados` ADD INDEX `idx_cadastros_dados_06` (`Cadastro_ID`);


ALTER TABLE `cadastros_enderecos` ADD INDEX `idx_cadastros_enderecos_01` (`Cadastro_Endereco_ID`);
ALTER TABLE `cadastros_enderecos` ADD INDEX `idx_cadastros_enderecos_02` (`Cadastro_ID`);
ALTER TABLE `cadastros_enderecos` ADD INDEX `idx_cadastros_enderecos_03` (`Tipo_Endereco_ID`);
ALTER TABLE `cadastros_enderecos` ADD INDEX `idx_cadastros_enderecos_04` (`Situacao_ID`);
ALTER TABLE `cadastros_enderecos` ADD INDEX `idx_cadastros_enderecos_05` (`CEP`);
ALTER TABLE `cadastros_enderecos` ADD INDEX `idx_cadastros_enderecos_06` (`Cadastro_ID`, `Situacao_ID`);
ALTER TABLE `cadastros_enderecos` ADD INDEX `idx_cadastros_enderecos_07` (`Cadastro_ID`, `Situacao_ID`, `Tipo_Endereco_ID`);


