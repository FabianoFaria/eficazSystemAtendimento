/*
	Titulo: Cadastros
	Slug: cadastros
	Descricao: Módulo para Cadastros de Clientes, Fornecedores e prestadores de serviço
	Versao: 1.0
	Data: 08/10/2013

	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Cadastros','Localizar cadastros de Clientes, fornecedores e prestadores','cadastro-localiza','1');
	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_menu)values('[modulo-id]','Cadastro','Cadastro de Clientes, fornecedores e prestadores','cadastro-dados','2','1');

	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Cadastros Tipos','Gerenciar Tipos dos Cadastros','cadastros-tipos','3');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Unidades','Gerenciar Unidade','cadastro-unidade','1','15','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Departamentos','Gerenciar Departamentos','cadastro-departamento','2','16','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Setores','Gerenciar Setores','cadastro-setor','3','17','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Tipo Vinculo','Gerenciar Tipos de Vinculos','cadastro-vinculo','4','12','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Tipo Cadastro','Gerenciar Tipos de Cadastros','cadastro-tipo','5','9','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Tipo Endereço','Gerenciar Tipos de Endereços','cadastro-endereco','6','10','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Tipo Telefone','Gerenciar Tipos de Telefones','cadastro-telefone','7','11','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Áreas de Atuação','Gerenciar Áreas de Atuação','cadastro-areas-atuacoes','8','34','[sub-modulo-id]');

//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Gerenciar Documentos','Gerenciar Documentos','cadastro-gerenciar-documentos','3');
	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Relatórios','Relatórios','cadastros-relatorios','4');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Pagina_Pai_ID)values('[modulo-id]','Relatório Regional','Relatório Regional','cadastro-relatorio-regional','1','[sub-modulo-id]');

	Ativado:07/04/2014 10:03
	Ativado:18/04/2014 11:05
	Ativado:22/04/2014 13:53
	Ativado:07/05/2014 19:04
	Ativado:07/05/2014 19:04
	Ativado:07/05/2014 19:04
	Ativado:07/05/2014 19:04
*/