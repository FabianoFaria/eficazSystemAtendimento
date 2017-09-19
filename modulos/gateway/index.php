<?php
/*
Plugin Name: Gateway de Pagamentos
Plugin URI: http://www.evonline.com.br
Description: M&oacute;dulo de Pagamentos para a ferramenta de produtos
Version: 1.5
Author: Meu Website Novo
Author URI: http://www.meuwebsitenovo.com.br
*/

	if($_GET['pg']!=""){
		require_once('../../../wp-config.php');
		require_once('../../../wp-load.php');
		require_once('../../../wp-includes/wp-db.php');
	}

	global $wpdb;
	global $pluginGateway;
	global $caminhoPluginGateway;

	$pluginGateway = 'ativo';
	$caminhoPluginGateway = get_bloginfo('wpurl')."/wp-content/plugins/gateway/";

	require( dirname( __FILE__ ).'/atualizaDB.php' );
	require( dirname( __FILE__ ).'/functions.php' );

	function produtosPagamentos() {
		require( dirname( __FILE__ ).'/pagamentos.php' );
	}

	define('TRADUTOR_GATEWAY', "gateway");
	add_action('init', 'gateway_textdomain');
	function gateway_textdomain() {
		if (function_exists('load_plugin_textdomain')) {
			$dir = dirname( (__FILE__) ) . '/languages/';
			if (true) {
				$dir = 'gateway/languages/';
			}

			load_plugin_textdomain(TRADUTOR_GATEWAY, false, $dir);
		}
	}
?>