$(document).ready(function(){
	$(".menu-secundario, .menu-secundario-sub").on('click', function () {
		$("#pagina-help-id").val($(this).attr("attr-id"));
		$("#conteudo-container").load("help-localiza-conteudo.php?pagina="+$(this).attr("attr-id"));
		$('html, body').animate({scrollTop: 0}, 500);
		$(".secundario-selecionado").removeClass("secundario-selecionado");
		$("#conteudo-titulo").html($(this).attr('attr-titulo'));
		$(this).addClass("secundario-selecionado");
	});

	var j = 0;
	$(".secundario-selecionado").each(function( i ) {j++;});
	$(".secundario-selecionado").each(function( i ) {i++;if(i<j) $(this).removeClass('secundario-selecionado');});

	$(".menu-superior-help").live('click', function(){
		$(".menu-titulo-selecionado").removeClass("menu-titulo-selecionado").addClass("menu-titulo");
		titulo = $(this).attr('attr-titulo').replace(' ', '%20').replace(' ', '%20').replace(' ', '%20').replace(' ', '%20').replace(' ', '%20');
		$("#conteudo-container").load("help-localiza-conteudo.php?pagina="+$("#pagina-help-id").val()+"&titulo="+titulo);
	});
	$(".atualizar-help").live('click', function(){
		$("#pagina-help-id").val($(this).attr('attr-id'));
		$("#frmDefault").attr('action', './atualizar-help/');
		$("#frmDefault").submit();
	});
});