<?php
	error_reporting(E_ERROR);
	session_start();
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");

	$idProduto 		= $_POST['produto-id'];
	$posicao 		= $_POST['posicao-imagem'];
	$arquivo		= $_FILES['imagem-upload'][name];
	$arquivoTmp		= $_FILES['imagem-upload'][tmp_name];
	if($arquivo != ""){
		$nomeArquivo	= $idProduto."-".$posicao."-".retiraCaracteresEspeciais(strtolower($arquivo));
		move_uploaded_file($arquivoTmp, "../../images/produtos/$nomeArquivo");
		mpress_query("update produtos_imagens set Situacao_ID = 2 where Produto_ID = $idProduto and Posicao = $posicao");
		mpress_query("insert into produtos_imagens(Produto_ID,Posicao,Nome_Imagem)values($idProduto,$posicao,'".str_replace('-',' ',str_replace('.jpg','',$arquivo))."')");
		converteImagemProduto($nomeArquivo,mpress_identity());
	}

	function converteImagemProduto($nomeImagem,$idImagem){
		geraImagemSistema($nomeImagem, 1024,1,$idImagem);
		geraImagemSistema($nomeImagem, 300,2, $idImagem);
		geraImagemSistema($nomeImagem, 150,3, $idImagem);
		unlink("../../images/produtos/$nomeImagem");
	}

	function geraImagemSistema($nomeImagem, $tamanho, $tipo, $idImagem){
		$i = imagecreatefromjpeg("../../images/produtos/$nomeImagem");
		$width 		= imagesx($i);
		$height 	= imagesy($i);
		$thumbW = $tamanho;
		$thumbH = $tamanho;
		if($width >=$height) $porcentoAplicado = $thumbW / $width; else $porcentoAplicado = $thumbH / $height;
		$new_width	= $width*$porcentoAplicado;
		$new_height = $height*$porcentoAplicado;
		$new_width	= (int) $new_width;
		$new_height = (int) $new_height;
		$nomeImagem =  str_replace('.jpg', '',$nomeImagem)."-".$new_width."x".$new_height.".jpg";
		$image_resized = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($image_resized, $i, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($image_resized,"../../images/produtos/$nomeImagem",$thumbW);
		imagedestroy($image_resized);
		mpress_query("update produtos_imagens set Imagem$tipo = '$nomeImagem' where Imagem_ID = $idImagem");
	}
?>
<script>
	parent.reloadImagenprodutos(<?php echo $idProduto;?>);
</script>