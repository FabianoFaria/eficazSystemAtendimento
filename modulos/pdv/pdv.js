$(document).ready(function(){
	if (($('#slug-pagina').val()=='pdv-inicio') && (!$('#tela-login').length)){
		$("#menu-container").hide();
		$("#titulo-principal").hide();
	}

	if ($('#slug-pagina').val()=='pdv-inicio'){

		/* SE O PAGAMENTO ESTIVER EM ANDAMENTO */
		if($('#andamento').val()=='pagamento'){
			mostraFuncao(115, 3);
		}
		else if($('#andamento').val()=='finalizado'){

		}
		/**/

		$("#pdv-container-principal").css('height', $(document).height()-85)
		$("body").css('background-color', '#f1f1f1')
		$("#pdv-codigo").focus()

		$("#div-produto-centro").scrollTop(10000);
		//$("#numero-caixa").focus();

		$(document).live( "keypress", function( event ) {
			//console.log("-->" + event.which);
			if(event.which==42){
				$("#fator").val('*');
				$("#pdv-quantidade").attr('readonly', false);
				$("#pdv-quantidade").val('');
				$("#pdv-quantidade").focus();
				return false;
			}
			if(event.which==13){
				if($("#fator").val()==''){
					if($("#pdv-quantidade").val()=='')
						$("#pdv-quantidade").val(1);
					localizaDadosProduto();
				}else{
					if($("#fator").val()=='-'){
						$("#frmDefault").submit();
					}
					else if($("#fator").val()=='%'){
						$("#produto-superior-busca").hide();
						$("#produto-superior-nome").show();
						$("#fator").val('');
						$("#pdv-quantidade").attr('readonly', true);
						$("#pdv-codigo").focus();
					}
					else if($("#fator").val()=='='){

					}
					else if($("#fator").val()=='#'){
						//$("#frmDefault").submit();
						/* CONSULTA DE PRECO */
						consultarDadosProduto();
						$("#fator").val('');
						$(".f10").click();
						//return false;
					}
					else if($("#fator").val()=='--'){
						valorDesconto = parseFloat(desformataValor($("#input-function-desconto").val()));
						if(valorDesconto==0){
							$("#input-function-desconto").focus();
						}
						else{
							$("#frmDefault").submit();
						}
					}
					else{
						$("#fator").val('');
						$("#pdv-quantidade").attr('readonly', true);
						$("#pdv-codigo").focus();
					}
				}
			}
			if($("#pdv-codigo").val().trim()!=''){
				//alertify.alert('???');
			}else{
				if(event.which!=42){
					if($("#fator").val()=='')
						$("#pdv-codigo").focus();
				}
			}
		});

		$(".sel-pos-pdv").live("click", function( event ) {
			atendenteID = $(this).attr('atendente-id');
			if ((atendenteID=='') || (atendenteID == userID)){
				$("#numero-caixa").val($(this).attr('pos'));
				$("#frmDefault").attr('action', caminhoScript+'/pdv/pdv-inicio');
				$("#frmDefault").submit();
			}
			else{
				alertify.alert('Aviso', "<center><b>Não é possivel ocupar um caixa registrado por outro atendente</b></center>");
			}
		});
		/*
		$("#numero-caixa").live( "change", function( event ) {
			$("#frmDefault").submit();
		});
		*/
		function consultarDadosProduto(){
			if($("#input-function-codigo").val().trim() != ""){
				caminho = caminhoScript+"/modulos/pdv/localizar-produto.php?codigo="+$("#input-function-codigo").val();
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						if(retorno.trim()==''){
							alertify.alert('Aviso', "<center><b>Produto não localizado</b></center>");
						}
						else{
							dados = retorno.split("|");
							alertify.alert('Dados Produto', "<center><b>" + dados[1] + " R$ " + dados[3] + "</b></center>");
						}
					}
				});
			}
			else{
				$("#input-function-codigo").focus();
			}
		}



		function localizaDadosProduto(){
			if($("#pdv-codigo").val().trim() != ""){
				caminho = caminhoScript+"/modulos/pdv/localizar-produto.php?codigo="+$("#pdv-codigo").val();
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						dados = retorno.split("|")
						if(dados[1] == undefined) {
							$("#div-produto-superior-centro").css("background","url("+caminhoScript+"/images/geral/imagem-produto.jpg) no-repeat center center");
							$("#produto-superior-nome").html("<span style='color:red'>Produto não localizado<span>");
							$("#pdv-codigo").val('');
						}else{
							$("#produto-nome").val(dados[1]);
							$("#produto-imagem").val(dados[5]);
							$("#pdv-valor-unitario").val(dados[3]);
							$("#frmDefault").submit();
							//$("#produto-superior-nome").html(dados[1]);
							//$("#pdv-valor-total").val(dados[4]);
							//$("#div-produto-superior-centro").css("background","url("+caminhoScript+"/uploads/"+dados[5]+") no-repeat center center");
						}
					}
				});
			}
		}
		$(".f1").live('click', function(e) {
			mostraFuncao(112, 4);
		});
		$(".f2").live('click', function(e) {
			mostraFuncao(113, 1);
		});
		$(".f3").live('click', function(e) {
			mostraFuncao(114, 4);
		});
		$(".f4").live('click', function(e) {
			mostraFuncao(115, 3);
		});
		$(".f5").live('click', function(e) {
			mostraFuncao(116, 2);
		});
		$(".f6").live('click', function(e) {
			mostraFuncao(117, 1);
		});
		$(".f7").live('click', function(e) {
			mostraFuncao(118, 5);
		});
		$(".f8").live('click', function(e) {
			$(".numero-pdv").click();
		});
		$(".f10").live('click', function(e) {
			mostraFuncao('', 0);
		});
		if($('#andamento').val()!='finalizado'){
			//alert($('#andamento').val());
			$(document).bind('keydown keyup', function(e) {
				//console.log("->"+e.which);
				if(e.which === 112) {
					mostraFuncao(e.which, 4);
					return false;
				}
				if(e.which === 113) {
					mostraFuncao(e.which, 1);
					return false;
				}
				if(e.which === 114) {
					mostraFuncao(e.which, 4);
					return false;
				}
				if(e.which === 115) {
				   mostraFuncao(e.which, 3);
				   return false;
				}
				if(e.which === 116) {
				   mostraFuncao(e.which, 2);
				   return false;
				}
				if(e.which === 117) {
				   mostraFuncao(e.which, 1);
				   return false;
				}
				if(e.which === 118) {
				   mostraFuncao(e.which, 5);
				   return false;
				}
				if(e.which === 119) {
					$(".numero-pdv").click();
				}
				if((e.which === 121) || (e.which === 27)) {
					mostraFuncao('',0);
					return false;
				}
				if(e.which === 82 && e.ctrlKey) {
				   return false;
				}
				if($("#fator").val()=="="){
					if((e.which === 97)||(e.which === 49)){
						$(".pdv-confirma-compra").click();
					}
					if((e.which === 98)||(e.which === 50)){
						mostraFuncao('',0);
						return false;
					}
				}
			});
		}
		function mostraFuncao(funcao, tipo){

			if ((funcao==113) || (funcao==115) || (funcao==116) || (funcao==117)){
				if($('#quantidade-produtos-pdv').val()==0){
					alertify.alert('Aviso','Ação não permitida, não há produtos inclusos na venda!');
					$('#pdv-codigo').focus();
					return false;
				}
			}
			//console.log(tipo);

			$("#produto-superior-busca").hide();
			$("#div-executa-funcoes").show();
			$("#produto-superior-nome").show();
			$("#input-function-codigo").val('');

			//alert(tipo);
			if(tipo==1){
				// CANCELAR ITEM (F2) e CANCELAR VENDA (F6)
				$("#div-produto-centro-finaliza").hide();
				$("#div-produto-centro-produtos").show();
				$("#div-produto-centro-busca").hide('');
				$("#div-produto-esquerdo-funcao-superior").css('height','100px');
				$("#div-produto-esquerdo-funcao").show();
				$("#div-produto-esquerdo-container").hide();
				$("#fator").val('-');
				preencheDetalhes(funcao);
				$("#input-function-evento").val(funcao);
				$("#input-function-codigo").focus();
				$("#produto-esquerdo-funcao-inferior-titulo").hide();
				$("#produto-esquerdo-funcao-inferior-campos").hide();
			}else if(tipo==2){
				// DESCONTO DE VALOR (F5)
				$("#div-produto-centro-finaliza").hide();
				$("#div-produto-centro-produtos").show();
				$("#div-produto-centro-busca").hide();
				$("#div-produto-esquerdo-funcao-superior").css('height','170px');
				$("#div-produto-esquerdo-funcao").show();
				$("#div-produto-esquerdo-container").hide();
				$("#fator").val('--');
				preencheDetalhes(funcao);
				$("#input-function-evento").val(funcao);
				$("#input-function-codigo").focus();
				$("#produto-esquerdo-funcao-inferior-titulo").show();
				$("#produto-esquerdo-funcao-inferior-campos").show();
			}else if(tipo==3){
				// FINALIZAR VENDA (F4)
				$("#div-produto-direito-superior, #div-produto-centro-finaliza, #div-produto-centro-busca, #div-produto-esquerdo-container").hide();
				$("#div-produto-centro-produtos, #div-produto-esquerdo-funcao").show();
				$("#div-produto-esquerdo-funcao-superior").css('height','145px');
				$("#fator").val('-');
				preencheDetalhes(funcao);
				$("#input-function-evento").val(funcao);
				$("#input-function-codigo").focus();
				$("#produto-esquerdo-funcao-inferior-campos, #produto-esquerdo-funcao-inferior-titulo").hide();
				abreOpcoesSelect($('#pdv-formas-pagamento'));
				$("#fator").val('@');
				$("#pdv-formas-pagamento").chosen().trigger("chosen:open");

			}else if(tipo==4){
				// LOCALIZAR ITEM (F4)
				$("#div-produto-centro-finaliza").hide();
				$("#div-produto-esquerdo-funcao-superior").css('height','47px');
				$("#div-produto-esquerdo-funcao").show();
				$("#div-produto-esquerdo-container").hide();
				$("#div-executa-funcoes").hide();
				$("#div-produto-superior-centro").css("background","");
				$("#fator").val('%');
				preencheDetalhes(funcao);
				$("#input-function-evento").val(funcao);
				$("#produto-esquerdo-funcao-inferior-titulo").hide();
				$("#produto-esquerdo-funcao-inferior-campos").hide();
			}else if(tipo==5){
				// CONSULTAR PREÇO (F7)
				$("#div-produto-centro-finaliza").hide();
				$("#div-produto-centro-produtos").show();
				$("#div-produto-centro-busca").hide();
				$("#div-produto-esquerdo-funcao-superior").css('height','100px');
				$("#div-produto-esquerdo-funcao").show();
				$("#div-produto-esquerdo-container").hide();
				$("#fator").val('#');
				preencheDetalhes(funcao);
				$("#input-function-evento").val(funcao);
				$("#input-function-codigo").focus();
				$("#produto-esquerdo-funcao-inferior-titulo").hide();
				$("#produto-esquerdo-funcao-inferior-campos").hide();

				//$("#div-produto-centro-finaliza").show();
				//$("#produto-superior-nome").html("<span style='color:red'>Consultar Pre&ccedil;o</p>");
				//$("#div-produto-superior-centro").css("background","");
			}else{
				// CANCELAR F10 E ESQ
				$("input").attr("readonly", false);
				$("#div-produto-centro-finaliza").hide();
				$("#div-produto-centro-produtos").show();
				$("#div-produto-centro-busca").hide();
				$("#div-produto-esquerdo-funcao").hide();
				$("#div-produto-esquerdo-container").show();
				$("#fator").val('');
				$("#pdv-formas-pagamento").val('');
				$("#pdv-condicao-pagamento").val('');
				$("#input-function-evento").val();
				$("#input-function-codigo").focus();
				$("#produto-esquerdo-funcao-inferior-titulo").hide();
				$("#produto-esquerdo-funcao-inferior-campos").hide();
				$("#pdv-codigo").focus();
			}
		}
		function preencheDetalhes(funcao){
			$("#input-function-codigo").show();
			$("#div-forma-pagamento").hide();
			$("#produto-superior-busca").hide();
			$("#produto-superior-nome").show();
			if(funcao==112){
				$("#produto-esquerdo-funcao-superior-titulo").html("Localizar Cliente");
				$("#produto-superior-nome").hide();
				$("#div-produto-centro-produtos").hide();
				$("#produto-superior-busca").show();
				$("#div-produto-centro-busca").show();
				$("#campo-busca-produto").focus().val('').attr('acao-busca','cliente');
				$("#div-produto-centro-busca").html('');
				$("#div-produto-centro-finaliza").hide();
			}
			if(funcao==113){
				$("#produto-esquerdo-funcao-superior-titulo").html("Cancelar Item <br><span style='font-size:11px;'>(Informe o Item a ser cancelado)</span>");
			}
			if(funcao==114){
				$("#produto-esquerdo-funcao-superior-titulo").html("Localizar Produto");
				$("#produto-superior-nome").hide();
				$("#div-produto-centro-produtos").hide();
				$("#produto-superior-busca").show();
				$("#div-produto-centro-busca").show();
				$("#campo-busca-produto").focus().val('').attr('acao-busca','produto');
				$("#div-produto-centro-busca").html('');
				$("#div-produto-centro-finaliza").hide();
			}
			if(funcao==115){
				$("#produto-esquerdo-funcao-superior-titulo").html('Finalizar Venda');
				formasPagamento();
			}
			if(funcao==116){
				$("#produto-esquerdo-funcao-superior-titulo").html("Aplicar desconto no Item");
			}
			if(funcao==117){
				$("#produto-esquerdo-funcao-superior-titulo").html("Cancelar Venda <br><span style='font-size:11px;'>(Informe Código de Cancelamento)</span>");
			}
			if(funcao==118){
				$("#produto-esquerdo-funcao-superior-titulo").html("Consultar Preço <br><span style='font-size:11px;'>(Informe Código do Produto)</span>");
			}
		}
		function formasPagamento(){
			$("#input-function-codigo").hide();
			$("#div-forma-pagamento").show();
			$("#div-executa-funcoes").hide('');
		}
		$("#campo-busca-produto").live('keyup', function (e) {
			if ($(this).attr('acao-busca')=='produto'){
				caminho = caminhoScript+"/modulos/pdv/pdv-localizar-produto-busca.php";
			}
			else{
				caminho = caminhoScript+"/modulos/pdv/pdv-localizar-cliente-busca.php";
			}
			if($(this).val().length>=3){
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$("#div-produto-centro-busca").html(retorno);
					}
				});
			}else{
				$("#div-produto-centro-busca").html('');
			}
		});

		$(".tabela-busca-produto").live('click', function (e) {
			$("#pdv-produto-variacao-id").val($(this).attr('attr-id'));
			$("#fator").val('');
			$("#frmDefault").submit();
		});

		$(".numero-pdv").live('click', function (e) {
			alertify.confirm('Aviso', "Tem certeza que deseja sair do caixa?",
				function(){
					$("#frmDefault").attr('action',caminhoScript+"/modulos/pdv/pdv-trocar-numero.php");
					$("#frmDefault").submit();
				},
				function(){
				}).set('labels', {ok:'Sim', cancel:'Cancelar'}
			);
		});
		$("#pdv-formas-pagamento").live('change', function (e) {
			$("#pdv-condicao-pagamento").val('');
			$.ajax({type: "POST",url: caminhoScript+"/modulos/pdv/selecionaCondicoesPagamento.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$(".pdv-finaliza-opcoes").remove();
					$("#pdv-condicao-pagamento").append(retorno);
					$(".pdv-finaliza-parcelado").show();
					abreOpcoesSelect($('#pdv-condicao-pagamento'));
				}
			});
		});

		/*
		$("#pdv-condicao-pagamento").live('change', function (e) {
			if($("#pdv-condicao-pagamento").val()=="") return false;;
			$("#fator").val('=');
			$("#div-produto-centro-finaliza").show();
			$("#div-produto-centro-busca").hide();
			$("#div-produto-centro-produtos").hide();
			$.ajax({type: "POST",url: caminhoScript+"/modulos/pdv/pdv-confirma-fechamento.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#div-produto-centro-finaliza").html(retorno);
					$("input").attr("readonly", true);
					$(".pdv-confirma-compra").focus();
				}
			});
		});
		*/

		$(".pdv-confirma-compra").live('click', function (e) {
			$.ajax({type: "POST",url: caminhoScript+"/modulos/pdv/pdv-finaliza-venda.php?situacao-id=98", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//console.log(retorno);
					$("#cadastro-id").val('');
					$("#frmDefault").submit();
				}
			});
		});
		$(".pdv-cancela-compra").live('click', function (e) {
			$.ajax({type: "POST",url: caminhoScript+"/modulos/pdv/pdv-finaliza-venda.php?situacao-id=99", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#frmDefault").submit();
				}
			});
		});

		$(".selecionar-cliente").live('click', function (e) {
			cadastroID = $(this).attr('attr-id');
			$('#cadastro-id').val(cadastroID);
			$('#frmDefault').submit();
		});

		$('.incluir-cliente').live('click', function () {
			dados = $('#frmDefault').serialize();
			caminho = caminhoScript+"/cadastros/cadastro-dados?tipo-fluxo=direto";
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				beforeClose: function() {
					cadastroID = $('.fancybox-iframe').contents().find('#cadastro-id').val();
					$('#cadastro-id').val(cadastroID);
					$('#frmDefault').submit();
				}
			});
		});

		/*
		$("#pdv-formas-pagamento").live('change', function () {
			$('#input-valor-finaliza').focus().select();
		});
		*/

		$("#pdv_formas_pagamento_chosen").live('keyup', function (e) {
			if(e.which === 13) {
				if($("#pdv-formas-pagamento").val()=='92')
					$("#pdv-condicao-pagamento").chosen().trigger("chosen:open");
				else
					$('#input-valor-finaliza').focus().select();
			}
		});
		$("#pdv_condicao_pagamento_chosen").live('keyup', function (e) {
			if(e.which === 13) {
				$('#input-valor-finaliza').focus().select();
			}
		});
		$("#input-valor-finaliza").live('keyup', function (e) {
			if(e.which === 13) {
				$("#pdv-formas-pagamento, #input-valor-finaliza").addClass('required');
				if(validarCamposGenerico("#pdv-formas-pagamento, #input-valor-finaliza")){
					$("#input-function-evento").val('115');
					$("#frmDefault").submit();
				}
			}
		});

		$("#pdv-formas-pagamento").live('change', function () {
			formaPagamento = $(this).val();
			/*CREDITO*/
			if (formaPagamento=='92'){
				$("#div-produto-esquerdo-funcao-superior").css('height','220px');
				$(".forma-pagamento").css('width', "75%");
				$(".parcelamento-credito").css('width', "20%").show();
			}
			/*OUTROS*/
			else{
				$("#div-produto-esquerdo-funcao-superior").css('height','145px');
				$(".forma-pagamento").css('width', "100%");
				$(".parcelamento-credito").hide();
				$('#input-valor-finaliza').focus().select();
			}
		});
	}


	/***********************************************/

	if (slugPagina=="pdv-fechamento-pdv"){
		//$("#data-inicio").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $(".botao-pesquisar-pdv").click(); }});

		$(".botao-pesquisar-pdv").live('click', function () {
			$("#frmDefault").attr("action",paginaAtual);
			$("#frmDefault").submit();
		});
		$(".faturar-vendas-pdv").live('click', function () {
			if ($('.pdv-fat').is(":checked")){
				$('.pdv-fat').attr('checked',false);
			}
			else{
				$('.pdv-fat').attr('checked',true);
			}
			exibirEscondeBtnFaturar();
		});
		$(".pdv-fat").live('click', function () {
			exibirEscondeBtnFaturar();
		});

		function exibirEscondeBtnFaturar(){
			if ($('.pdv-fat').is(":checked"))
				$('.executar-faturamento').show();
			else
				$('.executar-faturamento').hide();
		}

		$(".executar-faturamento").live('click', function () {
			campos = $("form").serialize();
			$(".div-aguarde").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>").show();
			caminho = caminhoScript+"/modulos/pdv/pdv-financeiro-faturar.php";
			$.ajax({type: "POST",url: caminho, data: campos, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					alertify.alert('Dados Produto', "<center><b>" + campos + "</b></center>" + retorno);
					//$('#frmDefault').submit();
				}
			});

		});

	}


});