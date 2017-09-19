/********* Inicio Funções Tela de Localizar Produtos ***********/
$(document).ready(function(){
	slugPagina = $("#slug-pagina").val();
	if (slugPagina=="produtos-cadastrados"){
		$("#localiza-descricao-produto").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-produtos").click(); }});
	}
	$("#botao-pesquisar-produtos").live('click', function () {
		//$("#frmDefault").attr("action",paginaAtual);
		$("#frmDefault").submit();
	});

	$(".produto-localiza").live('click', function () {
		$("#produto-id").val($(this).attr('produto-id'));
		$("#select-tipo-grupo-13").val($(this).attr('tipo-produto'));
		$("#frmDefault").attr("action",caminhoScript+"/produtos/produtos-cadastrar");
		$("#frmDefault").submit();
	});
	//carregarFornecedores();
	verificaTipoProduto();
});

/********* Fim Funções Tela de Localizar Produtos ***********/


/********* Incio Funções Cadastra Produtos ***********/

$(document).ready(function(){
	slugPagina = $("#slug-pagina").val();
	if (slugPagina=="produtos-cadastrar"){
		if ($("#produto-id").val()==""){
			$("#botao-excluir-produto").hide();
		}
		else{
			verificaTipoProduto();
		}

		if ($("#qtd-variacoes").val()==0){
			$("#botao-cadastra-produto-caracteristica").click();
		}

		/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
		$("#menu-superior-5").live('click', function () {
			carregarDocumentos($("#produto-id").val(),'produtos');
		});

		verificaFormaCobranca();
		$("#select-tipo-grupo-13").live('change', function () {
			verificaTipoProduto()
		});

		$("#botao-cadastra-produto").live('click', function () {
			salvarProduto($(this).attr('origem'));
		});

		$(".forma-cobranca").live('change', function () {
			verificaFormaCobranca();
		});

		/* FORNECEDORES */
		$("#menu-superior-3").live('click', function () {
			$("#conteudo-interno-fornecedor-id").html("<p align='center'><b>AGUARDE CARREGANDO...</b></p>");
			carregarFornecedores();
		});
		$(".botao-incluir-fornecedor").live('click', function () {
			$(".div-vincular-cadastros").show();
		});

		$(".seleciona-cadastro-geral").live('click', function () {
			if ($('#fornecedor-id').val()!=""){
				caminho = caminhoScript+"/modulos/produtos/produtos-fornecedores-insert.php?cadastro-id="+$('#fornecedor-id').val()+"&produto-id="+$("#produto-id").val()
				$("#conteudo-interno-fornecedor-id").html("<p align='center'><b>AGUARDE CARREGANDO...</b></p>");
				detalhesCampo= $.ajax(caminho).done(function(){
					$(".div-vincular-cadastros").html("");
					carregarFornecedores();
				});
			}
		});
		$(".btn-excluir-cadastro-geral").live('click', function () {
			var confirma=confirm("Tem certeza que deseja excluir o fornecedor do produto?");
			if (confirma==true){
				produtoCadastroID = $(this).attr('parametro');
				caminho = caminhoScript+"/modulos/produtos/produtos-fornecedores-delete.php?produto-cadastro-id="+produtoCadastroID;
				$("#conteudo-interno-fornecedor-id").html("<p align='center'><b>AGUARDE CARREGANDO...</b></p>");
				detalhesCampo= $.ajax(caminho).done(function(){
					carregarFornecedores();
				});
			}
		});

/*

		$(".btn-excluir-cadastro-geral").live('click', function () {
			excluirCadastroVinculo($(this).attr('parametro'));
		});

		$(".seleciona-cadastro-geral").live('click', function () {
			if ($('#novo-vinculo').val()!=""){
				tipoVinculoID = $('#novo-vinculo').attr('parametro');
				detalhesCampo= $.ajax(caminhoScript+"/modulos/cadastros/cadastro-vinculo-insert.php?cadastro-id="+$('#cadastro-id').val()+"&cadastro-filho-id="+$('#novo-vinculo').val()+"&tipo-vinculo-id="+tipoVinculoID)
				.done(function(){
					$("#div-cadastro-vinculo-"+tipoVinculoID).html("");
					carregarCadastroVinculo($("#cadastroID").val(),tipoVinculoID);
				});
			}
		});
*/
		/**/
		$(".link-cadastros").live('click', function () {
			$("#cadastroID").val($(this).attr("cadastro-id"));
			$("#frmDefault").attr("action",caminhoScript+"/cadastros/cadastro-dados");
			$("#frmDefault").submit();
		});

		$("#botao-excluir-produto").live('click', function () {
			var confirma=confirm("Tem certeza que deseja excluir o produto?");
				if (confirma==true){
				caminho = caminhoScript+"/modulos/produtos/produtos-delete.php?produto-id="+$("#produto-id").val();
				$("#frmDefault").attr("action",caminho);
				$("#frmDefault").submit();
			}
		});
	}

	$("#cadastra-nova-categoria").live('click', function () {
		var flag = 0;
		if ($("#nome").val()==""){ $("#nome").css('background-color', '#FFE4E4');flag=1;}
		if(flag==0){
			//$("#frmDefault").attr("action",caminhoScript+"/produtos/produtos-categorias");
			$("#frmDefault").submit();
		}
	});

});

function salvarProduto(origem){
	flag=0;

	$("#produto-nome").css('background-color', '');
	$("#select-tipo-grupo-13").css('background-color', '');
	$(".forma-cobranca").css('background-color', '');
	$(".valor-venda-variacao").css('background-color', '');

	cont = 0;

	if ($("#produto-nome").val().trim()==""){ $("#produto-nome").css('background-color', '#FFE4E4');flag=1;}
	if ($("#select-tipo-grupo-13").val().trim()==""){ $("#select_tipo_grupo_13_chosen a").css('background-color', '#FFE4E4');flag=1;}


	if($("#select-tipo-grupo-13").val()=="100"){
		if ($("#valor-venda-variacao-composto").val().trim()==""){$("#valor-venda-variacao-composto").css('background-color', '#FFE4E4');flag=3;}
		if ($("#total-composicao").val()=="0"){$("#select_produtos_chosen .chosen-single,#quantidade-produtos").css('background-color', '#FFE4E4');flag=3;}
	}
	else{
		$(".forma-cobranca").each(function(){
			cont = cont + 1;
			indice = $(this).attr('indice');
			$("#forma_cobranca_"+indice+"_chosen a").css('background-color', '');
			if (($("#acao-variacao").val()!="I") && (cont==1)){
				return;
			}
			if (($(this).val()=='')&&($("#select-tipo-grupo-13").val()!="100")){
				$("#forma_cobranca_"+indice+"_chosen a").css('background-color', '#FFE4E4');flag=2;
			}
			/*
			else{
				if ($(this).val()=='35'){
					valorVariacao = ReplaceAll(ReplaceAll($("#valor-venda-variacao-"+indice).val(),".",""),",",".");
					if ((valorVariacao==0)||(valorVariacao=="")){
						$("#valor-venda-variacao-"+$(this).attr("indice")).css('background-color', '#FFE4E4');flag=2;
					}
				}
			}
			*/
		});
	}
	if (flag==0){
		caminho = caminhoScript+"/modulos/produtos/produtos-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				//alert(retorno);
				//$("#div-retorno").html(retorno);

				alert("Produto salvo com sucesso!");
				//$('#div-retorno').html(retorno);
				if ((origem=='variacoes')&&($('#produto-id').val()!='')){
					carregarVariacoes($('#produto-id').val());
				}
				else{
					if ($('#produto-id').val()==''){
						$('#produto-id').val(retorno);
					}
					$("#frmDefault").submit();
				}

			}
		});
	}
	else{
		if (flag==1)
			$("#menu-superior-1").click();
		if (flag==2)
			$("#menu-superior-4").click();
		if (flag==3)
			$("#menu-superior-6").click();
	}
}

function carregarFornecedores(){
	$("#conteudo-interno-fornecedor-id").html("<p align='center'><b>AGUARDE CARREGANDO...</b></p>");
	$("#conteudo-interno-fornecedor-id").load(caminhoScript+"/modulos/produtos/carregar-fornecedores.php?produto-id="+$("#produto-id").val(),function(responseTxt,statusTxt,xhr){
		$(".div-vincular-cadastros").hide();
	});
}

function verificaTipoProduto(){
	/*
	switch(expression) {
		case 31:
			code block
			break;
		case 100:
			code block
			break;
		default:
			$(".dados-produto").show();
			$(".dados-servico").hide();
			$("#menu-superior-2").show();
			$("#menu-superior-3").show();
			$("#menu-superior-4").show();
			$("#menu-superior-6").hide();
			$(".descricao-variacao").width('30%');
	}
	*/
	if (($("#select-tipo-grupo-13").val()=="30")){
		$(".dados-produto").show();
		$(".dados-servico").hide();
		$("#menu-superior-2").show();
		$("#menu-superior-3").show();
		$("#menu-superior-4").show();
		$("#menu-superior-6").hide();
		$(".descricao-variacao").width('30%');
	}
	if (($("#select-tipo-grupo-13").val()=="31") || ($("#select-tipo-grupo-13").val()=="140")){
		$(".dados-produto").hide();
		$(".dados-servico").show();
		$("#menu-superior-4").show();
		$("#menu-superior-3").show();
		$("#menu-superior-2").hide();
		$("#menu-superior-6").hide();
		$(".descricao-variacao").width('40%');
	}
	if (($("#select-tipo-grupo-13").val()=="100")){
		$(".dados-produto").hide();
		$(".dados-servico").hide();
		$("#menu-superior-2").hide();
		$("#menu-superior-3").hide();
		$("#menu-superior-4").hide();
		$("#menu-superior-6").show();
		$(".descricao-variacao").width('40%');
	}
}

function verificaFormaCobranca(){
	$(".fc").hide();
	$(".forma-cobranca").each(function(){
		indice = $(this).attr('indice');
		if ($(this).val()=='35'){
			$(".fc-35-" + indice).show();
		}
		if ($(this).val()=='58'){
			$(".fc-58-" + indice).show();
		}
		if ($(this).val()=='36'){
			$(".fc-36-" + indice).show();
		}
	});
}

/********* Fim Funções Cadastra Produtos ***********/



/********* Inicio Funções Tabela de preços ***********/

	$("#tabela-preco-seleciona").live('change', function () {
		if($(this).val()=='-1'){
			$("#div-seleciona-tabela").hide();
			$("#div-cadastra-tabela").show();
			$("#div-produtos-cadastrados").hide();
		}else{
			$("#frmDefault").submit();
		}
	});
	$("#cancela-tabela").live('click', function () {
			$("#div-seleciona-tabela").show();
			$("#div-cadastra-tabela").hide();
			$("#tabela-preco-seleciona").val('').trigger('chosen:updated');
	});
	$("#nome-tabela").live('keyup', function () {
		if($("#nome-tabela").val()!="")
			$("#nome-tabela").css('background-color', '');
	});

	$("#cadastra-tabela").live('click', function () {
		if($("#nome-tabela").val()==""){
			$("#nome-tabela").css('background-color', '#FFE4E4');
			$("#nome-tabela").focus();
		}else{
			$("#frmDefault").submit();
		}
	});
	$("#botao-excluir-tabela").live('click', function () {
		$("#acao-tabela").val('e');
		$("#frmDefault").submit();
	});
	$("#botao-atualiza-tabela").live('click', function () {
		$("#acao-tabela").val('a');
		$("#frmDefault").submit();
	});

	$(".seleciona-produto-tabela").live('click', function () {
		if($(this).attr('checked'))
			$("#produto-selecionado-"+$(this).val()).val($(this).val());
		else
			$("#produto-selecionado-"+$(this).val()).val('');

	});


/********* Fim Funções Tabela de preços ***********/




/********* Inicio Funções Imagens***********/
	$("#imagem-upload").live('change', function () {
			$("#imagem-upload").css('background-color', '');
	});
	$("#botao-imagem-upload").live('click', function () {
		if($("#imagem-upload").val()==""){
			$("#imagem-upload").css('background-color', '#FFE4E4');
		}else{
			$("#frmDefault").attr('action','../modulos/produtos/produto-upload-imagem.php');
			$("#frmDefault").attr('target','iframe-upload-imagem');
			$("#frmDefault").attr('enctype','multipart/form-data');
			$("#frmDefault").attr('encoding','multipart/form-data');
			$("#frmDefault").submit();
			$("#frmDefault").attr('action','');
			$("#frmDefault").attr('target','');
			$("#frmDefault").attr('enctype','');
			$("#frmDefault").attr('encoding','');
	}
	});

	function reloadImagenprodutos(idProduto){
		$("#imagem-upload").val('');
		$("#posicao-imagem").val('1');
		$("#imagens-produtos").load('../modulos/produtos/produto-carrega-imagem.php?produtoID='+idProduto);
		carregarVariacoes(idProduto)
	}

	$(".btn-excluir-produto").live('click', function () {
		$("#imagens-produtos").load('../modulos/produtos/exclui-carrega-imagem.php?imagemID='+$(this).attr('produto-imagem-id')+'&produtoID='+$("#produtoID").val());
	});

	$("#botao-cadastra-produto-caracteristica").live('click', function () {
		$("#produto-variacao-0").show();
		verificaFormaCobranca();
		verificaTipoProduto();
		$("#acao-variacao").val("I");

		/*
		$("#botao-cadastra-produto-caracteristica").hide();
		$.ajax({type: "GET",url: '../modulos/produtos/produtos-nova-variacao.php?produtoID='+$("#produtoID").val()+'&qtdVariacoes='+$('#qtd-variacoes').val(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#conteudo-interno-variacoes").prepend(retorno);
				verificaFormaCobranca();
				verificaTipoProduto();
			}
		});
		*/
	});
	$("#botao-exclui-produto-variacao").live('click', function () {
		var confirma=confirm("Tem certeza que deseja excluir o variação do produto?");
		$.ajax({type: "GET",url: '../modulos/produtos/produto-exclui-variacao.php?variacaoID='+$(this).attr('attr-id')+'&produtoID='+$("#produtoID").val(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#conteudo-interno-variacoes").html(retorno);
				$("#botao-cadastra-produto-caracteristica").show();
				verificaFormaCobranca();
				verificaTipoProduto();
			}
		});
	});

function carregarVariacoes(produtoID){
	//alert(caminhoScript+"/modulos/produtos/produto-carregar-variacao.php?produtoID="+produtoID);
	$.ajax({type: "GET",url: caminhoScript+"/modulos/produtos/produto-carregar-variacao.php?produtoID="+produtoID, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#conteudo-interno-variacoes").html(retorno);
			$("#botao-cadastra-produto-caracteristica").show();
			verificaFormaCobranca();
			verificaTipoProduto();
		}
	});
}

$(".btn-excluir-categoria").live('click', function () {
	if(confirm("Tem certeza que deseja excluir a categoria?")){
		$("#categoria-exclui").val($(this).attr('id'));
		//$("#frmDefault").attr("action",caminhoScript+"/produtos/produtos-categorias");
		$("#frmDefault").submit();
	}
});

$(".btn-editar").live('click', function () {
	$("#categoria-atualiza").val($(this).attr('id'))
	$("#nome").val($(this).attr('descr'));
	$("#pai").val($(this).attr('pai'));
	$("#cadastra-nova-categoria").attr('value', 'Atualizar Categoria');
	$("#cancela-edita-tarefa").show();
});
$("#cancela-edita-tarefa").live('click', function () {
	$("#categoria-atualiza").val('')
	$("#nome").val('');
	$("#pai").val('');
	$("#cadastra-nova-tarefa").attr('value', 'Cadastrar Tarefa');
	$("#cancela-edita-tarefa").hide();
});


/* CONTROLE DE ESTOQUE - ENTRADA DE MATERIAIS*/
$(document).ready(function(){
	slugPagina = $("#slug-pagina").val();
	$(".botao-movimentacao-material").live('click', function () {
		$("#frmDefault").attr("action",caminhoScript+"/produtos/produtos-movimentacao-material");
		$("#frmDefault").submit();
	});
	if (slugPagina=="produtos-movimentacao-material"){
		verificaCamposEntrada();
		$(document).keypress(function(event) {if(event.keyCode==13){ $(".botao-pesquisar-entrada-material").click(); }});
		$(".tipo-entrada").live('change', function () {
			verificaCamposEntrada();
		});
		$(".botao-pesquisar-entrada-material").live('click', function () {
			$("#frmDefault").submit();
		});
		$(".executar-movimentacao").live('click', function () {
			if(validarCamposGenerico(".required")){
				$(".executar-movimentacao").hide();
				//if (confirm("Tem certeza que deseja realizar a movimentação?")){
					//executar rotina de insert no banco
					caminho = caminhoScript+"/modulos/produtos/produtos-movimentacao-material-executar.php";
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							//$(".div-retorno").html(retorno);
							alert("Movimentação realizada com sucesso!");
							$("#frmDefault").submit();
						}
					});
				//}
				//else
				//	$(".executar-movimentacao").show();

			}
		});

		/* PAREI AQUI EXPANDIR OS INPUTS!!!!!!!!!!!!!!!!!!!!*/

		$(".preencher-com").live('focus', function () {
			//alert(parseFloat(desformataValor($(this).val())));
			if (parseFloat(desformataValor($(this).val()))==0){
				$(this).val($(this).attr('preencher-com'));
				verificarNumeroSerie($(this));
			}
			$(".executar-movimentacao").show();
		});
		$(".preencher-com").live('blur', function () {
			$(this).css('background-color', '');
			if ($("#tipo-movimentacao").val()!="manual"){
				if ((parseFloat(desformataValor($(this).val())) > parseFloat(desformataValor($(this).attr('preencher-com'))))){
					$(this).css('background-color', '#FFE4E4').focus();
				}
			}
			verificarNumeroSerie($(this));
			$(".executar-movimentacao").show();
		});

		$(".valida-numero-serial").live('change', function () {
			valorAnterior = $(this).attr('ns-anterior');
			valor = $(this).val();
			if (valor!=""){
				$(".valida-numero-serial option[value='" + valor + "']").attr('disabled',true).trigger('chosen:updated');
			}
			$("#" + $(this).attr('id') + " option:selected").attr('disabled',false).trigger('chosen:updated');
			$(this).attr('ns-anterior', valor);
			if ((valorAnterior!=valor)&&(valorAnterior!="")){
				$(".valida-numero-serial option[value='" + valorAnterior + "']").attr('disabled',false).trigger('chosen:updated');
			}
		});

		function verificarNumeroSerie(campo){
			if (campo.hasClass('classe-ns')){
				pos = parseInt(parseInt(campo.attr("pos")) + 1);
				chaveEstrangeiraProdutoID = campo.attr("chave-estrangeira-produto-id");
				produtoVariacaoID = campo.attr("produto-variacao-id");
				valor = parseFloat(desformataValor(campo.val()));
				if (valor==0){
					$(".linha-estoque-"+pos).hide();
					$("#bloco-movimentacao-produto-"+pos).html("");
				}
				else{
					$(".linha-estoque-"+pos).show();
					caminho = caminhoScript+"/modulos/produtos/produtos-movimentacao-numero-serie.php?valor-baixa="+valor+"&chave-estrangeira="+chaveEstrangeiraProdutoID+"&produto-variacao-id="+produtoVariacaoID;
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							//$(".div-retorno").html(retorno);
							//alert(retorno);
							$("#bloco-movimentacao-produto-"+pos).html(retorno);
						}
					});
					/*
					valorAux = parseInt(valor);
					if (parseInt(valorAux)<0) {
						valorAux = (parseInt(valorAux) * (-1));
					}
					$(".linha-estoque-"+pos).show();

					strHtml = 	"		<div class='titulo-secundario' style='float:right; width:300px'> ";
					strHtml += 	"			<div class='titulo-secundario' style='float:right; width:100px'> ";
					strHtml += 	"				<p><b>Enviar para:</b></p>";
					strHtml += 	"			</div>";
					strHtml += 	"			<div class='titulo-secundario' style='float:right; width:200px'> ";
					strHtml += 	"				<p><b>Informe o(s) número(s) de série:</b></p>";
					strHtml += 	"			</div> ";
					for (i=1; parseInt(valorAux) >= i; i++) {
						strHtml += 	"		<div class='titulo-secundario' style='float:right; width:100px'> ";
						strHtml += 	"			<p><select id='enviar-material-para-"+chaveEstrangeiraProdutoID+"-"+i+"' name='enviar-material-para["+chaveEstrangeiraProdutoID+"][]' class='required select-enviar-material' style='width:100px'><option value='estoque'>Estoque</option><option value='laboratorio'>Laboratório</option></select></p>";
						strHtml += 	"		</div>";
						strHtml += 	"		<div class='titulo-secundario' style='float:right; width:200px'>";
						strHtml += 	"			<p><input type='text' id='numeros-series-"+chaveEstrangeiraProdutoID+"-"+i+"' name='numeros-series["+chaveEstrangeiraProdutoID+"][]' value='' class='required' style='width:180px'/></p>";
						strHtml += 	"		</div>";
					}
					strHtml += 	"		</div>";

					$("#bloco-movimentacao-produto-"+pos).html(strHtml);
					$("select").chosen({width: "99%", no_results_text: "Nenhum registro encontrado!", placeholder_text_single: " ", placeholder_text_multiple: " ", allow_single_deselect: true });
					*/
				}
			}
		}

		$(".btn-expandir-retrair-estoque").live('click', function () {
			produtoVariacaoID = $(this).attr("produto-variacao-id");
			pos = parseInt(parseInt($(this).attr("pos")) + 1);
			if($(this).hasClass('btn-expandir')){
				$(this).removeClass('btn-expandir');$(this).addClass('btn-retrair');
				$(".linha-estoque-"+pos).hide();
				$("#bloco-movimentacao-produto-"+pos).html("");
			}
			else{
				$(this).removeClass('btn-retrair');$(this).addClass('btn-expandir');
				caminho = caminhoScript+"/modulos/produtos/produtos-carregar-movimentacoes.php?produtoVariacaoID="+produtoVariacaoID;
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$("#bloco-movimentacao-produto-"+pos).html(retorno);
						$(".linha-estoque-"+pos).show();
					}
				});

			}

		});
	}
});

function verificaCamposEntrada(){
	$(".div-todos").hide();
	$(".div-"+$(".tipo-entrada:checked").val()).show();
}



/* INICIO PRODUTO COMPOSTO */
	$("#select-produtos,.chosen-single,#quantidade-produtos,#valor-venda-variacao-composto").live('change click', function () {
		$(".chosen-single,#quantidade-produtos,#valor-venda-variacao-composto").css('background-color', '').css('border', '');
	});
	$("#botao-incluir-produto").live('click', function () {
		var erro = 0;
		if($("#select-produtos").val()==''){
			$("#select_produtos_chosen").css('background-color', '#FFE4E4');
			erro = 1
		}
		if($("#quantidade-produtos").val()<=0){
			$("#quantidade-produtos").css('background-color', '#FFE4E4');
			erro = 1
		}
		if(erro==0){
			caminho = caminhoScript+"/modulos/produtos/produto-composto-inclui-derivacao.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#select-produtos").val('').trigger('chosen:updated');
					$("#quantidade-produtos").val('1');
					$("#div-detalhes-composicao").html(retorno);

				}
			});
		}
	});

	$(".exclui-item-composicao").live('click', function () {
		$("#composicao-id").val($(this).attr('composicao-id'));
		caminho = caminhoScript+"/modulos/produtos/produto-composto-exclui-derivacao.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#select-produtos").val('').trigger('chosen:updated');
				$("#quantidade-produtos").val('1');
				$("#div-detalhes-composicao").html(retorno);

			}
		});
	});

/* FINAL PRODUTO COMPOSTO */


/* INICIO - TABELAS DE PREÇO */

	$(".editar-tabelas-precos").live('click', function () {
		caminho = caminhoScript+"/modulos/produtos/produtos-tabela-preco-variacao.php?produto-variacao-id="+$(this).attr("produto-variacao-id");
		$.fancybox.open({ href : caminho, type : 'iframe', width: '90%', padding : 2});
	});

	$(".atualizar-valores-produto-tabelas").live('click', function () {
		caminho = caminhoScript+"/modulos/produtos/produtos-tabela-preco-variacao-salvar.php";
		$("#frmPrecos").attr("action",caminho);
		$("#frmPrecos").submit();
		//caminho = caminhoScript+"/modulos/produtos/produtos-tabela-preco-variacao.php?produto-variacao-id="+$(this).attr("produto-variacao-id");
		//$.fancybox.open({ href : caminho, type : 'iframe', width: '90%', padding : 2});
	});

	$(".botoes-acoes").live('click', function () {
		$(".botoes-acoes").hide();
	});

	$(".incluir-alterar-valores-faixas").live('click', function () {
		tipoFaixa = $(this).attr('tipo-faixa');
		$(".faixas-"+tipoFaixa).show();
		caminho = caminhoScript+"/modulos/produtos/produto-tabela-preco-faixa-carregar.php?tipo-faixa="+tipoFaixa;
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$(".faixas-"+tipoFaixa).html(retorno);
				$(".faixas-"+tipoFaixa).show();
				//$(".bloco-incluir-alterar-preco-faixas").html(retorno);
				//$(".bloco-incluir-alterar-preco-faixas").show();
			}
		});
	});


	$(".cancelar-incluir-faixa").live('click', function () {
		$(".botoes-acoes").show();
		$(".bloco-incluir-alterar-preco-faixas").hide().html("");
	});


	$(".salvar-faixa").live('click', function () {
		if(validarCamposGenerico(".bloco-incluir-alterar-preco-faixas .required")){
			ini = desformataValor($("#quantidade-faixa-inicial").val());
			fim = desformataValor($("#quantidade-faixa-final").val());
			if (parseFloat(ini) > parseFloat(fim)){
				alert("Quantidade Inicial não pode ser maior que Quantidade Final");
				return;
			}
			caminho = caminhoScript+"/modulos/produtos/produtos-tabela-preco-variacao-salvar.php?tipo-faixa="+$('#tipo-faixa').val();
			$("#frmPrecos").attr("action",caminho);
			$("#frmPrecos").submit();
		}
	});


	$(".btn-excluir-faixa-preco").live('click', function () {
		if(confirm("Tem certeza que deseja excluir a faixa?")){
			caminho = caminhoScript+"/modulos/produtos/produtos-tabela-preco-variacao-salvar.php?tipo-faixa="+$('#tipo-faixa').val()+"&acao=D&id="+$(this).attr('attr-id');
			$("#frmPrecos").attr("action",caminho);
			$("#frmPrecos").submit();
		}
		else{
			$(".botoes-acoes").show();
		}
	});


	$(".btn-editar-faixa-preco").live('click', function () {
		tipoFaixa = $(this).attr('tipo-faixa');
		caminho = caminhoScript+"/modulos/produtos/produto-tabela-preco-faixa-carregar.php?tipo-faixa="+tipoFaixa+"&id="+$(this).attr('attr-id');
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$(".faixas-"+tipoFaixa).html(retorno);
				$(".faixas-"+tipoFaixa).show();
			}
		});
	});

/* FIM - TABELAS DE PREÇO */
