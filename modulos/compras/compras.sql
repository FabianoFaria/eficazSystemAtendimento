CREATE TABLE IF NOT EXISTS `compras_ordem_compra` (
  `Ordem_Compra_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Cadastro_ID` int(10) DEFAULT NULL COMMENT 'Empresa responável pela compra',
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  `Data_Alteracao` datetime DEFAULT NULL,
  `Usuario_Alteracao_ID` int(11) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT '1',
  `Data_Limite_Retorno` datetime DEFAULT NULL,
  PRIMARY KEY (`Ordem_Compra_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `compras_ordem_compras_finalizadas` (
  `Ordem_Compra_Finalizada_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Ordem_Compra_ID` int(10) NOT NULL,
  `Ordem_Compra_Produto_ID` int(10) NOT NULL,
  `Fornecedor_ID` int(10) DEFAULT NULL,
  `Quantidade_Aprovada` varchar(50) NOT NULL,
  `Valor_Aprovado` varchar(50) NOT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Ordem_Compra_Finalizada_ID`),
  KEY `Ordem_Compra_ID` (`Ordem_Compra_ID`),
  KEY `Ordem_Compra_Produto_ID` (`Ordem_Compra_Produto_ID`),
  KEY `Usuario_Cadastro_ID` (`Usuario_Cadastro_ID`),
  KEY `Ordem_Compra_ID_Ordem_Compra_Produto_ID` (`Ordem_Compra_ID`,`Ordem_Compra_Produto_ID`),
  KEY `Ordem_Compra_ID_Ordem_Compra_Produto_ID_Usuario_Cadastro_ID` (`Ordem_Compra_ID`,`Ordem_Compra_Produto_ID`,`Usuario_Cadastro_ID`),
  KEY `Fornecedor_ID` (`Fornecedor_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `compras_ordem_compra_follows` (
  `Ordem_Compra_Follow_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Ordem_Compra_ID` int(10) DEFAULT NULL,
  `Descricao` text NOT NULL,
  `Situacao_ID` int(10) DEFAULT NULL,
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) NOT NULL,
  PRIMARY KEY (`Ordem_Compra_Follow_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `compras_ordens_compras_orcamentos` (
  `Ordem_Compra_Orcamento_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Ordem_Compra_ID` int(10) NOT NULL DEFAULT '0',
  `Fornecedor_ID` int(10) NOT NULL DEFAULT '0',
  `Produto_Variacao_ID` int(10) NOT NULL DEFAULT '0',
  `Valor_Retorno` varchar(50) NOT NULL DEFAULT '0',
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Ordem_Compra_Orcamento_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `compras_ordens_compras_produtos` (
  `Ordens_Compras_Produtos_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Ordem_Compra_ID` int(10) NOT NULL,
  `Compra_Solicitacao_ID` int(10) NOT NULL,
  `Situacao_ID` int(10) NOT NULL DEFAULT '1',
  `Data_Cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Ordens_Compras_Produtos_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `compras_solicitacoes` (
  `Compra_Solicitacao_ID` int(10) NOT NULL AUTO_INCREMENT,
  `Chave_Estrangeira` int(10) DEFAULT NULL,
  `Tabela_Estrangeira` varchar(50) DEFAULT NULL,
  `Produto_Variacao_ID` varchar(50) DEFAULT NULL,
  `Quantidade` decimal(15,2) NOT NULL,
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cadastro_ID` int(11) DEFAULT NULL,
  `Situacao_ID` int(11) DEFAULT '60',
  `Produto_Movimentacao_ID` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Compra_Solicitacao_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `compras_estoque_deleta` BEFORE DELETE ON `produtos_movimentacoes` FOR EACH ROW BEGIN
		delete from compras_solicitacoes where Produto_Movimentacao_ID = OLD.Produto_Movimentacao_ID;
	END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='';
DELIMITER //
CREATE TRIGGER `compras_estoque_insere` BEFORE INSERT ON `produtos_movimentacoes` FOR EACH ROW BEGIN
		declare estoqueMinimo int default 0;
		declare quantidadeEstoque int default 0;
		declare quantidadeInsere int default 0;
		declare quantidadeDisponivel int default 0;
		declare idMovimentacao int default 0;
		
		if NEW.Tipo_Movimentacao_ID = 67 then
			SELECT coalesce(estoque_minimo, 0) from produtos_estoque where Produto_Variacao_ID = NEW.Produto_Variacao_ID limit 1 into @estoqueMinimo;
			SELECT coalesce(sum(quantidade), 0) from produtos_movimentacoes where Produto_Variacao_ID = NEW.Produto_Variacao_ID limit 1 into @quantidadeEstoque;
			SELECT coalesce(max(Produto_Movimentacao_ID), 0) from produtos_movimentacoes limit 1 into @idMovimentacao;
			
			if ISNULL(@estoqueMinimo) then set @estoqueMinimo = 0; end if;
			if ISNULL(@quantidadeEstoque) then set @quantidadeEstoque = 0; end if;

			set @quantidadeEstoque = @quantidadeEstoque + NEW.Quantidade;
			set @quantidadeDisponivel =  @quantidadeEstoque - @estoqueMinimo;
			set @quantidadeInsere = 0;
	
			if @quantidadeEstoque <= @estoqueMinimo then 
				if((@quantidadeEstoque - @estoqueMinimo) - NEW.Quantidade) >=1 then
					set @quantidadeInsere = @quantidadeEstoque - @estoqueMinimo - NEW.Quantidade;
				else
					set @quantidadeInsere = NEW.Quantidade *-1;
				end if;
			end if;
			
			if @quantidadeInsere >= 1 then
				insert into compras_solicitacoes(Produto_Variacao_ID,Quantidade,Chave_Estrangeira,Tabela_Estrangeira,Usuario_Cadastro_ID, Produto_Movimentacao_ID)values(NEW.Produto_Variacao_ID,@quantidadeInsere,NEW.Chave_Estrangeira,NEW.Tabela_Estrangeira,NEW.Usuario_Cadastro_ID, last_insert_id()+1);
			end if;
		end if;
	END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;




INSERT INTO `tipo_grupo` (`Tipo_Grupo_ID`, `Descr_Tipo_Grupo`, `Situacao_ID`) VALUES (32, 'Situação de Compras', 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (60, 32, 'Requisição Aguardando Avaliação', NULL, 2);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (61, 32, 'Enviada para Orçamento', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (62, 32, 'Re-aberto', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (63, 32, 'Ordem de Compra Gerada', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (64, 32, 'Aguardando Aprovação', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (65, 32, 'Enviada para Faturamento', NULL, 1);


select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
insert into modulos(Nome, Slug, Descricao, Posicao, Versao)values('Compras','compras','Módulo de Compras',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;

insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Oculta_Menu)values(@moduloID,'Visualizar ordem de compra','Visualizar ordem de compra','compras-visualiza-ordem-gerada','0',1);
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Requisição de Compra','Requisição de Compra','compras-requisicao-cadastro','0','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Requisições','Requisições de compras','compras-requisicao','1');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Ordens de Compra','Localizar Ordem de Compra','compras-localizar','2');
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'Configurações Compras','Configurações Compras','compras-configuracoes','4');
select LAST_INSERT_ID() into @subModuloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao,Tipo_Grupo_ID, Pagina_Pai_ID)values(@moduloID,'Gerenciar Situações','Gerenciar Situações de Compra','compras-situacoes','1','32',@subModuloID);



ALTER TABLE `compras_ordem_compras_finalizadas`
	ADD COLUMN `Entregue` INT(11) NOT NULL DEFAULT '0' AFTER `Usuario_Cadastro_ID`;


update `tipo` set `Descr_Tipo` = 'Aguardando Aprovação', `Situacao_ID` = 1 where `Tipo_ID` = 64;


ALTER TABLE `compras_ordens_compras_produtos`
	ADD COLUMN `Dados` TEXT NULL AFTER `Compra_Solicitacao_ID`;


INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (104, 32, 'Compra Aprovada', NULL, 1);
INSERT INTO `tipo` (`Tipo_ID`, `Tipo_Grupo_ID`, `Descr_Tipo`, `Tipo_Auxiliar`, `Situacao_ID`) VALUES (105, 32, 'Compra Recusada', NULL, 1);


select Modulo_ID from modulos where Slug = 'compras' into @moduloID;
insert into modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao, Oculta_Menu)values(@moduloID,'Aprovar Compras','Aprovar Compras','compras-aprovar-recusar-compras','', 1);


delete from modulos_paginas where slug = 'compras-configuracoes';
delete from modulos_paginas where slug = 'compras-situacoes';



ALTER TABLE `compras_ordem_compras_finalizadas`
	ALTER `Quantidade_Aprovada` DROP DEFAULT;
ALTER TABLE `compras_ordem_compras_finalizadas`
	CHANGE COLUMN `Quantidade_Aprovada` `Quantidade_Aprovada` DECIMAL(15,2) NOT NULL AFTER `Fornecedor_ID`,
	CHANGE COLUMN `Entregue` `Quantidade_Entregue` DECIMAL(15,2) NOT NULL DEFAULT '0' AFTER `Quantidade_Aprovada`;
