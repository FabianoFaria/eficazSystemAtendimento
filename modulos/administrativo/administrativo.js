$(document).ready(function(){
	var paginaAtual = window.location.href.replace("#","");
	var slugPagina = $("#slug-pagina").val();
	$(".ativa-modulo").click(function() {
		slug = this.id.replace('modulo-','');
		$("#gerencia-modulo-retorno").load(caminhoScript+"/modulos/administrativo/ativa-plugin.php?slug="+slug,function(responseTxt,statusTxt,xhr){
			location.reload();
		});
	});
	$(".desativa-modulo").click(function() {
		slug = this.id.replace('modulo-','');
		$("#gerencia-modulo-retorno").load(caminhoScript+"/modulos/administrativo/desativa-plugin.php?slug="+slug,function(responseTxt,statusTxt,xhr){
			location.reload();
		});
	});
	$(".atualizar-modulo").click(function() {
		moduloID = $(this).attr("modulo-id");
		slug = $(this).attr("slug");
		$("#gerencia-modulo-retorno").load(caminhoScript+"/modulos/administrativo/ativa-plugin.php?slug="+slug+"&modulo-id="+moduloID,function(responseTxt,statusTxt,xhr){
			location.reload();
		});
	});


	$(".configurar-pagina-inicial").click(function() {
		$.ajax(caminhoScript+"/modulos/administrativo/atualiza-pagina-inicial.php?pagina="+this.id);
		alert("Pagina inicial atualizada com sucesso!");
	});

	$(".modulo-posicao").keyup(function(){
		$.ajax(caminhoScript+"/modulos/administrativo/atualiza-posicao-menu.php?posicao="+$("#"+this.id).val()+"&modulo="+this.id.replace('modulo-posicao-',''))
		.done(function(){
			location.reload();
		});
	});

	$("#salva-configuracao-email").click(function() {
	});

	$("#botao-excluir-grupo").click(function() {
		var confirma=confirm("Tem certeza que deseja excluir o grupo?");
		if (confirma==true){
			$.ajax(caminhoScript+"/modulos/administrativo/excluir-grupo.php?grupo-id="+$("#grupo-edicao").val())
			.done(function(){
				location.reload();
			});
		}
	});



	$("#grupo-edicao").change(function() {
		if($("#grupo-edicao").val()==-1){
			$("#grupo-conteudo-interno-inicio").hide();
			$("#grupo-conteudo-interno-novo").show();
			$("#grupo-selecionado").val('');
			$(".conteudo-pagina-tabela").hide();
			$("#nome-grupo").focus();
		}
		if($("#grupo-edicao").val()!="-1"){
			$("#grupo-selecionado").val('');
			$("#frmDefault").attr("action","./");
			$("#frmDefault").submit();
		}
		if($("#grupo-edicao").val()==''){
			$(".conteudo-pagina-tabela").hide();
		}
	})

	$("#cancela-grupo").click(function() {
			$("#grupo-edicao").val('');
			$("#grupo-conteudo-interno-inicio").show();
			$("#grupo-conteudo-interno-novo").hide();
	})

	$("#salva-grupo").click(function() {
		if ($('#nome-grupo').val().trim().length<3){ $("#nome-grupo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		}else{
			$("#frmDefault").attr("action","./");
			$("#frmDefault").submit();
		}
	})

	$("#atualiza-permissao").click(function() {
		$("#frmDefault").attr("action","./");
		$("#frmDefault").submit();
	});

	$(".permissao-modulo").click(function() {
		$(".modulo-"+$(this).attr("value")+"-G").attr("checked", false);
		$(".modulo-"+$(this).attr("value")+"-L").attr("checked", false);
		$(".modulo-"+$(this).attr("value")+"-"+$(this).attr("type")).attr("checked", true);
	});

	$(".configurar-leitura-gravacao").click(function() {
		if($(this).attr("permissao")=="L"){
			if($(".paginas-L-"+$(this).val()).attr("checked"))
				$(".paginas-G-"+$(this).val()).attr("checked", false);
		}else{
			if($(".paginas-G-"+$(this).val()).attr("checked"))
				$(".paginas-L-"+$(this).val()).attr("checked", false);
		}
	});


	/* INICIO - IMPORTACAO ARQUIVO COBERTURA DE ANTENAS */
	$("#botao-selecione-arquivo").live('click', function () {
		$("#arquivo-upload").click();
	});
	$("#arquivo-upload").live('change', function () {
		extensao = $('#arquivo-upload').val().substring( $('#arquivo-upload').val().lastIndexOf(".")).toLowerCase();
		if (extensao!='.csv'){
			alert("Arquivo Inválido \nFormato de arquivo aceito: .csv");
			$('#arquivo-upload').val("");
		}
		else{
			var campoarquivo = $("#arquivo-upload");
			var arquivos = campoarquivo[0].files;
			nomeArquivos = "";
			for (i=0; arquivos.length > i; i++){
				arquivo = arquivos[i];
				nomeArquivos += arquivo.name;
			}
			$("#nome-arquivo-tmp").html(nomeArquivos);
			$("#mensagem-aguarde").html("<b Style='color:red'>AGUARDE UPLOAD SENDO REALIZADO</b>");
			$("#mensagem-aguarde").show();
			nomeArquivoOriginal = nomeArquivos;
			var iframe = $('<iframe name="iframe-upload" id="iframe-upload" style="display: none" />');
			$("body").append(iframe);
			var form = $('#frmDefault');
			form.attr("action", caminhoScript+"/funcoes/upload-arquivo.php");
			form.attr("method", "post");
			form.attr("enctype", "multipart/form-data");
			form.attr("encoding", "multipart/form-data");
			form.attr("target", "iframe-upload");
			form.submit();
			$("#iframe-upload").load(function () {
				nomeArquivo = ($("#iframe-upload")[0].contentWindow.document.body.innerHTML);
				if (nomeArquivo==""){
					$("#mensagem-aguarde").html("<b>PROBLEMAS NO UPLOAD DO ARQUIVO</b>");
				}
				else{
					caminho = caminhoScript+"/modulos/chamados/chamados-salvar-anexo-workflow.php?workflow-id="+$("#workflow-id").val()+"&arquivo="+nomeArquivo+"&arquivo-original="+nomeArquivoOriginal+"&observacao=";
					$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							//carregarAnexosWorkflow();
							//$("#div-cadastro-anexo").html("");
							$('#botao-importacao').show();
							$("#mensagem-aguarde").html("<b>UPLOAD REALIZADO COM SUCESSO</b>");
							$('#arquivo-importar').val(nomeArquivo);
						}
					});
				}
			});
		}
	});
	$("#botao-importacao").live('click', function () {
		$('#div-arquivo-aberto').show();
		caminho = caminhoScript+"/modulos/administrativo/importar-cobertura-bd.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				retorno = $.trim(retorno);
				$('#div-retorno').html(retorno);
			}
		});
	});
	/************************/

});



/********* Inicio Funções Relatório Acesso ***********/
$(document).ready(function(){
	if ($("#slug-pagina").val()=="relatorio-acessos"){
		$('#botao-pesquisar-acesso').live('click', function () {
			$("#frmDefault").submit();
		});
	}
});



/********* Inicio Funções Documentos ***********/
$(document).ready(function(){
	//alert($("#slug-pagina").val());
	if ($("#slug-pagina").val()=="documentos-gerenciar-documentos"){
		$('#documentos-disponiveis').live('change', function () {
			$("#detalhes-documento-container").hide();
			if($(this).val()=='-1'){
				$("#documentos-disponiveis").hide();
				$("#cadastra-documento").show();
				$("#nome-documento").focus();
			}else{
				$("#detalhes-documento").val('');
				$("#documento-id").val($("#documentos-disponiveis").val());
				$("#frmDefault").submit();
			}
		});

		$('#cancela-documento').live('click', function () {
			$("#documentos-disponiveis").val('');
			$("#documentos-disponiveis").show();
			$("#cadastra-documento").hide();
			$("detalhes-documento").val('');
		});
		$('#salva-documento').live('click', function () {
			if($("#nome-documento").val()=='')
				$("#nome-documento").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			else{
				salvarExcluirDocumento();
			}
		});
		$('#atualiza-documento').live('click', function () {
			salvarExcluirDocumento();
		});
		$('#exclui-documento').live('click', function () {
			$('#action-excluir-documento').val('excluir');
			salvarExcluirDocumento();
		});
		$('#destino-documento').live('change', function () {
			salvarExcluirDocumento();
		});
	}

	function salvarExcluirDocumento(){
		if ($("#documentos-disponiveis").val()!="-1"){
			$("#detalhes-documento").val(tinyMCE.get('detalhes-documento').getContent());
		}
		caminho = caminhoScript+"/modulos/administrativo/documentos-salvar-modelo.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				//$('#div-retorno').html(retorno);
				$("#documento-id").val(retorno);
				$("#frmDefault").submit();
			}
		});
	}

});
/********* Final Funções Gerencia Documentos ***********/

	$('.inc-alt-campo').live('click', function () {
		$("#campo-id").val($(this).attr('campo-id'));
		$("#frmDefault").attr('action',caminhoScript+"/administrativo/formularios/formularios-campos-gerenciar");
		$("#frmDefault").submit();
	});

	$('.inc-alt-formulario').live('click', function () {
		$("#formulario-id").val($(this).attr('formulario-id'));
		$("#frmDefault").attr('action',caminhoScript+"/administrativo/formularios/formularios-dinamicos-gerenciar");
		$("#frmDefault").submit();
	});

	$('.salvar-formulario-dinamico').live('click', function () {
		$('.salvar-formulario-dinamico').hide();
		if (validarCamposGenerico(".form-incluir-alterar-formularios .required")){
			caminho = caminhoScript+"/modulos/administrativo/formularios-dinamicos-salvar.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//if($('#tipo-fluxo').val()=='direto'){
						//parent.carregarFormularios();
						//parent.$.fancybox.close();
					//else{
						$('#formulario-id').val(retorno);
						$("#frmDefault").submit();
					//}
				}
			});
		}
		else{
			$('.salvar-formulario-dinamico').show();
		}
	});

	$('.inc-alt-campo-formulario').live('click', function () {
		carregarCamposSelecaoFormulario('','')
	});

	function carregarCamposSelecaoFormulario(formularioCampoID, selecionado){
		$('#bloco-incluir-editar-campos-0').hide();
		caminho = caminhoScript+"/modulos/administrativo/formularios-campos-carregar.php?formulario-campo-id="+formularioCampoID+"&campo-selecionado="+selecionado;
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$('.inc-alt-campo-formulario, .campo-formulario-editar, .campo-formulario-excluir').hide();
				$('#bloco-incluir-editar-campos-0').html(retorno).show();
			}
		});
	}


	$('#campo-formulario-incluir').live('click', function () {
		if (validarCamposGenerico(".dados-campo-incluir .required")){
			formularioCampoID = $(this).attr('formulario-campo-id');
			caminho = caminhoScript+"/modulos/administrativo/formularios-dinamicos-campo-salvar.php?formulario-campo-id="+formularioCampoID;
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#frmDefault').submit();
				}
			});
		}
	});


	$('.campo-formulario-excluir').live('click', function () {
		if (confirm('Tem certeza que deseja excluir o campo?')){
			formularioCampoID = $(this).attr('formulario-campo-id');
			caminho = caminhoScript+"/modulos/administrativo/formularios-dinamicos-campo-salvar.php?acao=d&formulario-campo-id="+formularioCampoID;
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//alert(retorno);
					//$('#frmDefault').append(retorno);
					$('#frmDefault').submit();
				}
			});
		}
	});

	$('.campo-formulario-editar').live('click', function () {
		formularioCampoID = $(this).attr('formulario-campo-id');
		carregarCamposSelecaoFormulario(formularioCampoID,'');
	});



	$('#campo-formulario-dinamico').live('change', function () {
		carregarBlocoInclusaoProduto('');
	});

	/*
	$('.incluir-novo-modelo-campo').live('click', function () {
		carregarBlocoInclusaoProduto('I');
	});
	*/
	/*
	$('.editar-campo-selecionado').live('click', function () {
		carregarBlocoInclusaoProduto('U');
	});
	*/

	$('#cancelar-campo-formulario').live('click', function () {
		formularioCampoID = $(this).attr('formulario-campo-id');
		$('.inc-alt-campo-formulario, .campo-formulario-editar, .campo-formulario-excluir').delay("fast").fadeIn();
		$('#bloco-incluir-editar-campos-'+formularioCampoID).html('').hide();
	});

	function carregarBlocoInclusaoProduto(acao){
		$('.incluir-novo-modelo-campo, .editar-campo-selecionado').hide();
		if ($('#campo-formulario-dinamico').val()=='')
			$('.incluir-novo-modelo-campo').show();
		else
			$('.editar-campo-selecionado').show();


		$('.dados-campo-incluir, .dados-campo-modelo, .editar-criar-campo').hide();
		/*
		if (acao=='I'){
			$('.bloco-campos-disponiveis, .salvar-formulario-dinamico').hide();
			$('.dados-campo-modelo').delay("fast").fadeIn();
		}

		if (acao=='U'){
			$('.bloco-campos-disponiveis, .salvar-formulario-dinamico').hide();
			$('.dados-campo-modelo').delay("fast").fadeIn();

			nome = $('#campo-formulario-dinamico option:selected').text();
			tipoCampo = $('#campo-formulario-dinamico option:selected').attr('tipo-campo');
			descricao = $('#campo-formulario-dinamico option:selected').attr('descricao');
			quantidade = $('#campo-formulario-dinamico option:selected').attr('quantidade');

			$('#nome-campo').val(nome);
			$('#tipo-campo-modelo').val(tipoCampo).trigger('chosen:updated');
			$('#descricao-campo').val(descricao);
			$('#quantidade-opcoes-multiplas').val(quantidade).trigger('chosen:updated');
			carregarOpcoesCampo();
		}
		*/
		if ((acao=='') && ($('#campo-formulario-dinamico').val()!='')){
			$('.dados-campo-incluir, .editar-criar-campo').delay("fast").fadeIn();
			tipoCampoDescricao = $('#campo-formulario-dinamico option:selected').attr('tipo-campo-descricao');
			descricao = $('#campo-formulario-dinamico option:selected').attr('descricao');
			//$('#tipo-campo-detalhes').html("<b>" + tipoCampoDescricao + "</b><br>" + descricao);
			$('#tipo-campo-detalhes').html(tipoCampoDescricao);
		}
	}


	/*CAMPOS FORMULARIO DINAMICO*/
	$('.salvar-modelo-campo').live('click', function () {
		if (validarCamposGenerico("#dados-campo-modelo .required")){
			caminho = caminhoScript+"/modulos/administrativo/formularios-campos-salvar.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					if ($('#tipo').val()=='direto'){
						parent.carregarCamposSelecaoFormulario('',retorno.trim());
						parent.$.fancybox.close();
					}
					else{
						$('#campo-id').val(retorno.trim());
						$('#frmDefault').submit();
					}
				}
			});
		}
	});

	$('#tipo-campo-modelo').live('change', function () {
		carregarOpcoesCampo();
	});

	$('#quantidade-opcoes-multiplas').live('change', function () {
		carregarOpcoesDisponiveis();
	});

	function carregarOpcoesDisponiveis(){
		caminho = caminhoScript+"/modulos/administrativo/formularios-dinamicos-carregar-opcoes-campo.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$('#bloco-opcoes-diponiveis').html(retorno);
			}
		});
	}

	function carregarOpcoesCampo(){
		tipoCampo = $('#tipo-campo-modelo').val();
		$('.bloco-multiplas-opcoes').hide();
		if ((tipoCampo=='select')||(tipoCampo=='radio') || (tipoCampo=='checkbox')){
			$('.bloco-multiplas-opcoes').show();
			carregarOpcoesDisponiveis();
		}
		else{
			$('#bloco-opcoes-diponiveis').html('');
		}
	}

	$('.btn-incluir-modelo-campo').live('click', function () {
		dados = $('#frmDefault').serialize();
		caminho = caminhoScript+"/administrativo/formularios/formularios-campos-gerenciar?tipo=direto&"+dados;
		$.fancybox.open({
			href : caminho,
			type : 'iframe',
			width: '90%',
			padding : 2
		});
	});
	/**/

/*
	$('#cancelar-incluir-modelo-campo').live('click', function () {
		$('#campo-formulario-dinamico').val('').trigger('chosen:updated');
		$('.bloco-campos-disponiveis, .salvar-formulario-dinamico').delay("fast").fadeIn();;
		$('.dados-campo-modelo').hide();
	});

	$('#incluir-modelo-campo').live('click', function () {
		if (validarCamposGenerico("#dados-campo-modelo .required")){
			caminho = caminhoScript+"/modulos/administrativo/formularios-campos-salvar.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#incluir-campo-formulario').click();
					setTimeout(function(){ $('#campo-formulario-dinamico').val(retorno.trim()).trigger('chosen:updated'); carregarBlocoInclusaoProduto(''); }, 500);
				}
			});
		}
	});
	*/



/********* Inicio Funções Formularios ***********/
/*
	$('#formularios-disponiveis').live('change', function () {
		if($(this).val()==-1){
			$("#seleciona-formulario").hide();
			$("#novo-formulario").show();
			$(".dados-secundarios").hide();
		}else{
			$("#id-formulario").val($(this).val());
			$("#frmDefault").attr("action","./formularios-dinamicos");
			$("#frmDefault").submit();
		}
	});
	$('#cancela-formulario').live('click', function () {
		$("#seleciona-formulario").show();
		$("#novo-formulario").hide();
		$("#formularios-disponiveis").val('');
	});
	$('#nome-formulario').live('keyup', function () {
		if($('#nome-formulario').val()!='')$("#nome-formulario").css('background-color', '').css('outline', '');
	});
	$('#cadastra-formulario').live('click', function () {
		if($('#nome-formulario').val()==''){
			$("#nome-formulario").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		}else{
			caminho = caminhoScript+"/modulos/administrativo/cadastra-novo-formulario.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#id-formulario").val(retorno);
					$("#frmDefault").attr("action","./formularios-dinamicos");
					$("#frmDefault").submit();
				}
			});
		}
	});
	$('#exclui-formulario').live('click', function () {
		caminho = caminhoScript+"/modulos/administrativo/exclui-formulario.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#id-formulario").val(retorno);
				$("#frmDefault").attr("action","./formularios-dinamicos");
				$("#frmDefault").submit();
			}
		});
	});
	$('#salvar-formulario').live('click', function () {
		if($('#nome-formulario-edita').val()=='')$("#nome-formulario-edita").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		else{
			caminho = caminhoScript+"/modulos/administrativo/atualiza-formulario.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$("#frmDefault").attr("action","./formularios-dinamicos");
					$("#frmDefault").submit();
				}
			});
		}
	});

*/


/********* Final Funções Formularios ***********/





/********* Inicio Funções Configuração Empresa e/ou NFE ***********/
$(document).ready(function(){
	if ($('#select-empresa').val()=='-1') $('#texto-cadastro-localiza-empresa-selecionada').focus();
	$('#select-empresa').live('change', function () {
		//$("#frmDefault").attr("action",paginaAtual);
		$("#frmDefault").attr("action",caminhoScript+'/administrativo/gerenciar-empresas');
		$("#frmDefault").submit();
	});
	$('.salvar-empresa').live('click', function () {
		destino = $(this).attr('destino');
		$("#texto-cadastro-localiza-empresa-selecionada").css('background-color', '').css('outline', '');
		$("#texto-cadastro-localiza-empresa-selecionada").css('background-color', '').css('outline', '');
		flag = 0;
		if (($('#empresa-selecionada').val()=="")||($('#empresa-selecionada').val()=="0")) {
			$("#texto-cadastro-localiza-empresa-selecionada").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;
		}
		if(flag==0){
			gravaEmpresa(destino);
		}
	});

	$('#exclui-empresa').live('click', function () {
		destino = $(this).attr('destino');
		if ($('#select-empresa').val()!=""){
			$('#empresa-selecionada').val("D");
			gravaEmpresa(destino);
		}
	});
});

function gravaEmpresa(destino){
	if (destino!="") complementoCaminho = "#"+destino;
	caminho = caminhoScript+"/modulos/administrativo/gerenciar-empresas-grava.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			if ($('#select-empresa').val()=="-1")$('#empresa-id-nova').val($('#empresa-selecionada').val());
			if ($('#empresa-selecionada').val()=="D") $('#select-empresa').val("");
			//$("#div-retorno").html(retorno);
			//$("#div-retorno").show();
			alert(retorno);
			$("#frmDefault").attr("action",caminhoScript+"/administrativo/gerenciar-empresas"+complementoCaminho);
			$("#frmDefault").submit();
		}
	});

}


/****************/
/* FUNCOES CD*/
$(".cd-inc-alt").live('click', function () {
	$(".cd-inc-alt").hide();
	$("#listagem-cds").hide();
	$("#cd-id").val($(this).attr('cd-id'));
	caminho = caminhoScript+"/modulos/administrativo/gerenciar-empresas-cds-carregar.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#incluir-editar-cd').html(retorno);
			$("#form-incluir-alterar-cd").show();
		}
	});
});

$(".cd-cancelar").live('click', function () {
	$(".cd-inc-alt").show();
	$("#listagem-cds").show();
	$("#form-incluir-alterar-cd").html("").hide();
});

$(".cd-salvar").live('click', function () {
	if (validarCamposGenerico("#form-incluir-alterar-cd .required")){
		caminho = caminhoScript+"/modulos/administrativo/gerenciar-empresas-cds-salvar.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#listagem-cds").html(retorno);
				$("#listagem-cds").show();
				$("#form-incluir-alterar-cd").html("").hide();
				$(".cd-inc-alt").show();
			}
		});
	}
});
$(".cd-inc-exc").live('click', function () {
	if (confirm("Tem certeza que deseja excluir o CD?")){
		$("#cd-id").val($(this).attr('cd-id'));
		caminho = caminhoScript+"/modulos/administrativo/gerenciar-empresas-cds-salvar.php?acao=D";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#listagem-cds").html(retorno);
				$(".cd-inc-alt").show();
			}
		});
	}
});

/********* Final Funções Configuração Empresa e/ou NFE ***********/

/********* Inicio Funções Configuração de LAYOUT ***********/
$(document).ready(function(){
	$('.btn-upload-layout').live('click', function () {
		$("#"+$(this).attr("for")).click();
	});
});





$(document).ready(function(){
	if (slugPagina=='gerenciar-usuarios'){
		$(".incluir-novo-usuario").live('click', function () {
			$(".conteudo-interno-usuario, #div-localiza-cadastro-cadastro-usuario-id, #div-campos-consulta-cadastro-usuario-id").show();
		});

		$(".cadastro-localiza, .btn-incluir-novo-cadastro-auxiliar").live('click', function () {
			cadastroID = "";
			if ($(this).attr("cadastro-id").length){
				cadastroID = $(this).attr("cadastro-id")
			}
			abrirTelaUsuario(cadastroID);
		});


		$(".seleciona-cadastro-geral").live('click', function () {
			$('#div-cadastro-cadastro-usuario-id').hide();
			abrirTelaUsuario($(this).attr("cadastro-id"));
		});

		$('.btn-incluir-novo-cadastro').addClass('btn-incluir-novo-cadastro-auxiliar');
		$('.btn-incluir-novo-cadastro').attr('cadastro-id','');
		$('.btn-incluir-novo-cadastro').removeClass('btn-incluir-novo-cadastro');

		function abrirTelaUsuario(cadastroID){
			caminho = caminhoScript+"/cadastros/cadastro-dados?tipo-fluxo=direto&editar-acessos=1&cadastroID="+cadastroID;
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				afterClose : function(){
					$('#frmDefault').submit();
				}
			});
		}
	}
});
