<?php
	$wpdb->query("	CREATE TABLE IF NOT EXISTS wp_produtos_cadastros_pedidos_pagamentos (
					Produto_Cadastro_Pedido_Pagamento_ID INT(10)  NOT NULL AUTO_INCREMENT,
					Produto_Cadastro_Pedido_ID INT(10) ,
					Situacao CHAR(1) NULL DEFAULT 'P',
					Tipo_Pagamento varchar(50) null,
					Retorno mediumtext null,
					Data_Cadastro TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
					PRIMARY KEY (Produto_Cadastro_Pedido_Pagamento_ID))");

	$wpdb->query("	CREATE TABLE IF NOT EXISTS wp_gateway_regras_parcelamento (
					Gateway_Regras_Parcelamento_ID INT(10)  NOT NULL AUTO_INCREMENT,
					Utilizar_Parcelamento CHAR(1) ,
					Parcelas int NULL ,
					Aplicar_Juros CHAR(1) NULL ,
					Taxa_Juros varchar(10) NULL ,
					parcela_Inicial_Juros int(10) NULL ,
					PRIMARY KEY (Gateway_Regras_Parcelamento_ID))");
?>