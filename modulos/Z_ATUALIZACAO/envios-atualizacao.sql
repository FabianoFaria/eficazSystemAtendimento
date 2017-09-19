
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_01` (`Workflow_ID`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_02` (`Cadastro_ID_de`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_03` (`Cadastro_ID_de_Endereco`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_04` (`Cadastro_ID_para`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_05` (`Cadastro_ID_para_Endereco`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_06` (`Transportadora_ID`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_07` (`Tabela_Estrangeira`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_08` (`Chave_Estrangeira`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_09` (`Forma_Envio_ID`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_10` (`Data_Envio`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_11` (`Data_Previsao`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_12` (`Data_Entrega`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_13` (`Data_Cadastro`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_14` (`Usuario_Cadastro_ID`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_15` (`Workflow_ID`,`Cadastro_ID_de`, `Cadastro_ID_para`, `Cadastro_ID_de_Endereco`, `Cadastro_ID_para_Endereco`, `Transportadora_ID`, `Forma_Envio_ID`);
ALTER TABLE `envios_workflows` ADD INDEX `idx_envios_workflows_16` (`Tabela_Estrangeira`,`Chave_Estrangeira`, `Cadastro_ID_para`);


ALTER TABLE `envios_workflows_produtos` ADD INDEX `idx_envios_workflows_produtos_01` (`Workflow_Produto_ID`);
ALTER TABLE `envios_workflows_produtos` ADD INDEX `idx_envios_workflows_produtos_02` (`Workflow_ID`);
ALTER TABLE `envios_workflows_produtos` ADD INDEX `idx_envios_workflows_produtos_03` (`Produto_Variacao_ID`);
ALTER TABLE `envios_workflows_produtos` ADD INDEX `idx_envios_workflows_produtos_04` (`Usuario_Cadastro_ID`);
ALTER TABLE `envios_workflows_produtos` ADD INDEX `idx_envios_workflows_produtos_05` (`Situacao_ID`);
ALTER TABLE `envios_workflows_produtos` ADD INDEX `idx_envios_workflows_produtos_06` (`Workflow_ID`, `Workflow_Produto_ID`, `Situacao_ID`, `Data_Cadastro`, `Produto_Variacao_ID`);

ALTER TABLE `envios_follows` ADD INDEX `idx_envios_follows_01` (`Follow_ID`);
ALTER TABLE `envios_follows` ADD INDEX `idx_envios_follows_02` (`Workflow_ID`);
ALTER TABLE `envios_follows` ADD INDEX `idx_envios_follows_03` (`Situacao_ID`);
ALTER TABLE `envios_follows` ADD INDEX `idx_envios_follows_04` (`Usuario_Cadastro_ID`);
ALTER TABLE `envios_follows` ADD INDEX `idx_envios_follows_05` (`Data_Cadastro`);
ALTER TABLE `envios_follows` ADD INDEX `idx_envios_follows_06` (`Follow_ID`, `Workflow_ID`, `Situacao_ID`, `Usuario_Cadastro_ID`, `Data_Cadastro`);
ALTER TABLE `envios_follows` ADD INDEX `idx_envios_follows_07` (`Follow_ID`, `Workflow_ID`, `Situacao_ID`);




update envios_workflows set Tabela_Estrangeira = 'chamados' where Tabela_Estrangeira = 'chamados_workflows';


ALTER TABLE `envios_workflows_produtos`
	ADD COLUMN `Retorna` INT(10) NOT NULL DEFAULT '0' AFTER `Situacao_ID`;


ALTER TABLE `envios_workflows_produtos`
	ADD COLUMN `Data_Retorno` DATETIME NULL DEFAULT NULL AFTER `Retorna`;

ALTER TABLE `envios_workflows`
	ADD COLUMN `Observacao_Financeiro` VARCHAR(250) NULL DEFAULT NULL AFTER `Valor_Transporte`;
	
	

ALTER TABLE `envios_workflows`
	ADD COLUMN `Empresa_ID` INT(10) NULL DEFAULT NULL AFTER `Workflow_ID`;



ALTER TABLE `envios_workflows_produtos`
	ADD COLUMN `Embalado` INT(10) NOT NULL DEFAULT '0' AFTER `Retorna`,
	ADD COLUMN `Observacoes` VARCHAR(250) NOT NULL AFTER `Embalado`;


ALTER TABLE `envios_workflows_produtos`
	CHANGE COLUMN `Data_Cadastro` `Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `Quantidade`;


ALTER TABLE `envios_workflows_produtos`
	CHANGE COLUMN `Data_Retorno` `Data_Retorno` DATETIME NULL DEFAULT NULL AFTER `Observacoes`;


CREATE TABLE IF NOT EXISTS `envios_centros_distribuicao` (
  `CD_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Empresa_ID` int(10) NOT NULL,
  `Endereco_ID` int(10) NOT NULL,
  `Telefone_ID` int(10) NULL,
  `Contato` varchar(250) NULL,
  `Descricao` varchar(250) NOT NULL,
  `Situacao_ID` int(10) DEFAULT 1,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`CD_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


select Modulo_ID from modulos where Slug = 'envios' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'envios-configuracoes' into @moduloPaginaID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Pagina_Pai_ID)values(@moduloID,'Centros de Distribuição','Gerenciar Centros de Distribuição','envios-centros-distribuicao','1', @moduloPaginaID);



INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (59, 'Tipo Envio', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (129, 59, 'Envio', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (130, 59, 'Coleta', NULL, 1);

/* APENAS PARA EFICAZ!*/
/*
update envios_workflows set Tipo_Envio_ID = '129' where Cadastro_ID_de = 1089;
update envios_workflows set Tipo_Envio_ID = '130' where Cadastro_ID_para = 1089;
*/

ALTER TABLE `envios_workflows_produtos`
	CHANGE COLUMN `Quantidade` `Quantidade` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `Produto_Variacao_ID`,
	ADD COLUMN `Quantidade_Entregue` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `Quantidade`;

ALTER TABLE `envios_workflows_produtos`
	ADD COLUMN `Data_Alteracao` DATETIME NULL DEFAULT NULL AFTER `Data_Retorno`;

UPDATE tipo set Descr_Tipo = 'a:2:{s:11:"slug-pagina";s:27:"envios-configuracoes-gerais";s:18:"emails-copia-envio";s:12:"asdasdasdasd";}' where Tipo_ID = '1278'

ALTER TABLE `envios_workflows`
	ADD COLUMN `Tipo_Envio_ID` INT(10) NOT NULL DEFAULT '0' AFTER `Empresa_ID`;



select Modulo_ID from modulos where Slug = 'envios' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'envios-configuracoes' into @moduloPaginaID;
update modulos_paginas set Titulo = 'Gerenciar Módulo', Posicao = 10 where Slug = 'envios-configuracoes';
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Pagina_Pai_ID)values(@moduloID,'Configurações Gerais','Configurações Gerais Envios','envios-configuracoes-gerais','10', @moduloPaginaID);
update modulos_paginas Set Posicao = 10 where Slug = 'envios-configuracoes-gerais';









