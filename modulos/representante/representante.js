$(document).ready(function(){

	$("#botao-pesquisar-pedidos").live('click', function () {
		$("#frmDefault").submit();
	});

	$("#botao-pesquisar-relatorio-situacao").live('click', function () {
		$("#frmDefault").submit();
	});

	$(".editar-workflow").live('click', function () {
		//alert("Atenção esta funcionalidade esta em homologação");
		$('#localiza-workflow-id').val($(this).attr("workflow-id"));
		caminho = caminhoScript+"/representante/representante-pedido";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});

	if (($("#solicitante-id").val()!="") && ($("#solicitante-id").length>0)){
		caminho = caminhoScript+"/modulos/telemarketing/telemarketing-carregar-cadastro.php?cadastro-id="+$("#solicitante-id").val()+"&nome-campo=solicitante";
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#div-solicitante").html($.trim(retorno));
				if (($(".endereco-26").length>0)&&($(".endereco-38").length>0)){
					carregarProdutosSelect();
					carregarRepresentante();
				}
				else{
					alert("Cadastro sem os endereços principal e de instalação informados");
					$('#localiza-cadastro-id').val($("#solicitante-id").val());
					$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-cadastro");
					$("#frmDefault").submit();
				}
			}
		});
	}



	$(".validar-cpf").live('blur', function () {
		verificarCpf(this, $("#cadastro-id").val());
	});
	$(".validar-cnpj").live('blur', function () {
		verificarCnpj(this, $("#cadastro-id").val());
	});

	$(".localiza-cpf").live('blur', function () {
		if(!(ValidarCPF(this.value))){
			alert("CPF Inválido");
			this.focus();
			this.select();
		}
	});
	$(".localiza-cnpj").live('blur', function () {
		if(!(ValidarCNPJ(this.value))){
			alert("CNPJ Inválido");
			this.focus();
			this.select();
		}
	});


	$(".link-cadastro").live('click', function () {
		$('#localiza-cadastro-id').val($(this).attr('cadastro-id'));
		$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-cadastro");
		$("#frmDefault").submit();
	});

	$("#select-situacao-follow").live('change', function () {
		$("#select-motivo-follow").val("");
		$("#motivo-outros").val("");
		if($(this).val()=="40"){
			$("#div-select-motivo-follow").show();
		}else{
			$("#div-select-motivo-follow").hide();
		}
	});

	$("#select-motivo-follow").live('change', function () {
		$("#motivo-outros").val("");
		if($(this).val()=="43"){
			$("#div-motivo-outros").show();
		}else{
			$("#div-motivo-outros").hide();
		}
	});

	$("#botao-cadastra-workflow").live('click', function () {
		salvarValidarPedido();
	});
	$("#botao-reabrir-pedido").live('click', function () {
		salvarValidarPedido();
	});

/*
	$("#botao-pesquisar-perguntas").live('click', function () {
		$("#frmDefault").submit();
	});

	$(".botao-incluir-pergunta").live('click', function () {
		caminho = caminhoScript+"/faq/faq-gerenciar-pergunta";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});

	$(".pergunta-localiza").live('click', function () {
		$('#localiza-pergunta-id').val($(this).attr("pergunta-id"));
		caminho = caminhoScript+"/faq/faq-gerenciar-pergunta";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});

	$(".pergunta-ordem-localiza").live('blur', function () {
		caminho = caminhoScript+"/modulos/faq/fac-atualiza-ordem-pergunta.php?pergunta-id="+$(this).attr("pergunta-id")+"&ordem-pergunta="+$(this).val();
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8"});
	});


	$(".botao-salvar-resposta").live('click', function () {
		caminho = caminhoScript+"/modulos/faq/fac-salvar-resposta.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				carregarRespostas();
				$('#div-incluir-alterar-respostas').html("");
				$('#div-incluir-alterar-respostas').hide();
				$('#div-respostas').show();
			}
		});
	});
*/
});


function salvarValidarPedido(){
	flag = 0;
	if ($("#workflow-id").val()==""){
		$("#tipo-processo-id").css('background-color', '').css('outline', '');
		$("#div-produtos").css('background-color', '').css('outline', '');
		if ($('#tipo-processo-id').val()==""){ $("#tipo-processo-id").css('background-color', '#FFE4E4');flag=1;}
		if ($('#codigo-pedido').val()==""){ $("#codigo-pedido").css('background-color', '#FFE4E4');flag=1;}
		if ($('#solicitante-id').val()==""){alert("Informar Solicitante");flag=1;}
		qtdeProd=0;
		$(".produtos-flag").each(function(){qtdeProd=qtdeProd+1;});
		if (qtdeProd=="0"){$("#div-produtos").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}
	else{
		$("#descricao-follow").css('background-color', '').css('outline', '');
		$("#select-situacao-follow").css('background-color', '').css('outline', '');
		$("#select-motivo-follow").css('background-color', '').css('outline', '');
		$("#motivo-outros").css('background-color', '').css('outline', '');
		if ($('#descricao-follow').val()==""){$("#descricao-follow").css('background-color', '#FFE4E4');flag=1;}
		if ($('#codigo-pedido').val()==""){$("#codigo-pedido").css('background-color', '#FFE4E4');flag=1;}
		if ($('#select-situacao-follow').val()==""){$("#select-situacao-follow").css('background-color', '#FFE4E4');flag=1;}
		if ($("#select-situacao-follow").val()=="40"){
			if ($('#select-motivo-follow').val()==""){$("#select-motivo-follow").css('background-color', '#FFE4E4');flag=1;}
			if (($('#select-motivo-follow').val()=="43")&&($('#motivo-outros').val()=="")){$("#motivo-outros").css('background-color', '#FFE4E4');flag=1;}
		}
	}

	if (flag=="0"){
		caminho = caminhoScript+"/modulos/representante/representante-pedido-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				//$("#div-aux").html(retorno);
				$("#localiza-workflow-id").val(retorno);
				$("#frmDefault").attr("action",caminhoScript+"/representante/representante-pedido");
				$("#frmDefault").submit();
			}
		});
	}
}

$("#botao-importa-arquivo").live('click', function () {
	if($("#arquivo-upload").val()==""){
		$("#arquivo-upload").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
	}else{
			$("#frmDefault").attr('action','./representante-importacao');
			$("#frmDefault").attr('enctype','multipart/form-data');
			$("#frmDefault").attr('encoding','multipart/form-data');
			$("#frmDefault").submit();
			$("#frmDefault").attr('action','');
			$("#frmDefault").attr('target','');
			$("#frmDefault").attr('enctype','');
			$("#frmDefault").attr('encoding','');
	}
})

$("#arquivo-upload").live('change', function () {
	if($("#arquivo-upload").val()!=""){
		$("#arquivo-upload").css('background-color', '').css('outline', '');
	}
})

