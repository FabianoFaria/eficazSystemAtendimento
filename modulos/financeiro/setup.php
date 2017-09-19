/*
	Titulo: Financeiro
	Slug: financeiro
	Descricao: M�dulo para controle financeiro e contas a pagar e receber
	Versao: 1.0
	Data: 01/02/2014

	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Contas','Gerenciamento de contas a pagar e a receber','financeiro-contas','1');
	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Lan�amento','Lan�amentos de contas a pagar e a receber','financeiro-lancamento','2');
	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Aguardando Faturamento','Aguardando Faturamento','financeiro-aguardando-faturamento','2');
	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Relat�rios','Relat�rios Financeiros','financeiro-relatorio','3');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Resumo','Relat�rio Resumo Geral por per�odo','financeiro-relatorio-resumo-geral','1','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Totais Entradas X Sa�das','Relat�rio por per�odo de Entradas X Sa�das','financeiro-relatorio-periodo-entradas-saidas','2','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Entradas e Sa�das','Relat�rio Anal�tico de Entradas e Sa�das','financeiro-relatorio-entradas-saidas','2','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Transfer�ncias','Relat�rio de Controle de Transfer�ncias','financeiro-relatorio-transferencias','5','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Demonstra��o Financeira','Relat�rio de Demonstra��o Financeira','financeiro-relatorio-demonstracao-financeira','6','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Fluxo de Caixa','Relat�rio de Fluxo de Caixa','financeiro-relatorio-fluxo-caixa','7','[sub-modulo-id]');
	query: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values('[modulo-id]','Gerenciar M�dulo','Gerenciar M�dulo','financeiro-gerenciar','4');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values('[modulo-id]','Tipos de Conta','Gerenciar Tipos de Conta','financeiro-tipo-conta','1','[sub-modulo-id]','28');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID)values('[modulo-id]','Contas Banc�rias','Gerenciar Contas Banc�rias','financeiro-contas-bancarias','3','[sub-modulo-id]');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values('[modulo-id]','Centros de Custo','Gerenciar Centros de Custo','financeiro-centro-custo','2','[sub-modulo-id]','26');
	queryS: insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Pagina_Pai_ID,Tipo_Grupo_ID)values('[modulo-id]','Formas de Pagamento','Gerenciar Formas de Pagamento','financeiro-formas-pagamento','4','[sub-modulo-id]','25');

	//query: insert into tipo (Tipo_Grupo_ID, Descr_Tipo, Situacao_ID) VALUES (25, 'Dinhero', 1), (25, 'D�bito', 1), (25, 'Cr�dito', 1), (25, 'Cheque', 1), (25, 'Dep�sito', 1), (25, 'DOC', 1), (25, 'TED', 1);


	Ativado:16/04/2014 14:54
	Ativado:22/04/2014 13:53
*/

