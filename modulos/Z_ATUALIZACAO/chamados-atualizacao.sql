/*
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID,Descr_Tipo,Tipo_Auxiliar)values(106,19,"Venda", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID,Descr_Tipo,Tipo_Auxiliar)values(107,19,"Orçamento", '');
*/

/* atualização somente para eficaz 
select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Periodo X Áreas','Relatório Sintético Período X Total X Área','chamados-relatorio-periodo-areas','7', @subModuloID);
*/


alter table chamados_workflows_produtos
add Descricao_Produto varchar(250) null after Produto_Variacao_ID;

//update tipo set Situacao_ID = 3 where Tipo_ID IN (106,107);

select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Cadastro Orçamento','Cadastrar Orçamento','chamados-orcamento','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Orçamentos','Localizar Orçamento Cadastrados','chamados-orcamento-localizar','0');


select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'chamados-configuracoes' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID, Tipo_Grupo_ID)values(@moduloID,'Situações Orçamento','Gerenciar Situações do Orçamento','chamados-orcamento-situacoes','5', @subModuloID, '51');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Configurações Gerais','Configurações Gerais','chamados-configuracoes-gerais','1000', @subModuloID);

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (51, 'Situação Orçamentos', 1);
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(110,51,"Aberto", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(111,51,"Re-Aberto", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(112,51,"Cancelado", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(113,51,"Fechado", '');

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (52, 'Configurações', 1);
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(106,52,'','');
update tipo set Tipo_Grupo_ID = 52, Descr_Tipo = '', Tipo_Auxiliar = '' where Tipo_ID = 106;

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (53, 'Situação Propostas', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (54, 'Situação Item Proposta', 1);

INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(114,53,"Em aberto", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(115,53,"Enviado para pré-seleção de produtos e serviços", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(116,53,"Seleção de itens recebida", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(117,53,"Aguardando aprovação interna", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(118,53,"Aprovada internamente", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(119,53,"Recusado internamente", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(120,53,"Aguardando aprovação cliente", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(121,53,"Aprovada pelo cliente", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(122,53,"Recusado pelo cliente", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(123,54,"Item Enviado para confirmação", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(124,54,"Item Selecionado", '');
INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(125,54,"Item Recusado", '');

INSERT IGNORE INTO `tipo` (Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar)values(141,53,"Proposta Aprovada (Finalizada)", '');


select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Aprovar / Recusar - Orçamentos','Acesso para Aprovar / Recusar - Orçamentos','chamados-orcamentos-aprovar-recusar','', 1);



CREATE TABLE `orcamentos_workflows` (
	`Workflow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Empresa_ID` INT(10) NULL DEFAULT NULL COMMENT 'Cadastro da empresa para qual esta se abrindo o workflow',
	`Solicitante_ID` INT(10) NULL DEFAULT NULL,
	`Representante_ID` INT(10) NULL DEFAULT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Codigo` VARCHAR(250) NULL DEFAULT NULL,
	`Titulo` TINYTEXT NULL,
	`Data_Abertura` DATETIME NULL DEFAULT NULL,
	`Data_Finalizado` DATETIME NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Workflow_ID`),
	INDEX `idx_orcamentos_workflows_01` (`Workflow_ID`),
	INDEX `idx_orcamentos_workflows_02` (`Empresa_ID`),
	INDEX `idx_orcamentos_workflows_03` (`Solicitante_ID`),
	INDEX `idx_orcamentos_workflows_04` (`Representante_ID`),
	INDEX `idx_orcamentos_workflows_05` (`Situacao_ID`),
	INDEX `idx_orcamentos_workflows_06` (`Codigo`),
	INDEX `idx_orcamentos_workflows_07` (`Data_Abertura`),
	INDEX `idx_orcamentos_workflows_08` (`Data_Finalizado`),
	INDEX `idx_orcamentos_workflows_09` (`Solicitante_ID`, `Data_Abertura`, `Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `orcamentos_follows` (
	`Follow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Workflow_ID` INT(10) NOT NULL,
	`Descricao` MEDIUMTEXT NULL,
	`Dados` MEDIUMTEXT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Follow_ID`),
	INDEX `idx_orcamentos_follows_01` (`Follow_ID`),
	INDEX `idx_orcamentos_follows_02` (`Workflow_ID`),
	INDEX `idx_orcamentos_follows_03` (`Situacao_ID`),
	INDEX `idx_orcamentos_follows_04` (`Data_Cadastro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `orcamentos_propostas` (
	`Proposta_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Workflow_ID` INT(10) NOT NULL,
	`Titulo` VARCHAR(250) NULL DEFAULT NULL,
	`Tabela_Preco_ID` INT(11) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NULL DEFAULT NULL,
	`Status_ID` INT(11) NULL DEFAULT '114',
	`Situacao_ID` INT(11) NULL DEFAULT NULL,
	PRIMARY KEY (`Proposta_ID`),
	INDEX `idx_orcamentos_workflows_01` (`Proposta_ID`),
	INDEX `idx_orcamentos_workflows_02` (`Workflow_ID`),
	INDEX `idx_orcamentos_workflows_03` (`Titulo`),
	INDEX `idx_orcamentos_workflows_04` (`Situacao_ID`),
	INDEX `idx_orcamentos_workflows_09` (`Proposta_ID`, `Workflow_ID`, `Titulo`, `Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orcamentos_propostas_follows` (
	`Follow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Proposta_ID` INT(10) NOT NULL,
	`Descricao` MEDIUMTEXT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Follow_ID`),
	INDEX `idx_orcamentos_propostas_follows_01` (`Follow_ID`),
	INDEX `idx_orcamentos_propostas_follows_02` (`Proposta_ID`),
	INDEX `idx_orcamentos_propostas_follows_03` (`Situacao_ID`),
	INDEX `idx_orcamentos_propostas_follows_04` (`Data_Cadastro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `orcamentos_propostas_produtos` (
	`Proposta_Produto_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Proposta_ID` INT(10) NOT NULL,
	`Produto_Variacao_ID` INT(10) NOT NULL,
	`Categoria_ID` INT(10) NULL DEFAULT NULL,
	`Descricao` VARCHAR(2000) NULL DEFAULT NULL,
	`Quantidade` DECIMAL(10,2) NULL DEFAULT NULL,
	`Valor_Custo_Unitario` DECIMAL(10,2) NULL DEFAULT NULL,
	`Valor_Venda_Unitario` DECIMAL(10,2) NULL DEFAULT NULL,
	`Cobranca_Cliente` INT(11) NULL DEFAULT NULL,
	`Pagamento_Prestador` INT(11) NULL DEFAULT NULL,
	`Prestador_ID` INT(11) NULL DEFAULT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Status_ID` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	`Usuario_Alteracao_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Proposta_Produto_ID`),
	INDEX `idx_orcamentos_propostas_produtos_01` (`Proposta_Produto_ID`),
	INDEX `idx_orcamentos_propostas_produtos_02` (`Proposta_ID`),
	INDEX `idx_orcamentos_propostas_produtos_03` (`Produto_Variacao_ID`),
	INDEX `idx_orcamentos_propostas_produtos_04` (`Usuario_Cadastro_ID`),
	INDEX `idx_orcamentos_propostas_produtos_05` (`Situacao_ID`),
	INDEX `idx_orcamentos_propostas_produtos_06` (`Proposta_ID`, `Proposta_Produto_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID`),
	INDEX `idx_orcamentos_propostas_produtos_07` (`Proposta_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID`),
	INDEX `idx_orcamentos_propostas_produtos_08` (`Proposta_ID`, `Produto_Variacao_ID`, `Situacao_ID`),
	INDEX `idx_orcamentos_propostas_produtos_09` (`Categoria_ID`, `Situacao_ID`),
	INDEX `idx_orcamentos_propostas_produtos_10` (`Proposta_ID`, `Proposta_Produto_ID`, `Produto_Variacao_ID`, `Usuario_Cadastro_ID`, `Situacao_ID`, `Categoria_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `orcamentos_produtos_chamados_produtos` (
	`Orcamento_Produto_Chamado_Produto_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Proposta_Produto_ID` INT(10) NOT NULL,
	`Chamado_Produto_ID` INT(10) NOT NULL,
	`Chamado_ID` INT(10) NOT NULL,
	`Orcamento_ID` INT(10) NOT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Orcamento_Produto_Chamado_Produto_ID`),
	INDEX `idx_orcamentos_produtos_chamados_produtos_01` (`Orcamento_Produto_Chamado_Produto_ID`),
	INDEX `idx_orcamentos_produtos_chamados_produtos_02` (`Proposta_Produto_ID`),
	INDEX `idx_orcamentos_produtos_chamados_produtos_03` (`Chamado_Produto_ID`),
	INDEX `idx_orcamentos_produtos_chamados_produtos_04` (`Situacao_ID`),
	INDEX `idx_orcamentos_produtos_chamados_produtos_05` (`Proposta_Produto_ID`, `Chamado_Produto_ID`, `Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE `chamados_workflows_produtos`
	ADD COLUMN `Prestador_ID` INT(10) NULL DEFAULT NULL AFTER `Pagamento_Prestador`;

ALTER TABLE `chamados_workflows_produtos`
	CHANGE COLUMN `Data_Cadastro` `Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `Situacao_ID`;


update tipo set Situacao_ID = 2 where Tipo_ID IN (73);



select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Chamados X CD - Logistica','Relatório resumo de chamados relacionados a logistica','chamados-relatorio-resumo','8', @subModuloID);


select Modulo_ID from modulos where Slug = 'chamados' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'relatorio-chamados' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Atendimentos','Relatório resumo atendimentos','chamados-relatorio-produtos-atendimentos','9', @subModuloID);


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
COLLATE='utf8'
ENGINE=InnoDB;




CREATE TABLE `orcamentos_chamados` (
	`Orcamento_Chamado_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Orcamento_ID` INT(10) NOT NULL,
	`Chamado_ID` INT(10) NOT NULL,
	`Situacao_ID` INT(10) NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`Orcamento_Chamado_ID`),
	INDEX `idx_orcamentos_chamados_01` (`Orcamento_Chamado_ID`),
	INDEX `idx_orcamentos_chamados_02` (`Orcamento_ID`),
	INDEX `idx_orcamentos_chamados_03` (`Chamado_ID`),
	INDEX `idx_orcamentos_chamados_04` (`Situacao_ID`),
	INDEX `idx_orcamentos_chamados_05` (`Orcamento_ID`, `Chamado_ID`, `Situacao_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


ALTER TABLE `chamados_workflows_produtos`
	ADD COLUMN `Cliente_Final_ID` INT(10) NULL DEFAULT NULL AFTER `Prestador_ID`;
