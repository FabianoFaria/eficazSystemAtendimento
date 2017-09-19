
select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Laboratorio','laboratorio','Módulo Laboratório',@posicao,'1.5');
select Modulo_ID from modulos where Slug='laboratorio' into @moduloID;
insert into modulos_paginas (Modulo_ID, Titulo,Descricao, Slug,Posicao) values (@moduloID,'Materiais para Análise','Listagem de materias para analise','laboratorio-materiais-analise','1');


CREATE TABLE IF NOT EXISTS `laboratorio_produtos` (
	`Laboratorio_Produto_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Produto_Movimentacao_ID` INT(10) NOT NULL DEFAULT '0',
	`Produto_Variacao_ID` INT(10) NOT NULL DEFAULT '0',
	`Status_ID` INT(11) NOT NULL DEFAULT '131',
	`Tecnico_Responsavel_ID` INT(11) NOT NULL DEFAULT '0',
	`Descricao` TEXT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL DEFAULT '0',
	`Situacao_ID` INT(3) NOT NULL DEFAULT '1',
	PRIMARY KEY (`Laboratorio_Produto_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `laboratorio_produtos_follows` (
	`Laboratorio_Follow_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Laboratorio_Produto_ID` INT(10) NOT NULL DEFAULT '0',
	`Status_ID` INT(11) NOT NULL DEFAULT '131',
	`Descricao` TEXT NULL,
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT NOT NULL DEFAULT '0',
	`Situacao_ID` INT(3) NOT NULL DEFAULT '1',
	PRIMARY KEY (`Laboratorio_Follow_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



select Modulo_ID from modulos where Slug = 'laboratorio' into @moduloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Oculta_Menu) values (@moduloID,'Análise Laboratório','Gerenciar Análise de Material Laboratório','laboratorio-material-analise','0','1');
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao) values (@moduloID,'Gerenciar Módulo','Gerenciar Módulo Laboratório','laboratorio-gerenciar-modulo','2');
select Modulo_Pagina_ID from modulos_paginas where Slug = 'laboratorio-gerenciar-modulo' into @moduloPaginaID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Tipo_Grupo_ID, Pagina_Pai_ID) values (@moduloID,'Situação Material','Gerenciar Situações do Material','laboratorio-situacao-material','1', '60', @moduloPaginaID);


INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (60, 'Situação Material Laboratório', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (131, 60, 'Aguardando Análise', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (132, 60, 'Recuperado', '', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (133, 60, 'Descartado', '', 1);

