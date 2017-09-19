select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao) values('Site','site','Módulo de integração com Sites e Lojas Virtuais',@posicao,'1.5');

select Modulo_ID from modulos where Slug = 'site' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Gerenciar Sites','Gerenciador e configurador de sites','site-gerenciador','1');


CREATE TABLE `sites_dados` (
	`Site_ID` INT(11) NOT NULL AUTO_INCREMENT,
	`Empresa_ID` INT(11) NOT NULL,
	`URL` VARCHAR(500) NOT NULL,
	`Dados` TEXT NOT NULL,
	`Situacao_ID` INT(11) NOT NULL DEFAULT '1',
	`Data_Cadastro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Cadastro_ID` INT(11) NOT NULL,
	PRIMARY KEY (`Site_ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


INSERT INTO `modulos_acessos` (`Modulo_Acesso_ID`, `Titulo`, `Acessos`, `Situacao_ID`) VALUES (-4, 'Site','', 1);


delimiter //
CREATE FUNCTION fnCriaSlug(texto TEXT)
	RETURNS TEXT
	LANGUAGE SQL
	DETERMINISTIC
	CONTAINS SQL
	SQL SECURITY DEFINER
BEGIN
	set texto =  trim(lower(texto));
	set texto =  replace(texto,'à','a');
	set texto =  replace(texto,'À','a');
	set texto =  replace(texto,'Á','a');
	set texto =  replace(texto,'á','a');
	set texto =  replace(texto,'Â','a');
	set texto =  replace(texto,'â','a');
	set texto =  replace(texto,'Ã','a');
	set texto =  replace(texto,'ã','a');
	set texto =  replace(texto,'Ç','c');
	set texto =  replace(texto,'ç','c');
	set texto =  replace(texto,'È','e');
	set texto =  replace(texto,'è','e');
	set texto =  replace(texto,'É','e');
	set texto =  replace(texto,'é','e');
	set texto =  replace(texto,'Ê','e');
	set texto =  replace(texto,'ê','e');
	set texto =  replace(texto,'Í','i');
	set texto =  replace(texto,'ì','i');
	set texto =  replace(texto,'Í','i');
	set texto =  replace(texto,'í','i');
	set texto =  replace(texto,'Î','i');
	set texto =  replace(texto,'î','i');
	set texto =  replace(texto,'Ò','o');
	set texto =  replace(texto,'ò','o');
	set texto =  replace(texto,'Ó','o');
	set texto =  replace(texto,'ó','o');
	set texto =  replace(texto,'Ô','o');
	set texto =  replace(texto,'ô','o');
	set texto =  replace(texto,'Õ','o');
	set texto =  replace(texto,'õ','o');
	set texto =  replace(texto,'Ù','u');
	set texto =  replace(texto,'ù','u');
	set texto =  replace(texto,'Ú','u');
	set texto =  replace(texto,'ú','u');
	set texto =  replace(texto,'ü','u');
	set texto =  replace(texto,'Ü','u');
	set texto =  replace(texto,'/','-');
	set texto =  replace(texto,' ','-');
	set texto =  replace(texto,'_','-');
	set texto =  replace(texto,',','-');
	set texto =  replace(texto,'.','-');
	set texto =  replace(texto,'&','-');
	set texto =  replace(texto,'(','-');
	set texto =  replace(texto,')','-');
	set texto =  replace(texto,'-',' ');
	set texto =  replace(trim(texto),' ','-');
	set texto =  replace(texto,'--','-');
	set texto =  replace(texto,'--','-');
	set texto =  replace(texto,'--','-');
	RETURN trim(texto);
END
//
delimiter ;
