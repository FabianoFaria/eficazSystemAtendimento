CREATE TABLE IF NOT EXISTS `faq_perguntas` (
  `Pergunta_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Descricao` varchar(2000) NOT NULL,
  `Situacao_ID` int(11) NOT NULL,
  `Ordem` int(11) NOT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Pergunta_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `faq_respostas` (
  `Resposta_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Pergunta_ID` int(10) NOT NULL,
  `Descricao` varchar(2000) NOT NULL,
  `Ordem` int(11) NOT NULL,
  `Situacao_ID` int(11) NOT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Resposta_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Faq','faq','Módulo para controle de FAQ (Perguntas e respostas)',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar FAQ','Gerenciamento de perguntas e respostas','faq-gerenciar','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Pergunta','Gerenciamento de perguntas','faq-gerenciar-pergunta','1');

