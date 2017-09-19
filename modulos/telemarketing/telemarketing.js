/********* Inicio Funções Tela de Localizar Cadastros ***********/
$(document).ready(function(){

	$("#botao-pesquisar-relatorio-situacao").live('click', function () {
		$("#frmDefault").submit();
	});


	/*PAGINA LOCALIZA INSTALADORES*/
	if  ($("#slug-pagina").val()=="telemarketing-localiza-instaladores"){
		$("#localiza-instalador-cep").focus();
		$("#localiza-instalador-cep").mask("99999-999");
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-instalador").click();}});

		$("#botao-localizar-instalador").live('click', function () {
			flag = 0;
			$("#localiza-instalador-cep").css('background-color', '').css('outline', '');
			//alert((soNumero($("#localiza-instalador-cep").val()).length));
			if((soNumero($("#localiza-instalador-cep").val()).length)!=8){$("#localiza-instalador-cep").css('background-color', '').css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
			if (flag==0){
				$("#frmDefault").submit();
			}
		});
	}

	/*PAGINA LOCALIZA PEDIDOS*/
	if  ($("#slug-pagina").val()=="telemarketing-pedidos"){
		$("#localiza-id").focus();
		$(document).keypress(function(event) {
			if(event.keyCode==13){$("#botao-pesquisar-cadastros").click();}
		});

		$("#localiza-cpf").mask("999.999.999-99");
		$("#localiza-cnpj").mask("99.999.999/9999-99");

		$("#botao-pesquisar-cadastros").live('click', function () {
			$("#localiza-codigo").css('background-color', '').css('outline', '');
			$("#localiza-nome-completo").css('background-color', '').css('outline', '');
			$("#localiza-cpf").css('background-color', '').css('outline', '');
			$("#localiza-cnpj").css('background-color', '').css('outline', '');
			$("#localiza-id").css('background-color', '').css('outline', '');
			//alert((soNumero($("#localiza-cpf").val()).length));
			if (($.trim($("#localiza-codigo").val())=="")&&($.trim($("#localiza-nome-completo").val())=="")&&((soNumero($("#localiza-cpf").val()).length)!=11)&&((soNumero($("#localiza-cnpj").val()).length)!=14)&&($.trim($("#localiza-id").val())=="")){
				$("#localiza-codigo").css('background-color', '').css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				$("#localiza-nome-completo").css('background-color', '').css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				$("#localiza-cpf").css('background-color', '').css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				$("#localiza-cnpj").css('background-color', '').css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				$("#localiza-id").css('background-color', '').css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			}
			else
				$("#frmDefault").submit();
		});
	}

	/*PAGINA TELEMARKETING CADASTRO*/
	if  ($("#slug-pagina").val()=="telemarketing-cadastro"){
		$(document).keypress(function(event) {
			if(event.keyCode==13){$("#botao-salvar-cadastro").click();}
		});
	}

	/*PAGINA TELEMARKETING RELATORIOS*/
	if  ($("#slug-pagina").val()=="telemarketing-relatorio-dinamico"){
		$("#localiza-cpf").mask("999.999.999-99");
		$("#localiza-cnpj").mask("99.999.999/9999-99");
	}
	$("#botao-pesquisar-relatorio").live('click', function () {
		$("#frmDefault").submit();
	});


	$("#localizar-incluir-novo-cadastro").live('click', function () {
		abreTelaCadastro();
	});

	$("#div-destaque").live('click', function () {
		$("#div-destaque").hide();
		$("#div-novo-cadastro").hide();
	});
	$("#botao-cancelar-cadastro").live('click', function () {
		$("#div-destaque").hide();
		$("#div-novo-cadastro").hide();
	});

	$("#botao-salvar-cadastro").live('click', function () {
		salvarValidarCadastro();
	});

	$(".radio-tipo-grupo-8").live('click', function () {
		exibirTipoPessoa();
	});
	exibirTipoPessoa();



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
		$("#select-motivo-follow").val("").trigger('chosen:updated');
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



	$(".mascara-cep").live('blur', function () {
		if((soNumero($(this).val()).length)==8){
			tipoEndereco = "";
			if ($(this).attr('tipo-endereco').length>0){
				tipoEndereco = '-'+$(this).attr('tipo-endereco');
			}
			localizaEndereco($(this).val(),tipoEndereco);
		}
	});

	/********* NOVO WORKFLOW ***********/
	$(".novo-workflow").live('click', function () {
		$('#localiza-cadastro-id').val($(this).attr('cadastro-id'));
		$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-pedido");
		$("#frmDefault").submit();
	});

	$("#botao-novo-pedido").live('click', function () {
		salvarValidarCadastro('novo-pedido');
	});

	/********* VISUALIZA WORKFLOW ***********/
	$(".editar-workflow").live('click', function () {
		$('#localiza-workflow-id').val($(this).attr('workflow-id'));
		$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-pedido");
		$("#frmDefault").submit();
	});

	$("#botao-abrir-pedido").live('click', function () {
		salvarValidarPedido();
	});
	$("#botao-cadastra-workflow").live('click', function () {
		salvarValidarPedido();
	});
	$("#botao-reabrir-pedido").live('click', function () {
		salvarValidarPedido();
	});


	/******** FUNCOES DE CRIACAO DE PEDIDO *********/

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
	if (($("#pedido-solicitante-id").val()=="") && ($("#pedido-solicitante-id").length>0)){
		$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-pedidos");
		$("#frmDefault").submit();
	}




	$("#select-produtos").live('change', function () {
		carregarDadosProduto();
	});

	var contador = 0;
	$("#botao-adicionar-produto").live('click', function () {
		flag = 0;
		$("#select-produtos").css('background-color', '').css('outline', '');
		if ($('#select-produtos').val()==""){$("#select-produtos").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		contador++;
		qtdeMaxima = $('#quantidade-maxima-produtos-pedido').val();
		if (flag==0){
			qtde = 0;
			$(".quantidade-pedido").each(function(){
				qtde = (qtde + parseInt($(this).attr('value')));
			});
			if (qtde>=qtdeMaxima){
				alert("Não é possivel inserir novos Produtos quantidade máxima por pedido: " + qtdeMaxima);
				$('#select-produtos option').removeAttr("selected");
				carregarDadosProduto();
				return;
			}

			$("#div-produtos").css('background-color', '').css('outline', '');
			produtoVariacaoID = $("#select-produtos").val();
			descricaoProduto = $("#select-produtos option:selected").text();
			quantidadeProduto = $("#quantidade-produto").val();
			valorVendaUnit = ReplaceAll($("#valor-venda-unitario").val(),",",".");
			valorCustoUnit = ReplaceAll($("#valor-custo-unitario").val(),",",".");
			valorTotalProduto = (quantidadeProduto * valorVendaUnit);

			prod = "<div id='div-produto-numero-" + contador +"'>" +
				   "	<div class='produtos-flag esconde'></div>" +
				   "	<div class='titulo-secundario fundo-claro' Style='height:30px; width:50%; float:left;' align='left'>" + descricaoProduto + "</div>" +
				   "	<div class='titulo-secundario fundo-claro esconde' Style='height:30px; width:10%; float:left;' align='right'>" + quantidadeProduto + "</div>" +
				   "	<div class='titulo-secundario fundo-claro' Style='height:30px; width:15%; float:left;' align='right'>R$ " + number_format(valorVendaUnit,'2',',','.') + "</div>" +
				   "	<div class='titulo-secundario fundo-claro esconde' Style='height:30px; width:15%; float:left;' align='right'>R$ " + number_format(valorTotalProduto,'2',',','.') + "</div>" +
				   "	<div class='titulo-secundario fundo-claro esconde' Style='height:30px; width:05%; float:left;'><div class='btn-excluir btn-excluir-produto' title='Excluir' produto-numero='" + contador +"'>&nbsp;</div>" +
				   "	<div class='titulo-secundario' Style='height:30px; width:05%; float:left;'>" +
				   "		<input type='hidden' name='produto-variacao-id-pedido[]'  value='" + produtoVariacaoID + "'/>" +
				   "		<input type='hidden' name='valor-venda-pedido[]' value='" + valorVendaUnit + "'/>" +
				   "		<input type='hidden' name='valor-custo-pedido[]' value='" + valorCustoUnit + "'/>" +
				   "		<input type='hidden' name='quantidade-pedido[]' class='quantidade-pedido' value='" + quantidadeProduto + "'/>" +
				   "		<input type='hidden' name='descricao-produto[]' value='" + descricaoProduto + "'/>" +
				   "	</div>" +
				   "</div>";
			$('#div-produtos').append(prod);
			$('#select-produtos option').removeAttr("selected");
			carregarDadosProduto();
		}
	});
	$(".btn-excluir-produto").live('click', function () {
		 produtoNumero = $(this).attr('produto-numero');
		 $("#div-produto-numero-" + produtoNumero).html("");
		 $("#div-produto-numero-" + produtoNumero).hide();
	});



	$("#btn-copiar-colar").live('click', function () {
		tipoEnderecoDe = $(this).attr('tipo-endereco-de');
		tipoEnderecoPara = $(this).attr('tipo-endereco-para');
		$("#cep-endereco-"+tipoEnderecoPara).val($("#cep-endereco-"+tipoEnderecoDe).val());
		$("#logradouro-endereco-"+tipoEnderecoPara).val($("#logradouro-endereco-"+tipoEnderecoDe).val());
		$("#numero-endereco-"+tipoEnderecoPara).val($("#numero-endereco-"+tipoEnderecoDe).val());
		$("#bairro-endereco-"+tipoEnderecoPara).val($("#bairro-endereco-"+tipoEnderecoDe).val());
		$("#cidade-endereco-"+tipoEnderecoPara).val($("#cidade-endereco-"+tipoEnderecoDe).val());
		$("#uf-endereco-"+tipoEnderecoPara).val($("#uf-endereco-"+tipoEnderecoDe).val());
		$("#referencia-endereco-"+tipoEnderecoPara).val($("#referencia-endereco-"+tipoEnderecoDe).val());
		$("#complemento-endereco-"+tipoEnderecoPara).val($("#complemento-endereco-"+tipoEnderecoDe).val());
	});

});

function carregarDadosProduto(){
	$("#div-dados-produto").hide();
	if (($("#select-produtos").val()!="") && ($("#select-produtos").val()!=null)){
		caminho = caminhoScript+"/modulos/telemarketing/telemarketing-carregar-dados-produto.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#div-dados-produto").show();
					$("#div-dados-produto").html(retorno);
				}
		});
	}
}


function salvarValidarCadastro(tipo){
	$("#cadastro-nome").css('background-color', '').css('outline', '');
	$("#cadastro-cpf").css('background-color', '').css('outline', '');
	$("#cadastro-email").css('background-color', '').css('outline', '');

	$("#cadastro-nome-fantasia").css('background-color', '').css('outline', '');
	$("#cadastro-cnpj").css('background-color', '').css('outline', '');
	$("#cadastro-telefone-residencial").css('background-color', '').css('outline', '');
	$("#cadastro-telefone-comercial").css('background-color', '').css('outline', '');
	$("#cadastro-telefone-celular").css('background-color', '').css('outline', '');

	$(".tipo-endereco").each(function(){
		tipoEndereco = $(this).val();
		$("#cep-endereco-"+tipoEndereco).css('background-color', '').css('outline', '');
		$("#logradouro-endereco-"+tipoEndereco).css('background-color', '').css('outline', '');
		$("#numero-endereco-"+tipoEndereco).css('background-color', '').css('outline', '');
		$("#bairro-endereco-"+tipoEndereco).css('background-color', '').css('outline', '');
		$("#cidade-endereco-"+tipoEndereco).css('background-color', '').css('outline', '');
		$("#uf-endereco-"+tipoEndereco).css('background-color', '').css('outline', '');
	});

	flag = 0;
	if(($(".radio-tipo-grupo-8:checked").val())=="24"){
		if ($.trim($('#cadastro-nome').val())==""){$("#cadastro-nome").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#cadastro-cpf').val()==""){$("#cadastro-cpf").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#cadastro-telefone-residencial').val()==""){$("#cadastro-telefone-residencial").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#cadastro-telefone-celular').val()==""){$("#cadastro-telefone-celular").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}
	if(($(".radio-tipo-grupo-8:checked").val())=="25"){
		if ($.trim($('#cadastro-nome-fantasia')).val()==""){ $("#cadastro-nome-fantasia").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#cadastro-cnpj').val()==""){ $("#cadastro-cnpj").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#cadastro-telefone-comercial').val()==""){$("#cadastro-telefone-comercial").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#cadastro-telefone-celular').val()==""){$("#cadastro-telefone-celular").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}
	$(".tipo-endereco").each(function(){
		tipoEndereco = $(this).val();
		if ($('#cep-endereco-'+tipoEndereco).val()==""){ $("#cep-endereco-"+tipoEndereco).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($.trim($('#logradouro-endereco-'+tipoEndereco).val())==""){ $("#logradouro-endereco-"+tipoEndereco).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($.trim($('#numero-endereco-'+tipoEndereco).val())==""){ $("#numero-endereco-"+tipoEndereco).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($.trim($('#bairro-endereco-'+tipoEndereco).val())==""){ $("#bairro-endereco-"+tipoEndereco).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($.trim($('#cidade-endereco-'+tipoEndereco).val())==""){ $("#cidade-endereco-"+tipoEndereco).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($.trim($('#uf-endereco-'+tipoEndereco).val())==""){ $("#uf-endereco-"+tipoEndereco).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	});

	if (flag==0){
		caminho = caminhoScript+"/modulos/telemarketing/telemarketing-cadastro-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				if (tipo=='novo-pedido'){
					$('#localiza-cadastro-id').val($.trim(retorno));
					$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-pedido");
					$("#frmDefault").submit();
				}
				else{
					alert("Cadastro salvo com sucesso!");
					$('#localiza-cadastro-id').val($.trim(retorno));
					$("#frmDefault").submit();
				}
			}
		});
	}

}

function carregarProdutosSelect(){
	cidade = $(".endereco-38").attr('cidade-endereco');
	uf = $(".endereco-38").attr('uf-endereco');
	caminho = caminhoScript+"/modulos/telemarketing/telemarketing-carregar-produtos-select.php?uf="+uf+"&cidade="+cidade;
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
			$("#select-lista-produtos").html(retorno);
			carregarDadosProduto();
		}
	});
}

function carregarRepresentante(){
	cidade = $(".endereco-38").attr('cidade-endereco');
	uf = $(".endereco-38").attr('uf-endereco');
	caminho = caminhoScript+"/modulos/telemarketing/telemarketing-carregar-representante.php?uf="+uf+"&cidade="+cidade;
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
			$("#div-representante").html(retorno);
		}
	});
}

/*
function carregarPrestadoresSelect(){
	caminho = caminhoScript+"/modulos/telemarketing/telemarketing-carregar-prestador-select.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
			$("#select-lista-prestadores").html(retorno);
		}
	});
}
*/

function abreTelaCadastro(){
	$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-cadastro");
	$("#frmDefault").submit();
	/*
	$("<div id='div-destaque' Style='position:absolute;width:100%;background-color:#000000;opacity:0.7;'></div>").appendTo("body");
	$("#div-destaque").height($(document).height());
	$("#div-destaque").width("100%");
	$("#div-destaque").show();

	//alturaDiv = parseInt(mouseTopClick - 300);
	alturaDiv = parseInt(-200);
	$("#div-novo-cadastro").css("margin-top", alturaDiv+"px")
	if ($(document).width()>800){
		marginLeft = (($(document).width() - 800) / 2);
		$("#div-novo-cadastro").css("margin-left", marginLeft+"px")
	}

	caminho = caminhoScript+"/modulos/telemarketing/telemarketing-cadastro.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-novo-cadastro").html($.trim(retorno));
			exibirTipoPessoa();
		}
	});
	$("#div-novo-cadastro").show();
	*/
}

function exibirTipoPessoa(){
	if(($(".radio-tipo-grupo-8:checked").val())=="24"){
		$(".div-pf").show();
		$(".div-pj").hide();
		$("#cadastro-cpf").select();
		$("#cadastro-cpf").focus();
	}
	if(($(".radio-tipo-grupo-8:checked").val())=="25"){
		$(".div-pj").show();
		$(".div-pf").hide();
		$("#cadastro-cnpj").select();
		$("#cadastro-cnpj").focus();
	}
}


function salvarValidarPedido(){
	flag = 0;
	if ($("#workflow-id").val()==""){
		$("#tipo-processo-id").css('background-color', '').css('outline', '');
		$("#div-produtos").css('background-color', '').css('outline', '');
		$("#codigo-pedido").css('background-color', '').css('outline', '');
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
		$("#codigo-pedido").css('background-color', '').css('outline', '');
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
		caminho = caminhoScript+"/modulos/telemarketing/telemarketing-pedido-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#localiza-workflow-id").val(retorno);
				if ($("#workflow-id").val()==""){
					$.ajax("http://clarotvlivre.brasilsat.com.br/sistema/modulos/telemarketing/envia-email-pedido.php?workflowID="+retorno)
					.done(function(){
						$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-pedido");
						$("#frmDefault").submit();
					});
				}else{
					$("#frmDefault").attr("action",caminhoScript+"/telemarketing/telemarketing-pedido");
					$("#frmDefault").submit();
				}
			}
		});
	}

}


function verificarCpf(campo,excessao){
	//alert(excessao);
	if((soNumero(campo.value).length)==11){
		if(!(ValidarCPF(campo.value))){
			alert("CPF Inválido");
			campo.focus();
			campo.select();
		}else{
			retorno = $.ajax(caminhoScript+"/modulos/telemarketing/telemarketing-validar-cadastro.php?campo="+campo.value+"&cadastro-id="+excessao)
			.done(function(){
				if ($.trim(retorno.responseText)!=""){
					alert("Já existe um Cadastro com o CPF Informado");
					campo.focus();
					campo.select();
				}
			});
		}
	}
}


function verificarCnpj(campo, excessao){
	if((soNumero(campo.value).length)==14){
		if(!(ValidarCNPJ(campo.value))){
			alert("CNPJ Inválido");
			campo.focus();
			campo.select();
		}else{
			retorno = $.ajax(caminhoScript+"/modulos/telemarketing/telemarketing-validar-cadastro.php?campo="+campo.value+"&cadastro-id="+excessao)
			.done(function(){
				if ($.trim(retorno.responseText)>1){
					alert("Já existe um Cadastro com o CNPJ Informado");
					campo.focus();
					campo.select();
				}
			});
		}
	}
}


$(document).ready(function(){
	$("#forma-pagamento").live('change', function () {
		$("#frame-abre-pagamento-pedido").hide();
		$("#frame-abre-pagamento-pedido").attr("src", "");
		$("#tipo-cartao-pgto").hide();
		if($(this).val() == "c"){
			$("#tipo-cartao-pgto").show();
		}else if($(this).val() == "b"){
			pedido = $("#workflow-id").val();
			fornecedor = $("#fornecedor-id").val();
			$("#frame-abre-pagamento-pedido").show();
			$("#frame-abre-pagamento-pedido").height(290);
			$('html, body').animate({scrollTop: $("#ancora-pagamento").offset().top }, 500);
			$("#frame-abre-pagamento-pedido").attr("src", "../modulos/gateway/boletos/index.php?pid="+pedido+"&representante="+fornecedor);
		}else{
			$("#frame-abre-pagamento-pedido").hide();
		}
	});

	$(".rd-forma-pagamento").live('click', function () {
		$("#frame-abre-pagamento-pedido").hide();
		$("#frame-abre-pagamento-pedido").attr("src", "");
		if($("#parcelamento-cartao-valida").val()=='n'){
			$("#frame-abre-pagamento-pedido").height(680);
			pedido = $("#workflow-id").val();
			fornecedor = $("#fornecedor-id").val();
			$("#frame-abre-pagamento-pedido").show();
			$('html, body').animate({scrollTop: $("#ancora-pagamento").offset().top }, 500);
			$("#frame-abre-pagamento-pedido").attr("src", "../modulos/gateway/cielo/index.php?pid="+pedido);
			return false;
		}
	});
	$(".seleciona-parcelas").live('click', function () {
			$("#frame-abre-pagamento-pedido").height(680);
		$("#frame-abre-pagamento-pedido").hide();
		$("#frame-abre-pagamento-pedido").attr("src", "");
		$("#parcelamento-cartao").hide();
		$("#frame-abre-pagamento-pedido").show();
		$('html, body').animate({scrollTop: $("#ancora-pagamento").offset().top }, 500);
		cartao = $("#seleciona-cartao").val();
		pedido = $("#workflow-id").val();
		fornecedor = $("#fornecedor-id").val();
		parcelasPGTO = $(this).val();
		$("#frame-abre-pagamento-pedido").attr("src", "../modulos/gateway/cielo/pages/carrinho.php?tp="+cartao+"&pid="+pedido+"&parcelasPGTO="+parcelasPGTO+"&revenda="+fornecedor);
		return false;
	});
});

/* RELATORIOS*/
$(document).ready(function(){
	$("#botao-relatorio-dinamico").live('click', function () {
		$("#frmDefault").submit();
	});
});
