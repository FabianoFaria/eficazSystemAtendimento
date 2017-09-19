var valorTotalAnt;
$(document).ready(function(){
	var paginaAtual = window.location.href.replace("#","");
	var slugPagina = $("#slug-pagina").val();

	/********* Inicio Funções localiza Contas e Titulos***********/
	if (slugPagina=="nfe-emitidas"){
		//$("#localiza-cadastro-para").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-nfe").click(); }});

		$("#botao-pesquisar-nfe").live('click', function () {
			caminho = caminhoScript+"/nfe/nfe-emitidas";
			$('#frmDefault').attr("action",caminho);
			$("#frmDefault").submit();
		});
	}
	
	
	
	$(".localiza-conta").live('click', function () {
		contaID = $(this).attr("conta-id");
		if ($('#lancamento-fancybox').val()==1){
			caminho = caminhoScript+"/financeiro/financeiro-lancamento?tipo=direto&slug-pagina="+$('#slug-pagina').val()+"&localiza-conta-id="+contaID;
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				//afterClose : function(){
				beforeClose : function(){
					if($('.fancybox-iframe').contents().find('#lancamento-salvo').val()=='1'){
						location.reload(true);
					}
				},
				helpers: {
					overlay: {
						locked: false
					}
				}
			});
		}
		else{
			$("#localiza-conta-id").val(contaID);
			caminho = caminhoScript+"/financeiro/financeiro-lancamento";
			$('#frmDefault').attr("action",caminho);
			$("#frmDefault").submit();
		}
	});
	
});

