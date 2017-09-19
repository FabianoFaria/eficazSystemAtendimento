delete from tipo_grupo where tipo_grupo_ID in (44,45);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (44, 'Tempo de Atendimento', 1);
INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (45, 'Regionais', 1);

ALTER TABLE `cadastros_dados`
	ADD COLUMN `Regional_ID` INT NOT NULL DEFAULT '0' AFTER `Areas_Atuacoes`;


select Modulo_ID from modulos where Slug = 'cadastros' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'cadastros-tipos' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Pagina_Pai_ID)values(@moduloID,'Regional','Cadastro de Regionais','cadastro-regional','10','45', @subModuloID);



ALTER TABLE `cadastros_vinculos` ADD INDEX `Cadastro_ID` (`Cadastro_ID`);
ALTER TABLE `cadastros_vinculos` ADD INDEX `Cadastro_ID_Cadastro_Filho_ID` (`Cadastro_ID`, `Cadastro_Filho_ID`);



select Modulo_ID from modulos where Slug = 'cadastros' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'cadastros-tipos' into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID,Pagina_Pai_ID)values(@moduloID,'Gerenciar Módulo','Gerenciar Módulo Cadastros','cadastro-gerenciar-modulo','100','', @subModuloID);


ALTER TABLE `cadastros_telefones`
	ADD COLUMN `Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `Situacao_ID`;


ALTER TABLE `cadastros_enderecos`
	ADD COLUMN `Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `Situacao_ID`;




select Modulo_ID from modulos where Slug = 'cadastros' into @moduloID;
select Modulo_Pagina_ID from modulos_paginas where Slug = 'cadastros-tipos' into @subModuloID;
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID, 'Configurações','Configurações Gerais Módulo de Cadastros','cadastro-gerenciar-modulo','100',@subModuloID);
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID, 'Importar','Importar cadastros em massa','cadastro-importar','101',@subModuloID);
insert into modulos_paginas(Modulo_ID, Titulo, Descricao, Slug, Posicao, Pagina_Pai_ID) values(@moduloID, ' Exportar','Exportar cadastros em massa','cadastro-exportar','102',@subModuloID);


ALTER TABLE `cadastros_dados` ADD INDEX `idx_cadastros_dados_07` (`Cpf_Cnpj`);
