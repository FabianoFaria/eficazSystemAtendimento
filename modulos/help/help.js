$(document).ready(function(){
	$(".help-cadastra-novo").on('click', function () {
		$("#frmDefault").attr('action', './cadastrar-help');
		$("#frmDefault").submit();
	});
	$(".help-atualiza-dados").on('click', function () {
		$("#id-pagina-help").val('')
		$("#frmDefault").attr('action', './atualizar-help');
		$("#frmDefault").submit();
	});
	$("#modulo-selecionado").on('change', function(){
		$(this).css('background-color', '').css('outline', '');
		$("#select-pagina-principal-inclui").load(caminhoScript+"/modulos/help/localiza-paginas-modulo.php?modulo="+$(this).val());
	});
	$("#select-pagina-principal").live('change', function(){
		$(this).css('background-color', '').css('outline', '');
		$("#select-pagina-secundaria-inclui").load(caminhoScript+"/modulos/help/localiza-paginas-modulo.php?modulo="+$("#modulo-selecionado").val()+"&pagina="+$(this).val());
	});
	$("#titulo-help").live('keyup', function(){
		if($(this).val() != "")
			$(this).css('background-color', '').css('outline', '');
	});
	$(".help-inclui-novo").on('click', function(){
		var erro = 0;
		$(".required").each(function( i ) {
			if($(this).val()==""){
				$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				erro = 1;
			}
			if(tinyMCE.get('campo-teste-2').getContent()==""){
				$('iframe').contents().find('body').css('backgroundColor', '#FFE4E4');
				erro = 1;
			}
		});
		$(document.getElementById('campo-teste-2_ifr').contentWindow.document).keydown(function() {
			$('iframe').contents().find('body').css('backgroundColor', '');
			erro = 1;
		});
		if(erro==0){
			$("#campo-teste-2").val(tinyMCE.get('campo-teste-2').getContent());
			$("#frmDefault").attr('action', '../modulos/help/inclui-help.php');
			$("#frmDefault").submit();
		}
	});
	$("#botao-pesquisar-help").live('click', function(){
		$("#frmDefault").attr('action', './gerenciar-help');
		$("#frmDefault").submit();
	});
	$(".menu-superior-help").on('click', function(){
		titulo = $(this).attr('attr-titulo');
	})
	$(".help-visualizar").on('click', function(){
		$("#pagina-help-id").val($(this).attr('attr-id'));
		$("#frmDefault").attr('action', './atualizar-help/');
		$("#frmDefault").submit();
	});
	$("#botao-atualizar-help").live('click', function(){
		$("#frmDefault").attr('action',caminhoScript+"/modulos/help/update-help.php");
		$("#frmDefault").submit();
	});
});