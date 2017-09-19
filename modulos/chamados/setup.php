/*
	Titulo: Controle de Chamados
	Slug: chamados
	Descricao: M�dulo para controle de chamados e workflow
	Versao: 1.0
	Data: 03/10/2013

	query:  insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values('[modulo-id]','Cadastrar Chamado','Cadastrar Chamado','chamados-cadastro-chamado','0','1');
	query:  insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Chamados','Localizar Chamados Cadastrados','chamados-localizar-chamado','1');
	query:  insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Relat�rios','Gerar Relat�rios dos Chamados','relatorio-chamados','2');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Relat�rio Din�mico','Relat�rios Din�micos','chamados-relatorio-dinamico','1','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Situa��o Geral','Relat�rio sint�tico com a situa��o geral de todos os chamados cadastrados','chamados-relatorio-geral','2','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Regional','Relat�rio Chamados por Estado','chamados-relatorio-regional','3','[sub-modulo-id]');

	query:  insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Configura��es Chamados','Configura��es Chamados','chamados-configuracoes','3');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Gerenciar Situa��es','Gerenciar Situa��es de Chamado','chamados-situacoes','1','18','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Tipo Chamado','Gerenciar Tipo de Chamado','chamados-tipos','2','19','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values('[modulo-id]','Gerenciar Prioridades','Gerenciar Prioridades','chamados-prioridades','3','21','[sub-modulo-id]');

//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','�reas','Cadastros de �reas para os chamados','chamados-area',4);
//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Grupos','Cadastros de grupos para os chamados','chamados-grupo',5);
//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','�tens','Cadastros de �tens para os chamados','chamados-item','6');
//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Sub-�tens','Cadastros de sub-�tens para os chamados','chamados-sub-item','7');
//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','A��es','Cadastros de a��es para os chamados','chamados-acao','8');
//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','SLA','Cadastros de sla para os chamados','chamados-sla','9');

//	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Gerenciar Campos','Cadastrar, alterar e excluir campos do formul�rio de chamados','chamados-campos','10');
//	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Gerenciar Formul�rios','Cria��o e altera��o de formul�rios','chamados-campos-formularios','1','[sub-modulo-id]');
//	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Vincular Campos','Cria��o e altera��o grupos','chamados-campos-vincula','2','[sub-modulo-id]');
//	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Novo Campo','Cria��o e altera��o de campo','chamados-novo-campo','3','[sub-modulo-id]');





























	Ativado:06/04/2014 14:26
	Ativado:07/04/2014 10:03
	Ativado:07/04/2014 10:48
	Ativado:22/04/2014 13:53
*/