var valorTotalAnt;
var qtdeParcelasAnt;
$(document).ready(function(){
	var paginaAtual = window.location.href.replace("#","");
	var slugPagina = $("#slug-pagina").val();

	/********* Inicio Funções localiza Contas e Titulos***********/

	if (slugPagina=="financeiro-contas"){
		$("#localiza-cadastro-para").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-contas").click(); }});
	}

	$(".botao-incluir-conta").live('click', function () {
		if ($('#lancamento-fancybox').val()==1){
			caminho = caminhoScript+"/financeiro/financeiro-lancamento?tipo=direto&slug-pagina="+$('#slug-pagina').val()+"&filtro-cadastro-conta="+$('#filtro-cadastro-conta').val();
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
				afterClose : function(){
					location.reload(true);
					//$("#frmDefault").submit();
				}
			});
		}
		else{
			caminho = caminhoScript+"/financeiro/financeiro-lancamento";
			$('#frmDefault').attr("action",caminho);
			$("#frmDefault").submit();
		}
	});

	$("#botao-pesquisar-contas").live('click', function () {
		$("#frmDefault").attr("action",paginaAtual);
		$("#frmDefault").submit();
	});

	$("#botao-pesquisar-relatorio-cadastros").live('click', function () {
		$("#frmDefault").attr("action",paginaAtual);
		$("#frmDefault").attr("target","_top");
		$("#frmDefault").submit();
	});

	if ($("#slug-pagina").val()=="financeiro-contas"){
		mostrarCamposLocalizaContas();
		$("#localiza-situacao-conta").live('change', function () {
			mostrarCamposLocalizaContas();
		});
	}

	$(".btn-expandir-retrair-operacoes").live('click', function () {
		if($(this).hasClass('btn-expandir')){
			$(this).removeClass('btn-expandir');$(this).addClass('btn-retrair');
			campoID = "#"+$(this).attr('nome-bloco');
			$("#tipo-operacao").val($(this).attr('tipo-operacao'));
			caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-relatorio-entradas-saidas.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$(campoID).html(retorno);
				}
			});
		}
		else{
			$(this).removeClass('btn-retrair');$(this).addClass('btn-expandir');
			$("#"+$(this).attr('nome-bloco')).html("&nbsp;");
		}
	});


	if ($("#slug-pagina").val()=="financeiro-relatorio-resumo-geral"){
			exibirFiltrosDemonstrativo();
			$("#exibir-informacoes").live('change', function () {
				exibirFiltrosDemonstrativo();
				$("#localiza-cadastro-de option").attr('selected', false);
				$("#localiza-centro-custo option").attr('selected', false);
		});
	}

	if ($("#slug-pagina").val()=="financeiro-relatorio-resumo-geral"){
		$("#botao-salvar-excel").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico-session.php");
			$("#frmDefault").submit();
		});
	}


	if ($("#slug-pagina").val()=="financeiro-relatorio-demonstracao-financeira"){
		exibirFiltrosDemonstrativo();
		$("#exibir-informacoes").live('change', function () {
			exibirFiltrosDemonstrativo();
			$("#localiza-cadastro-de option").attr('selected', false);
			$("#localiza-centro-custo option").attr('selected', false);
		});
		$("#botao-salvar-excel").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico.php");
			$("#frmDefault").attr("target","_top");
			conteudo = $("#conteudo-interno-retorno").html();
			conteudo = ReplaceAll(conteudo,"styletitulo"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:15px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'");
			conteudo = ReplaceAll(conteudo,"styledestaque"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'");
			conteudo = ReplaceAll(conteudo,"stylenormal"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;solid #fff;'");
			conteudo = ReplaceAll(conteudo,"100%","718'");
			$("#conteudo-relatorio").val(conteudo);
			$("#frmDefault").submit();
		});

		$("#botao-imprimir").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-impressao-generico.php");
			$("#frmDefault").attr("target","_blank");
			conteudo = $("#conteudo-interno-retorno").html();
			conteudo = ReplaceAll(conteudo,"styletitulo"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:15px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'");
			conteudo = ReplaceAll(conteudo,"styledestaque"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'");
			conteudo = ReplaceAll(conteudo,"stylenormal"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;solid #fff;'");
			conteudo = ReplaceAll(conteudo,"100%","718'");
			$("#conteudo-relatorio").val(conteudo);
			$("#frmDefault").submit();
		});
	}

	if ($("#slug-pagina").val()=="financeiro-relatorio-fluxo-caixa"){
		exibirFiltrosFluxoCaixa()
		$("#agrupar-por").live('change', function () {
			exibirFiltrosFluxoCaixa();
		});
		$("#botao-salvar-excel-fluxo").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico-session.php");
			$("#frmDefault").submit();
		});
	}

	if ($("#slug-pagina").val()=="financeiro-relatorio-fluxo-caixa"){
		$("#botao-salvar-excel").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico-session.php");
			$("#frmDefault").submit();
		});
	}

	if (($("#slug-pagina").val()=="financeiro-relatorio-transferencias")||($("#slug-pagina").val()=="financeiro-relatorio-entradas-saidas")|| ($("#slug-pagina").val()=="financeiro-relatorio-periodo-entradas-saidas")){
		$("#botao-salvar-excel").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico-session.php");
			$("#frmDefault").submit();
		});
		$("#botao-imprimir").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-impressao-generico-session.php");
			$("#frmDefault").attr("target","_blank");
			$("#frmDefault").submit();
		});
	}

	if ($("#slug-pagina").val()=="financeiro-relatorio-receitas-despesas"){
		$("#botao-imprimir").live('click', function () {
			$("#topo-container, #menu-container, #titulo-principal, #rodape-container, .titulo-container").hide();
			window.print();
			$("#topo-container, #menu-container, #titulo-principal, #rodape-container, .titulo-container").show();
		});
	}

	$(".localiza-conta").live('click keyup', function () {
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

	carregarLancamentosCentroCusto();

	$("#lancamento-centro-custo").live('change', function () {
		carregarLancamentosCentroCusto();
	});

	function carregarLancamentosCentroCusto(){
		opcoes = $("#lancamento-centro-custo option:selected").length;
		if (opcoes>1){
			caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-centros-custos-lancamento.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#centro-custo-valores").html(retorno);
				}
			});
		}
		else{
			$('#centro-custo-valores').html('');
		}
	}
	$(".botao-incluir-alterar-produto").live('click', function () {
		financeiroProdutoID = $(this).attr('financeiro-produto-id');
		caminho = caminhoScript+"/modulos/financeiro/financeiro-localizar-produtos-financeiro.php?financeiro-produto-id="+financeiroProdutoID;
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$(".botao-incluir-alterar-produto, .botao-excluir-produto").fadeOut();
				if (financeiroProdutoID==''){
					$('#incluir-produto-conta').html(retorno).fadeIn();
				}
				else{
					$('.bloco-produto-financeiro-'+financeiroProdutoID).html(retorno).fadeIn().css('border','0');
					$('html, body').animate({scrollTop: $('.bloco-produto-financeiro-'+financeiroProdutoID).offset().top }, 1000);
				}

			}
		});
	});

	$(".botao-excluir-produto").live('click', function () {
		if (confirm('Tem certeza que seja excluir o produto?')){
			financeiroProdutoID = $(this).attr('financeiro-produto-id');
			caminho = caminhoScript+"/modulos/financeiro/financeiro-excluir-produtos-financeiro.php?financeiro-produto-id="+financeiroProdutoID;
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//$('body').append(retorno);
					carregarProdutosConta();
				}
			});


		}
	});





	$(".botao-salvar-produto").live('click', function () {
		if(validarCamposGenerico('#bloco-incluir-alterar-produto .required')){
			caminho = caminhoScript+"/modulos/financeiro/financeiro-produto-salvar.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$(".botao-incluir-alterar-produto, .botao-excluir-produto").fadeIn();
					carregarProdutosConta();
					$('body').append(retorno);
				}
			});
		}
	});

	$("#select-produtos").live('change', function () {
		$('#descricao-produto-variacao-aux').val($("#select-produtos option:selected").text());
		if ($("#select-produtos").val()!=""){
			detalhesCampo= $.ajax(caminhoScript+"/modulos/financeiro/financeiro-produto-detalhes.php?produto-variacao-id="+$("#select-produtos").val()+"&contaID="+$("#conta-id").val())
			.done(function(){
				$("#div-detalhes-produto").html(detalhesCampo.responseText);
				calcularTotaisProdutos();
			});
		}
		else{
			$("#div-detalhes-produto").hide();
			calcularTotaisProdutos();
		}
	});

	$("#quantidade-produtos, #valor-venda-unitario").live('blur change keypress click keyup', function () {
		calcularTotaisProdutos();
	});

	function calcularTotaisProdutos(){
		if ($("#select-produtos").val()!=""){
			valorVendaUnitario = desformataValor($("#valor-venda-unitario").val());
			qtde = desformataValor($("#quantidade-produtos").val());
			if (qtde>0){
				if ($("#valor-venda-unitario").length){
					totalVenda = parseFloat(qtde * valorVendaUnitario);
					$("#total-venda-produtos").val(number_format(totalVenda,'2',',','.'));
				}
				else
					$("#total-venda-produtos").val("0,00");
			}
		}
	}


	$(".botao-cancelar-produto").live('click', function () {
		carregarProdutosConta();
		$(".botao-incluir-alterar-produto, .botao-excluir-produto").fadeIn();
		//$('#incluir-produto-conta').hide().html("");
	});


	/********* Inicio Funções Emissão de Contas e Titulos***********/

	if ($("#slug-pagina").val()=="financeiro-lancamento"){
		$(window).bind('keydown', function(event) {
			if (event.ctrlKey || event.metaKey) {
				if(event.which == 32){
					selecionado = $(".menu-interno-superior-selecionado").attr("id");
					if ((selecionado == "menu-superior-1") || (selecionado == "menu-superior-2")){
						if (($("#menu-superior-6").length)>0){
							$("#menu-superior-6").click();
						}
						else{
							$("#menu-superior-3").click();
						}
					}
					if (selecionado == "menu-superior-6"){
						$("#menu-superior-3").click();
					}
					if (selecionado == "menu-superior-3"){
						$("#menu-superior-1").click();
					}
				}
			}
		});

		carregarVencimentos();
		verificaTipoContas();
		exibirJurosCorrecoes();
		verificarModalidadeLancamento();

		$(".menu-interno-modulo").live('click', function () {
			verificaTipoContas();
		});

		/* INICIO - COBRANCA DE JUROS + */
		$("#aplicar-juros, #aplicar-correcao-monetaria, #aplicar-cobranca-honorarios").live('click', function () {
			exibirJurosCorrecoes();
		});


		if ($('#lancamento-exibir-dados-empresa').val()=='1'){
			$("#cadastro-id-de").live('change', function () {
				$('#dados-cadastro-de').html('');
				cadastroID = $("#cadastro-id-de").val();
				nomeCampo = 'campo-auxiliar';
				descricaoCampo = $('#configFinanceiroCadastro').html();
				selecionaEndereco = '';
				console.log('-->' + cadastroID);
				if (cadastroID!=''){
					caminho = caminhoScript+"/funcoes/cadastro-carregar-cadastro-geral.php?nome-campo="+nomeCampo+"&cadastro-id="+cadastroID+"&descricao-campo="+descricaoCampo+"&seleciona-endereco="+selecionaEndereco;
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							$('#dados-cadastro-de').html(retorno);
						}
					});
				}
			});
		}


		function exibirJurosCorrecoes(){
			$('.aplicar-juros, .aplicar-correcao-monetaria, .aplicar-cobranca-honorarios').hide();
			$('#percentual-juros-mensal-simples, #percentual-juros-mensal-composto, #percentual-juros-multa, #percentual-honorarios, #indice-correcao').removeClass('required');
			if ($('#aplicar-juros').attr('checked')){
				$('.aplicar-juros').show();
				//$('#percentual-juros-mensal-simples, #percentual-juros-mensal-composto, #percentual-juros-multa').addClass('required');
			}
			else{
				$('#percentual-juros-mensal-simples, #percentual-juros-mensal-composto, #percentual-juros-multa').val('');
			}
			if ($('#aplicar-cobranca-honorarios').attr('checked')){
				$('.aplicar-cobranca-honorarios').show();
				$('#percentual-honorarios').addClass('required');
			}
			else{
				$('#percentual-honorarios').val('');
			}
			if ($('#aplicar-correcao-monetaria').attr('checked')){
				$('.aplicar-correcao-monetaria').show();
				$('#indice-correcao').addClass('required');
			}
			else{
				$('#indice-correcao').val('').trigger('chosen:updated');
			}
		}

		function verificarModalidadeLancamento(){
			if ($('#modFrete').val()=='9'){
				$(".dados-transportadora").hide();
			}
			else{
				$(".dados-transportadora").show();
			}
		}
		$("#modFrete").live('change', function () {
			verificarModalidadeLancamento();
		});

		/* INICIO - FUNCOES REFERENTES AO MODULO DE IGREJA */
		/* SO CARREGA SE MODULO DE IGREJA ATIVO */
		if ($('#modulo-igreja-ativo').length){
			$("#menu-superior-6").live('click', function () {
				$(".contas-a-receber").hide();
				$(".contas-a-pagar").hide();
				$(".transferencias").hide();
				$(".membros").show();
				$("#data-lancamento-membros").val($("#data-vencimento-1").val());
				$("#quantidade-membros").focus();
			});

			$("#botao-salvar-lancamento-membro").live('click', function () {
				flag=0;
				$("#data-lancamento-membros").css('background-color', '').css('outline', '');
				$("#quantidade-membros").css('background-color', '').css('outline', '');
				//$("#texto-cadastro-localiza-cadastro-id-de").css('background-color', '').css('outline', '');
				$("#cadastro_id_de_chosen").css('background-color', '').css('outline', '');

				if ($('#data-lancamento-membros').val()==""){ $("#data-lancamento-membros").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus(); flag=1; }
				if (($.trim($('#quantidade-membros').val())=="") || ($.trim($('#quantidade-membros').val())<=0)){ $("#quantidade-membros").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus(); flag=1;}
				//if ($('#cadastro-id-de').val()==""){ $("#texto-cadastro-localiza-cadastro-id-de").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); $("#texto-cadastro-localiza-cadastro-id-de").focus(); flag=1;}
				if ($('#cadastro-id-de').val()==""){ $("#cadastro_id_de_chosen").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); $("#cadastro-id-de").focus(); flag=1;}
				if (flag==0){
					caminho = caminhoScript+"/modulos/igreja/igreja-salvar-lancamento-membros.php";
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							carregarLancamentoMembro();
						}
					});
				}
			});

			$("#cadastro-id-de").live('change', function () {
				carregarLancamentoMembro();
			});

			$(".btn-excluir-lancamento-membro").live('click', function () {
				if(confirm("Você tem certeza que deseja excluir o lançamento?")){
					caminho = caminhoScript+"/modulos/igreja/igreja-excluir-lancamento-membros.php?lancamentoID="+$(this).attr("igreja-lancamento-id");
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							carregarLancamentoMembro();
						}
					});
				}
			});


			function carregarLancamentoMembro(){
				caminho = caminhoScript+"/modulos/igreja/igreja-carregar-lancamento-membros.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$("#div-igreja-membros").html(retorno);
						if(!($("#div-dados-gerais").is(":visible")))
							$(".conjunto6").show();
						$("#data-lancamento-membros").val($("#data-vencimento-1").val());
					}
				});
			}
		}

		/* FIM - FUNCOES REFERENTES AO MODULO DE IGREJA*/


		/* funcoes referentes a emissao da NF*/
		//se ja existir nota para conta mostrar aba NF
		if ($('#nf-id').val()==""){
			gerarNumeracaoNF();
		}

		$("#menu-superior-3").live('click', function () {
			carregarLancamentosMensais();
		});

		$(".radio-tipo-grupo-27").live('click', function () {
			verificaTipoContas();
			carregarTipoConta();
		});

		$("#menu-superior-2").live('click', function () {
			ajustarTodosProdutosServicos();
		});


		/*se registro existe*/
		if(($("#conta-id").val()!="") && ($("#conta-id").length>0)){
			/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
			$("#menu-superior-4").live('click', function () {
				carregarDocumentos($("#conta-id").val(),'financeiro');
			});

			//$('.radio-tipo-grupo-27').attr('disabled', true);
		}

		/*Valores*/
		$(".alterar-vencimento").live('click', function () {
			$(this).hide();
			ordem = $(this).attr("ordem");
			$(".fechado-"+ordem).hide();
			$(".aberto-"+ordem).show();
			$("#situacao-vencimento-"+ordem).attr("situacao-pagamento-id","");
		});

		$("#valor-total").live('blur', function () {
			if (valorTotalAnt!=$("#valor-total").val()){
				carregarVencimentos();
			}
		});
		$("#qtde-parcelas").live('blur', function () {
			$("#qtde-parcelas").css('background-color', '').css('outline', '');
			if (($('#qtde-parcelas').val()=="")||($('#qtde-parcelas').val()<2)){
				$("#qtde-parcelas").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				$("#qtde-parcelas").select();
				$("#qtde-parcelas").focus();
			}
			if (qtdeParcelasAnt!=$("#qtde-parcelas").val()){
				carregarVencimentos();
			}
		});

		$(".tipo-pgto").live('change', function () {
			if ($(this).val()=="p"){
				$(".div-numero-parcelas").show();
				$("#qtde-parcelas").val("2");
			}
			else{
				$(".div-numero-parcelas").hide();
				$("#qtde-parcelas").val("1");
			}
			carregarVencimentos();
		});



		$("#titulo-lancamento").live('click keyup', function () {
			carregarVencimentos();
		});

		$(".forma-pagamento").live('change', function () {
			if ($(this).val()==47)
				$('#boleto-gerar-'+$(this).attr('posicao')).show();
			else
				$('#boleto-gerar-'+$(this).attr('posicao')).hide();
		});



		$(".btn-disseminar").live('click', function () {
			tipoGrupo = $(this).attr("tipo-grupo");
			if (tipoGrupo == 'forma-pagamento'){
				$(".forma-pagamento").val($("#forma-pagamento-1").val()).trigger('chosen:updated');
			}
			if (tipoGrupo=='situacao-vencimento'){
				$(".situacao-vencimento").val($("#situacao-vencimento-1").val()).trigger('chosen:updated');
			}
			if (tipoGrupo=='data-vencimento'){
				dataVencimento = $("#data-vencimento-1").val();
				cont = 0;
				$(".data-vencimento").each(function(){
					cont++;
					if (cont>1){
						dataVencimento = adicionarMesData(dataVencimento,1);
						$(this).val(dataVencimento);
					}
				});
			}
			exibirInfoPagamento();
		});

		$(".situacao-vencimento").live('change', function () {
			exibirInfoPagamento();
		});

		$(".botao-atualizar-conta").live('click', function () {
			fluxo = "";
			salvarConta(fluxo);
		});

		$(".botao-excluir-conta").live('click', function () {
			if (confirm("Tem certeza que deseja excluir esta conta? os dados não poderão ser recuperados posteriormente")){
				$(".botao-excluir-conta").hide();
				$(".botao-atualizar-conta").hide();
				caminho = caminhoScript+"/modulos/financeiro/financeiro-conta-excluir.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						alertify.alert("Aviso","Registro excluído com sucesso");
						if ($('#lancamento-fancybox').val()=="1"){
							parent.$.fancybox.close();
						}
						else{
							$("#frmDefault").attr("action",caminhoScript+"/financeiro/financeiro-contas");
							$("#frmDefault").submit();
						}
						/*
						afterClose : function(){
							$("#frmDefault").submit();
						}
						*/
					}
				});
			}
		});


		$(".botao-salvar-conta").live('click', function () {
			$(".botao-salvar-conta").hide();
			fluxo = $(this).attr("fluxo");
			salvarConta(fluxo);
		});

		$("#botao-visualizar-parcelas").live('click', function () {
			$("#botao-visualizar-parcelas").hide();
			$(".coluna-vencimentos").show();
		});


		/* LANCAMENTO CONTAS */
		$("#cadastro-conta-id-de").live('change', function () {
			$("#cadastro-id-de").val($("#cadastro-conta-id-de option:selected").attr("empresa-id")).trigger('chosen:updated');
		});
		$("#cadastro-conta-id-para-transf").live('change', function () {
			$("#cadastro-id-para-transf").val($("#cadastro-conta-id-para-transf option:selected").attr("empresa-id")).trigger('chosen:updated');
		});
	}

	$(".link-ordem-compra").live('click', function () {
		$("#ordem-compra-id").val($(this).attr("ordem-compra-id"));
		$("#frmDefault").attr("action",caminhoScript+"/compras/compras-visualiza-ordem-gerada");
		$("#frmDefault").submit();
	});
	$(".link-chamado").live('click', function () {
		$("#workflow-id").val($(this).attr('workflow-id'));
		caminho = caminhoScript+"/chamados/chamados-cadastro-chamado";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});


	/* FINANCEIRO AGUARDANDO FATURAMENTO */
	if ($("#slug-pagina").val()=="financeiro-aguardando-faturamento"){
		visualizaCamposOrigem();
		$(".seleciona-todas-faturar").live('click', function () {
			$(".produto-faturar").attr("checked", true);
			$(".produto-cancelar").attr("checked", false);
		});
		$(".seleciona-todas-cancelar").live('click', function () {
			$(".produto-faturar").attr("checked", false);
			$(".produto-cancelar").attr("checked", true);
		});

		$(".produto-cancelar").live('click', function () {
			$("#produto-faturar-"+$(this).attr('posicao')).attr("checked", false);
		});
		$(".produto-faturar").live('click', function () {
			$("#produto-cancelar-"+$(this).attr('posicao')).attr("checked", false);
		});


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

			// console.log('teste de botão...');

			// console.log('teste de origem  '+$(this).attr('origem'));

			// console.log('teste de origem  '+$(this).val());

			if ($(this).attr('origem')=='orcamentos'){
				dados = $('#frmDefault .prod-faturar:checked, #frmDefault .prod-cancelar:checked').serialize();
				dados += "&empresa-id=" 	+ $(this).attr('empresa-id');
				dados += "&tipo-id=" 		+ $(this).attr('tipo-id');
				dados += "&cadastro-id=" 	+ $(this).attr('cadastro-id');
				dados += "&modulo="			+ $(this).attr('origem');
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
							if(slugPagina=='financeiro-aguardando-faturamento'){
								location.reload(true);
							}
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
			else{
				flagFat = 0; flagCanc = 0;
				$(".produto-faturar").each(function(){
					if($(this).attr('checked')) flagFat = flagFat + 1;
				});
				$(".produto-cancelar").each(function(){
					if($(this).attr('checked')) flagCanc = flagCanc + 1;
				});
				if ((flagFat==0)&&(flagCanc==0)){
					alertify.alert('Aviso','Selecione ao menos um registro para faturar/cancelar!');
				}
				else{
					if (($("#origem-selecionada").val()=="compras") || ($("#origem-selecionada").val()=="envios")){
						flagFornDif = false;
						fornecedorIDAnt = "";
						$(".produto-faturar").each(function(){
							if($(this).attr('checked')){
								if ((fornecedorIDAnt!="") && (fornecedorIDAnt!=($(this).attr('fornecedor-id')))){
									flagFornDif = true;
					botao-faturar-cancelar			}
								fornecedorIDAnt = $(this).attr('fornecedor-id');
							}
						});
						if (flagFornDif){
							if(!(confirm("Você esta realizando o faturamento de produtos de diferentes fornecedores para mesma conta, tem certeza que deseja continuar?"))){
								return;
							}
						}
					}
					caminho = caminhoScript+"/modulos/financeiro/financeiro-atualizar-produtos-faturamento.php";
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							//$('#div-retorno').html(retorno);
							if (retorno==""){
								caminho = caminhoScript+"/financeiro/financeiro-aguardando-faturamento";
								$('#frmDefault').attr("action",caminho);
								$("#frmDefault").submit();
							}
							else{
								$('#localiza-conta-id').val(retorno);
								caminho = caminhoScript+"/financeiro/financeiro-lancamento";
								$('#frmDefault').attr("action",caminho);
								$("#frmDefault").submit();
							}

						}
					});
				}
			}
		});
		$("#origem-faturamento").live('change', function () {
			visualizaCamposOrigem();
		});
	}
});

function gerarNumeracaoNF(){
	caminho = caminhoScript+"/modulos/nfe/nfe-gerar-numero-nf.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#nf-numero").val($.trim(retorno));
		}
	});
}

function visualizaCamposOrigem(){
	$(".div-todos").hide();
	$(".div-origem-"+$("#origem-faturamento").val()).show();
}

function salvarConta(fluxo){
	flag = 0;

	$("#texto-cadastro-localiza-cadastro-id-para").css('background-color', '').css('outline', '');
	$("#qtde-parcelas").css('background-color', '').css('outline', '');
	$("#valor-total").css('background-color', '').css('outline', '');
	$(".forma-pagamento").css('background-color', '').css('outline', '');
	$(".situacao-vencimento").css('background-color', '').css('outline', '');
	$(".data-vencimento").css('background-color', '').css('outline', '');
	$(".valor-vencimento").css('background-color', '').css('outline', '');
	$(".data-pago").css('background-color', '').css('outline', '');
	$(".valor-pago").css('background-color', '').css('outline', '');

	//contas a pagar e a receber
	
	// if(($(".radio-tipo-grupo-27:checked").val()=="44")||($(".radio-tipo-grupo-27:checked").val()=="45")){
	// 	if ($('#lancamento-tipo-conta').val()==""){
	// 		$("#lancamento_tipo_conta_chosen a").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
	// 		flag=1;
	// 	}
	// }
	

	//transferencias
	if($(".radio-tipo-grupo-27:checked").val()=="46"){
		/*if ($('#cadastro-id-para').val()==""){ $("#texto-cadastro-localiza-cadastro-id-para").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); $("#texto-cadastro-localiza-cadastro-id-para").focus(); flag=1;}*/
		$('#cadastro-conta-id-para-transf, #cadastro-id-para-transf').addClass('required');
	}



	// AQUI COMENTADO PARA MANDAR NOTA FISCAL DE TRASPORTE COM VALORES ZERADOS
	//if (desformataValor($('#valor-total').val())==0){ $("#valor-total").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); $("#valor-total").focus(); flag=1;}

	if (desformataValor($('#qtde-parcelas').val())==0){ 
		$("#qtde-parcelas").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		$("#qtde-parcelas").focus(); flag=1;
	}

	$(".situacao-vencimento").each(function(){
		ordem = $(this).attr("ordem");
		if ($(this).val()==""){$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($(this).val()=="49"){
			if (($("#valor-pago-"+ordem).val()=="") || ((parseFloat(ReplaceAll(ReplaceAll($("#data-pago-"+ordem).val(),".",""),",",".")))==0)){
				$("#valor-pago-"+ordem).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				flag=1;
			}
			if (($("#data-pago-"+ordem).val()=="")){
				$("#data-pago-"+ordem).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				flag=1;
			}
		}
	});
	/*
	$(".data-vencimento").each(function(){
		if ($(this).val()==""){
			$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	});
	*/
	var total = 0;
	$(".valor-vencimento").each(function(){
		if ($(this).val()==""){
			$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			flag=1;
		}
		else{
			/*
			if((parseFloat(ReplaceAll(ReplaceAll($(this).val(),".",""),",",".")))==0){
				$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				flag=1;
			}
			*/
			total = parseFloat(total) + parseFloat(ReplaceAll(ReplaceAll($(this).val(),".",""),",","."));
		}
	});
	/*
	if (total == 0){
		 $("#valor-total").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		 $(".valor-vencimento").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		 flag=1;
	}
	*/

	if(((Math.round(parseFloat(total*100),2)/100)) != parseFloat(ReplaceAll(ReplaceAll($("#valor-total").val(),".",""),",","."))){
		$("#valor-total").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		$(".valor-vencimento").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		flag=1;
		alertify.alert("Aviso","O somatório das parcelas divergem do valor total informado.");
	}
	if (($('.mensagem-contabil').html()!="") && ($('.valor-contabil').length>1)){
		$('.mensagem-contabil').css('background-color', '#FFE4E4');
		alertify.alert("Aviso","O somatório da divisão de valores divergem do valor total informado.");
		flag=1;
	}
	//alert(validarCamposGenerico('.required'));
	if((flag==0)&&(validarCamposGenerico('.required'))){
		if ((fluxo=="continuar")&&($(".tipo-pgto:checked").val()=='v')){
			$("#aux-forma-pagamento").val($("#forma-pagamento-1").val());
			$("#aux-data-vencimento").val($("#data-vencimento-1").val());
			$("#aux-data-pago").val($("#data-pago-1").val());
			$("#aux-situacao-vencimento").val($("#situacao-vencimento-1").val());
		}
		caminho = caminhoScript+"/modulos/financeiro/financeiro-salvar-conta.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				// SE FATURAMENTO DIRETO NA O.S. (Fancybox)
				/*
				if (retorno.trim()=='direto'){
					parent.carregarFinanceiro();
					parent.$.fancybox.close();
				}
				else{
				*/
					/* linha abaixo responsavel por dizer se foi salvo o registro */
					$("#lancamento-salvo").val("1");
					if (fluxo=="continuar"){
						carregarTitulos();
						$("#cadastro-id-para").val("");
						/* INICIO - LIMPANDO O FAVORECIDO */
						$("#div-campos-consulta-cadastro-id-para").show();
						$("#div-localiza-cadastro-cadastro-id-para").hide();
						$("#div-cadastro-cadastro-id-para").hide();
						/* FIM - LIMPANDO O FAVORECIDO */
						$("#texto-cadastro-localiza-cadastro-id-para").css('background-color', '').css('outline', '').val("");
						$("#observacao").val("");
						$(".lancamento-tipo-conta").val("").focus().click().trigger('chosen:updated').chosen().trigger("chosen:open");
						alertify.success("Registro salvo com sucesso!");
					}
					else{
						$("#conta-id").val($.trim(retorno));
						$("#localiza-conta-id").val($.trim(retorno));
						alertify.alert("Aviso","Transação salva com sucesso");
						$('#frmDefault').attr("action","");
						$("#frmDefault").submit();
					}
				//}
			}
		});
	}
	else{
		if ((fluxo=="continuar")||(fluxo=="sair")){
			$("#botao-salvar-conta").show();
			if ($("#conta-id").val()!='')
				$('#botao-salvar-conta-continuar').hide();
			else
				$('#botao-salvar-conta-continuar').show();
		}
	}
}

function exibirInfoPagamento(){
	flag = 0;
	$(".situacao-vencimento").each(function(){
		ordem = $(this).attr("ordem");
		situacaoPagamentoID = $(this).attr("situacao-pagamento-id");
		if (($(this).val()=="49")&& (situacaoPagamentoID!="49")){
			$("#data-pago-"+ordem).val($("#data-vencimento-"+ordem).val());
			valorPago = $("#valor-vencimento-"+ordem).val();
			/* CASO VALOR CORRIGIDO POR JUROS E MULTAS BUSCA O VALOR CORRIGIDO*/
			if($("#valor-corrigido-"+ordem).length){
				valorPago = $("#valor-corrigido-"+ordem).val();
			}
			$("#valor-pago-"+ordem).val(valorPago);
			$(".pago-"+ordem).show();
		}
		else{
			$(".pago-"+ordem).hide();
		}
	});
	$(".info-pagamento").show();
}

function carregarVencimentos(){
	if ($("#qtde-parcelas").val()!=""){
		caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-vencimentos.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#div-titulos").html($.trim(retorno));
				verificaTipoContas();
				exibirInfoPagamento();
				valorTotalAnt = $("#valor-total").val();
				qtdeParcelasAnt = $("#qtde-parcelas").val();
			}
		});
	}
}

function verificaTipoContas(){
	$(".contas-a-receber").hide()
	$(".contas-a-pagar").hide()
	$(".transferencias").hide()
	$(".membros").hide()
	$(".bloco-contabil-lancamento").show();
	$("#conteudo-interno-cadastro-id-para").show();
	if($(".radio-tipo-grupo-27:checked").val()=="44"){
		$(".contas-a-pagar").show();
	}
	if($(".radio-tipo-grupo-27:checked").val()=="45"){
		$(".contas-a-receber").show();
	}
	if($(".radio-tipo-grupo-27:checked").val()=="46"){
		$(".lancamento-tipo-conta").val("").trigger('chosen:updated');
		$(".bloco-contabil-lancamento").hide();
		$(".transferencias").show();
		$("#conteudo-interno-cadastro-id-para").hide();
	}
}

function carregarTipoConta(){
	caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-tipo-conta.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$(".lancamento-tipo-conta").empty().append(retorno).trigger('chosen:updated');
		}
	});

	/*
	caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-tipo-conta.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#seleciona-tipo-conta").html($.trim(retorno));
		}
	});
	*/
}


function carregarTitulos(){
	caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-titulos.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-titulo").html($.trim(retorno));
			verificaTipoContas();
			carregarVencimentos();
			/*
			if(!(($("#conta-id").val()!="") && ($("#conta-id").length>0))){
				if ($("#cadastro-id-de").val()==""){
				}
				else{
					$("#valor-total").focus();
					$("#valor-total").select();
				}
			}
			*/
		}
	});
}

function mostrarCamposLocalizaContas(){
	if (($("#localiza-situacao-conta").val()=="-1")||($("#localiza-situacao-conta").val()=="-2"))
		$(".div-normal").hide();
	else
		$(".div-normal").show();
}


//var cadastroIDdeAnt = "";
//var contaIDAnt = "";
//var dataVencimentoAnt = "";

function carregarLancamentosMensais(){
	//if (($("#cadastro-id-de").val()!="")&&($("#conta-id").val()=="")&&($(".data-vencimento").length>0)){
	if (($("#cadastro-id-de").val()!="")&&($(".data-vencimento").length>0)){
		$("#div-lancamentos-mes").html("<p align='center'>AGUARDE CARREGANDO</p>");
		caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-lancamentos-mes.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#div-lancamentos-mes").html($.trim(retorno));
			}
		});
	}
}


function carregarProdutosConta(){
	$("#div-produtos").html('');
	caminho = caminhoScript+"/modulos/financeiro/financeiro-carregar-produtos-conta.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
			$("#div-produtos").html($.trim(retorno));
			ajustarTodosProdutosServicos();
		}
	});
}


function exibirFiltrosDemonstrativo(){
	$(".div-filtros").hide();
	$(".div-rel-"+$("#exibir-informacoes").val()).show();
}

function exibirFiltrosFluxoCaixa(){
	if ($("#agrupar-por").val()=="cc"){
		$("#div-cadastros").hide();
		$("#localiza-cadastro-de option").attr('selected', false);
	}
	else{
		$("#div-cadastros").show();
	}
}

/* NF-e*/
$(document).ready(function(){

	/*NF-E PRODUTOS E SERVIÇOS*/
	$(".botao-gerar-xml-a1").live('click', function () {
		var mensagem = "";
		erro = 0;
		if ($('#modFrete').length){
			if ($('#modFrete').val()=='9'){
				$('.obriga-frete').removeClass('obrigatorio');
			}
			else{
				$('.obriga-frete').addClass('obrigatorio');
			}
		}
		$($(".obrigatorio").get().reverse()).each(function(i) {
			$(this).css('background-color', '').css('outline', '');
			if ($(this).hasClass("ncm-produto")){
				if ($(this).val().length!=8){
					$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
					erro=1;
					if ($(this).attr("bloco-pai").length){
						$("#"+$(this).attr("bloco-pai")).click();
					}
					if(erro==1) $(this).focus();
				}
			}
			else{
				if($(this).val()==""){
					if($(this).is('select')==true) {
						$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen a").css('background-color', '#FFE4E4');
						if($(this).attr('multiple')=='multiple') {
							$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_" + $(this).attr('indice') +"_chosen ul").css('background-color', '#FFE4E4');
						}
						erro=1;
					}
					else{
						$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
						erro=1;
					}
					if ($(this).attr("bloco-pai").length){
						$("#"+$(this).attr("bloco-pai")).click();
					}
					if(erro==1) $(this).focus();
				}
			}
		});
		if (($("#cadastro-id-para").val()=="0")||($("#cadastro-id-para").val()=="")){
			$("#menu-superior-1").click();
			$("#texto-cadastro-localiza-cadastro-id-para").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			erro=1;
		}
		if ($('.produto-id').length==0){
			$("#menu-superior-2").click();
			alertify.alert('Aviso',"Não há nenhum produto ou serviço cadastrado para este lançamento financeiro.");
			return false;
		}
		if (erro==1){
			$(".div-mensagem-aguardando").html("");
			alertify.alert('Aviso',"Existem campos obrigatórios não preenchidos"+mensagem);
		}else{
			$(".div-mensagem-aguardando").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
			$("#menu-superior-5").click();
			carregarEmissaoNFE();
			carregarProdutosConta();
		}
	});
	//quantidade-produto

	$(".botao-imprimir-danfe-a1").live('click', function () {
		caminho = caminhoScript+"/modulos/nfe/nfe-imprimir-danfe-a1.php";
		$('#frmDefault').attr("action",caminho);
		$('#frmDefault').attr("target","_blank");
		$("#frmDefault").submit();
		$('#frmDefault').attr("target","");
		$('#frmDefault').attr("action","");
		$('#menu-superior-4').click();
	});

	$('.quantidade-produto').on('blur', function() { ajustarProdutoServico($(".quantidade-produto").index(this)); });
	$('.valor-unitario-produto').on('blur', function() { ajustarProdutoServico($(".valor-unitario-produto").index(this)); });

	$('.cst-icms-produto').on('change', function() { ajustarProdutoServico($(".cst-icms-produto").index(this)); });
	$('.percentual-icms-produto').on('blur', function() { ajustarProdutoServico($(".percentual-icms-produto").index(this)); });
	$('.base-calculo-icms-produto').on('blur', function() { ajustarProdutoServico($(".base-calculo-icms-produto").index(this)); });

	$('.cst-ipi-produto').on('change', function() { ajustarProdutoServico($(".cst-ipi-produto").index(this)); });
	$('.percentual-ipi-produto').on('blur', function() { ajustarProdutoServico($(".percentual-ipi-produto").index(this)); });
	$('.base-calculo-ipi-produto').on('blur', function() { ajustarProdutoServico($(".base-calculo-ipi-produto").index(this)); });

	$('.cst-pis-produto').on('change', function() { ajustarProdutoServico($(".cst-pis-produto").index(this)); });
	$('.percentual-pis-produto').on('blur', function() { ajustarProdutoServico($(".percentual-pis-produto").index(this)); });
	$('.base-calculo-pis-produto').on('blur', function() { ajustarProdutoServico($(".base-calculo-pis-produto").index(this)); });

	$('.cst-cofins-produto').on('change', function() { ajustarProdutoServico($(".cst-cofins-produto").index(this)); });
	$('.percentual-cofins-produto').on('blur', function() { ajustarProdutoServico($(".percentual-cofins-produto").index(this)); });
	$('.base-calculo-cofins-produto').on('blur', function() { ajustarProdutoServico($(".base-calculo-cofins-produto").index(this)); });


	$('.cst-icsqn-servico').on('change', function() { ajustarProdutoServico($(".cst-icsqn-servico").index(this)); });
	$('.percentual-issqn-servico').on('blur', function() { ajustarProdutoServico($(".percentual-issqn-servico").index(this)); });


	/* NF-E A1 CANCELAMENTO*/
	$(".botao-exibir-cancelar-nfe-a1").live('click', function () {
		$(".botao-exibir-cancelar-nfe-a1").hide();
		$(".bloco-cancelar-nfe-a1").show();
	});

	$(".botao-cancelar-nfe-a1").live('click', function () {
		flag = 0;
		$("#justificativa-cancelamento-nfe").val($.trim($("#justificativa-cancelamento-nfe").val()));
		$("#justificativa-cancelamento-nfe").css('background-color', '').css('outline', '');
		if ($('#justificativa-cancelamento-nfe').val().length<15){ $("#justificativa-cancelamento-nfe").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus(); flag=1; alertify.alert('','A justificativa deve possuir no mínimo 15 caracteres'); }
		if (flag==0){
			if(confirm("Você tem certeza que deseja realizar o cancelamento da NF-e?")){
				caminho = caminhoScript+"/modulos/nfe/nfe-cancelar-nfe-a1.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						carregarBlocoNFE();
						carregarProdutosConta();
					}
				});
			}
		}
	});

	/*NFS-E SERVIÇOS*/
	$("#botao-gerar-xml-servicos").live('click', function () {
		alertify.alert('Aviso',"A emissão de notas fiscais de serviço não esta disponivel para uso.\nAs notas de serviço devem ser emitidas diretamente pelo site da prefeitura.");
	});


});

function carregarEmissaoNFE(){
	/*
	caminho = caminhoScript+"/modulos/nfe/nfe-gerar-xml-modelo-a1.php";
	$('#frmDefault').attr('action',caminho);
	$('#frmDefault').submit();
	*/
	caminho = caminhoScript+"/modulos/nfe/nfe-gerar-xml-modelo-a1.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			//alert(retorno);
			//$("html").prepend(retorno);
			carregarBlocoNFE();
		}
	});
}

function carregarBlocoNFE(){
	$("#dados-emissao-nfe").load(caminhoScript+"/modulos/financeiro/financeiro-carregar-emissao-nfe.php?conta-id="+$("#conta-id").val(),function(responseTxt,statusTxt,xhr){
		//$("html").append($.trim(retorno)); // PARA DEPURAR GERACAO DO XML
	});
}


function ajustarTodosProdutosServicos(i){
	$(".produto-id").each(function(){
		i = $(".produto-id").index(this);
		ajustarProdutoServico(i);
	});
}

function ajustarProdutoServico(i){
	quantidade = desformataValor($('.quantidade-produto:eq('+i+')').val());
	valorUnitario = desformataValor($('.valor-unitario-produto:eq('+i+')').val());
	valorTotal = number_format((quantidade * valorUnitario), 2, ',', '.');
	$('.valor-total-produto:eq('+i+')').val(valorTotal);
	// só entra aqui se NF-e Ativa
	// PRODUTOS
	if (($('.tipo-produto:eq('+i+')').val()=="30")||($('.tipo-produto:eq('+i+')').val()=="100")||($('.tipo-produto:eq('+i+')').val()=="175")){
		//ICMS
		var cstICMS = $('.cst-icms-produto:eq('+i+')').val();
		//alert(cstICMS);
		if ((cstICMS=="") || (cstICMS=="101") || (cstICMS=="102") || (cstICMS=="103") || (cstICMS=="300") || (cstICMS=="400")){
			$('#div-aliq-icms-'+i).hide();
			$('#div-bc-icms-'+i).hide();
			$('#div-valor-icms-'+i).hide();
		}
		if (cstICMS=="500"){
			$('#div-aliq-icms-'+i).hide();
			$('#div-bc-icms-'+i).hide();
			$('#div-valor-icms-'+i).hide();
			$('#div-cst-500-'+i).hide();
		}
		if (cstICMS=="00"){
			$('.base-calculo-icms-produto:eq('+i+')').val(valorTotal);
			$('#div-aliq-icms-'+i).show();
			$('#div-bc-icms-'+i).show();
			$('#div-valor-icms-'+i).show();
			percICMS = desformataValor($('.percentual-icms-produto:eq('+i+')').val());
			bcICMS = desformataValor($('.base-calculo-icms-produto:eq('+i+')').val());
			valorICMS = number_format((bcICMS * ((percICMS)/100)), 2, ',', '.');
			$('.valor-icms-produto:eq('+i+')').val(valorICMS);
		}
		//IPI
		if ($('.industrializado:eq('+i+')').val()=="1"){
			var cstIPI = $('.cst-ipi-produto:eq('+i+')').val();
			if ((cstIPI=="") || (cstIPI=="01") || (cstIPI=="02") || (cstIPI=="03") || (cstIPI=="04") || (cstIPI=="51") || (cstIPI=="52") || (cstIPI=="53") || (cstIPI=="54") || (cstIPI=="55")){
				$('#div-aliq-ipi-'+i).hide();
				$('#div-bc-ipi-'+i).hide();
				$('#div-valor-ipi-'+i).hide();
			}
			if ((cstIPI=="00") || (cstIPI=="49") || (cstIPI=="50") || (cstIPI=="99")){
				$('.base-calculo-ipi-produto:eq('+i+')').val(valorTotal);
				$('#div-aliq-ipi-'+i).show();
				$('#div-bc-ipi-'+i).show();
				$('#div-valor-ipi-'+i).show();
				percIPI = desformataValor($('.percentual-ipi-produto:eq('+i+')').val());
				bcIPI = desformataValor($('.base-calculo-ipi-produto:eq('+i+')').val());
				valorIPI = number_format((bcIPI * ((percIPI)/100)), 2, ',', '.');
				$('.valor-ipi-produto:eq('+i+')').val(valorIPI);
			}
		}
	}
	// SERVIÇOS
	if ($('.tipo-produto:eq('+i+')').val()=="31"){
		$('.base-calculo-issqn-servico:eq('+i+')').val(valorTotal);
		percISSQN = desformataValor($('.percentual-issqn-servico:eq('+i+')').val());
		bcISSQN = desformataValor($('.base-calculo-issqn-servico:eq('+i+')').val());
		valorISSQN = number_format((bcISSQN * ((percISSQN)/100)), 2, ',', '.');
		$('.valor-issqn-servico:eq('+i+')').val(valorISSQN);
	}

	//PIS
	var cstPIS = $('.cst-pis-produto:eq('+i+')').val();
	$('.base-calculo-pis-produto:eq('+i+')').val(valorTotal);
	if ((cstPIS=="") || (cstPIS=="04") || (cstPIS=="06") || (cstPIS=="07") || (cstPIS=="08") || (cstPIS=="09")){
		$('#div-aliq-pis-'+i).hide();
		$('#div-bc-pis-'+i).hide();
		$('#div-valor-pis-'+i).hide();
	}
	if ((cstPIS=="01") || (cstPIS=="02")){
		$('#div-aliq-pis-'+i).show();
		$('#div-bc-pis-'+i).show();
		$('#div-valor-pis-'+i).show();
		percPIS = desformataValor($('.percentual-pis-produto:eq('+i+')').val());
		bcPIS = desformataValor($('.base-calculo-pis-produto:eq('+i+')').val());
		valorPIS = number_format((bcPIS * ((percPIS)/100)), 2, ',', '.');
		$('.valor-pis-produto:eq('+i+')').val(valorPIS);
	}
	if ((cstPIS=="49") || (cstPIS=="50") || (cstPIS=="51") || (cstPIS=="52") || (cstPIS=="53") || (cstPIS=="54") || (cstPIS=="55") || (cstPIS=="56") ||
		(cstPIS=="60") || (cstPIS=="61") || (cstPIS=="62") || (cstPIS=="63") || (cstPIS=="64") || (cstPIS=="65") || (cstPIS=="66") || (cstPIS=="67") ||
		(cstPIS=="70") || (cstPIS=="71") || (cstPIS=="72") || (cstPIS=="73") || (cstPIS=="74") || (cstPIS=="75") || (cstPIS=="98") || (cstPIS=="99")){
		$('#div-aliq-pis-'+i).show();
		$('#div-bc-pis-'+i).show();
		$('#div-valor-pis-'+i).show();
		percPIS = desformataValor($('.percentual-pis-produto:eq('+i+')').val());
		bcPIS = desformataValor($('.base-calculo-pis-produto:eq('+i+')').val());
		valorPIS = number_format((bcPIS * ((percPIS)/100)), 2, ',', '.');
		$('.valor-pis-produto:eq('+i+')').val(valorPIS);
	}
	//COFINS
	var cstCOFINS = $('.cst-cofins-produto:eq('+i+')').val();
	$('.base-calculo-cofins-produto:eq('+i+')').val(valorTotal);
	if ((cstCOFINS=="") || (cstCOFINS=="04") || (cstCOFINS=="06") || (cstCOFINS=="07") || (cstCOFINS=="08") || (cstCOFINS=="09")){
		$('#div-aliq-cofins-'+i).hide();
		$('#div-bc-cofins-'+i).hide();
		$('#div-valor-cofins-'+i).hide();
	}
	if ((cstCOFINS=="01") || (cstCOFINS=="02")){
		$('#div-aliq-cofins-'+i).show();
		$('#div-bc-cofins-'+i).show();
		$('#div-valor-cofins-'+i).show();
		percCOFINS = desformataValor($('.percentual-cofins-produto:eq('+i+')').val());
		bcCOFINS = desformataValor($('.base-calculo-cofins-produto:eq('+i+')').val());
		valorCOFINS = number_format((bcCOFINS * ((percCOFINS)/100)), 2, ',', '.');
		$('.valor-cofins-produto:eq('+i+')').val(valorCOFINS);
	}
	if ((cstCOFINS=="49") || (cstCOFINS=="50") || (cstCOFINS=="51") || (cstCOFINS=="52") || (cstCOFINS=="53") || (cstCOFINS=="54") || (cstCOFINS=="55") || (cstCOFINS=="56") ||
		(cstCOFINS=="60") || (cstCOFINS=="61") || (cstCOFINS=="62") || (cstCOFINS=="63") || (cstCOFINS=="64") || (cstCOFINS=="65") || (cstCOFINS=="66") || (cstCOFINS=="67") ||
		(cstCOFINS=="70") || (cstCOFINS=="71") || (cstCOFINS=="72") || (cstCOFINS=="73") || (cstCOFINS=="74") || (cstCOFINS=="75") || (cstCOFINS=="98") || (cstCOFINS=="99")){
		$('#div-aliq-cofins-'+i).show();
		$('#div-bc-cofins-'+i).show();
		$('#div-valor-cofins-'+i).show();
		percCOFINS = desformataValor($('.percentual-cofins-produto:eq('+i+')').val());
		bcCOFINS = desformataValor($('.base-calculo-cofins-produto:eq('+i+')').val());
		valorCOFINS = number_format((bcCOFINS * ((percCOFINS)/100)), 2, ',', '.');
		$('.valor-cofins-produto:eq('+i+')').val(valorCOFINS);
	}
	ajustaQtdeProdutosNF();
}

/* Ajusta quantidade total de itens transportadora */
function ajustaQtdeProdutosNF(){
	qTot = 0;
	$(".quantidade-produto").each(function(){
		qTot += parseFloat(desformataValor($(this).val()));
	});
	$('#qVol').val(qTot);
}


/* Inicio do Relatorio de calendario*/
$(".calendario-mes").live('click', function () {
	data = $(this).attr('attr-mes-ano').split("|");
	$("#mes-calendario").val(data[0]);
	$("#ano-calendario").val(data[1]);
	$("#frmDefault").submit();
});

$("#botao-pesquisar-calendario").live('click', function () {
	$("#frmDefault").submit();
});



/********* Inicio Funções Tabela de comissionamento ***********/

$("#tabela-comissao-seleciona").live('change', function () {
	if($(this).val()=='-1'){
		$("#div-seleciona-tabela").hide();
		$("#div-cadastra-tabela").show();
		$("#div-produtos-cadastrados").hide();
	}else{
		$("#frmDefault").submit();
	}
});
$("#cancela-tabela-comissao").live('click', function () {
		$("#div-seleciona-tabela").show();
		$("#div-cadastra-tabela").hide();
		$("#tabela-comissao-seleciona").val('').trigger('chosen:updated');
});
/*
$("#nome-tabela").live('keyup', function () {
	if($("#nome-tabela").val()!="")
		$("#nome-tabela").css('background-color', '').css('outline', '');
});
*/
$("#cadastra-tabela-comissao").live('click', function () {
	if($("#nome-tabela").val()==""){
		$("#nome-tabela").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		$("#nome-tabela").focus();
	}else{
		$("#frmDefault").submit();
	}
});
$("#botao-excluir-tabela-comissao").live('click', function () {
	$("#acao-tabela").val('e');
	$("#frmDefault").submit();
});
$("#botao-atualiza-tabela-comissao").live('click', function () {
	$("#acao-tabela").val('a');
	$("#frmDefault").submit();
});
$("#replicar-comissao").live('click', function () {
	$(".comissao-lista").val($("#comissao-geral").val());
});

/*
$(".seleciona-produto-tabela").live('click', function () {
	if($(this).attr('checked'))
		$("#produto-selecionado-"+$(this).val()).val($(this).val());
	else
		$("#produto-selecionado-"+$(this).val()).val('');

});
*/
$(".exibir-incluir-faixa").live('click', function () {
	$("#salvar-faixa").val("Incluir");
	$(".botoes-acao").hide();
	$(".bloco-incluir-faixa").show();
	ultimoValor = parseFloat($("#ultimo-valor").val());
	if (ultimoValor!=0){
		ultimoValor = parseFloat(ultimoValor + 0.01);
	}
	$("#valor-inicial").val(number_format(ultimoValor, 2, ',', '.'));
	$("#tabela-comissao-faixa-id").val("");
});
$(".cancelar-incluir-faixa").live('click', function () {
	$(".botoes-acao").show();
	$(".bloco-incluir-faixa").hide();
});

$(".salvar-faixa").live('click', function () {
	if(validarCamposGenerico('.required')){
		valIni = desformataValor($("#valor-inicial").val());
		valFim = desformataValor($("#valor-final").val());
		if (eval(parseFloat(valIni) >= parseFloat(valFim))){
			alertify.alert('','Valor Inicial não pode ser maior que Valor Final');
			return;
		}
		if ($("#salvar-faixa").val()=="Incluir"){
			$("#acao-faixa").val('I');
		}
		if ($("#salvar-faixa").val()=="Atualizar"){
			$("#acao-faixa").val('U');
		}
		//alert($("#acao-faixa").val());
		$("#frmDefault").submit();
	}
});

$(".btn-excluir-comissao").live('click', function () {
	$("#acao-faixa").val('D');
	$("#tabela-comissao-faixa-id").val($(this).attr('faixa-id'));
	$("#frmDefault").submit();
});

$(".btn-editar-comissao").live('click', function () {
	$("#salvar-faixa").val("Atualizar");
	$(".botoes-acao").hide();
	$(".bloco-incluir-faixa").show();
	faixaID = $(this).attr('faixa-id');
	$("#descricao-faixa").val($("#descricao-"+faixaID).html().trim());
	$("#valor-inicial").val($("#valor-inicial-"+faixaID).html().trim());
	$("#valor-final").val($("#valor-final-"+faixaID).html().trim());
	$("#perc-comissao-faixa").val($("#percentual-comissao-"+faixaID).html().trim());
	$("#tabela-comissao-faixa-id").val(faixaID);
	/*
	$("#acao-faixa").val('D');
	$("#frmDefault").submit();
	*/
});



/********* Fim Funções Tabela de comissionamento ***********/


/* Funções de Boleto */
$(".gerar-boleto").live('click', function () {
	tituloID = $(this).attr('titulo-id');
	caminho = caminhoScript+"/modulos/financeiro/financeiro-gerar-boleto.php?titulo-id="+tituloID;
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
		afterClose : function(){
			//$("#menu-superior-4").click();
			carregarTitulos();
		}
	});
});
/* Funções de Boleto */


/* FUNÇÕES DE GERENCIAMENTO DE CONTA */
$(document).ready(function(){
	if (slugPagina=="financeiro-gerenciar-contas"){
		$(".inc-alt-conta").live('click', function () {
			cadastroContaID = $(this).attr('cadastro-conta-id');
			if (cadastroContaID==''){
				$('#cadastro-conta-id').val('');
				$('#acao-conta').val('I');
			}
			else{
				$('#cadastro-conta-id').val(cadastroContaID);
				$('#acao-conta').val('U');
			}
			$("#frmDefault").submit();
		});
		$(".botao-salvar-conta-gestao").live('click', function () {
			if(validarCamposGenerico('.bloco-dados-conta-gestao .required')){
				caminho = caminhoScript+"/modulos/financeiro/financeiro-conta-salvar.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#cadastro-conta-id').val(retorno.trim());
						$('#acao-conta').val('U');
						alertify.alert('Aviso','Dados salvos com sucesso');
						$('#frmDefault').submit();
					}
				});
			}
		});

		$("#tipo-conta").live('change', function () {
			carregarConfigConta();
		});
		carregarConfigConta();
	}
});

function carregarConfigConta(tipoID){
	$('.dados-banco, .dados-banco-boleto').hide();
	tipoID = $("#tipo-conta").val();
	if (tipoID=='170'){
		$('.dados-banco').show();
	}
	if (tipoID=='171'){
		$('.dados-banco, .dados-banco-boleto').show();
	}
	if (TipoID=='173'){
		$('.dados-banco').show();
	}

}



$(".localizar-contas-acao").live('click', function () {
	$('#filtro-cadastro-conta').val($(this).attr('attr-pos'));
	//alert($('#filtro-cadastro-conta').val());
	//caminho = caminhoScript+"/modulos/financeiro/financeiro-contas-bancarias/";
	//$('#frmDefault').attr('action',caminho);
	$('#frmDefault').submit();
});



/*
$(".botao-incluir-conta-bancaria").live('click', function () {
	//alert('funcionalidade em revisão');
	$("#acao-conta").val('I');
	caminho = caminhoScript+"/modulos/financeiro/financeiro-contas-bancarias/";
	$('#frmDefault').attr('action',caminho);
	$('#frmDefault').submit();
});
*/

$("#dividir-valores").live('click', function () {
	$('#numero-opcoes').val(parseInt($('#numero-opcoes').val()) + 1);
	caminho = caminhoScript+"/modulos/financeiro/financeiro-bloco-contabil.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#bloco-contabil").html(retorno);
		}
	});
});

$(".btn-excluir-contabil").live('click', function () {
	$('#numero-opcoes').val(parseInt($('#numero-opcoes').val()) - 1);
	$('.bloco-contabil-'+$(this).attr('posicao')).remove();
	if ($('#numero-opcoes').val()==1){
		caminho = caminhoScript+"/modulos/financeiro/financeiro-bloco-contabil.php?acao=D";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#bloco-contabil").html(retorno);
			}
		});
	}
});
$(document).ready(function(){
	if (slugPagina=='financeiro-lancamento'){
		calculosContabil();
	}
	$(".totalizar-contabil, #valor-total").live('blur change keypress click keyup', function () {
		calculosContabil();
	});
	function calculosContabil(){
		var valorAux = 0;
		var valorContabilTotal = 0;
		if ($(".totalizar-contabil").length>1){
			$(".totalizar-contabil").each(function(){
				valorAux = desformataValor($(this).val());
				if (valorAux>0){
					valorContabilTotal = parseFloat(valorAux) + parseFloat(valorContabilTotal);
				}else{
					$(this).addClass('required');
				}
			});
			$("#valor-total-contabil").val(number_format(valorContabilTotal,'2',',','.'));
		}
		valorTotal = desformataValor($('#valor-total').val());
		if (valorContabilTotal != valorTotal){
			diferenca = (parseFloat(valorTotal) -  parseFloat(valorContabilTotal));
			if (diferenca<0)
				diferenca = (parseFloat(diferenca) * -1);
			$('.mensagem-contabil').html("<b style='color:red'>O somat&oacute;rio dos valores divergem <br> diferen&ccedil;a de " + number_format(diferenca,'2',',','.') + " </b>");
		}
		else{
			$('.mensagem-contabil').html("");
		}
	}

	if (slugPagina=="financeiro-relatorio-receitas-despesas"){
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-contas").click(); }});
		$(".menu-relatorio-min").live('click', function () {
			$("#filtro-relatorio").val($(this).attr('id'));
			$("#frmDefault").submit();
		});
	}

	$(".btn-acao-array").live('click', function () {
		if(validarCamposGenerico('.required')){
			caminho = caminhoScript+"/modulos/financeiro/financeiro-salvar-indice.php";
			$('#frmDefault').attr('action', caminho);
			$('#frmDefault').submit();
		}
	});

	$(".link-orcamento").live('click', function () {
		workflowIDAnt = $("#workflow-id").val();
		$("#workflow-id").val($(this).attr('workflow-id'));
		$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
		$("#frmDefault").submit();
		$("#workflow-id").val(workflowIDAnt);
	});

});
