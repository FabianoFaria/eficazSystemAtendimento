$(document).ready(function(){
	$("#botao-localizar-requisicao").click(function() {
		$("#frmDefault").submit();
	});
	$(".required").blur(function() {
		if($(this).val()!='')$(".required").css('background-color', '#f6f6f6').css('outline', '1px solid #dddddd');
	});
	$(".cadastra-solicitacao").live('click', function () {
		$("#localiza-solicitacao-id").val($(this).attr("solicitacao-id"));
		$("#frmDefault").attr("action",caminhoScript+"/compras/compras-requisicao-cadastro");
		$("#frmDefault").submit();
	});
	$(".seleciona-todas-aceitar").click(function() {
		$("#retorno-erro").html('');
		$(".compras-aceitar").attr("checked", true);
		$(".compras-negar").attr("checked", false);
	});
	$(".seleciona-todas-negar").click(function() {
		$("#retorno-erro").html('');
		$(".compras-aceitar").attr("checked", false);
		$(".compras-negar").attr("checked", true);
	});
	$("#compras-gerar-ordem-compra").click(function() {
		var required = 0;
		$(".compras-validar").each(function(){
			if($(this).attr('checked')) required = required+ 1;
		});
		if(required==0){
			$("#retorno-erro").html('<b>ERRO:</b> Nenhuma requisição selecionada!');
		}else{
			if(confirm("Confirma a atualização das requisições?")){
				$("#frmDefault").attr("action",caminhoScript+"/modulos/compras/compras-gerar-ordem-compra.php");
				$("#frmDefault").submit();
			}
		}
	});
	$(".compras-aceitar").click(function() {
		$("#retorno-erro").html('');
		posicaoInicio = $(this).attr('posicao');
		$(".compras-negar").each(function(){
			if(posicaoInicio==$(this).attr('posicao'))
				$(this).attr('checked', false);
		});
	});
	$(".compras-negar").click(function() {
		$("#retorno-erro").html('');
		posicaoInicio = $(this).attr('posicao');
		$(".compras-aceitar").each(function(){
			if(posicaoInicio==$(this).attr('posicao'))
				$(this).attr('checked', false);
		});
	});


	/* TELA REQUISIÇÃO*/
	/*
	$("#botao-localizar-produtos").live('click', function () {
		caminho = caminhoScript+"/modulos/compras/compras-carregar-produtos-select.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#div-produtos-select").html(retorno);
				$("#div-produtos-select").show();
				$("#div-produtos-incluir").show();
				$("#div-produtos-texto").hide();
				$("#div-produtos-localizar").hide();
			}
		});
	});
	$("#botao-cancelar-produtos").live('click', function () {
		if($("#div-produtos-select").html()!=""){
			$("#div-produtos-select").html("");
			$("#div-produtos-select").hide();
			$("#div-produtos-incluir").hide();
			$("#div-produtos-texto").show();
			$("#div-produtos-localizar").show();
		}else{
			$("#frmDefault").attr("action",caminhoScript+"/compras/compras-requisicao");
			$("#frmDefault").submit();
		}
	});
	*/
	$("#botao-incluir-produtos").live('click', function () {
		flag = 0;
		$("#select-produtos").css('background-color', '').css('outline', '');
		$("#texto-localizar-produtos").css('background-color', '').css('outline', '');
		$("#quantidade-produtos").css('background-color', '').css('outline', '');

		if ($("#select-produtos").val()==""){ $("#select-produtos").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); flag=1;}
		if ($("#quantidade-produtos").val()==""){ $("#quantidade-produtos").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); flag=1;}
		if ($("#quantidade-produtos").val()<=0){ $("#quantidade-produtos").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); flag=1;}
		if (flag==0){
			caminho = caminhoScript+"/modulos/compras/compras-salvar-requisicao.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#frmDefault").attr("action",caminhoScript+"/compras/compras-requisicao");
					$("#frmDefault").submit();
				}
			});
		}
	});
	/**/

	/* TELA VISUALIZA ORDEM DE COMPRA */

	$(".btn-cancelar-compra-produto").live('click', function () {
		var confirma=confirm("Tem certeza que deseja cancelar a requisição de compra deste produto?");
		if (confirma==true){
			$("#aux-produto-variacao-id").val($(this).attr("produto-variacao-id"));
			caminho = caminhoScript+"/modulos/compras/compras-excluir-produto-ordem-compra.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//$("#div-retorno").html(retorno);
					if ($("#cont-prod").val()>1){
						$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
						$("#frmDefault").submit();
					}
					else{
						$("#frmDefault").attr("action",caminhoScript+"/compras/compras-requisicao");
						$("#frmDefault").submit();
					}
				}
			});
		}
	});

	$(".botao-cancelar-oc").live('click', function () {
		var confirma=confirm("Tem certeza que deseja excluir a ordem de compra?\n\nNo caso da exclusão os produtos retornam para status de aguardando Resquisição aguardando avaliação.");
		if (confirma==true){
			caminho = caminhoScript+"/modulos/compras/compras-excluir-ordem-compra.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#frmDefault").attr("action",caminhoScript+"/compras/compras-requisicao");
					$("#frmDefault").submit();
				}
			});

		}
	});


	$(".botao-repetir-oc").live('click', function () {
		var confirma=confirm("Tem certeza que deseja repetir a ordem de compra?");
		if (confirma==true){
			caminho = caminhoScript+"/modulos/compras/compras-repetir-ordem-compra.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					alert("Nova Ordem de Compra Nº " + retorno + " gerada com sucesso");
					$("#ordem-compra-id").val(retorno);
					$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
					$("#frmDefault").submit();
				}
			});

		}
	});

	$(".nota-fiscal-pm").live('click', function () {
		$('.pm-nf').hide()
		$('.pm-nf-' + $(this).attr('pmID')).show()
	});
	$(".btn-cancelar-nf").live('click', function () {
		$('.pm-nf').hide()
	});
	$(".btn-atualizar-nf").live('click', function () {
		$('.pm-nf').hide()
		pmID = $(this).attr('pmID');
		nf = $('#nota-fiscal-'+pmID).val();
		caminho = caminhoScript+"/modulos/compras/compras-atualizar-nfe-movimentacao.php?pmID=" + pmID + "&nf=" + nf;
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#frmDefault").submit();
			}
		});
	});







	/*
	function confirmar(mensagem, sim, nao){
		if(sim=="") sim=="Sim";
		if(nao=="") nao=="Não";
		$("#div-fundo").remove();
		$("<div id='div-fundo' Style='position:absolute; left:0px; top: 0px; right: 0px; background-color:#000000; opacity:0.7;'></div>").appendTo("body");
		$("#div-fundo").height($(document).height());
		$("#div-fundo").width("100%");
		$("#div-fundo").show();
		html =  " <div id='div-confirmar' style='position:absolute; width:800px; height:250px; z-index:100; overflow-X:auto; display:none; border-radius:15px;'> " +
				"	<table width='100%' height='100%' border='0' cellspacing='10' cellpadding='4' bgcolor='#F9F9F9' style='border: black 1px solid'> " +
				"		<tr> " +
				"			<td colspan='2' valign='top'> " +
				"				<p style='margin:5px'><b>"+ mensagem + "</b></p>" +
				"			</td>" +
				"		</tr>" +
				"		<tr>" +
				"			<td align='center' width='50%'><input type='button' id='botao-submeter-email' name='botao-submeter-email' Style='width:150px' value='Enviar'></td>" +
				"			<td align='center' width='50%'><input type='button' id='botao-cancelar-email' name='botao-cancelar-email' Style='width:150px' value='Cancelar'></td>" +
				"		</tr>" +
				"	</table>" +
				"</div>";

		alturaDiv = parseInt(mouseTopClick - 300);
		$("#div-confirmar").css("margin-top", alturaDiv+"px")
		if ($(document).width()>800){
			marginLeft = (($(document).width() - 800) / 2);
			$("#div-confirmar").css("margin-left", marginLeft+"px")
		}
		$("#div-confirmar").show();
	}
	*/


	$("#botao-salvar-oc, #botao-enviar-orcamento").live('click', function () {
		flag = 0;
		$("#botao-clicado").val($(this).attr("id"));
		$("#data-limite-retorno").css('background-color', '').css('outline', '');
		//if ($('#data-limite-retorno').val()==""){ $("#data-limite-retorno").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if (flag==0){
			caminho = caminhoScript+"/modulos/compras/compras-salvar-ordem-compra.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					if ($("#botao-clicado").val()=="botao-enviar-orcamento")
						alert("Ordem de compra enviada para orçamento");
					if ($("#botao-clicado").val()=="botao-salvar-oc")
						alert("Ordem de compra salva com sucesso");
					//$("#div-retorno").html(retorno);
					$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
					$("#frmDefault").submit();
				}
			});
		}
	});

	$(".valor-manual-compra").live('blur', function () {
		valorCustoItem = desformataValor($("#valor-custo-item-"+$(this).attr('attd-id')).html());
		if(($(this).val().replace('.','').replace(',','.')<=0)||$(this).val()==''){
			valorTotalCompra = eval(valorCustoItem)*eval(quantidadeAprovada);
			$(this).val(number_format(valorCustoItem, '2', ',', '.'));
		}
		calcularTotalCompra();
	});
	$(".valor-manual-compra").live('keyup', function () {
		$(this).css('background-color', '#f6f6f6').css('outline', '1px solid #dddddd');

		quantidadeAprovada	 = desformataValor($("#quantidade-aprovada-"+$(this).attr('attd-id')).val());
		quantidadeSolicitada = desformataValor($("#quantidade-item-"+$(this).attr('attd-id')).html());
		valorCustoItem = desformataValor($("#valor-custo-item-"+$(this).attr('attd-id')).html());

		if(desformataValor($(this).val()) > 0)
			valorTotalCompra = eval(desformataValor($(this).val())) * eval(quantidadeAprovada);
		else
			valorTotalCompra = eval(valorCustoItem) * eval(quantidadeAprovada);

		$("#valor-total-"+$(this).attr('attd-id')).html(number_format(valorTotalCompra, '2', ',', '.'));

		calcularTotalCompra();
	});

	$(".quantidade-aprovada-compra").live('keyup', function () {
		$(this).css('background-color', '#f6f6f6').css('outline', '1px solid #dddddd');
		valorCustoItem = $("#valor-custo-item-"+$(this).attr('attd-id')).html().replace('.','').replace(',','.');
		valorAtualItem = $("#valor-produto-item-"+$(this).attr('attd-id')).val().replace('.','').replace(',','.');
		if(valorAtualItem > 0)
			valorTotalCompra = eval($(this).val().replace('.','').replace(',','.'))*eval(valorAtualItem);
		else
			valorTotalCompra = eval($(this).val().replace('.','').replace(',','.'))*eval(valorCustoItem);
		$("#valor-total-"+$(this).attr('attd-id')).html(number_format(valorTotalCompra, '2', ',', '.'));

		calcularTotalCompra();
	});

	$(".workflow-compra").live('click', function () {
		$("#ordem-compra-id").val($(this).attr("ordem-compra-id"));
		$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
		$("#frmDefault").submit();
	});


	/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
	$("#menu-superior-6").live('click', function () {
		carregarDocumentos($("#ordem-compra-id").val(),'compras');
	});

	function calcularTotalCompra(){
		valorTotal = 0;
		$('.valor-total-produto').each(function(){
			valorTotal = valorTotal + parseFloat(desformataValor($(this).html()));
		});
		$("#total-geral-compra").html(number_format(valorTotal, '2', ',', '.'));
	}

	$("#botao-finalizar-oc, #botao-enviar-aprovacao, #botao-aprovar-oc, #botao-reprovar-oc").live('click', function () {
		$("#cadastro-id").css('background-color', '').css('outline', '');
		var erro = 0;
		if ($(this).attr("id")=="botao-finalizar-oc"){
			if (($("#cadastro-id").val()=="")||($("#cadastro-id").val()=="0")){
				$("#cadastro-id").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				erro=1;
			}
		}

		$("#botao-clicado").val($(this).attr("id"));
		$('.quantidade-aprovada-compra').each(function(){
			if(($(this).val().replace('.','').replace(',','.')<=0)||$(this).val()==''){erro=1;$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD')};
		});
		$('.valor-manual-compra').each(function(){
			if(($(this).val().replace('.','').replace(',','.')<=0)||$(this).val()==''){erro=1;$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD')};
		});
		$('.fornecedor-compras-produtos').each(function(){
			if(($(this).val().replace('.','').replace(',','.')<=0)||$(this).val()==''){erro=1;$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD')};
		});
		if(erro==0){
			if ($('#enviar-email').is(":checked")){
				confirmarEnvioEmail();
			}
			else{
				submeteOC();
			}
		}
	});

	$("#div-destaque").live('click', function () {
		$("#div-destaque").hide();
		$("#div-email").hide();
	});
	$("#botao-cancelar-email").live('click', function () {
		$("#div-destaque").hide();
		$("#div-email").hide();
	});
	$("#botao-submeter-email").live('click', function () {
		arrEmails = $("#emails-envio").val().split(";");
		emailsInvalidos = "";
		for (i=0; i < arrEmails.length; i++){
			if ($.trim(arrEmails[i])!=""){
				if ((validaEmailString($.trim(arrEmails[i])))==false){
					emailsInvalidos += "\n" + arrEmails[i] + " ";
				}
			}
		}
		if (emailsInvalidos == ""){
			if ($("#botao-clicado").val()=="botao-reabrir-oc")
				submeteReabreOC();
			else
				submeteOC();
		}
		else
			alert("Os seguintes emails são inválidos:"+emailsInvalidos);
	});


	function submeteOC(){
		caminho = caminhoScript+"/modulos/compras/compras-atualizar-ordem-compra.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				if ($("#botao-clicado").val()=="botao-finalizar-oc") alert("Ordem de compra finalizada e enviada para faturamento");
				if ($("#botao-clicado").val()=="botao-enviar-aprovacao") alert("Ordem de compra enviada para aprovação");
				if ($("#botao-aprovar-oc").val()=="botao-aprovar-oc") alert("Ordem de compra aprovada");
				if ($("#botao-reprovar-oc").val()=="botao-reprovar-oc") alert("Ordem de compra reprovada");
				//alert(retorno);
				//$("body").append(retorno);
				$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
				$("#frmDefault").submit();
			}
		});
	}

	function confirmarEnvioEmail(){
		$("#div-destaque").remove();
		$("<div id='div-destaque' Style='position:absolute; left:0px; top: 0px; right: 0px; background-color:#000000; opacity:0.7;'></div>").appendTo("body");
		$("#div-destaque").height($(document).height());
		$("#div-destaque").width("100%");
		$("#div-destaque").show();

		alturaDiv = parseInt(mouseTopClick - 300);
		$("#div-email").css("margin-top", alturaDiv+"px")

		if ($(document).width()>800){
			marginLeft = (($(document).width() - 800) / 2);
			$("#div-email").css("margin-left", marginLeft+"px")
		}
		$("#div-email").show();
		emails = "";
		$(".email-workflow").each(function(){
			if ($(this).val()!=""){
				validaEmail = validaEmailString($(this).val());
				if ((emails.indexOf($(this).val())==-1) && (validaEmail)){
					emails = emails + $(this).attr('value') + "; ";
				}
			}
		});
		$("#emails-envio").val(emails);
	}


	$('.fornecedor-compras-produtos').live('change', function () {
		$(this).css('background-color', '#f6f6f6').css('outline', '1px solid #dddddd');
	});


	$("#botao-reabrir-oc").live('click', function () {
		$("#botao-clicado").val($(this).attr("id"));
		if ($('#enviar-email').is(":checked"))
			confirmarEnvioEmail();
		else
			submeteReabreOC();
	});

	function submeteReabreOC(){
		caminho = caminhoScript+"/modulos/compras/compras-reabrir-ordem-compra.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
				$("#frmDefault").submit();
			}
		});
	}


	/*LINK CHAMADOS*/
	$(".link-chamados").live('click', function () {
		$("#workflow-id").val($(this).attr('workflow-id'));
		$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-cadastro-chamado");
		$("#frmDefault").submit();
	});
	/**/

	$(".link-produto").live('click', function () {
		$("#produto-id").val($(this).attr('produto-id'));
		$("#frmDefault").attr("action",caminhoScript+"/produtos/produtos-cadastrar");
		$('#frmDefault').attr("target","_blank");
		$("#frmDefault").submit();
		$('#frmDefault').attr("target","");

	});

	if (slugPagina=="compras-localizar"){
		/*LOCALIZAR COMPRAS*/
		$("#botao-localizar-compras").live('click', function () {
			$("#frmDefault").submit();
		});
		/**/
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-compras").click(); }});
	}


	$(".link-conta").live('click', function () {
		$("#localiza-conta-id").val($(this).attr('localiza-conta-id'));
		$("#localiza-titulo-id").val($(this).attr('localiza-titulo-id'));
		caminho = caminhoScript+"/financeiro/financeiro-lancamento";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});

	$(".link-aguardando-fat").live('click', function () {
		caminho = caminhoScript+"/financeiro/financeiro-aguardando-faturamento";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});


	$(".atalho-estoque").live('click', function () {
		$("#frmDefault").attr("action",caminhoScript+"/produtos/produtos-movimentacao-material");
		$("#frmDefault").submit();
	});





});
