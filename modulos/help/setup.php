/*
	Titulo: Ajuda
	Slug: help
	Descricao: Módulo para gerenciamento dos menus de ajuda do sistema
	Versao: 1.0
	Data: 27/05/2014

	query: CREATE TABLE help(Help_ID INT(11) NOT NULL AUTO_INCREMENT, Modulo_ID INT(11) NOT NULL, Modulo_Pagina_ID INT(11) NOT NULL, Titulo varchar(150) NOT NULL, Descricao TEXT NOT NULL, Data_Cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, Situacao_ID INT(11) NOT NULL DEFAULT '1', PRIMARY KEY (Help_ID));
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Gerenciar Ajuda','Gerenciar Ajuda','gerenciar-help','1');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Oculta_Menu)values('[modulo-id]','Cadastrar nova Ajuda','Cadastrar nova Ajuda','cadastrar-help','1','1');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Oculta_Menu)values('[modulo-id]','Atualizar Ajuda','Atualizar Ajuda','atualizar-help','1','1');
	Ativado:29/05/2014 08:49
*/