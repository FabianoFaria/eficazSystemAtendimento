<?php

// Make sure file is not cached (as it happens for example on iOS devices)
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!function_exists("get_header"))
	require_once("functions.gerais.php");
if (!function_exists("mpress_query"))
	include("../config.php");

require_once("../funcoes/converte-upload-imagem.php");

global $caminhoFisico, $dadosUserLogin;

@set_time_limit(5 * 60); // 5 minutes execution time
$targetDir = $caminhoFisico."/uploads/";
$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds
if (!file_exists($targetDir)) {
	@mkdir($targetDir);
}
if (isset($_REQUEST["name"])) {
	$fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
	$fileName = $_FILES["file"]["name"];
} else {
	$fileName = uniqid("file_");
}

// Arruma nomes
$arquivoOriginal = utf8_decode($fileName);
$arquivo = retiraCaracteresEspeciais(date('Ymd_hms')."_".utf8_decode($fileName));
$fileName = retiraCaracteresEspeciais(date('Ymd_hms')."_".utf8_decode($fileName));
//$fileName = retiraCaracteresEspeciais(utf8_decode($fileName)."_".date('Ymd_hms'));


$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

// Remove old temp files
if ($cleanupTargetDir) {
	if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
	}

	while (($file = readdir($dir)) !== false) {
		$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// If temp file is current file proceed to the next
		if ($tmpfilePath == "{$filePath}.part") {
			continue;
		}

		// Remove temp file if it is older than the max age and is not the current file
		if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
			@unlink($tmpfilePath);
		}
	}
	closedir($dir);
}


// Open temp file
if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

if (!empty($_FILES)) {
	if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}

	// Read binary input stream and append it to temp file
	if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
} else {
	if (!$in = @fopen("php://input", "rb")) {
		die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
	}
}

while ($buff = fread($in, 4096)) {
	fwrite($out, $buff);
}

@fclose($out);
@fclose($in);

// Check if file has been uploaded
if (!$chunks || $chunk == $chunks - 1) {
	// Strip the temp .part suffix off
	rename("{$filePath}.part", $filePath);
	if(substr($arquivo, -3) == "jpg" || substr($arquivo, -3) == "png"){
		$arquivoFull = converteImagemProduto($arquivo);
		$arquivo = $arquivoFull['miniatura'];
	}
}

/*********** Salvando na tabela de anexos ***********/
$idReferencia	 = $_POST['idReferencia'];
$origemDocumento = $_POST['origemDocumento'];
$usuarioID = $_POST['usuarioID'];
$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

$sql = "insert into modulos_anexos (Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Nome_Arquivo_Original, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro, Complemento)
							values ('$idReferencia', '$origemDocumento', '$arquivo', '$arquivoOriginal', 1, '$usuarioID', $dataHoraAtual,'".serialize($arquivoFull)."')";
mpress_query($sql);


/****************************************************/


// Return Success JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
