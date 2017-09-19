CREATE TABLE IF NOT EXISTS `telemarketing_follows` (
  `Follow_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Workflow_ID` int(10) NOT NULL,
  `Descricao` mediumtext,
  `Dados` mediumtext,
  `Situacao_ID` int(10) DEFAULT NULL,
  `Motivo_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Responsabilidade_ID` int(10) DEFAULT NULL,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Follow_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `telemarketing_workflows` (
  `Workflow_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Tipo_Workflow_ID` int(10) DEFAULT NULL,
  `Codigo` varchar(50) DEFAULT NULL,
  `Solicitante_ID` int(10) DEFAULT NULL,
  `Fornecedor_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`Workflow_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `telemarketing_workflows_produtos` (
  `Workflow_Produto_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Workflow_ID` int(10) NOT NULL,
  `Produto_Variacao_ID` int(10) NOT NULL,
  `Quantidade` int(10) DEFAULT '0',
  `Valor_Custo_Unitario` int(10) DEFAULT '0',
  `Valor_Venda_Unitario` int(10) DEFAULT '0',
  `Situacao_ID` int(10) NOT NULL DEFAULT '1',
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  `Auxiliar` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Workflow_Produto_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (22, 'Situações Telemarketing', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (23, 'Motivos Telemarketing', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (24, 'Processos Telemarketing', 1);

INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (37, 24, 'Pedido', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (39, 22, 'Aguardando Pagamento', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (40, 22, 'Cancelado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (41, 22, 'Finalizado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (42, 22, 'Pagamento Efetuado', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (43, 23, 'Outros', NULL, 1);


select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Descricao, Slug, Posicao, Versao)values('Tele Marketing','telemarketing','Módulo para controle de workflows de telemarketing',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Localizar Pedidos','Localizar Pedidos','telemarketing-pedidos','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Oculta_Menu)values(@moduloID,'Cadastro','Cadastro','telemarketing-cadastro','2','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Pedido','Pedido','telemarketing-pedido','3','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Localizar Instaladores','Localizar Instaladores','telemarketing-localiza-instaladores','4');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Relatórios','Relatórios Gerais','telemarketing-relatorios','5');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Geral Dinâmico','Relatório Dinâmico','telemarketing-relatorio-dinamico','1',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Individual Operadores','Relatório Dinâmico','telemarketing-relatorio-operadores','2',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values(@moduloID,'Controle de Prazos','Relatório de controle de Prazos','telemarketing-relatorio-controle-prazos','3',@subModuloID);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Configurações Gerais','Configurações Gerais','telemarketing-configuracoes','6');
	select LAST_INSERT_ID() into @subModuloID;
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Gerenciar Situações','Gerenciar Situações do Pedido','telemarketing-situacoes','1','22',@subModuloID);
	insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Gerenciar Motivos','Gerenciar Motivos de Chamado','telemarketing-motivos','2','23',@subModuloID);
