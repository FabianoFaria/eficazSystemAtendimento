

$("#site-seleciona").live('change', function () {
	$("#frmDefault").submit();
});
$("#cancelar-dados-site").live('click', function () {
	$("#site-id").val('');
	$("#frmDefault").submit();
});

$(".cadastrar-dados-site").live('click', function () {
	//if (validarCamposGenerico(".div-dados-site .required")){
		if ($("#site-id").val()=="-1")
			$("#acao-site").val('I');
		else
			$("#acao-site").val('U');

		//alert($("#acao-site").val());
		$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-salvar.php");
		$("#frmDefault").submit();
	//}
});

$("#desativar-dados-site").live('click', function () {
	$("#acao").val('D');
	$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-salvar.php");
	$("#frmDefault").submit();
});

$(".exportar-categorias").live('click', function () {
	$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-exportar-categorias.php");
	$("#frmDefault").submit();
});
$(".exportar-produtos").live('click', function () {
	$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-exportar-produtos.php");
	$("#frmDefault").submit();
});
$(".exportar-limpar-dados").live('click', function () {
	$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-exportar-limpar-dados.php");
	$("#frmDefault").submit();
});
$(".exportar-produtos-categorias").live('click', function () {
	$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-exportar-produtos-categorias.php");
	$("#frmDefault").submit();
});
$(".exportar-produtos-variacoes").live('click', function () {
	$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-exportar-produtos-variacoes.php");
	$("#frmDefault").submit();
});
$(".exportar-produtos-variacoes-imagens").live('click', function () {
	$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-exportar-produtos-imagens.php");
	$("#frmDefault").submit();
});



/*
$("#site-seleciona").live('change', function () {
	if ($(this).val()=="-1"){
		$("#div-seleciona-site").hide();
		$("#div-dados-site").show();
	}
	else{
		$("#div-seleciona-site").hide();
		$("#div-dados-site").hide();
	}
});

$("#cancelar-dados-site").live('click', function () {
	$("#div-seleciona-site").show();
	$("#div-dados-site").hide();
	$("#site-seleciona").val('').trigger('chosen:updated');
});

$("#cadastrar-dados-site").live('click', function () {
	if (validarCamposGenerico("#div-dados-site .required")){
		$("#frmDefault").attr("action",caminhoScript+"/modulos/site/site-salvar.php");
		$("#frmDefault").submit();
	}
});
*/