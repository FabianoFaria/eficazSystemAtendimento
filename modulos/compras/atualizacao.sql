
ALTER TABLE `compras_ordem_compra`
	ADD COLUMN `Observacao` TEXT NULL DEFAULT '' AFTER `Data_Limite_Retorno`;
	
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (43, 'Emails Compras', 1);

select Modulo_ID from modulos where Slug = 'compras' into @moduloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao)values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo','compras-gerenciar-modulo', '3');
select Modulo_Pagina_ID from modulos_paginas where Slug = 'compras-gerenciar-modulo' into @subModuloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Tipo_Grupo_ID, Pagina_Pai_ID) values(@moduloID,'Emails Compras','Gerenciar emails do Compras','compras-emails-compras','1','43',@subModuloID);


/*****************/	
update modulos_paginas set posicao = 4 where Slug = 'compras-gerenciar-modulo' and posicao = 3;
select Modulo_ID from modulos where Slug = 'compras' into @moduloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao)values(@moduloID,'Relatórios','Relatórios Compras','compras-relatorios', '3');
select Modulo_Pagina_ID from modulos_paginas where Slug = 'compras-relatorios' into @subModuloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID,'Dinâmico','Relatório Dinâmico de Compras','compras-relatorio-dinamico','1',@subModuloID);


	
	
