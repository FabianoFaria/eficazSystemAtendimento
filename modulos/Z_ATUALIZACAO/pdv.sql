CREATE TABLE IF NOT EXISTS `pdv` (
  `PDV_ID` int(10) NOT NULL AUTO_INCREMENT,
  `PDV_Resumo_ID` int(10) NULL,
  `Caixa_Numero` int(10) DEFAULT NULL,
  `Atendente_ID` int(10) DEFAULT NULL,
  `Produto_Variacao_ID` int(10) DEFAULT NULL,
  `Quantidade` int(10) DEFAULT NULL,
  `Tipo` int(10) DEFAULT '0',
  `Valor` varchar(50) DEFAULT '0',
  `Situacao_ID` int(11) DEFAULT '97',
  `Data_Cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PDV_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pdv_resumo` (
	`PDV_Resumo_ID` INT(10) NOT NULL AUTO_INCREMENT,
	`Caixa_Numero` INT(10) NULL DEFAULT '0',
	`Usuario_ID` INT(10) NULL DEFAULT '0',
	`Data_Inicio` DATETIME NULL DEFAULT NULL,
	`Data_Fim` DATETIME NULL DEFAULT NULL,
	`Quantidade_Itens` INT(11) NULL DEFAULT NULL,
	`Valor_Total` VARCHAR(50) NULL DEFAULT NULL,
	`Valor_Estorno` VARCHAR(50) NULL DEFAULT NULL,
	`Valor_Parcela` VARCHAR(50) NULL DEFAULT NULL,
	`Forma_Pagamento` VARCHAR(50) NULL DEFAULT NULL,
	`Condicao_Pagamento` VARCHAR(50) NULL DEFAULT NULL,
	`Situacao_ID` VARCHAR(50) NULL DEFAULT '97',
	`Data_Situacao` DATETIME NULL DEFAULT NULL,
	`Data_Cadastro` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`Usuario_Finalizacao_ID` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`PDV_Resumo_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

select COALESCE(max(Posicao), 1, max(Posicao))+1 from modulos where Posicao < 1000 into @posicao;
INSERT IGNORE INTO modulos(Nome, Slug, Descricao, Posicao, Versao)values('Frente de Caixa','pdv','Módulo para front end de vendas',@posicao,'1.5');
select LAST_INSERT_ID() into @moduloID;
INSERT IGNORE INTO modulos_paginas(Modulo_ID,Titulo,Descricao,Slug,Posicao)values(@moduloID,'PDV','Frente de caixa','pdv-inicio','6');

INSERT ignore INTO tipo_grupo(Tipo_Grupo_ID,Descr_Tipo_Grupo,Situacao_ID) VALUES (37, 'Situação PDV', 1);
INSERT IGNORE INTO tipo (Tipo_ID,Tipo_Grupo_ID, Descr_Tipo, Situacao_ID) VALUES (97, 37, 'Aberta', 1), (98, 37, 'Finalizada', 1), (99, 37, 'Cancelada', 1);
INSERT IGNORE INTO tipo (Tipo_ID,Tipo_Grupo_ID, Descr_Tipo, Situacao_ID) VALUES (90, 25, 'Dinheiro', 1), (91, 25, 'Débito', 1), (92, 25, 'Crédito', 1), (93, 25, 'Cheque', 1), (94, 25, 'Depósito', 1), (95, 25, 'DOC', 1), (96, 25, 'TED', 1);
