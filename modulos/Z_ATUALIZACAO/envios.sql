CREATE TABLE IF NOT EXISTS `envios_follows` (
  `Follow_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Workflow_ID` int(10) NOT NULL,
  `Descricao` mediumtext,
  `Situacao_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Follow_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `envios_workflows` (
  `Workflow_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Cadastro_ID_de` int(10) DEFAULT NULL,
  `Cadastro_ID_de_Endereco` int(10) DEFAULT NULL,
  `Cadastro_ID_para` int(10) DEFAULT NULL,
  `Cadastro_ID_para_Endereco` int(10) DEFAULT NULL,
  `Transportadora_ID` int(10) DEFAULT NULL,
  `Tabela_Estrangeira` varchar(100) DEFAULT NULL,
  `Chave_Estrangeira` int(10) DEFAULT NULL,
  `Forma_Envio_ID` int(10) DEFAULT NULL,
  `Codigo_Rastreamento` varchar(250) DEFAULT NULL,
  `Valor_Transporte` decimal(12,2) DEFAULT NULL,
  `Data_Envio` datetime DEFAULT NULL,
  `Data_Previsao` datetime DEFAULT NULL,
  `Data_Entrega` datetime DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  `ID_Servico_Envio` int(11) DEFAULT NULL,
  PRIMARY KEY (`Workflow_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `envios_workflows_produtos` (
  `Workflow_Produto_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Workflow_ID` int(10) NOT NULL,
  `Produto_Variacao_ID` int(10) NOT NULL,
  `Quantidade` decimal(10,2) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Situacao_ID` int(10) DEFAULT '1',
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Workflow_Produto_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (30, 'Situação de Envio', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (31, 'Forma de Envio', 1);

INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (52, 30, 'Enviado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (53, 30, 'Entregue', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (54, 31, 'Correio', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (55, 31, 'Transportadora', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (56, 30, 'Solicitação de Envio', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (57, 30, 'Solicitação de Retirada', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (59, 30, 'Cancelado', NULL, 1);


select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Centro de Distribuição','envios','Módulo de Distribuição e Logística',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Cadastrar Envio','Cadastrar Envio','envios-cadastro','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Controle de Envios','Localizar Envios Cadastrados','envios-localizar','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Configurações Envios','Configurações Envios','envios-configuracoes','2');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Gerenciar Situações','Gerenciar Situações de Envio','envios-situacoes','1','30',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Gerenciar Formas de Envio','Gerenciar Formas de Envio','envios-formas-envios','2','31',@subModuloID);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Relatórios','Relatórios','envios-relatorios','3');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Pagina_Pai_ID)values(@moduloID,'Relatório Geral','Relatório Geral','envios-relatorio-geral','1',@subModuloID);



