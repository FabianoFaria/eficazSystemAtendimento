var valorTotalAnt;
$(document).ready(function(){
	var paginaAtual = window.location.href.replace("#","");
	var slugPagina = $("#slug-pagina").val();

	/********* Inicio Funções localiza Contas e Titulos***********/
	if (slugPagina=="leilao-localiza"){
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-leiloes").click(); }});
		$("#botao-pesquisar-leiloes").live('click', function () {
			$("#frmDefault").submit();
		});


		$(".leilao-inc-alt").live('click', function () {
			$("#leilao-id").val($(this).attr("leilao-id"));
			$("#frmDefault").attr('action', caminhoScript+'/leilao/leilao-cadastro');
			$("#frmDefault").submit();
		});
	}


	/********* Inicio Funções Localiza Chamado ***********/
	if ($("#slug-pagina").val()=="leilao-configuracoes-gerais"){
		$(".botao-salva-configuracoes-gerais").live('click', function () {
			$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-configuracoes-gerais-salvar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					alert("Configuração atualizada com sucesso");
					//$("#div-retorno").html(retorno);
					//$("#frmDefault").submit();
				}
			});
		});
	}
	if ($("#slug-pagina").val()=="leilao-cadastro"){
		$(".botao-salvar-leilao").live('click', function () {
			if (validarCamposGenerico('#div-dados-leilao .required')){
				$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-cadastro-salvar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//$('#div-retorno').html(retorno);
						$('#leilao-id').val(retorno);
						$("#frmDefault").submit();
					}
				});
			}
		});

		$(".leilao-inc-alt-lote").live('click', function () {
			$(".leilao-inc-alt-lote").hide();
			$(".leilao-del-lote").hide();
			leilaoLoteID = $(this).attr("leilao-lote-id");
			$("#leilao-lote-id").val(leilaoLoteID);
			$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-lote-incluir-editar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					if (leilaoLoteID=="")
						$("#leilao-incluir-lote").html(retorno).show();
					else{
						$(".leilao-lote-"+leilaoLoteID).hide();
						$(".leilao-editar-lote-"+leilaoLoteID).html(retorno).show();
					}
				}
			});
		});
		$(".botao-cancelar-lote").live('click', function () {
			leilaoLoteID = $(this).attr("leilao-lote-id");
			if (leilaoLoteID==""){
				$('#leilao-incluir-lote').html("").hide();
				$(".leilao-inc-alt-lote").show();
			}
			else{
				$(".leilao-editar-lote-"+leilaoLoteID).hide();
				$(".leilao-lote-"+leilaoLoteID+", .leilao-inc-alt-lote, .leilao-del-lote").show();
			}
		});
		$(".botao-salvar-lote").live('click', function () {
			if (validarCamposGenerico('#div-dados-leilao-lotes .required')){
				$("#leilao-lote-id").val($(this).attr("leilao-lote-id"));
				$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-lote-salvar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//$('#div-retorno').html(retorno).show();
						//$('#leilao-incluir-lote').html(retorno).show();
						$('#leilao-incluir-lote').html("").hide();
						carregarProdutosLeilao();
					}
				});
			}
		});

		$(".leilao-del-lote").live('click', function () {
			if (confirm("Tem certeza que deseja excluir registro?")){
				$("#leilao-lote-id").val($(this).attr("leilao-lote-id"));
				$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-lote-excluir.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						carregarProdutosLeilao();
					}
				});
			}
		});


		function carregarProdutosLeilao(){
			$("#leilao-lotes-cadastrados").html("<p align='center' style='width:100%'>Aguarde carregando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>").show();
			$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-lotes-carregar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#leilao-lotes-cadastrados').html(retorno).show();
				}
			});
		}

		// div dos produtos listados leilao-lote-cadastrados
	}


	/********* Inicio Funções localiza Contas e Titulos***********/
	if (slugPagina=="leilao-plano-localiza"){
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-planos").click(); }});
		$("#botao-pesquisar-planos").live('click', function () {
			$("#frmDefault").submit();
		});
		$(".plano-inc-alt").live('click', function () {
			$("#plano-id").val($(this).attr("plano-id"));
			$("#frmDefault").attr('action', caminhoScript+'/leilao/leilao-plano-cadastro');
			$("#frmDefault").submit();
		});
	}

	if (slugPagina=="leilao-plano-cadastro"){
		$(".botao-salvar-plano").live('click', function () {
			if (validarCamposGenerico('#div-dados-plano .required')){
				$(".botao-salvar-plano").hide();
				$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-plano-salvar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//$('#div-retorno').html(retorno);
						$('#plano-id').val(retorno);
						$("#frmDefault").submit();
					}
				});
			}
		});


		$(".pacote-inc-alt").live('click', function () {
			$(".pacote-inc-alt, .pacote-del").hide();
			pacoteLoteID = $(this).attr("pacote-id");
			$("#pacote-id").val(pacoteLoteID);
			$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-pacote-incluir-editar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					if (pacoteLoteID=="")
						$("#pacote-incluir").html(retorno).show();
					else{
						$(".pacote-"+pacoteLoteID).hide();
						$(".pacote-editar-"+pacoteLoteID).html(retorno).show();
					}
				}
			});
		});
		$(".botao-cancelar-pacote").live('click', function () {
			pacoteID = $(this).attr("pacote-id");
			if (pacoteID==""){
				$('#pacote-incluir').html("").hide();
				$(".pacote-inc-alt").show();
			}
			else{
				$(".pacote-editar-"+pacoteID).hide();
				$(".pacote-"+pacoteID+", .pacote-inc-alt, .pacote-del").show();
			}
		});

		$(".botao-salvar-pacote").live('click', function () {
			if (validarCamposGenerico('#div-dados-pacotes .required')){
				$("#pacote-id").val($(this).attr("pacote-id"));
				$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-pacote-salvar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//$('#div-retorno').html(retorno).show();
						//$('#leilao-incluir-lote').html(retorno).show();
						$(".pacote-inc-alt").show();
						$('#pacote-incluir').html("").hide();
						carregarPacotes();
					}
				});
			}
		});

		function carregarPacotes(){
			$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-pacotes-carregar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#pacotes-cadastrados').html(retorno).show();
				}
			});
		}

		$(".pacote-del").live('click', function () {
			if (confirm("Tem certeza que deseja excluir o registro?")){
				$("#pacote-id").val($(this).attr("pacote-id"));
				$.ajax({type: "POST",url: caminhoScript+"/modulos/leilao/leilao-pacote-excluir.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						carregarPacotes();
					}
				});
			}
		});

	}
});

