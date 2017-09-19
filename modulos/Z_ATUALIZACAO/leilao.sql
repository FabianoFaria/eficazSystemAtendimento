select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao) values('Leilão','leilao','Módulo para gerenciamento de leilões',@posicao,'1.5');
select Modulo_ID from modulos where Slug = 'leilao' into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Dados Leilão','Dados do gerais do Leilão','leilao-cadastro','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID, 'Leilões', 'Localizar Leilões Cadastrados','leilao-localiza','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Dados Plano','Dados do gerais do Plano','leilao-plano-cadastro','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID, 'Planos', 'Gerenciar Planos / Pacotes','leilao-plano-localiza','2');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID, 'Relatórios', 'Relatórios módulo de Leilão','leilao-relatorios','3');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID, 'Gerenciar Modulo','Gerenciar Modulo de Leilão','leilao-gerencia-modulo','4');

select Modulo_Pagina_ID from modulos_paginas where Slug = 'leilao-gerencia-modulo' into @paginaPaiID;
insert into modulos_paginas(Modulo_ID, Pagina_Pai_ID, Titulo,Descricao,Slug,Posicao)values(@moduloID, @paginaPaiID, 'Configurações Gerais','Configurações Gerais de Leilão','leilao-configuracoes-gerais','1');


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (61, 'Situação Leilão', 1);
INSERT INTO `tipo` (`Tipo_Grupo_ID`, `Tipo_ID`, `Descr_Tipo`, `Situacao_ID`) VALUES (61, 134, 'Rascunho', 1);
INSERT INTO `tipo` (`Tipo_Grupo_ID`, `Tipo_ID`, `Descr_Tipo`, `Situacao_ID`) VALUES (61, 135, 'Publicado', 1);
INSERT INTO `tipo` (`Tipo_Grupo_ID`, `Tipo_ID`, `Descr_Tipo`, `Situacao_ID`) VALUES (61, 136, 'Finalizado', 1);
INSERT INTO `tipo` (`Tipo_Grupo_ID`, `Tipo_ID`, `Descr_Tipo`, `Situacao_ID`) VALUES (61, 137, 'Confirmado', 1);
INSERT INTO `tipo` (`Tipo_Grupo_ID`, `Tipo_ID`, `Descr_Tipo`, `Situacao_ID`) VALUES (61, 138, 'Lote sem lances', 3);

INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Situacao_ID`) VALUES (139, 13, 'Planos / Pacotes', 3);

CREATE TABLE IF NOT EXISTS `leiloes_dados` (
  `Leilao_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Empresa_ID` int(11) NOT NULL,
  `Titulo` varchar(250) NOT NULL,
  `Lance_Aberto` binary(1) NOT NULL DEFAULT '0',
  `Descricao` text NOT NULL,
  `Data_Leilao` datetime DEFAULT NULL,
  `Tempo_Duracao_Inicial` time DEFAULT '00:00:00',
  `Tempo_Renovacao_Lance` time DEFAULT '00:00:00',
  `Valor_Lance` decimal(15,2) DEFAULT '0.00',
  `Situacao_ID` int(11) NOT NULL DEFAULT '134',
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Leilao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS `leiloes_lotes` (
  `Leilao_Lote_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Leilao_ID` int(11) NOT NULL,
  `Produto_Variacao_ID` int(11) NOT NULL,
  `Quantidade` int(11) NOT NULL DEFAULT '0',
  `Valor_Inicial` decimal(15,2) NOT NULL DEFAULT '0.00',
  `Valor_Lance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `Descricao` text NOT NULL,
  `Data_Inicio` datetime DEFAULT NULL,
  `Data_Fim` datetime DEFAULT NULL,
  `Ordem` int(11) NOT NULL DEFAULT '0',
  `Situacao_ID` int(11) NOT NULL DEFAULT '134',
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Leilao_Lote_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




CREATE TABLE IF NOT EXISTS `leiloes_lotes_lances` (
  `Lance_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Leilao_Lote_ID` int(11) NOT NULL,
  `Valor_Lance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `Usuario_Lance_ID` int(11) NOT NULL,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  `Situacao_ID` int(11) NOT NULL DEFAULT '1',
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Lance_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

