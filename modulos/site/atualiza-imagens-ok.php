<?php
	include('wp-config.php');
	include('sistema/config.php');

//	error_reporting(E_ALL);
//	ini_set('display_errors', 'On');

	global $wpdb;
	$wp_upload_dir = wp_upload_dir();
	$fotos = mpress_query("select Chave_Estrangeira + 100000 + 1000 Produto_ID, Nome_Arquivo_Original Arquivo
						  from modulos_anexos
						  where tabela_estrangeira = 'produtos' and Situacao_ID = 1 limit 2");
	while($row = mpress_fetch_array($fotos)){
		$fotoLocal 		= "/home/storage/9/0d/a4/okscrapbook1/public_html/sistema/uploads/$row[Arquivo]";
		$fotoRemota 	= "/home/storage/9/0d/a4/okscrapbook1/public_html/sistema/uploads/$row[Arquivo]";

		$filetype = wp_check_filetype(basename($fotoLocal), null );
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . preg_replace( '/\.[^.]+$/', '', basename($fotoLocal)),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename($fotoLocal)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $fotoLocal, $parent_post_id );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $fotoLocal );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		$wpdb->query("insert into wp_produtos_produtos_imagens(Produto_ID, Imagem_ID, Posicao_Imagem)values($row[Produto_ID],$attach_id,1)");

		echo "<pre>";
		print_r($attachment);
		echo "</pre>";

	}


/*
	foreach($fotos as $indice=>$foto){


		$i++;
		$fotoArr 	= explode('/',$foto->Foto);
		$nomeFoto 	= $fotoArr[count($fotoArr)-1];
		$dirRemoto	= $fotoArr[count($fotoArr)-2];
		$fotoLocal 	= "/home/storage/8/cc/fa/apolar1/public_html/wp-content/uploads/fotos-imoveis/$nomeFoto";
		$fotoRemota 	= "/apolar/Web/Sistemas/Fotos/fotosimoveis/$dirRemoto/$nomeFoto";
		$postID = $foto->Post_ID;

		$filetype = wp_check_filetype(basename($fotoLocal), null );
		$wp_upload_dir = wp_upload_dir();
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $fotoLocal ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $fotoLocal ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $fotoLocal, $parent_post_id );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $fotoLocal );
		wp_update_attachment_metadata( $attach_id, $attach_data );
*/
//	}
?>
