INSERT INTO tipo(Tipo_Grupo_ID, Tipo_ID, Descr_Tipo)values(9,108,'Aluno');
INSERT INTO tipo(Tipo_Grupo_ID, Tipo_ID, Descr_Tipo, Situacao_ID)values(12,109,'Aluno',3);

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (46, 'Instituições', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (47, 'Campus', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (48, 'Cursos', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (49, 'Periodos', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (50, 'Cargos', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (55, 'Turnos', 1);

select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;

insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Turmas','turmas','Módulo para gerenciamento de turmas',@posicao,'1.0');
select LAST_INSERT_ID() into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Turmas Cadastradas','Turmas Cadastradas','turmas-localizar-turma','1','0');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Cadastrar Nova Turma','Cadastrar Nova Turma','turmas-cadastrar-turma','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Gerenciar Turmas','Gerenciar Turmas','turmas-gerenciar-turma','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo','turmas-gerenciar-modulo','2','0');

select LAST_INSERT_ID() into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Instituições','Instituições','turmas-gerenciar-instituicao','1','46',@subModuloID );
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Campus','Campus','turmas-gerenciar-campus','2','47',@subModuloID );
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Cursos','Cursos','turmas-gerenciar-cursos','3','48',@subModuloID );
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Periodos','Periodos','turmas-gerenciar-periodos','4','49',@subModuloID );
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Turnos','Turnos','turmas-gerenciar-turnos','5','55',@subModuloID );
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Cargos','Cargos','turmas-gerenciar-cargos','6','50',@subModuloID );


CREATE TABLE IF NOT EXISTS `turmas_dados` (
  `Turma_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Cadastro_ID` int(11) NOT NULL,
  `Codigo` varchar(30) NOT NULL,
  `Nome_Turma` varchar(250) NOT NULL,
  `Instituicao_ID` int(10) NOT NULL,
  `Campus_ID` int(10) DEFAULT NULL,
  `Curso_ID` int(10) DEFAULT NULL,
  `Periodo_ID` int(10) DEFAULT NULL,
  `Turno_ID` int(10) DEFAULT NULL,
  `Quantidade` int(10) DEFAULT NULL,
  `Responsavel_ID` int(10) DEFAULT NULL,
  `Usuario_ID` int(10) DEFAULT NULL,
  `Situacao_ID` int(10) NOT NULL DEFAULT '1',
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Turma_ID`),
  KEY `idx_turmas_01` (`Instituicao_ID`,`Campus_ID`,`Curso_ID`,`Periodo_ID`),
  KEY `Cadastro_ID` (`Cadastro_ID`),
  KEY `Cadastro_ID_Instituicao_ID_Campus_ID_Curso_ID_Periodo_ID` (`Cadastro_ID`,`Instituicao_ID`,`Campus_ID`,`Curso_ID`,`Periodo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `turmas_dados_alunos` (
  `Turma_Aluno_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Turma_ID` int(10) NOT NULL,
  `Cadastro_ID` int(10) NOT NULL,
  `Situacao_ID` int(10) NOT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_ID` int(10) NOT NULL,
  `Data_Alteracao` datetime DEFAULT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Turma_Aluno_ID`),
  KEY `idx_turmas_alunos_01` (`Turma_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `turmas_eventos` (
  `Evento_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Turma_ID` int(10) NOT NULL,
  `Tipo_Evento_ID` int(10) NOT NULL,
  `Local_Evento_ID` int(10) NOT NULL,
  `Descricao` varchar(250) NOT NULL,
  `Data_Evento` datetime NOT NULL,
  `Situacao_ID` int(10) NOT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  `Data_Alteracao` datetime DEFAULT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Evento_ID`),
  KEY `idx_turmas_eventos_01` (`Evento_ID`,`Turma_ID`,`Tipo_Evento_ID`,`Situacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (57, 'Tipos de Eventos', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (58, 'Locais de Eventos', 1);

select Modulo_ID from modulos where Slug = 'turmas' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'turmas-gerenciar-modulo' into @subModuloID;
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Tipo_Grupo_ID, Pagina_Pai_ID) values (@moduloID, 'Tipos de Evento','Gerenciar Tipos de Evento','turmas-gerenciar-tipos-eventos','7','57', @subModuloID);
insert into modulos_paginas (Modulo_ID, Titulo, Descricao, Slug, Posicao, Tipo_Grupo_ID, Pagina_Pai_ID) values (@moduloID, 'Locais de Evento','Gerenciar Locais de Evento','turmas-gerenciar-locais-eventos','8','58', @subModuloID);



ALTER TABLE `turmas_eventos`
	ADD COLUMN `Participantes` INT(10) NOT NULL AFTER `Descricao`;



CREATE TABLE IF NOT EXISTS `turmas_projecoes` (
  `Projecao_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Proposta_ID` int(10) NOT NULL,
  `Situacao_ID` int(10) NOT NULL,
  `Dados` TEXT NOT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(10) DEFAULT NULL,
  `Data_Alteracao` datetime DEFAULT NULL,
  `Usuario_Alteracao_ID` int(10) DEFAULT NULL,
  PRIMARY KEY (`Projecao_ID`)
);
