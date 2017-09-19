//********* Inicio Funções Localiza Cadastros Genérico ***********/
$(document).ready(function(){
	$(".texto-cadastro-localiza").live('click keyup', function (e) {
		nomeCampo = $(this).attr("nome-campo");
		condicaoEmpresa = $(this).attr("condicao-empresa");

		pos = $('.ls-sel').attr('pos');
		if (e.keyCode=="40"){
			if (pos==''){
				$('.ls-1').addClass('ls-sel');
			}
			else{
				$('.ls').removeClass('ls-sel');
				$('.ls-'+(pos + 1)).addClass('ls-sel');
			}
		}
		if (e.keyCode=="38"){
			if (pos==''){
				$('.ls-1').addClass('ls-sel');
			}
			else{
				$('.ls').removeClass('ls-sel');
				$('.ls-'+(pos - 1)).addClass('ls-sel');
			}
		}

		if ($(this).val().trim().length>=3){
			$("#div-localiza-cadastro-"+nomeCampo).show();
			caminho = caminhoScript+"/funcoes/cadastro-seleciona-geral.php?nome-campo="+nomeCampo+"&condicao-empresa="+condicaoEmpresa;
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#div-localiza-cadastro-"+nomeCampo).html(retorno);
				}
			});
		}
		else{
			$("#div-localiza-cadastro-"+nomeCampo).hide();
			$("#div-localiza-cadastro-"+nomeCampo).html("");
		}


	});
	$(".texto-cadastro-localiza").live('blur', function () {
		nomeCampo = $(this).attr("nome-campo");
		if ($(this).val().trim().length==0)
			$("#"+nomeCampo).val('');
	});
	$(".seleciona-cadastro-geral").live('click', function () {
		nomeCampo = $(this).attr("nome-campo");
		$("#"+nomeCampo).val($(this).attr("cadastro-id"));
		$("#div-campos-consulta-"+nomeCampo).hide();
		$("#div-localiza-cadastro-"+nomeCampo).hide();
		$("#div-localiza-cadastro-"+nomeCampo).html("");
		$("#div-cadastro-"+nomeCampo).show();
		if (($("#"+nomeCampo).attr("visualizar-detalhes"))==1){
			carregarCadastroGeral(nomeCampo);
		}
	});
	$(".editar-cadastro-generico").live('click', function () {
		nomeCampo = $(this).attr("campo-alvo");
		$("#div-campos-consulta-"+nomeCampo).show();
		$("#div-localiza-cadastro-"+nomeCampo).show();
		$("#div-cadastro-"+nomeCampo).hide();
		$("#texto-cadastro-localiza-"+nomeCampo).click();
		$("#texto-cadastro-localiza-"+nomeCampo).focus();
	});
	$(".limpar-cadastro-generico").live('click', function () {
		nomeCampo = $(this).attr("nome-campo");
		//if ($("#"+nomeCampo).val()!=''){
			$("#div-localiza-cadastro-"+nomeCampo).hide();
			//$("#div-localiza-cadastro-"+nomeCampo).html("");
			$("#div-campos-consulta-"+nomeCampo).hide();
			$("#div-cadastro-"+nomeCampo).show();
			$("#botao-alterar-"+nomeCampo).show();
		//}
	});
	$(".link-geral-cadastro").live('click', function () {
		caminho = caminhoScript+"/cadastros/cadastro-dados?tipo-fluxo=direto&cadastroID="+$(this).attr("cadastro-id");
		//alert(caminho);
		$.fancybox.open({
			href : caminho,
			type : 'iframe',
			width: '90%',
			padding : 2,
			beforeClose: function() {
				cadastroID = $('.fancybox-iframe').contents().find('#cadastro-id').val();
				nome = $('.fancybox-iframe').contents().find('#nome-completo').val()+$('.fancybox-iframe').contents().find('#razao-social').val();
				$('#aux-cad-new').attr('cadastro-id', cadastroID);
				$('#aux-cad-new').val(cadastroID);
				$('#aux-cad-new').click();
				$('.texto-cadastro-localiza').val(nome);
			},
			helpers: {
				overlay: {
					locked: false
				}
			}
		});
		/*
		$("#cadastroID").val($(this).attr("cadastro-id"));
		$("#frmDefault").attr("action",caminhoScript+"/cadastros/cadastro-dados");
		$('#frmDefault').attr("target","_blank");
		$("#frmDefault").submit();
		$("#frmDefault").attr("action","");
		$('#frmDefault').attr("target","");
		*/
	});
	$(".link-geral-turma").live('click', function () {
		//$("#frmDefault").append("<input type='hidden' id='id-turma' name='id-turma' value='" + $(this).attr('turma-id') + "'/>");
		$("#id-turma").val($(this).attr('turma-id'));
		//alert($(this).attr('turma-id'));
		alert($("#id-turma").val());
		$("#frmDefault").attr("action",caminhoScript+"/turmas/turmas-gerenciar-turma/");
		$("#frmDefault").submit();
		$("#frmDefault").attr("action","");
		$('#frmDefault').attr("target","");
	});

	$(".btn-incluir-novo-cadastro").live('click', function () {
		dados = $('#frmDefault').serialize();
		caminho = caminhoScript+"/cadastros/cadastro-dados?tipo-fluxo=direto";
		$.fancybox.open({
			href : caminho,
			type : 'iframe',
			width: '90%',
			padding : 2,
			helpers: {
				overlay: {
					locked: false
				}
			},
			beforeClose: function() {
				cadastroID = $('.fancybox-iframe').contents().find('#cadastro-id').val();
				nome = $('.fancybox-iframe').contents().find('#nome-completo').val()+$('.fancybox-iframe').contents().find('#razao-social').val();
				$('#aux-cad-new').attr('cadastro-id', cadastroID);
				$('#aux-cad-new').val(cadastroID);
				$('#aux-cad-new').click();
				$('.texto-cadastro-localiza').val(nome);
			}
		});
	});

});


function carregarCadastroGeral(nomeCampo){
	cadastroID = $("#"+nomeCampo).val();
	descricaoCampo = $("#"+nomeCampo).attr("descricao-campo");
	selecionaEndereco = $("#"+nomeCampo).attr("seleciona-endereco");
	caminho = caminhoScript+"/funcoes/cadastro-carregar-cadastro-geral.php?nome-campo="+nomeCampo+"&cadastro-id="+cadastroID+"&descricao-campo="+descricaoCampo+"&seleciona-endereco="+selecionaEndereco;
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-cadastro-"+nomeCampo).html(retorno);
			$("#botao-alterar-"+nomeCampo).show();
		}
	});
}

/********* Fim Funções Localiza Cadastros Genérico ***********/