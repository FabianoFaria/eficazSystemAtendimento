
/********* Inicio Funções Tela de localizar envios ***********/

$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");

	if ($("#slug-pagina").val()=="envios-localizar"){
		$(".workflow-envio").live('click', function () {
			$("#localiza-workflow-id").val($(this).attr("workflow-id"));
			$("#workflow-id").val($(this).attr("workflow-id"));
			$("#frmDefault").attr("action",caminhoScript+"/envios/envios-cadastro");
			$("#frmDefault").submit();
		});

		$("#botao-localizar-envio").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/envios/"+$("#slug-pagina").val());
			$("#frmDefault").submit();
		});

		//$("#localiza-tipo-envio").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-localizar-envio").click(); }});
	}

	// ********** Tela de cadastro de envios ************//
	if ($("#slug-pagina").val()=="envios-cadastro"){
		//exibirCamposSituacao();
		if(($("#workflow-id").val()!="") && ($("#localiza-workflow-id").val()!="")){
			carregarChamado('envio-id-chamado');
		}
		/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
		$("#menu-superior-3").live('click', function () {
			carregarDocumentos($("#workflow-id").val(),'envios');
		});

		$("#origem-envio").live('change', function () {
			verificaOrigem();
		});

		$("#envio-codigo-chamado, #envio-id-chamado").live('blur', function () {
			campoid = $(this).attr("id");
			carregarChamado(campoid);
		});

		$("#select-situacao-follow").live('change', function () {
			exibirCamposSituacao();
		});

		$(".botao-cadastra-envio").live('click', function () {
			salvarEnvio();
		});

		$("#botao-enviar-faturamento").live('click', function () {
			$("#enviar-faturamento").val("1");
			salvarEnvio();
		});

		/* INICO PRODUTOS*/
		$(".btn-novo-produto").live('click', function () {
			incluirEditarProduto("");
		});


		$(".botao-cancelar-produto").live('click', function () {
			$('#div-produtos-incluir-editar').hide();
			carregarProdutos();
		});

		$("#botao-salvar-produto").live('click', function () {
			flag = 0;
			$("#select_produtos_chosen a").css('background-color', '').css('outline', '');
			$("#texto-localizar-produtos").css('background-color', '').css('outline', '');
			$("#quantidade-produtos").css('background-color', '').css('outline', '');
			$("#valor-custo-unitario").css('background-color', '').css('outline', '');
			$(".envios-retorna-escolhe").css('background-color', '').css('outline', '');

			if ($("#select-produtos").val()==""){ $("#select_produtos_chosen a").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); $("#texto-localizar-produtos").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); flag=1;}
			if ($("#quantidade-produtos").val()==""){ $("#quantidade-produtos").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD'); flag=1;}
			if (($("#radio-retorna:checked").val()!="0") && ($("#radio-retorna:checked").val()!="1")){
				$(".envios-retorna-escolhe").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				flag=1;
			}
			if (flag==0){
				caminho = caminhoScript+"/modulos/envios/envios-workflow-produto-salvar.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$("#div-produtos-incluir-editar").html("");
						//$("#div-produtos-incluir-editar").html(retorno);
						carregarProdutos();
					}
				});
			}
		});

		$("#select-produtos").live('change', function () {
			$("#altura-variacao").val($(this).find(":selected").attr("altura"));
			$("#largura-variacao").val($(this).find(":selected").attr("largura"));
			$("#comprimento-variacao").val($(this).find(":selected").attr("comprimento"));
			$("#peso-variacao").val($(this).find(":selected").attr("peso"));
		});


		$('.btn-excluir-produto-workflow').live('click', function () {
			workflowProdutoID = $(this).attr('workflow-produto-id');
			var confirma=confirm("Tem certeza que deseja excluir?");
			if (confirma==true){
				retorno = $.ajax(caminhoScript+"/modulos/envios/envios-workflow-produto-excluir.php?workflow-produto-id="+workflowProdutoID)
				.done(function(){
					carregarProdutos();
				});
			}
		});
		$('.btn-editar-produto-workflow').live('click', function () {
			workflowProdutoID = $(this).attr('workflow-produto-id');
			incluirEditarProduto(workflowProdutoID);
		});

		/* FIM PRODUTOS */

		/* LINK CHAMADOS */
		$(".workflow-chamado").live('click', function () {
			$("#workflow-id").val($(this).attr("workflow-id"));
			$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-cadastro-chamado");
			$("#frmDefault").submit();
		});
		/*
		$(".radio-retorna").live('click', function () {
			if($("#radio-retorna:checked").val()=="1"){
				$(".envios-retorna").show();
			}
			else{
				$(".envios-retorna").hide();
			}

		});
		*/
		$(".atalho-estoque").live('click', function () {
			$("#workflow-id").val($(this).attr("workflow-id"));
			$("#frmDefault").attr("action",caminhoScript+"/produtos/produtos-movimentacao-material");
			$("#frmDefault").submit();
		});
	}


	/********* Inicio Funções Relatorio Geral ***********/
	if ($("#slug-pagina").val()=="envios-relatorio-geral"){
		$(".workflow-envio").live('click', function () {
			$("#localiza-workflow-id").val($(this).attr("workflow-id"));
			$("#workflow-id").val($(this).attr("workflow-id"));
			$("#frmDefault").attr("action",caminhoScript+"/envios/envios-cadastro");
			$("#frmDefault").submit();
		});
		$("#botao-localizar-envio").live('click', function () {
			$("#frmDefault").attr("action",paginaAtual);
			$("#frmDefault").attr("target","");
			$("#frmDefault").submit();
		});
		$("#botao-salvar-excel").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico-session.php");
			$("#frmDefault").attr("target","");
			$("#frmDefault").submit();
		});
		$("#botao-imprimir").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-impressao-generico-session.php");
			$("#frmDefault").attr("target","_blank");
			$("#frmDefault").submit();
		});
	}

	$("#forma-envio").live('change', function () {
		carregarProdutos();
	});

	$(".tipo-envio-correio").live('click', function () {
		carregarProdutos();
	});
	$("#tipo-envio").live('change', function () {
		$("#select-situacao-follow option[value='56']").attr('disabled',false).trigger('chosen:updated');
		$("#select-situacao-follow option[value='57']").attr('disabled',false).trigger('chosen:updated');
		if ($("#tipo-envio").val()=="129")
			$("#select-situacao-follow option[value='57']").attr('disabled',true).trigger('chosen:updated');
		if ($("#tipo-envio").val()=="130")
			$("#select-situacao-follow option[value='56']").attr('disabled',true).trigger('chosen:updated');
	});
});

function carregarProdutos(){
	$(".btn-novo-produto").show();
	$('#div-produtos').show();
	if ($("#forma-envio").val()==54)
		$("#formas-envio-correio").show();
	else
		$("#formas-envio-correio").hide();

	caminho = caminhoScript+"/modulos/envios/envios-carregar-produtos.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#div-produtos').show();
			$("#div-produtos").html($.trim(retorno));
		}
	});
}

function incluirEditarProduto(workflowProdutoID){
	$('#div-produtos-incluir-editar').hide();
	$("#div-produtos-incluir-editar").html("");
	$(".btn-editar-produto-workflow").hide("");
	$(".btn-excluir-produto-workflow").hide("");
	$(".btn-novo-produto").hide("");
	caminho = caminhoScript+"/modulos/envios/envios-carregar-produto.php?workflow-produto-id="+workflowProdutoID;
	$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			if (workflowProdutoID!=""){
				$("#conteudo-produto-"+workflowProdutoID).html($.trim(retorno));
			}
			else{
				$('#div-produtos-incluir-editar').show();
				$("#div-produtos-incluir-editar").html($.trim(retorno));
			}
		}
	});
}

function exibirCamposSituacao(){
	$(".div-situacao").show();
	if ($("#situacao-atual-chamado").val()=="53"){
		$(".div-data-entrega").show()
	}
	else{
		if ($("#select-situacao-follow").val()=="53"){
			$(".div-data-entrega").show()
			caminho = caminhoScript+"/funcoes/carregar-data-atual.php?tipo=d&formato=d/m/Y H:i";
			$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#data-entrega").val(retorno);
				}
			});
		}
		else{
			$(".div-data-entrega").hide();
			$("#data-finalizado-chamado").val("");
			$("#hora-finalizado-chamado").val("");
		}
	}
}

function verificaOrigem(){
	$(".origem-envio").hide();
	$(".origem-envio-"+$("#origem-envio").val()).show();
	$(".div-origem-envio").html("");
}


function salvarEnvio(){
	if (($('#situacao-atual-chamado').val()=="53") || ($('#situacao-atual-chamado').val()=="59")){
		$(".div-situacao").show();
	}
	$("#texto-cadastro-localiza-cadastro-id-de").css('background-color', '').css('outline', '');
	$("#texto-cadastro-localiza-cadastro-id-para").css('background-color', '').css('outline', '');
	$("#texto-cadastro-localiza-cadastro-id-trans").css('background-color', '').css('outline', '');
	$("#envio-codigo-chamado").css('background-color', '').css('outline', '');
	$("#forma_envio_chosen a").css('background-color', '').css('outline', '');
	$("#tipo_envio_chosen a").css('background-color', '').css('outline', '');
	$("#data-envio").css('background-color', '').css('outline', '');
	$("#hora-envio").css('background-color', '').css('outline', '');
	$("#data-entrega").css('background-color', '').css('outline', '');
	$("#hora-entrega").css('background-color', '').css('outline', '');
	$("#descricao-follow").css('background-color', '').css('outline', '');
	$("#select_situacao_follow_chosen a").css('background-color', '').css('outline', '');
	$("#empresa_id_chosen a").css('background-color', '').css('outline', '');
	$("#valor-transporte").css('background-color', '').css('outline', '');

	flag = 0;
	if ($('#cadastro-id-de').val()==""){ $("#texto-cadastro-localiza-cadastro-id-de").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if ($('#cadastro-id-para').val()==""){ $("#texto-cadastro-localiza-cadastro-id-para").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if ($('#empresa-id').val()==""){ $("#empresa_id_chosen a").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if ($('#tipo-envio').val()==""){ $("#tipo_envio_chosen a").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}

	if ($("#enviar-faturamento").val()=="1"){
		if ($('#cadastro-id-trans').val()==""){ $("#texto-cadastro-localiza-cadastro-id-trans").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if (parseFloat(ReplaceAll(ReplaceAll($('#valor-transporte').val(),".",""),",","."))<=0){ $("#valor-transporte").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}

	if (($('.radio-origem:checked').val()=="1") && ($.trim($('#envio-codigo-chamado').val())=="")){$("#envio-codigo-chamado").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}

	if (($('#select-situacao-follow').val()!="56")&&($('#select-situacao-follow').val()!="57")&&($('#select-situacao-follow').val()!="59")){
		if ($('#forma-envio').val()==""){ $("#forma_envio_chosen a").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#data-envio').val()==""){ $("#data-envio").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#hora-envio').val()==""){ $("#hora-envio").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}
	if ($('#select-situacao-follow').val()==""){ $("#select_situacao_follow_chosen a").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if (($('#descricao-follow').val()=="") && (($('#situacao-atual-chamado').val()=="53")||($('#situacao-atual-chamado').val()=="59"))){ $("#descricao-follow").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if ($('#select-situacao-follow').val()=="53"){
		if ($('#data-entrega').val()==""){ $("#data-entrega").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
		if ($('#hora-entrega').val()==""){ $("#hora-entrega").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}

	//seleciona-endereco-envio-de
	//seleciona-endereco-envio-para
	//if ($('#cadastro-id-de-endereco').val()==""){ $("#descricao-follow").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	//if ($('#cadastro-id-para-endereco').val()==""){ $("#descricao-follow").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	//alert(flag);

	//$("#frmDefault").attr('action', caminhoScript+"/modulos/envios/envios-salvar.php");
	//$("#frmDefault").submit();

	if (flag==0){
		caminho = caminhoScript+"/modulos/envios/envios-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#enviar-faturamento").val("");
				$("#workflow-id").val($.trim(retorno));
				$("#localiza-workflow-id").val($.trim(retorno));
				$("#frmDefault").attr("action",caminhoScript+"/envios/"+$("#slug-pagina").val());
				$("#frmDefault").submit();
			}
		});
	}

}

function carregarChamado(idCampo){
	if ($("#"+idCampo).val()!=""){
		$("#envio-codigo-chamado").css('background-color', '').css('outline', '');
		$("#envio-id-chamado").css('background-color', '').css('outline', '');
		if (($("#envio-codigo-chamado").val().trim()!="")||($("#envio-id-chamado").val().trim()!="")){
			caminho = caminhoScript+"/modulos/envios/envios-validar-chamado.php?envio-codigo-chamado="+$("#envio-codigo-chamado").val()+"&envio-id-chamado="+$("#envio-id-chamado").val().trim();
			$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					if ($.trim(retorno)== ""){
						$("#"+idCampo).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
						//$("#"+idCampo).focus();
						//$("#"+idCampo).select();
						//alert("Nenhum registro localizado");
					}else{
						$("#div-chamado").html($.trim(retorno));
						if (idCampo=="envio-id-chamado")
							$("#envio-codigo-chamado").val($("#codigo-estrangeira").val());
						if (idCampo=="envio-codigo-chamado")
							$("#envio-id-chamado").val($("#chave-estrangeira").val());
					}
				}
			});
		}
		else{
			$("#div-chamado").html("");
		}
	}
}
/*
function exibirDadosFinanceiro(){
	caminho = caminhoScript+"/modulos/envios/envios-carregar-financeiro.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-financeiro-dados").html($.trim(retorno));
		}
	});
}
*/