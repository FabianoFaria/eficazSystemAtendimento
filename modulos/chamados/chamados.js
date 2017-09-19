/********* Inicio Funções Localiza Chamado ***********/
$(document).ready(function(){
	$("#botao-localizar-chamado").live('click', function () {
		if(validaCamposObrigatorios())
			$("#frmDefault").submit();
	});
	$(".workflow-localiza").live('click', function () {
		$("#workflow-id").val($(this).attr('workflow-id'));
		$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-cadastro-chamado");
		$("#frmDefault").submit();
	})
	if ($("#slug-pagina").val()=="chamados-localizar-chamado"){
		$("#localiza-chamado-solicitante").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-chamado").click(); }});
	}
	$(".formata-data-hora").click();

	/*
	$(".exibir-tarefas").live('click', function () {
		//alert($(this).attr('workflow-id'));
	});
	*/
});


/********* Inicio Funções Tela de Chamado ***********/

$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");
	if ($("#slug-pagina").val()=="chamados-cadastro-chamado"){
		if ($("#workflow-id").val()==""){
			$("#texto-cadastro-localiza-solicitante-id").focus();
		}
		else{
			$('#div-produtos-incluir-editar').hide();
			//carregarProdutos();
			carregarCompras();
			carregarFinanceiro();

			/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
			$("#menu-superior-3").live('click', function () {
				carregarDocumentos($("#workflow-id").val(),'chamados');
			});
		}
		exibirDataFinalizado();

		$("#select-grupo-chamado").live('change', function () {
			campo = $(this).attr('campo');
			$.ajax({type: "POST",url: caminhoScript+"/modulos/chamados/chamados-localiza-usuarios-grupo.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#" + campo).empty().append(retorno).trigger('chosen:updated');
				}
			});
		});

		$("#select-prioridade").live('change', function () {
			calculaDataRetorno();
		});
		$("#data-abertura-chamado").live('change', function () {
			calculaDataRetorno();
		});

		/*CONTADOR*/

		if ($("#data-limite").val()!=""){
			iniciaContador($("#data-limite").val(), 'div-tempo-restante');
		}

		$("#data-limite").live('change', function () {
			iniciaContador($("#data-limite").val(), 'div-tempo-restante');
		});


		$(".link-conta").live('click', function () {
			/*
			$("#localiza-conta-id").val($(this).attr('localiza-conta-id'));
			//$("#localiza-titulo-id").val($(this).attr('localiza-titulo-id'));
			caminho = caminhoScript+"/financeiro/financeiro-lancamento";
			$('#frmDefault').attr("action",caminho);
			$("#frmDefault").submit();
			*/
			dados = $('#frmDefault').serialize();
			caminho = caminhoScript+"/financeiro/financeiro-lancamento?localiza-conta-id="+$(this).attr('localiza-conta-id')+'&tipo=direto';
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2
			});
		});

		$(".link-aguardando-fat").live('click', function () {
			$("#radio-tipo-grupo").val($(this).attr('tipo-id'));
			caminho = caminhoScript+"/financeiro/financeiro-aguardando-faturamento";
			$('#frmDefault').attr("action",caminho);
			$("#frmDefault").submit();
		});

		/* ORDEM COMPRA*/

		$(".link-ordem-compra").live('click', function () {
			$("#ordem-compra-id").val($(this).attr("ordem-compra-id"));
			$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
			$("#frmDefault").submit();
		});
		$(".link-requisicao").live('click', function () {
			$("#localiza-requisicao-id").val($(this).attr("requisicao-id"));
			$("#frmDefault").attr("action",caminhoScript+"/compras/compras-requisicao");
			$("#frmDefault").submit();
		});

		/* GERAR PROPOSTA */
		$("#proposta-gerar").live('click', function () {
			caminho = caminhoScript+"/modulos/chamados/chamados-proposta-execucao-excel.php";
			var form = $('#frmDefault');
			form.attr("action",caminho);
			form.attr("enctype", "");
			form.attr("encoding", "");
			$("#frmDefault").submit();
		});

		/*CHAMADO*/

		$("#botao-cadastra-workflow, #botao-salva-workflow").live('click', function () {
			if ($('#select-situacao-follow').val()=="34")
				$('#data-finalizado-chamado').addClass('required');
			else
				$('#data-finalizado-chamado').removeClass('required');

			if(validarCamposGenerico("#div-solicitante-dados .required, #div-chamado-dados .required, #div-situacao-chamado .required")){
				if ($('#enviar-email').is(":checked")){
					confirmarEnvioEmail();
				}else{
					submeterSalvarChamado();
				}
			}
		});


		$("#botao-reabrir-chamado").live('click', function () {
			flag = 0;
			$("#descricao-motivo-reabertura").show();
			$("#descricao-follow").css('background-color', '').css('outline', '');
			if ($('#descricao-follow').val().trim()==""){ $("#descricao-follow").css('background-color', '#FFE4E4').focus();flag=1;}
			if(flag==0){
				if ($('#enviar-email').is(":checked")){
					confirmarEnvioEmail();
				}else{
					submeterSalvarChamado();
				}
			}
		});

		$("#codigo-workflow").live('blur', function () {
			$("#codigo-workflow").css('background-color', '').css('outline', '');
			if ($("#codigo-workflow").val().trim()!=""){
				caminho = caminhoScript+"/modulos/chamados/chamados-validar-codigo.php?workflow-id="+$("#workflow-id").val()+"&codigo-workflow="+$("#codigo-workflow").val();
				$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						if ($.trim(retorno)!= "" ){
							$("#codigo-workflow").css('background-color', '#FFE4E4');
							var confirma=confirm(" Já existe um chamado com esta numeração cadastrada gostaria de visualizá-lo? \n (Obs. Caso opte por SIM (OK) você perderá os dados inseridos na atual tela");
							if (confirma==true){
								$("#workflow-id").val($.trim(retorno));
								$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-cadastro-chamado");
								$("#frmDefault").submit();
							}
						}
					}
				});
			}
		});

		/* SOLICITAÇÃO DE ENVIO PARA O CENTRO DE DISTRIBUIÇÃO */

		$(".check-envio").live('change', function () {
			verificaBotoesEnvio('check-envio');
		});
		$(".todos-cd").live('click', function () {
			verificaBotoesEnvio('todos-cd');
		});
		$(".radio-retorna").live('click', function () {
			if ($(this).val()=="1") $(".envios-exibe-campos-"+$(this).attr("workflow-produto-id")).show(); else $(".envios-exibe-campos-"+$(this).attr("workflow-produto-id")).hide();
		});

		$("#botao-solicitacao-envio, #botao-solicitacao-retirada").live('click', function () {
			flagEnvio = false;
			flagEnvio2 = true;
			acao = $(this).attr("acao");
			$("#acao-cd").val($(this).attr("acao"));
			$("#tipo-cd").val($(this).attr("tipo"));
			$(".check-envio").each(function(){
				$(".envios-retorna-"+$(this).attr("workflow-produto-id")).css('background-color', '').css('outline', '');
				$(".envios-embalado-"+$(this).attr("workflow-produto-id")).css('background-color', '').css('outline', '');
				if ($(this).prop('checked')){
					flagEnvio = true;
					retorno = $("#radio-retorna-" + $(this).attr("workflow-produto-id")+":checked").val();
					if((retorno!='0') && (retorno!="1")){
						$(".envios-retorna-"+$(this).attr("workflow-produto-id")).css('background-color', '#FFE4E4');
						flagEnvio2 = false;
					}
					embalado = $("#radio-embalado-" + $(this).attr("workflow-produto-id")+":checked").val();
					if((embalado!='0') && (embalado!="1")){
						$(".envios-embalado-"+$(this).attr("workflow-produto-id")).css('background-color', '#FFE4E4');
						flagEnvio2 = false;
					}
				}
			});
			if ((flagEnvio)&&(flagEnvio2)){
				var confirma=confirm("Tem certeza que deseja incluir uma nova solicitação de "+$(this).attr("acao")+"?");
				if (confirma==true){
					caminho = caminhoScript+"/modulos/envios/envios-solicitacao-envio.php";
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							$(".check-envio").prop('checked',false);
							carregarEnviosCD('');
							alertify.alert("<b>Aviso</b>", "Solicitação incluída com sucesso!");
							//$(".conjunto3").show();
						}
					});
				}
			}
			//else{
			//	alert("Selecionar ao menos um produto para "+acao);
			//}
		});



		$(".btn-excluir-envio").live('click', function () {
			envioID = $(this).attr("workflow-id");
			//alert($(this).attr("workflow-id"));
			var confirma=confirm("Tem certeza que deseja cancelar?");
			if (confirma==true){
				caminho = caminhoScript+"/modulos/chamados/chamados-envio-cd-cancelar.php?workflow-id="+envioID;
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						carregarEnviosCD('');
					}
				});
			}
		});

		/* LINK COM MODULO DE CENTRO DE DISTRIBUIÇÃO*/

		$(".workflow-envio").live('click', function () {
			$("#localiza-workflow-id").val($(this).attr("workflow-id"));
			$("#workflow-id").val($(this).attr("workflow-id"));
			$("#frmDefault").attr("action",caminhoScript+"/envios/envios-cadastro");
			$("#frmDefault").submit();
		});


		/*PRODUTOS*/

		$("#select-situacao-follow").live('change', function () {
			exibirDataFinalizado();
		});

		$(".btn-editar-produto-chamado").live('click', function () {
			workflowProdutoID = $(this).attr('chave-primaria-id');
			$(".botao-exibir-produto").hide();
			$(".btn-editar-produto-chamado").hide();
			$(".btn-excluir-produto-chamado").hide();
			exibirInserirAtualizarProduto(workflowProdutoID,"chamado");
		});

		$("#select-produtos").live('change', function () {
			if ($("#select-produtos").val()!=""){
				detalhesCampo= $.ajax(caminhoScript+"/modulos/chamados/carregar-produto-detalhes.php?produto-variacao-id="+$("#select-produtos").val()+"&workflow="+$("#workflow-id").val()+"&tipo=chamado")
				.done(function(){
					$("#div-detalhes-produto").html(detalhesCampo.responseText);
					calcularValoresTotais();
					carregarOptionValuePrestadoresProduto();
				});
				if ($(this).find(":selected").attr("faturamento-direto")=='1')
					$('#checkbox-faturamento-direto, #checkbox-pagamento-prestador').attr('checked',true);
				else
					$('#checkbox-faturamento-direto, #checkbox-pagamento-prestador').attr('checked',false);
			}
			else{
				$("#div-detalhes-produto").delay("fast").hide();
				calcularValoresTotais();
			}
		});

		$("#valor-venda-unitario").live('blur', function () {
			$("#valor-venda-unitario").css('background-color', '').css('outline', '');
			if($("#forma-cobranca").val()=="58"){
				valor 	= parseFloat(ReplaceAll(ReplaceAll($("#valor-venda-unitario").val(),".",""),",","."));
				valorMin = parseFloat(ReplaceAll(ReplaceAll($("#valor-venda-minima-unitario").val(),".",""),",","."));
				if (valor<valorMin){
					$(this).css('background-color', '#FFE4E4');
					$(this).focus();
					alertify.alert("<b>Aviso</b>", "Valor Informado Abaixo do valor mínimo de cobrança");
				}
			}
			calcularValoresTotais();
		});


		$("#valor-custo-unitario").live('blur', function () {
			if ($("#valor-custo-unitario").val()==""){
				$("#valor-custo-unitario").css('background-color', '#FFE4E4');
				$("#valor-custo-unitario").select();
			}
			else{
				$("#valor-custo-unitario").css('background-color', '').css('outline', '');
				calcularValoresTotais();
			}
		});

		$("#valor-custo-unitario").live('change', function () {
			calcularValoresTotais();
		});
		$('#valor-custo-unitario').live('keypress', function (e) {
			calcularValoresTotais();
		});
		$('#valor-custo-unitario').live('click keyup', function (e) {
			calcularValoresTotais();
		});
		$("#valor-custo-unitario").live('click', function () {
			$("#valor-custo-unitario").select();
		});

		$("#checkbox-pagamento-prestador").live('click', function () {
			calcularValoresTotais();
		});
		$("#checkbox-cobranca-cliente").live('click', function () {
			calcularValoresTotais();
		});

		$("#quantidade-produtos").live('blur', function () {
			calcularValoresTotais();
		});
		$("#quantidade-produtos").live('change', function () {
			calcularValoresTotais();
		});
		$('#quantidade-produtos').live('keypress', function (e) {
			calcularValoresTotais();
		});
		$('#quantidade-produtos').live('click keyup', function (e) {
			calcularValoresTotais();
		});


		/*
		$('.btn-editar-produto-workflow').live('click', function () {
			workflowProdutoID = $(this).attr('workflow-produto-id');
			caminho = caminhoScript+"/modulos/chamados/carregar-produto.php?workflow-produto-id="+workflowProdutoID;
			$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#div-produtos-incluir-editar').show();
					$("#div-produtos-incluir-editar").html($.trim(retorno));
					$('#div-produtos-select').show();
					$('#div-produtos-alterar').show();
					$('#div-produtos-texto').hide();
					$('#div-produtos-localizar').hide();
					$('#div-produtos').hide();
				}
			});
		});
		*/
		$('.btn-excluir-produto-chamado').live('click', function () {
			workflowProdutoID = $(this).attr('chave-primaria-id');
			var confirma=confirm("Tem certeza que deseja excluir o Produto?");
			if (confirma==true){
				retorno = $.ajax(caminhoScript+"/modulos/chamados/chamados-workflow-produto-excluir.php?workflow-produto-id="+workflowProdutoID)
				.done(function(){
					carregarProdutos();
					carregarFinanceiro();
				});
			}
		});

		/*
		$("#botao-localizar-produtos").live('click', function () {
			flag = 0;
			$("#texto-localizar-produtos").css('background-color', '').css('outline', '');
			//if ($("#texto-localizar-produtos").val().trim().length<3){ $("#texto-localizar-produtos").css('background-color', '#FFE4E4');flag=1;}
			if (flag==0){
				detalhesCampo= $.ajax(caminhoScript+"/modulos/chamados/carregar-chamados-select.php?workflow-id="+$("#workflow-id").val()+"&descricao="+$('#texto-localizar-produtos').val())
				.done(function(){
					$("#div-produtos-incluir").show();
					$("#div-produtos-select").show();
					$("#div-produtos-select").html(detalhesCampo.responseText);
					$("#select-produtos").focus();
					$("#div-produtos-localizar").hide();
					$("#div-produtos-texto").hide();
				});
			}
		});
		*/

		$("#div-destaque").live('click', function () {
			$("#div-destaque").hide();
			$("#div-email").hide();
		});
		$("#botao-cancelar-email-workflow").live('click', function () {
			$("#div-destaque").hide();
			$("#div-email").hide();
		});
		$("#botao-submeter-email-workflow").live('click', function () {
			submeterSalvarChamado();
		});

		$(".historico-financeiro").live('click', function () {
			id = $(this).attr('workflow-produto-id');
			tipo = $(this).attr('tipo');
			linha = $(this).attr('linha');
			if ($("#historico-fat-"+linha).is(':visible')){
				$("#historico-fat-"+linha).hide();
				$("#historico-fat-"+linha).html('');
			}
			else{
				caminho = caminhoScript+"/modulos/chamados/chamados-carregar-historico-financeiro.php?workflow-produto-id="+id+"&tipo="+tipo;
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$("#historico-fat-"+linha).show();
						$("#historico-fat-"+linha).html(retorno);
					}
				});
			}
		});

	}

	$(".faturar-rel-pagar, .faturar-rel-receber").live('click', function () {
		vericaMostrarBotaoFaturar();
	});


	$(".selecionar-todas").live('click', function () {
		tipo = $(this).attr('tipo');
		if ($('#todas-faturar-rel-'+tipo).is(':checked')){
			$('#todas-faturar-rel-'+tipo).attr('checked',false);
			$('.faturar-rel-'+tipo).attr('checked',false);
		}
		else{
			$('#todas-faturar-rel-'+tipo).attr('checked',true);
			$('.faturar-rel-'+tipo).attr('checked',true);
		}
		vericaMostrarBotaoFaturar();
	});


	$("#botao-faturar-relatorio").live('click', function () {
		caminho = caminhoScript+"/modulos/chamados/chamados-faturar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#frmDefault").submit();
			}
		});
	});
});


function calculaDataRetorno(){
	caminho = caminhoScript+"/modulos/chamados/chamados-calcula-data-retorno.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#data-limite").val(retorno);
			iniciaContador($("#data-limite").val(), 'div-tempo-restante');
		}
	});
}


function vericaMostrarBotaoFaturar(){
	flag = false;
	$(".faturar-rel-pagar").each(function(){if ($(this).prop('checked')){flag = true;}});
	$(".faturar-rel-receber").each(function(){if ($(this).prop('checked')){flag = true;}});
	if (flag) $('.botao-faturar-relatorio').show(); else $('.botao-faturar-relatorio').hide();
}


function verificaBotoesEnvio(campo){
	if (campo=='todos-cd'){
		$(".check-envio").prop('checked',true);
	}
	flagEnvio = false;
	$(".check-envio").each(function(){
		if ($(this).prop('checked')){
			flagEnvio = true;
			$(".envios-dados-" + $(this).attr("workflow-produto-id")).show();
		}
		else{
			$(".envios-dados-" + $(this).attr("workflow-produto-id")).hide();
		}
	});
	if (flagEnvio){$(".btn-envios").show();}else{$(".btn-envios").hide();}
}


function confirmarEnvioEmail(){
	$("<div id='div-destaque' Style='position:absolute; top: 0px; left: 0px; width:100%;background-color:#000000;opacity:0.7;'></div>").appendTo("body");
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

function exibirDataFinalizado(){
	$("#div-situacao-follow").css('width','73%');
	if ($("#situacao-atual-chamado").val()=="34"){
		$(".div-data-finalizado-mostra").show()
	}else{
		$(".div-data-finalizado-mostra").hide()
		if ($("#select-situacao-follow").val()=="34"){
			$(".div-data-finalizado").show()
			$("#div-situacao-follow").css('width','58%');
			$(".formata-data-hora").click();
			caminho = caminhoScript+"/funcoes/carregar-data-atual.php?tipo=d&formato=d/m/Y";
			$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
				caminho = caminhoScript+"/funcoes/carregar-data-atual.php?tipo=h&formato=H:i";
				$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno2){
						$("#data-finalizado-chamado").val(retorno + " "+retorno2);
					}
				});
				}
			});
		}else if($("#select-situacao-follow").val()=="73"){
			$("#div-situacao-follow").css('width','58%');
			$(".formata-data-hora").click();
		}else{
			$(".div-data-finalizado").hide();
			$("#data-finalizado-chamado").val("");
			$("#hora-finalizado-chamado").val("");
		}
	}
}

function submeterSalvarChamado(){
	caminho = caminhoScript+"/modulos/chamados/chamados-salvar.php";
	var form = $('#frmDefault');
	form.attr("action",caminho);
	form.attr("enctype", "");
	form.attr("encoding", "");
	form.attr("target","_top");
	$("#frmDefault").submit();
}


function carregarProdutos(){
	$('#div-produtos').show();
	caminho = caminhoScript+"/modulos/chamados/carregar-produtos.php?workflow-id="+$("#workflow-id").val();
	$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#div-produtos').show();
			$("#div-produtos").html($.trim(retorno));
		}
	});
}


function carregarEnviosCD(visualizar){
	//$('#div-centro-distribuicao').show();
	caminho = caminhoScript+"/modulos/chamados/carregar-envios-cd.php?workflow-id="+$("#workflow-id").val()+"&visualizar="+visualizar;
	$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-centro-distribuicao").html($.trim(retorno));
			//$(".conjunto3").show();
		}
	});
}


function carregarFinanceiro(){
	$('#div-financeiro').show();
	caminho = caminhoScript+"/modulos/chamados/carregar-financeiro.php?workflow-id="+$("#workflow-id").val()+'&tabela-estrangeira='+$("#tabela-estrangeira").val();
	$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#div-financeiro').show();
			$("#div-financeiro").html($.trim(retorno));
		}
	});
}


function carregarCompras(){
	caminho = caminhoScript+"/modulos/chamados/carregar-compras.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-compras-dados").html($.trim(retorno));
		}
	});
}


function calcularValoresTotais(){

	if ($("#select-produtos").val()!=""){
		valorCustoUnitario = ReplaceAll(ReplaceAll($("#valor-custo-unitario").val(),".",""),",",".");
		if ($("#forma-cobranca").val()=="36"){
			percentualVenda = ReplaceAll(ReplaceAll($("#percentual-venda").val(),".",""),",",".");
			valorVendaUnitario = parseFloat((parseFloat(valorCustoUnitario) + parseFloat(valorCustoUnitario * percentualVenda)/100));
			valorVendaUnitarioMascara = number_format(valorVendaUnitario,'2',',','.');
			$("#valor-venda-unitario").val(valorVendaUnitarioMascara);
		}
		else{
			valorVendaUnitario = ReplaceAll(ReplaceAll($("#valor-venda-unitario").val(),".",""),",",".");
		}

		/*
		if (valorVendaUnitario<=0)
			$("#checkbox-cobranca-cliente").attr('checked',false);
		//else
		//	$("#checkbox-cobranca-cliente").attr('checked',true);
		if (valorCustoUnitario<=0)
			$("#checkbox-pagamento-prestador").attr('checked',false);
		//else
		//	$("#checkbox-pagamento-prestador").attr('checked',true);
		*/

		qtde = $("#quantidade-produtos").val();
		if (qtde>0){

			if ($("#checkbox-pagamento-prestador").is(":checked")){
				if ($("#valor-custo-unitario").length){
					//valorCustoUnit =  ReplaceAll(ReplaceAll($("#valor-custo-unitario").val(),".",""),",",".");
					var totalCusto = new String(qtde * valorCustoUnitario);
					totalCusto = number_format(totalCusto,'2',',','.');
					$("#total-custo-produtos").val(totalCusto);
				}
				else
					$("#total-custo-produtos").val("0,00");
			}
			else
				$("#total-custo-produtos").val("0,00");

			if ($("#checkbox-cobranca-cliente").is(":checked")){
				if ($("#valor-venda-unitario").length){
					//valorVendaUnit = ReplaceAll(ReplaceAll($("#valor-venda-unitario").val(),".",""),",",".");
					totalVenda = new String(qtde * valorVendaUnitario);
					totalVenda = number_format(totalVenda,'2',',','.');
					$("#total-venda-produtos").val(totalVenda);
				}
				else
					$("#total-venda-produtos").val("0,00");
			}
			else
				$("#total-venda-produtos").val("0,00");
		}
	}
	if ($("#checkbox-pagamento-prestador").is(":checked")){
		$(".exibir-terceiro").delay("fast").fadeIn();
	}
	else{
		$(".exibir-terceiro").delay("fast").fadeOut();
	}
}




/********* Inicio Funções Relatório ***********/
$(document).ready(function(){
	$("#botao-localizar-chamado-relatorio").live('click', function () {
		$("#frmDefault").attr("action","");
		$("#frmDefault").submit();
	});
	$("#botao-salvar-excel").live('click', function () {
		$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico-session.php");
		$("#frmDefault").submit();
	});
});


/********* Inicio Funções Relatorio Situacao Geral ***********/
$(document).ready(function(){
	$("#botao-pesquisar-relatorio-situacao").live('click', function () {
		$("#frmDefault").submit();
	});
});

$(".busca-mapa").live('click', function () {
	src =$("#localizacao-dados").val()+"&busca="+$("#busca-string-mapa").val();
	open(src, "frm-mapa-localizacao");
});

$(".mostra-localizacao").live('click', function () {
	src = caminhoScript+"/includes/google/index.php?origem="+$("#origem-mapa").val()+"&destino="+$("#destino-mapa").val();
	$("#frm-mapa-localizacao").removeClass("esconde");
	$("#frm-mapa-localizacao").attr('src',src);
});
$(".mostra-localizacao-select").live('change', function () {
	src = caminhoScript+"/includes/google/index.php?origem="+$("#origem-mapa").val()+"&destino="+$("#destino-mapa").val();
	$("#frm-mapa-localizacao").removeClass("esconde");
	$("#frm-mapa-localizacao").attr('src', src);
});


$(".localizar-cadastros-regiao-mapa").live('click', function () {
	valorCampo = ""+$("#tipos-cadastros-regiao").val()+"";

	if(valorCampo == "null"){
		$("#tipos_cadastros_regiao"+"_chosen a").css('background-color', '#FFE4E4');
	}else{
		src = caminhoScript+"/includes/google/index.php?origem="+$("#origem-mapa").val()+"&destino="+$("#destino-mapa").val()+"&tipo="+valorCampo;
		$("#frm-mapa-localizacao").removeClass("esconde");
		$("#frm-mapa-localizacao").attr('src', src);
	}
});





/* RELATORIO AREAS PERIODO */
$(document).ready(function(){
	if ($("#slug-pagina").val()=="chamados-relatorio-periodo-areas"){
		setTimeout(function(){recarregarPagina()}, 10000);

		$(".mostrar-workflows-relatorio").live('click', function () {
			$("#exibir-lista-chamados").hide();
			$("#workflow-id").val($(this).attr("workflows"));
			caminho = caminhoScript+"/modulos/chamados/chamados-carregar-chamados-relatorio.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#exibir-lista-chamados").html(retorno).delay("fast").fadeIn();
				}
			});
		});

		$("#desconsiderar-sabado, #desconsiderar-domingo").live('click', function () {
			$("#frmDefault").submit();
		});
	}
});

function recarregarPagina(){
	if($("#reload-relatorio").is(":checked")){
		$("#frmDefault").submit();
	}
	setTimeout(function(){recarregarPagina()}, 10000);
}



//acionaReload();//Dispara



/* FUNCOES DE ORÇAMENTO */


/********* Inicio Funções Localiza Chamado ***********/
$(document).ready(function(){
	if ($("#slug-pagina").val()=="chamados-orcamento-localizar"){
		$("#localiza-orcamento-solicitante").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-orcamento").click(); }});

		$("#botao-localizar-orcamento").live('click', function () {
			$("#frmDefault").submit();
		});
		$(".orcamento-localiza").live('click', function () {
			$("#workflow-id").val($(this).attr('workflow-id'));
			$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
			$("#frmDefault").submit();
		});
		$("#localiza-chamado-solicitante").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-chamado").click(); }});
	}
	if ($("#slug-pagina").val()=="chamados-orcamento"){
		/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
		$("#menu-superior-3").live('click', function () {
			carregarDocumentos($("#workflow-id").val(),'orcamentos');
		});


		$(".local-evento").live('change', function () {
			categoria = $(this).attr("categoria");
			propostaID = $(this).attr("proposta");
			$("#valor-local-" + propostaID + "-" + categoria).val($(this).find(":selected").attr("valor-produto"));
			// Valores Fixos
			if ($(this).find(":selected").attr("forma-cobranca")=="35"){
				$("#valor-local-" + propostaID + "-" + categoria).attr('readonly', true);
			}
			// Valores Abertos
			else{
				$("#valor-local-" + propostaID + "-" + categoria).attr('readonly', false);
			}
			//alert($(this).find(":selected").attr("produto-categoria-id"));
			$("#produto-categoria-id-" + propostaID + "-" + categoria).val($(this).find(":selected").attr("produto-categoria-id"));
		});

		/* INICIO PRODUTOS ORÇAMENTO*/

		$(".btn-editar-produto-orcamento").live('click', function () {
			propostaProdutoID = $(this).attr('chave-primaria-id');
			$(".botao-exibir-produto").hide();
			$(".btn-editar-produto-orcamento").hide();
			$(".btn-excluir-produto-orcamento").hide();
			exibirInserirAtualizarProduto(propostaProdutoID,"orcamento");
		});

		$("#select-produtos").live('change', function () {
			if ($("#select-produtos").val()!=""){
				detalhesCampo= $.ajax(caminhoScript+"/modulos/chamados/carregar-produto-detalhes.php?produto-variacao-id="+$("#select-produtos").val()+"&workflow="+$("#workflow-id").val()+"&tipo=orcamento")
				.done(function(){
					$("#div-detalhes-produto").html(detalhesCampo.responseText);
					calcularValoresTotais();
					carregarOptionValuePrestadoresProduto();
				});
				if ($(this).find(":selected").attr("faturamento-direto")=='1')
					$('#checkbox-faturamento-direto, #checkbox-pagamento-prestador').attr('checked',true);
				else
					$('#checkbox-faturamento-direto, #checkbox-pagamento-prestador').attr('checked',false);
			}
			else{
				$("#div-detalhes-produto").delay("fast").hide();
				calcularValoresTotais();
			}
		});

		$("#valor-custo-unitario").live('change', function () {
			calcularValoresTotais();
		});
		$('#valor-custo-unitario').live('keypress', function (e) {
			calcularValoresTotais();
		});
		$('#valor-custo-unitario').live('click keyup', function (e) {
			calcularValoresTotais();
		});
		$("#valor-custo-unitario").live('click', function () {
			$("#valor-custo-unitario").select();
		});

		$("#checkbox-pagamento-prestador").live('click', function () {
			calcularValoresTotais();
		});
		$("#checkbox-cobranca-cliente").live('click', function () {
			calcularValoresTotais();
		});

		$("#quantidade-produtos").live('blur', function () {
			calcularValoresTotais();
		});
		$("#quantidade-produtos").live('change', function () {
			calcularValoresTotais();
		});
		$('#quantidade-produtos').live('keypress', function (e) {
			calcularValoresTotais();
		});
		$('#quantidade-produtos').live('click keyup', function (e) {
			calcularValoresTotais();
		});


		$("#valor-venda-unitario").live('blur', function () {
			$("#valor-venda-unitario").css('background-color', '').css('outline', '');
			if($("#forma-cobranca").val()=="58"){
				valor 	= parseFloat(ReplaceAll(ReplaceAll($("#valor-venda-unitario").val(),".",""),",","."));
				valorMin = parseFloat(ReplaceAll(ReplaceAll($("#valor-venda-minima-unitario").val(),".",""),",","."));
				if (valor<valorMin){
					$(this).css('background-color', '#FFE4E4');
					$(this).focus();
					alertify.alert("<b>Aviso</b>", "Valor Informado Abaixo do valor mínimo de cobrança");
				}
			}
			calcularValoresTotais();
		});


		$("#valor-custo-unitario").live('blur', function () {
			if ($("#valor-custo-unitario").val()==""){
				$("#valor-custo-unitario").css('background-color', '#FFE4E4');
				$("#valor-custo-unitario").select();
			}
			else{
				$("#valor-custo-unitario").css('background-color', '').css('outline', '');
				calcularValoresTotais();
			}
		});
		/* FIM PRODUTOS ORÇAMENTO*/


		$("#situacao-follow-orcamento").live('change', function () {
			if ($("#situacao-follow-orcamento").val()=="113")
				$(".div-data-finalizado").show();
			else
				$(".div-data-finalizado").hide();

		});

		$(".botao-salvar-orcamento").live('click', function () {
			$(".botao-salvar-orcamento").hide();
			if ($("#situacao-follow-orcamento").val()=="113")
				$("#data-finalizado-orcamento").addClass('required');
			else
				$("#data-finalizado-orcamento").removeClass('required');

			if ($('#situacao-atual').val()!=$('#situacao-follow-orcamento').val())
				$("#descricao-follow").addClass('required');
			else
				$("#descricao-follow").removeClass('required');

			if(validarCamposGenerico("#div-solicitante-dados .required, #div-orcamento-dados .required")){
				caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-salvar.php";
				dados = $(".dados-orc").serialize();
				//alert(dados);
				//$("form").serialize()
				/*
				$("#frmDefault").attr('action', caminho);
				$("#frmDefault").submit();
				*/
				$.ajax({type: "POST",url: caminho, data: dados, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						alertify.alert('<b>Aviso</b>',"Orçamento salvo com sucesso", function(){
							//$("#div-retorno").html(retorno);
							$("#workflow-id").val(retorno.trim());
							$("#frmDefault").attr('action', caminhoScript+"/chamados/chamados-orcamento");
							$("#frmDefault").submit();
						});
					}
				});
			}
			else{
				$(".botao-salvar-orcamento").show();
			}
		});


		$(".btn-excluir-produto-orcamento").live('click', function () {
			propostaProdutoID = $(this).attr('chave-primaria-id');
			propostaID = $('#proposta-id').val();
			excluirProdutoOrcamento(propostaProdutoID, propostaID);
		});


		$(".required").live('change keyup', function () {
			if($(this).val().trim()!="")$(this).css('background-color', '').css('outline', '');
		});


		$("#botao-reabrir-orcamento").live('click', function () {
			$("#botao-reabrir-orcamento").hide();
			$(".dados-follows-orcamento").show();
			$("#botao-atualizar-situacao").click();
		});


		$(".orcamento-localiza").live('click', function () {
			$("#workflow-id").val($(this).attr('workflow-id'));
			$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
			$("#frmDefault").submit();
		});

		$(".aba-normal").live('click', function () {
			$(".botao-cancelar-produto").click();
			propostaID = $(this).attr("proposta-id");
			$("#proposta-id").val(propostaID);
			$(".abas-geral").removeClass("aba-selecionada");
			$(".abas-geral").addClass("aba-normal");
			$(this).removeClass("aba-normal");
			$(this).addClass("aba-selecionada");
			$(".blocos-propostas").hide();
			$("#div-propostas-" + propostaID).show();
			if (!($('#flag-proposta-'+propostaID).length)){
				carregarProposta(propostaID);
			}
			//verificaBotaoIncluirEditarExcluir();
		});

		verificaBotaoIncluirEditarExcluir();

		$(".btn-ver-detalhes-produto").live('click', function () {
			if ($("#detalhes-produto-" + $(this).attr("chave-primaria-id")).is(":visible"))
				$("#detalhes-produto-" + $(this).attr("chave-primaria-id")).hide();
			else
				$("#detalhes-produto-" + $(this).attr("chave-primaria-id")).show();
		});

		$(".botao-exibir-proposta").live('click', function () {
			$(".botao-exibir-proposta, .incluir-nova-proposta").hide();
			$(".opcoes-proposta, #incluir-proposta").show();
			$("#titulo-proposta").focus();
		});
		$(".botao-nova-proposta").live('click', function () {
			$(".opcoes-proposta").hide();
			$(".incluir-nova-proposta").show();
			$("#titulo-proposta").focus();
		});
		$(".botao-copiar-proposta").live('click', function () {
			$(".opcoes-proposta").hide();
			$(".botao-exibir-proposta").show();
			caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-localizar-proposta.php?modulo=chamados&orcamento-id="+$("#workflow-id").val();
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '847px',
				padding : 2
			});
		});
		$(".cancelar-proposta").live('click', function () {
			$(".botao-exibir-proposta").show();
			$("#incluir-proposta").hide();
		});

		$(".salvar-proposta").live('click', function () {
			flag = 0;
			$("#titulo-proposta").css('background-color', '').css('outline', '');
			if ($("#titulo-proposta").val()==""){ $("#titulo-proposta").css('background-color', '#FFE4E4'); flag=1;}
			if (flag==0){
				$("#incluir-proposta").hide();
				$("#proposta-id").val("");
				caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-salvar-propostas.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//alert($.trim(retorno));
						//$('#div-retorno').html(retorno);
						$('#proposta-id').val($.trim(retorno));
						carregarOrcamentos();
					}
				});
			}
		});

		$(".aba-selecionada").live('dblclick', function () {
			$(".cancelar-atualizar-proposta").click();
			$(".botao-exibir-proposta").hide();
			propostaID = $(this).attr("proposta-id");
			nomeAtual = $("#titulo-"+propostaID).attr("tit");
			valor = 0;
			if ($('#tipo-listagem').val()=="completa")
				valor = parseFloat(desformataValor($("#total-proposta-"+propostaID).html()));
			else{
				if ($("#valor-total-produto-"+propostaID).length)
					valor = parseFloat(desformataValor($("#valor-total-produto-"+propostaID).val()));
			}
			caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-proposta-editar.php?proposta-id="+propostaID+"&nome-atual="+nomeAtual+"&valor="+valor;
			$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#titulo-" + propostaID).html(retorno);
				}
			});
		});
		$(".cancelar-atualizar-proposta").live('click', function () {
			$(".titulo-fixo").each(function(){
				$(this).html($(this).attr("tit"));
			});
			$(".botao-exibir-proposta").show();
			//$("#titulo-" + $(this).attr("proposta-id")).html($("#titulo-" + propostaID).attr("tit"));
			/*
			$(".editar-titulo").hide();
			$(".titulo-fixo").show();
			$(".botao-exibir-proposta").show();
			$(".editar-proposta").hide();
			*/
		});
		$(".atualizar-proposta").live('click', function () {
			flag = 0;
			propostaID = $("#proposta-id").val();
			novoTitulo = $("#editar-titulo").val().trim();
			$(".editar-titulo").css('background-color', '').css('outline', '');
			if ($("#editar-titulo").val().trim()==""){ $("#editar-titulo").css('background-color', '#FFE4E4'); flag=1;}
			if (flag==0){
				caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-salvar-propostas.php?editar-titulo="+novoTitulo+"&proposta-id="+propostaID;
				$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$("#titulo-" + propostaID).html(novoTitulo);
						$("#titulo-" + propostaID).attr("tit",novoTitulo);
						$(".botao-exibir-proposta").show();
					}
				});
			}
		});
		$(".botao-excluir-proposta").live('click', function () {
			if (confirm("Tem certeza que deseja excluir a Proposta?")){
				caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-excluir-proposta.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//$('#div-retorno').html(retorno);
						//$('#proposta-id').val($.trim(retorno));
						carregarOrcamentos();
					}
				});
			}
		});
	}

	/* INICIO - FATURAMENTO */
	/*
	$(".check-fat, .todos-fat").live('click', function () {
		if ($(this).hasClass('todos-fat')){
			if ($(this).attr('tipo')=='pagar'){
				if ($(".check-fat-pagar").is(":checked")){
					$(".check-fat-pagar").prop('checked',false);
				}
				else{
					$(".check-fat-pagar").prop('checked',true);
					$(".check-fat-receber").prop('checked',false);
				}
			}
			if ($(this).attr('tipo')=='receber'){
				if ($(".check-fat-receber").is(":checked")){
					$(".check-fat-receber").prop('checked',false);
				}
				else{
					$(".check-fat-pagar").prop('checked',false);
					$(".check-fat-receber").prop('checked',true);
				}
			}
		}
		else{
			if ($(this).hasClass('check-fat-pagar'))
				$(".check-fat-receber").prop('checked',false);
			if ($(this).hasClass('check-fat-receber'))
				$(".check-fat-pagar").prop('checked',false);
		}
		$('.botao-faturar-saida').hide();
		$(".check-fat-pagar").each(function(){
			if ($(this).prop('checked'))
				$('.botao-faturar-saida').show();
		});

		$('.botao-faturar-entrada').hide();
		$(".check-fat-receber").each(function(){
			if ($(this).prop('checked'))
				$('.botao-faturar-entrada').show();
		});
	});
	*/

		/* PRODUTOS FATURAMENTO ORCAMENTO*/
		$(".sel-todas-faturar").live('click', function () {
			$('.prod-faturar, .prod-cancelar').attr("checked", false);
			$('.prod-faturar-'+$(this).attr('indice')).attr("checked", true);
			$('.prod-cancelar-'+$(this).attr('indice')).attr("checked", false);
			exibeBotaoFaturarCancelar();
			verificaTipoOrcamento($(this).attr('tipo-id'));
		});
		$(".sel-todas-cancelar").live('click', function (){
			$('.prod-faturar, .prod-cancelar').attr("checked", false);
			$('.prod-faturar-'+$(this).attr('indice')).attr("checked", false);
			$('.prod-cancelar-'+$(this).attr('indice')).attr("checked", true);
			exibeBotaoFaturarCancelar();
			verificaTipoOrcamento($(this).attr('tipo-id'));
		});
		$(".prod-cancelar").live('click', function (){
			$('.prod-cancelar').not($('.prod-cancelar-'+$(this).attr('indice'))).attr('checked', false);
			$('.prod-faturar').attr('checked', false);
			exibeBotaoFaturarCancelar();
			verificaTipoOrcamento($(this).attr('tipo-id'));
		});
		$(".prod-faturar").live('click', function (){
			$('.prod-faturar').not($('.prod-faturar-'+$(this).attr('indice'))).attr('checked', false);
			$('.prod-cancelar').attr('checked', false);
			exibeBotaoFaturarCancelar();
			verificaTipoOrcamento($(this).attr('tipo-id'));
		});

		function exibeBotaoFaturarCancelar(){
			$('.botao-faturar-cancelar').hide();
			$(".prod-faturar:checked").each(function(){
				$('.botao-faturar-cancelar-'+$(this).attr('indice')).show().attr('value','Faturar');
			});
			$(".prod-cancelar:checked").each(function(){
				$('.botao-faturar-cancelar-'+$(this).attr('indice')).show().attr('value','Cancelar');
			});
		}

		function verificaTipoOrcamento(tipo){
			if (tipo==44){
				$('.prod-tipo-45').attr('checked', false);
				$('.btn-tipo-45').hide();
			}
			if (tipo==45){
				$('.prod-tipo-44').attr('checked', false);
				$('.btn-tipo-44').hide();
			}
		}
		/**/

		$(".botao-faturar-cancelar").live('click', function () {
			if ($(this).attr('origem')=='orcamentos'){
				dados = $('#frmDefault .prod-faturar:checked, #frmDefault .prod-cancelar:checked').serialize();
				dados += "&empresa-id=" + $(this).attr('empresa-id');
				dados += "&tipo-id=" + $(this).attr('tipo-id');
				dados += "&cadastro-id=" + $(this).attr('cadastro-id');
				dados += "&modulo="+$(this).attr('origem');
				dados += "&chave-estrangeira="+$(this).attr('chave-estrangeira');
				if ($(this).val()=='Faturar'){
					caminho = caminhoScript+"/financeiro/financeiro-lancamento?tipo=direto&"+dados;
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
						afterClose:function(){
							carregarFinanceiro();
						}
					});
				}
				if ($(this).val()=='Cancelar'){
					alertify.confirm('Aviso', "Tem certeza que deseja cancelar o faturamento destes itens?",
					function(){
						caminho = caminhoScript+"/modulos/financeiro/financeiro-cancelar-produtos-fatura.php";
						$.ajax({type: "POST",url: caminho, data: dados, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
							success: function(retorno){
								//console.log(retorno);
								location.reload(true);
							}
						});
					},
					function(){}).set('labels', {ok:'Sim', cancel:'Cancelar'}
					);
				}
			}
		});

		/**/

	$(".botao-faturar-entrada, .botao-faturar-saida").live('click', function () {
		/*
		if ($(this).attr('origem')=='orcamentos'){
			dados = $('#frmDefault .prod-faturar:checked, #frmDefault .prod-cancelar:checked').serialize();
			dados += "&empresa-id=" + $('#empresa-id').val();
			dados += "&tipo-id=" + $(this).attr('tipo-id');
			dados += "&cadastro-id=" + $('#solicitante-id').val();
			dados += "&modulo="+$(this).attr('origem');
			dados += "&chave-estrangeira="+$(this).attr('chave-estrangeira');
		}
		if ($(this).val()=='Faturar'){
			caminho = caminhoScript+"/financeiro/financeiro-lancamento?tipo=direto&"+dados;
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
				afterClose:function(){
					alert('reaload das uncoes certas');
				}
			});
		}
		*/

		tabelaEstrangeira = $(this).attr('tabela-estrangeira');
		if ($(this).attr('tipo')=='enviar'){
			var confirma=confirm("Tem certeza que deseja enviar os produtos/serviços para faturamento?");
			if (confirma==true){
				$(".botao-faturar-entrada, .botao-faturar-saida").hide();
				caminho = caminhoScript+"/modulos/chamados/chamados-faturar.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//$("#div-retorno").html(retorno);
						$(".check-fat").prop('checked',false);
						carregarFinanceiro();
						$("#menu-superior-7").click();
						alertify.alert("<b>Aviso</b>","Solicitação de faturamento incluída com sucesso!");
					}
				});
			}
		}
		/*
		if ($(this).attr('tipo')=='direto'){
			dados = $('#frmDefault').serialize();
			caminho = caminhoScript+"/financeiro/financeiro-lancamento?tipo=direto&modulo="+tabelaEstrangeira+"&"+dados;
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				helpers: {
					overlay: {
						locked: false
					}
				}
			});
		}
		*/
	});
	$(".link-refaturar").live('click', function () {
		caminho = caminhoScript+"/modulos/chamados/chamados-refaturar-produto-chamado.php?financeiro-produto-id="+$(this).attr('financeiro-produto-id');
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				//$("#div-retorno").html(retorno);
				carregarFinanceiro();
				carregarProdutos();
				alertify.alert("<b>Aviso</b>","Solicitação de Re-Faturamento realizado com sucesso!");
			}
		});

	});
	/* FIM - FATURAMENTO */


	$(".sel-copiar-proposta").live('click', function () {
		$(".sel-copiar-proposta").hide();
		caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-copiar-proposta.php?proposta-id="+$(this).attr("proposta-id")+"&orcamento-id="+$("#orcamento-id").val();
		$.ajax({type: "GET", url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("body").append(retorno);
				alertify.alert('<b>Aviso</b>',"Proposta Copiada com sucesso!");
				parent.$.fancybox.close();
				parent.carregarOrcamentos();
			}
		});

	});

});


function verificaBotaoIncluirEditarExcluir(){
	$(".aba-selecionada").each(function(){
		$(".botao-exibir-produto, .btn-excluir-produto-orcamento, .btn-editar-produto-orcamento").hide();
		if ($(this).attr('editavel')=='true') {
			$(".botao-exibir-produto, .btn-excluir-produto-orcamento, .btn-editar-produto-orcamento").show();
		}
	});
}


/*PRODUTOS GERAIS*/
function exibirInserirAtualizarProduto(chaveEstrangeira,tipo){
	//alert(chaveEstrangeira);
	$('#div-produtos-incluir-editar').hide();
	solicitanteID = $("#solicitante-id").val();
	caminho = caminhoScript+"/modulos/chamados/chamados-localizar-produto.php?chaveEstrangeira="+chaveEstrangeira+"&tipo="+tipo+"&solicitanteID="+solicitanteID;
	$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			if (chaveEstrangeira==""){
				$('#div-produtos-incluir-editar').html($.trim(retorno));
				$('#div-produtos-incluir-editar').show();
			}
			else{
				$('#conteudo-produto-' + chaveEstrangeira).html($.trim(retorno));
				$('#conteudo-produto-' + chaveEstrangeira).show();
			}
		}
	});
}

function incluirAlterarProdutoOrcamento(){
	flag = 0;
	$("#select_produtos_chosen a").css('background-color', '').css('outline', '');
	$("#quantidade-produtos").css('background-color', '').css('outline', '');
	$("#valor-custo-unitario").css('background-color', '').css('outline', '');
	if ($("#select-produtos").val()==""){ $("#select_produtos_chosen a").css('background-color', '#FFE4E4'); flag=1;}
	if ($("#quantidade-produtos").val()==""){ $("#quantidade-produtos").css('background-color', '#FFE4E4'); flag=1;}
	if (flag==0){
		$("#botao-incluir-produtos").hide();
		$("#botao-cancelar-produtos").hide();
		caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-produto-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				carregarProposta($('#proposta-id').val());
				$(".botao-salvar-proposta-completo, .botao-exibir-produto, .btn-editar-produto-orcamento, .btn-excluir-produto-orcamento").show();
			}
		});
	}
}


function incluirAlterarProdutoChamado(){
	flag = 0;
	$("#select_produtos_chosen a").css('background-color', '').css('outline', '');
	$("#quantidade-produtos").css('background-color', '').css('outline', '');
	$("#valor-custo-unitario").css('background-color', '').css('outline', '');
	if ($("#select-produtos").val()==""){ $("#select_produtos_chosen a").css('background-color', '#FFE4E4'); flag=1;}
	if ($("#quantidade-produtos").val()==""){ $("#quantidade-produtos").css('background-color', '#FFE4E4'); flag=1;}
	if (flag==0){
		$("#botao-incluir-produtos").hide();
		$("#botao-cancelar-produtos").hide();
		caminho = caminhoScript+"/modulos/chamados/chamados-workflow-produto-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$('#div-produtos-incluir-editar').hide();
				$('#div-produtos-incluir-editar').html("");
				$(".botao-exibir-produto").show();

				carregarProdutos();
				carregarCompras();
				carregarFinanceiro();
				carregarEnviosCD('esconde');
			}
		});
	}
	/*
	if (flag==0){
		caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-produto-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				//alert(retorno);
				//$('#div-retorno').html(retorno);
				if ($("#proposta-id").val()==""){
					carregarOrcamentos();
				}
				else{
					carregarProdutosOrcamento($("#proposta-id").val());
					$('#div-produtos-incluir-editar').hide("");
					$('#div-produtos-incluir-editar').html("");
					$(".botao-exibir-produto").show();
				}
			}
		});
	}
	*/

}

function carregarOrcamentos(){
	$("#div-propostas").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
	caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-carregar-orcamentos.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-propostas").html(retorno)
		}
	});
}
function carregarProposta(propostaID){
	$("#bloco-proposta-"+propostaID).html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
	$("#bloco-proposta-"+propostaID).show();
	caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-carregar-proposta.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#bloco-proposta-"+propostaID).html(retorno);
			$('#div-produtos-incluir-editar').hide("");
			$('#div-produtos-incluir-editar').html("");
			verificaBotaoIncluirEditarExcluir();
			carregarFinanceiro();
		}
	});
}

function carregarProdutosOrcamento(propostaID){
	caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-carregar-produtos.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-propostas-"+propostaID).html(retorno);
		}
	});
}


function excluirProdutoOrcamento(propostaProdutoID, propostaID){
	var confirma=confirm("Tem certeza que deseja excluir o Produto?");
	if (confirma==true){
		caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-produto-excluir.php?propostaProdutoID="+propostaProdutoID;
		$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				carregarProposta(propostaID);
				//$('#conteudo-produto-' + propostaProdutoID).hide();
			}
		});
	}
}

function calcularTotalCategoria(proposta, categoria){
	var totalValor = 0;
	var totalItens = 0;
	var totalProdutos = 0;
	$(".quantidade-produto-variacao-" + proposta + "-" + categoria).each(function(){
		posicao = $(this).attr("posicao");
		if (($(this).val()!="0") && ($(this).val()!="")){
			totalItens += parseInt(desformataValor($(this).val()));
			totalValor += parseFloat(desformataValor($("#valor-total-variacao-" + proposta + "-" + posicao).html()));
			totalProdutos++;
			//alert(parseInt($(".quantidade-produto-variacao-" + proposta + "-" + categoria).val()));
		}
	});
	//alert(proposta + ' ' + categoria);
	if ($('#valor-local-' + proposta + '-' + categoria).length){
		totalValor += parseFloat(desformataValor($('#valor-local-' + proposta + '-' + categoria).val()));
	}

	$("#valor-total-categoria-"+proposta+"-"+categoria).html(number_format(totalValor,'2',',','.'));
	$("#quantidade-itens-categoria-"+proposta+"-"+categoria).html(totalItens);
	$("#produtos-selecionados-categoria-"+proposta+"-"+categoria).html(totalProdutos);
	calcularTotaisProposta(proposta);
}

function calcularTotaisProposta(proposta){
	totSel = 0;
	totQtd = 0;
	totGeral = 0;
	$(".produtos-selecionados-categoria-" + proposta).each(function(){
		categoria = $(this).attr("categoria");
		totSel += parseInt(desformataValor($("#produtos-selecionados-categoria-" + proposta + "-" + categoria).html().trim()));
		totQtd += parseInt(desformataValor($("#quantidade-itens-categoria-" + proposta + "-" + categoria).html().trim()));
		totGeral += parseFloat(desformataValor($("#valor-total-categoria-" + proposta + "-" + categoria).html().trim()));
	});
	$("#total-selecionados-" + proposta).html(number_format(totSel,'0','','.'));
	$("#total-quantidade-" + proposta).html(number_format(totQtd,'0','','.'));
	$("#total-proposta-" + proposta).html(number_format(totGeral,'2',',','.'));
}

function carregarFollowsProposta(){
	$('#bloco-follows-proposta-' + propostaID).html("<p align='center'>Aguarde processando</p>");
	propostaID = $("#proposta-id").val();
	caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-carregar-follows-proposta.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#bloco-follows-proposta-' + propostaID).html(retorno);
			$(".botao-salvar-proposta-completo").show();
		}
	});
}



$(document).ready(function(){
	$(".botao-salvar-produto").live('click', function () {
		if ($("#slug-pagina").val()=="chamados-cadastro-chamado")
			incluirAlterarProdutoChamado();

		if ($("#slug-pagina").val()=="chamados-orcamento")
			incluirAlterarProdutoOrcamento();
	});

	if ($("#slug-pagina").val()=="chamados-orcamento"){
		$("#menu-superior-2").live('click', function () {
			if ($('#proposta-id').val()==''){
				$('.botao-exibir-proposta').click();
			}
		});
	}


	$(".botao-cancelar-produto").live('click', function () {
		if ($("#slug-pagina").val()=="chamados-orcamento"){
			$('#div-produtos-incluir-editar').hide();
			$('#div-produtos-incluir-editar').html("");
			$(".botao-exibir-produto, .btn-editar-produto-orcamento, .btn-excluir-produto-orcamento, .botao-salvar-proposta-completo").show();
			carregarProposta($('#proposta-id').val());
		}
		if ($("#slug-pagina").val()=="chamados-cadastro-chamado"){
			$('#div-produtos-incluir-editar').hide().html("");
			$(".btn-excluir-produto-chamado, .botao-exibir-produto, .btn-editar-produto-chamado").show();
			carregarProdutos();
		}
	});

	$(".atualizar-dados-frete").live('click', function () {
		caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-salvar-frete-proposta.php";
		$.ajax({type: "POST",url: caminho, data: $("#proposta-id, .flag-frete").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$('.botao-exibir-produto').show();
				carregarProposta($('#proposta-id').val());
				alertify.alert("<b>Aviso</b>","Dados atualizados!");
			}
		});

	});

	$(".flag-frete").live('change blur click keyup', function () {
		propostaID = $('#proposta-id').val();
		tipoFrete = $('#tipo-frete-'+propostaID).val();
		valorFrete = 0;
		valorSeguro = 0;
		valorSaldoGeral = 0;
		valorTotalProduto = 0;
		valorCustoGeral = 0;

		valorFrete = parseFloat(desformataValor($('#valor-frete-'+propostaID).val()));
		valorSeguro = parseFloat(desformataValor($('#valor-seguro-'+propostaID).val()));
		$(".valor-total-produto").each(function(){
			valorTotalProduto += parseFloat(desformataValor($(this).val()));
		});
		$(".valor-custo-total").each(function(){
			valorCustoGeral += parseFloat(desformataValor($(this).val()));
		});

		//valorSaldoGeral = parseFloat(valorTotalGeral - valorCustoGeral);
		//valorSaldoGeral = parseFloat(valorTotalProduto - valorCustoGeral);

		$('#valor-total-geral-produto-'+propostaID).val(number_format(valorTotalProduto,'2',',','.'));
		$('#valor-total-geral-geral-'+propostaID).val(number_format((valorTotalProduto + valorFrete + valorSeguro),'2',',','.'));
		$('#valor-total-geral-frete-'+propostaID).val(number_format(valorFrete+valorSeguro,'2',',','.'));

		//$('#valor-total-saldo-'+propostaID).val(number_format((valorTotalProduto - valorCustoGeral),'2',',','.'));
		//$('#valor-total-custo-geral-'+propostaID).val(number_format(valorCustoGeral,'2',',','.'));
		$(".atualizar-dados-frete").show();
	});

	$(".tipo-frete").live('change', function () {
		if ($(this).val()!='CIF'){
			$('.exibe-valores-frete').hide();
			$('#valor-frete-'+propostaID).val("0,00");
			$('#valor-seguro-'+propostaID).val("0,00");
			$('#forma-envio-'+propostaID).val('').trigger('chosen:updated');
			$('#endereco-entrega-'+propostaID).val('').trigger('chosen:updated');
		}
		else{
			$('.exibe-valores-frete').show();
		}
	});

	$(".botao-exibir-produto").live('click', function () {
		if ($("#slug-pagina").val()=="chamados-orcamento"){
			$(".botao-exibir-produto").hide();
			$(".btn-editar-produto-orcamento").hide();
			$(".btn-excluir-produto-orcamento").hide();
			$(".botao-salvar-proposta-completo").hide();

			exibirInserirAtualizarProduto("","orcamento");
		}
		if ($("#slug-pagina").val()=="chamados-cadastro-chamado"){
			$(".botao-exibir-produto").hide();
			$(".btn-editar-produto-chamado").hide();
			$(".btn-excluir-produto-chamado").hide();
			exibirInserirAtualizarProduto("","chamado");
		}
	});

	/*FUNCOES MODO DE VISUALIZAÇÃO LISTAGEM COMPLETA*/
	$(".exibir-produtos-categoria").live('click', function (){
		//objeto = $(this);
		proposta = $(this).attr("proposta");
		posicao = $(this).attr("posicao");

		/*
		$(".blocos-categorias").each(function(){
			if($(this).is(":visible")){
				$(this).slideToggle(500);
			}
		});
		*/

		if ($(this).hasClass("btn-retrair")){
			$(this).removeClass("btn-retrair").addClass("btn-expandir");
			$(".conteudo-interno-categoria-" + proposta + "-" + posicao).slideToggle(500);
			$(".bloco-categoria-" + proposta + "-" + posicao).css("border-bottom","1px solid #bcc4d3");
		}
		else{
			if ($(this).hasClass("btn-expandir")){
				$(this).removeClass("btn-expandir").addClass("btn-retrair");
				$('.conteudo-interno-categoria-'+proposta+"-"+posicao).slideToggle(500);
				$(".bloco-categoria-" + proposta +"-"+posicao).css("border-bottom","solid 0px");
			}
		}
	});
	$(".qtde-produto").live('click', function (){
		$(this).focus();
		$(this).select();
	});

	/*
	$(".qtde-produto").live('click', function (){
		$(this).focus();
		$(this).select();
	});
	*/

	$(".calcular-total-produto").live('blur keyup', function (){
		posicao = $(this).attr("posicao");
		proposta = $(this).attr("proposta");
		categoria = $(this).attr("categoria");
		qtd = desformataValor($("#quantidade-produto-" + proposta + "-" + posicao).val());
		valor = desformataValor($("#valor-produto-variacao-" + proposta + "-" + posicao).val());
		if ((qtd!="") && (valor!=""))
			total = (parseFloat(qtd) * parseFloat(valor));
		else
			total = 0;
		$("#valor-total-variacao-" + proposta + "-" + posicao).html(number_format(total,'2',',','.'));
	});

	$(".calcular-total-produto, .calcular-total-categoria").live('blur keyup change', function (){
		calcularTotalCategoria($(this).attr("proposta"),$(this).attr("categoria"));
	});

	$(".botao-cancelar-acoes").live('click', function (){
		propostaID = $("#proposta-id").val();
		$("#observacao-proposta-"+propostaID).val("");
		$(".observacao-proposta-"+propostaID).hide();
		$(".botao-salvar-proposta-completo").show();
	});


	$(".botao-salvar-proposta-completo").live('click', function (){
		acao = $(this).attr("tipo");
		propostaID = $("#proposta-id").val();
		if (acao=="115"){
			if(!($(".status-produto-"+propostaID).is(":checked"))){
				alertify.alert("<b>Aviso</b>","É necessário selecionar os produtos e serviços para pré-seleção");
				return false;
			}
		}
		valorTotalProposta = 0;
		if ($('#tipo-listagem').val()=="completa"){
			valorTotalProposta = parseFloat(desformataValor($("#total-proposta-"+propostaID).html().trim()));
		}
		else{
			if ($("#valor-total-produto-"+propostaID).length)
				valorTotalProposta = parseFloat(desformataValor($("#valor-total-produto-"+propostaID).val()));
		}

		if (valorTotalProposta<=0){
			alertify.alert("<b>Aviso</b>","A proposta deve possuir itens e valores para a ação desejada");
			return false;
		}
		if ((acao=="118")||(acao=="119")||(acao=="121")||(acao=="122")){
			if ((acao=="119")||(acao=="122"))
				$("#texto-observacao-"+propostaID).html('Motivo:');
			else
				$("#texto-observacao-"+propostaID).html('Observa&ccedil;&atilde;o:');

			$("#observacao-proposta-"+propostaID).css('background-color', '').css('outline', '');
			$(".observacao-proposta-"+propostaID).show();
			$(".botao-salvar-proposta-completo").hide();
			$(this).show();
			if ($("#observacao-proposta-"+propostaID).val().trim()==""){
				$("#observacao-proposta-"+propostaID).css('background-color', '#FFE4E4');
				return false;
			}
		}
		$(".observacao-proposta-"+propostaID).hide();
		$(".botao-salvar-proposta-completo").hide();
		$("#situacao-auxiliar").val(acao);
		$("#div-propostas-"+propostaID).hide();
		$("#div-aux-aguarde").show().html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
		caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-salvar-produtos-proposta.php";
		$.ajax({type: "POST",url: caminho, data: $("#proposta-id, #situacao-auxiliar, #workflow-id, .cp-orc-"+propostaID).serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				if ((acao=="119")||(acao=="122")||(acao=="141")||(acao=="120")){
					carregarOrcamentos();
					if (acao=="141"){
						carregarOrcamentosChamados('esconde');
						carregarFinanceiro();
						//$('#menu-superior-4').click();
					}
				}
				else{
					$("#div-aux-aguarde").hide().html("");
					$(".botao-salvar-proposta-completo").show();
					$("#bloco-proposta-"+propostaID).html(retorno).show();
					$("#div-propostas-"+propostaID).delay("fast").fadeIn();
				}
			}
		});
	});

	$(".incluir-prestador-produto").live('click', function (){
		produtoID = $("#select-produtos").find(":selected").attr("produto-id");
		caminho = caminhoScript+"/produtos/produtos-cadastrar?tipo-fluxo=direto&produto-id="+produtoID;
		console.log(caminho);
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
				carregarOptionValuePrestadoresProduto();
			}
		});



	});

	$(".pre-selecao-selecionar").live('click', function (){
		proposta = $(this).attr("proposta");
		categoria = $(this).attr("categoria");
		if($(".status-produto-"+proposta+"-"+categoria).is(":checked"))
			$(".status-produto-"+proposta+"-"+categoria).attr("checked",false);
		else
			$(".status-produto-"+proposta+"-"+categoria).attr("checked",true);
	});

	$(".gerar-os").live('click', function (){
		if ($(".gerar-os").is(":checked")){
			$(".campos-gerar-os").show();
		}
		else{
			//$(".campos-gerar-os").hide();
		}
	});


	$(".sel-todas-prod-os").live('click', function (){
		if ($(".gerar-os").is(":checked")){
			$(".gerar-os").attr("checked",false);
			//$(".campos-gerar-os").hide();
		}
		else{
			$(".gerar-os").attr("checked",true);
			$(".campos-gerar-os").show();
		}
	});


	$(".botao-gerar-os").live('click', function (){
		if(validarCamposGenerico(".campos-gerar-os .required")){
			caminho = caminhoScript+"/modulos/chamados/chamados-gerar-chamados-orcamentos.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//$("#div-retorno").html(retorno);
					carregarOrcamentosChamados('');
				}
			});
		}
	});

	$(".link-chamado").live('click', function () {
		antigo = $("#workflow-id").val();
		$("#workflow-id").val($(this).attr('chamado-id'));
		$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-cadastro-chamado");
		$("#frmDefault").submit();
		$("#workflow-id").val(antigo);
	});

	$(".link-orcamento").live('click', function () {
		workflowIDAnt = $("#workflow-id").val();
		$("#workflow-id").val($(this).attr('workflow-id'));
		$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
		$("#frmDefault").submit();
		$("#workflow-id").val(workflowIDAnt);
	});
});

function carregarOrcamentosChamados(esconde){
	caminho = caminhoScript+"/modulos/chamados/chamados-carregar-orcamentos-chamados.php?esconde="+esconde;
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#orcamentos-produtos-chamados").html(retorno);
		}
	});
}

function carregarOptionValuePrestadoresProduto(){
	prestadorID = $('#select-prestador').val();
	caminho = caminhoScript+"/modulos/chamados/chamados-carregar-prestadores-produto.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			//$("#orcamentos-produtos-chamados").html(retorno);
			$("#select-prestador").empty().append(retorno).val(prestadorID).trigger('chosen:updated');
		}
	});
}

/********* Inicio Funções Localiza Chamado ***********/
$(document).ready(function(){
	if ($("#slug-pagina").val()=="chamados-configuracoes-gerais"){
		$(".seleciona-exibicao").live('click', function () {
			if ($(this).is(":checked"))
				$('.bloco-'+$(this).attr('id')).show();
			else
				$('.bloco-'+$(this).attr('id')).hide();
		});
		$(".botao-salva-configuracoes-gerais").live('click', function () {
			caminho = caminhoScript+"/modulos/chamados/chamados-configuracoes-gerais-salvar.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//$("#div-retorno").html(retorno);
					alertify.alert("<b>Aviso</b>","Configurações atualizadas");
					$("#frmDefault").submit();
				}
			});
		});
	}
});


/********* Inicio Funções Localiza Chamado ***********/
$(document).ready(function(){
	if ($("#slug-pagina").val()=="chamados-relatorio-resumo"){
		$(".botao-pesquisar-resumo").live('click', function () {
			$("#frmDefault").submit();
		});
	}
});

/********* Inicio Funções Formas de Pagamento ***********/
$(document).ready(function(){
	if ($("#slug-pagina").val()=="chamados-formas-pagamento"){
		$(".quantidade-parcelas").live('change', function () {
			caminho = caminhoScript+"/modulos/chamados/chamados-formas-pagamento-carregar.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#qtde-parcelas-formas-pagamento').html(retorno);
				}
			});
		});
	}

	if ($("#slug-pagina").val()=="chamados-orcamento"){
		$(".forma-pagamento").live('change', function () {
			//carregarFormaPagamento($('#proposta-id').val());
			carregarFormaPagamento();
		});
		$(".atualizar-dados-vencimento").live('click', function () {
			salvarDadosVencimento('Vencimentos atualizados');
		});
	}
});

function carregarFormaPagamento(){
	propostaID = $('#proposta-id').val();
	formaPagamentoID = $('#forma-pagamento-'+propostaID).val();
	valorTotalGeralGeral = $('#valor-total-geral-geral-'+propostaID).val();
	caminho = caminhoScript+"/modulos/chamados/chamados-formas-pagamento-carregar-opcoes.php?proposta-id="+propostaID+"&forma-pagamento="+formaPagamentoID+"&valor-total-geral-geral="+desformataValor(valorTotalGeralGeral);
	console.log(caminho);
	$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#exibir-campos-forma-pagamento-'+propostaID).html(retorno);
		}
	});
}
function salvarDadosVencimento(mensagem){
	caminho = caminhoScript+"/modulos/chamados/chamados-orcamento-salvar-vencimentos-proposta.php";
	$.ajax({type: "POST",url: caminho, data: $("#proposta-id, .vencimentos").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			if (mensagem!='')
				alertify.alert("<b>Aviso</b>",mensagem);
		}
	});
}

$(document).ready(function(){
	/* BOTAO INCLUIR NOVO PRODUTO */
	$(".btn-incluir-novo-produto").live('click', function () {
		dados = $('#frmDefault').serialize();
		caminho = caminhoScript+"/produtos/produtos-cadastrar?tipo-fluxo=direto";
		$.fancybox.open({
			href : caminho,
			type : 'iframe',
			width: '90%',
			padding : 2,
			beforeClose: function() {
				produtoVariacaoID = $('.fancybox-iframe').contents().find('.produto-variacao-id').val();
				$('.botao-exibir-produto').click();
				//$('.texto-cadastro-localiza').val(nome);
				/*
				cadastroID = $('.fancybox-iframe').contents().find('#cadastro-id').val();
				nome = $('.fancybox-iframe').contents().find('#nome-completo').val()+$('.fancybox-iframe').contents().find('#razao-social').val();
				$('#aux-cad-new').attr('cadastro-id', cadastroID);
				$('#aux-cad-new').val(cadastroID);
				$('#aux-cad-new').click();
				*/
			}
		});
	});
});



/*****************************************/
/***** INICIO - BLOCO OPORTUNIDADES ******/
/*****************************************/

$(document).ready(function(){
	if ($("#slug-pagina").val()=="oportunidades"){
		$(".oportunidade-localiza").live('click', function () {
			$('#oportunidade-id').val($(this).attr('oportunidade-id'));
			$("#frmDefault").attr('action', caminhoScript + "/chamados/oportunidade/");
			$("#frmDefault").submit();
		});
	}
	if ($("#slug-pagina").val()=="oportunidade"){
		if ($('#workflow-id').val()!=''){
			alertify.alert('<b>Aviso</b>',"Esta oportunidade ja possui um orçamento, você será redirecionado para o orçamento Nº " + $('#workflow-id').val(), function(){
				$('body').hide();
				$("#frmDefault").attr('action', caminhoScript + "/chamados/chamados-orcamento/");
				$("#frmDefault").submit();
			});
		}
		$('#texto-cadastro-localiza-cliente-id').focus();
	}
});



function carregarOportunidades(oportunidadeID, cadastroID){

}


function carregarListaOportunidades(cadastroID){
	caminho = caminhoScript+"/funcoes/oportunidades-carregar-lista-oportunidades.php?cadastroID="+cadastroID;
	$.ajax({type: "POST", url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#div-oportunidades-cadastradas').html(retorno);
		}
	});

}

function carregarOportunidade(oportunidadeID, cadastroID){
	caminho = caminhoScript+"/funcoes/oportunidades-carregar-oportunidade.php?oportunidadeID="+oportunidadeID+"&cadastroID="+cadastroID;
	//console.log(caminho);
	$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			//$('.div-oportunidade-'+oportunidadeID).html(retorno);
			$('.div-oportunidade-').html(retorno);
		}
	});
}

function salvarOportunidade(oportunidadeID){
	$(".botao-salvar-oportunidade").hide();
	if(validarCamposGenerico('#div-oportunidade-cadastro .required, #div-solicitante-dados .required')){
		caminho = caminhoScript+"/modulos/chamados/oportunidades-salvar-oportunidade.php";
		$.ajax({type: "POST", url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				alertify.alert('<b>Aviso</b>','Oportunidade salva com sucesso!');
				$('#orcamento-id').val(retorno.trim());
				$('#frmDefault').submit();
				$(".botao-salvar-oportunidade").show();
			}
		});
	}
	else{
		$(".botao-salvar-oportunidade").show();
	}
}

/* INCLUINDO E EDITANDO OPORTUNIDADES */
$(".botao-nova-oportunidade, .botao-editar-oportunidade").live('click', function () {
	$(".botao-nova-oportunidade, .botao-editar-oportunidade, .botao-excluir-oportunidade").show();
	oportunidadeID = $(this).attr('oportunidade-id');
	cadastroID = $(this).attr('cadastro-id');
	carregarOportunidade(oportunidadeID, cadastroID);
});

/* SALVAR OPORTUNIDADE */
$(".botao-salvar-oportunidade").live('click', function () {
	oportunidadeID = $(this).attr('oportunidade-id');
	salvarOportunidade(oportunidadeID);
});

/* CANCELAR SALVAR OPORTUNIDADE */
$(".botao-cancelar-salvar-oportunidade").live('click', function () {
	$(".botao-nova-oportunidade, .botao-editar-oportunidade, .botao-excluir-oportunidade").show();
	oportunidadeID = $(this).attr('oportunidade-id');
	$('.div-oportunidade-').html('');
	//$('.div-oportunidade-'+oportunidadeID).html('');
});

$(".botao-excluir-oportunidade").live('click', function () {
	oportunidadeID = $(this).attr('oportunidade-id');
	cadastroID = $(this).attr('cadastro-id');
	alertify.confirm('', "Tem certeza que deseja excluir o registro?",
	function(){
		caminho = caminhoScript+"/modulos/chamados/oportunidades-excluir-oportunidade.php?oportunidadeID="+oportunidadeID;
		$.ajax({type: "POST", url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				carregarListaOportunidades(cadastroID);
			}
		});
	},
	function(){
	}).set('labels', {ok:'Sim', cancel:'Cancelar'});
});

$("#botao-localizar-oportunidades").live('click', function () {
	$('#frmDefault').submit();
});


/*
$(".oportunidade-situacao-funil").live('click', function () {
	situacaoID = $(this).val();
	if (situacaoID==183){
		alert(situacaoID);
	}
});
*/
/*****************************************/
/***** FIM - BLOCO OPORTUNIDADES *********/
/*****************************************/

/********* Inicio - Funções Localiza Chamado ***********/
$(document).ready(function(){
	if ($("#slug-pagina").val()=="chamados-orcamentos-relatorios"){

		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-contas").click(); }});
		$(".menu-relatorio-min").live('click', function () {
			$("#filtro-relatorio").val($(this).attr('id'));
			$("#frmDefault").submit();
		});

		//$("#localiza-orcamento-solicitante").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-orcamento").click(); }});

		$("#botao-localizar-orcamento").live('click', function () {
			$(this).val('');
			$("#frmDefault").submit();
		});
		$(".orcamento-localiza").live('click', function () {
			$("#workflow-id").val($(this).attr('workflow-id'));
			$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
			$("#frmDefault").submit();
		});
		if ($("#slug-pagina").val()=="chamados-localizar-chamado"){
			$("#localiza-chamado-solicitante").focus();
			$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-chamado").click(); }});
		}
	}
});
/********* Fim - Funções Localiza Chamado ***********/

/********* Inicio Funções Relatório Geral de Acompanhamento de Clientes ***********/
$(document).ready(function(){
	if ($("#slug-pagina").val()=="relatorio-acompanhamento-clientes"){
		$("#botao-pesquisar-clientes").live('click', function () {
			$("#frmDefault").submit();
		});
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-clientes").click(); }});
		$(".redefinir-representante").live('click', function () {
			id = $(this).attr('cadastro-id');
			linha = $(this).attr('linha');
			editarDadosCliente(id, linha);
		});

		function editarDadosCliente (id, linha){
			$('#rep-'+linha).hide();
			caminho = caminhoScript+"/modulos/chamados/carregar-representantes.php?cadastro-id="+id+"&linha="+linha;
			$.ajax({type: "POST", url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#select-rep-'+linha).html(retorno);
				}
			});
		}

		$(".atualizar-representante").live('click', function () {
			cadastroID = $(this).attr('cadastro-id');
			linha = $(this).attr('linha');
			representantes = $('#representante-cliente-'+cadastroID).val();
			classificacao = $('#classificacao-id-'+cadastroID).val();
			caminho = caminhoScript+"/modulos/chamados/atualizar-representantes.php?cadastro-id="+cadastroID + "&representantes=" + representantes + "&classificacao=" + classificacao;
			$.ajax({type: "POST", url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//alert(retorno);
					$('#rep-'+linha).html(retorno).show();
					$('#select-rep-'+linha).html('');
				}
			});
		});
		$(".cancelar-representante").live('click', function () {
			linha = $(this).attr('linha');
			$('#rep-'+linha).show();
			$('#select-rep-'+linha).html('');
		});


		/********* Classifica Valores ***********/
		$(".classifica-valor").live('click', function () {
			elemento = $(this);
			cadastroID = $(this).attr('cadastro-id');
			linha = $(this).attr('linha');
			if ($('#select-rep-'+linha).html().trim()==''){
				editarDadosCliente(cadastroID, linha);
			}
			if (($(this).hasClass('estrela-ativa')) && ($(this).attr('valor')==$('#classificacao-cadastro-'+cadastroID).val())){
				$('.classifica-valor-cad-'+cadastroID).addClass('estrela-inativa').removeClass('estrela-ativa');
				$('#classificacao-cadastro-'+cadastroID).val('');
				$('#classificacao-id-'+cadastroID).val('');
			}
			else{
				i = 0;
				$(".classifica-valor-cad-"+cadastroID).removeClass('estrela-ativa').addClass('estrela-inativa');
				$(".classifica-valor-cad-"+cadastroID).each(function(){
					i++;
					$(this).addClass('estrela-ativa').removeClass('estrela-inativa');
					if (parseFloat($(this).attr('valor')) == parseFloat(elemento.attr('valor'))){
						valorClicado = $(this).attr('valor');
						return false;
					}
				});
				$('#classificacao-cadastro-'+cadastroID).val(valorClicado);
				$('#classificacao-id-'+cadastroID).val(elemento.attr('classificacao-id'));
			}

		});


	}
});


/********* Fim Funções Relatório Geral de Acompanhamento de Clientes ***********/