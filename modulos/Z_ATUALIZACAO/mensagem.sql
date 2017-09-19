CREATE TABLE IF NOT EXISTS `mensagens` (
  `Mensagem_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Remetente_ID` int(10) DEFAULT NULL,
  `Destinatarios` varchar(250) DEFAULT NULL,
  `Destinatarios_Copia` varchar(250) DEFAULT NULL,
  `Assunto` varchar(250) DEFAULT NULL,
  `Mensagem` text,
  `Destinatarios_Leitura` text,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Situacao_Mensagem_ID` int(11) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT '1',
  `Mensagem_Pai_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`Mensagem_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (35, 'Situação Mensagens', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (68, 35, 'Mensagens Enviadas', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (69, 35, 'Mensagens Salvas', NULL, 1);

select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Mensagens','mensagens','Módulo para gerenciamento de mensagens',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;


insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Recebidas','Mensagens recebidas','mensagem-recebida','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Enviadas','Mensagens enviadas','mensagem-enviada','2');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Rascunhos','mensagems salvas em rascunho','mensagem-rascunho','3');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Compor Nova','Compor nova mensagem','mensagem-nova','4');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Oculta_Menu)values(@moduloID,'Visualizar Mensagem','Visualizar Mensagens','mensagem-visualizar','0',1);
