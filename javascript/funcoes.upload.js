/********* Inicio Funções Documentos ***********/
$(document).ready(function(){
	slugPagina = $("#slug-pagina").val();
	$('.btn-editar-documento-anexo').live('click', function () {
		$("#div-documentos-cadastrados").hide();
		$("#div-documentos-edicao").html("<p align='center' style='width:100%'>Aguarde carregando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
		$("#botao-cancelar-documento-edita").show();
		$("#botao-atualiza-documento-anexo").show();
		$("#botao-novo-documento").hide();
		$("#div-documentos-edicao").load(caminhoScript+"/modulos/administrativo/documentos-edita-anexo.php?anexo-id="+$(this).attr('anexo-id')+"&origem-documento="+$("#origem-documento").val());
	});

	$("#botao-novo-documento").live('click', function () {
		carregarCadastroDocumento('',$("#origem-documento").val());
	});
	$("#botao-cancelar-documento").live('click', function () {
		carregarDocumentos($("#id-referencia").val(),$("#origem-documento").val());
	});
	$("#botao-cancelar-documento-edita").live('click', function () {
		$("#div-documentos-edicao").html('');
		carregarDocumentos($("#id-referencia").val(),$("#origem-documento").val());
	});

	$("#botao-novo-documento-inclui").live('click', function () {
		$("#botao-cancelar-documento, #botao-novo-documento-inclui").hide();
		flag = true;
		if ($("#origem-documento").val()=="orcamentos"){
			//if ($('#agrupar-produtos').length){
				//if ($('#agrupar-produtos').val()!='agrupar-por-cliente'){
					flag = validarCamposGenerico("#id-referencia-secundario");
				//}
			//}
		}
		if (flag){
			$("#div-mensagem").html("<p align='center' style='width:100%'>Aguarde gerando documento</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
			$("#nome-arquivo").val($("#tipo-documento-id option:selected").attr('nome-arquivo'));
			$("#div-cadastro-documentos").hide();
			$("#botao-cancelar-documento").hide();

			/* inicio trecho para depurar*/
			/*
			$("#frmDefault").attr("action", caminhoScript+"/modulos/administrativo/documentos-gera-arquivo-pdf.php");
			$("#frmDefault").attr("target", "_blank");
			$("#frmDefault").submit();
			*/
			/*fim trecho para depurar */
			//alert($("#frmDefault #div-documentos :input").serialize());
			$.ajax({type: "POST",url: caminhoScript+"/modulos/administrativo/documentos-gera-arquivo-pdf.php", data: $("#frmDefault #div-documentos :input").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					carregarDocumentos($("#id-referencia").val(),$("#origem-documento").val());
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert("ERRO AO GERAR DOCUMENTO \nStatus: " + textStatus + "\nErro: " + errorThrown);
				}
			});
		}
		else{
			$("#botao-cancelar-documento, #botao-novo-documento-inclui").show();
		}
	});
/*
	$("#botao-novo-documento-inclui").live('click', function () {
		$("#nome-arquivo").val($("#tipo-documento-id option:selected").attr('nome-arquivo'));
		$("#div-cadastro-documentos").hide();
		$("#botao-cancelar-documento").hide();
		$("#div-mensagem").html("<p align='center'><b>AGUARDE GERANDO DOCUMENTO...</b></p>");
		$.ajax({type: "POST",url: caminhoScript+"/modulos/administrativo/documentos-gera-arquivo-pdf.php", data: $("#frmDefault #div-documentos :input").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				carregarDocumentos($("#id-referencia").val(),$("#origem-documento").val());
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("ERRO AO GERAR DOCUMENTO \nStatus: " + textStatus + "\nErro: " + errorThrown);
			}
		});
	});
*/

	$("#tipo-documento-id").live('change', function () {
		acertaTamanhosDocumentos();
	});
	$("#botao-selecione-arquivo").live('click', function () {
		$("#pickfiles").click();
	});


	$(".link-ver-html").live('click', function () {
		anexoID = $(this).attr('anexo-id');
		caminho = caminhoScript+"/modulos/administrativo/documentos-vizualizar-anexo.php?anexo-id="+anexoID;
		$.fancybox.open({
			href : caminho,
			type : 'iframe',
			width: '90%',
			padding : 2
		});
	});




	//alert(slugPagina);
	// EXCESSÃO NO CADASTRO DE IMAGENS
	if (slugPagina=="produtos-cadastrar"){
		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash,silverlight,html4',
			browse_button : 'pickfiles',
			container: document.getElementById('frmDefault'),
			url : caminhoScript+'/includes/plupload.php',
			multi_selection : true,
			multipart : true,
			multipart_params : {idReferencia : $('#id-referencia').val()},
			multipart_params : {origemDocumento : $('#origem-documento').val()},
			multipart_params : {usuarioID : $('#usuario-cadastro-documento-id').val()},
			filters : {
				max_file_size : '5mb',
				//Determina tipos de arquivos aceitos na hora da seleção de arquivos (explorer)
				mime_types: [
					{title : "Image files", extensions : "jpg,png"}
				]
			},
			init: {
				PostInit: function() {
					$('#filelist').html('');
					$("#uploadfiles").live('click', function () {
						uploader.settings.multipart_params["idReferencia"] = $('#id-referencia').val();
						uploader.settings.multipart_params["origemDocumento"] = $('#origem-documento').val();
						uploader.settings.multipart_params["usuarioID"] = $('#usuario-cadastro-documento-id').val();
						uploader.start();
						return false;
					});
				},
				FilesAdded: function(up, files) {
					html = "<table width='100%' style='float:left;margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>";
					html += "	<tr><td class='tabela-fundo-escuro-titulo' width='70%'>Upload de Arquivos</td>";
					html += "		<td class='tabela-fundo-escuro-titulo' width='15%' align='center'>Tamanho</td>";
					html += "		<td class='tabela-fundo-escuro-titulo' width='15%' align='center'>Porcentagem Upload</td></tr>";
					plupload.each(files, function(file) {
						html += "	<tr><td class='tabela-fundo-claro'>" + file.name + "</td>";
						html += "		<td class='tabela-fundo-claro' align='center'>" + plupload.formatSize(file.size) + "</td>";
						html += "		<td class='tabela-fundo-claro' align='center'><div id='" + file.id + "'><b></b></div></td></tr>";
					});
					html += "</table>";
					$('#filelist').append(html);
					$("#uploadfiles").click();
				},
				UploadProgress: function(up, file) {
					document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
				},
				UploadComplete: function(up, files) {
					//alert($('#id-referencia').val());
					carregarDocumentos($('#id-referencia').val(),$('#origem-documento').val());
					carregarVariacoes(idReferencia);
				},
				Error: function(up, err) {
					if(err.code == -600){
						$('#console').html("<p style='color:red;'>Erro: Tamanho do arquivo acima do permitido (Tamanho Máximo: 5mb)</p>");
					}
					else if(err.code == -601){
						$('#console').html("<p style='color:red;'>Erro: Tipo de arquivo não permitido (Permitidos: jpg, png)</p>");
					}
					else{
						$('#console').html("<p style='color:red;'>Erro: " + err.code + ": " + err.message+"</p>");
					}
				}
			}
		});
		uploader.init();
	}
	else{
		if (slugPagina=="cadastro-importar"){
			var uploader = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'pickfiles',
				container: document.getElementById('frmDefault'),
				url : caminhoScript+'/includes/plupload.php',
				multi_selection : true,
				multipart : true,
				multipart_params : {idReferencia : $('#id-referencia').val()},
				multipart_params : {origemDocumento : $('#origem-documento').val()},
				multipart_params : {usuarioID : $('#usuario-cadastro-documento-id').val()},
				filters : {
					max_file_size : '55mb',
					//Determina tipos de arquivos aceitos na hora da seleção de arquivos (explorer)
					mime_types: [
						{title : "Arquivos csv", extensions : "csv"},
					]
				},
				init: {
					PostInit: function() {
						$('#filelist').html('');
						$("#uploadfiles").live('click', function () {
							uploader.settings.multipart_params["idReferencia"] = $('#id-referencia').val();
							uploader.settings.multipart_params["origemDocumento"] = $('#origem-documento').val();
							uploader.settings.multipart_params["usuarioID"] = $('#usuario-cadastro-documento-id').val();
							uploader.start();
							return false;
						});
					},
					FilesAdded: function(up, files) {
						html = "<table width='100%' style='float:left;margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>";
						html += "	<tr><td class='tabela-fundo-escuro-titulo' width='70%'>Upload de Arquivos</td>";
						html += "		<td class='tabela-fundo-escuro-titulo' width='15%' align='center'>Tamanho</td>";
						html += "		<td class='tabela-fundo-escuro-titulo' width='15%' align='center'>Porcentagem Upload</td></tr>";
						plupload.each(files, function(file) {
							html += "	<tr><td class='tabela-fundo-claro'>" + file.name + "</td>";
							html += "		<td class='tabela-fundo-claro' align='center'>" + plupload.formatSize(file.size) + "</td>";
							html += "		<td class='tabela-fundo-claro' align='center'><div id='" + file.id + "'><b></b></div></td></tr>";
						});
						html += "</table>";
						$('#filelist').append(html);
						$("#uploadfiles").click();
					},
					UploadProgress: function(up, file) {
						document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
					},
					UploadComplete: function(up, files) {
						//alert($('#id-referencia').val());
						carregarDocumentos($('#id-referencia').val(),$('#origem-documento').val());
						carregarVariacoes(idReferencia);
					},
					Error: function(up, err) {
						if(err.code == -600){
							$('#console').html("<p style='color:red;'>Erro: Tamanho do arquivo acima do permitido (Tamanho Máximo: 20mb)</p>");
						}
						else if(err.code == -601){
							$('#console').html("<p style='color:red;'>Erro: Tipo de arquivo não permitido (Permitidos: .csv)</p>");
						}
						else{
							$('#console').html("<p style='color:red;'>Erro: " + err.code + ": " + err.message+"</p>");
						}
					}
				}
			});
			uploader.init();
		}
		else{
			var uploader = new plupload.Uploader({
				runtimes : 'html5,flash,silverlight,html4',
				browse_button : 'pickfiles',
				container: document.getElementById('frmDefault'),
				url : caminhoScript+'/includes/plupload.php',
				multi_selection : true,
				multipart : true,
				multipart_params : {idReferencia : $('#id-referencia').val()},
				multipart_params : {origemDocumento : $('#origem-documento').val()},
				multipart_params : {usuarioID : $('#usuario-cadastro-documento-id').val()},
				filters : {
					max_file_size : '55mb',
					/* //Determina tipos de arquivos aceitos na hora da seleção de arquivos (explorer)
					mime_types: [
						{title : "Image files", extensions : "jpg,gif,png"},
						{title : "Zip files", extensions : "zip"}
					]
					*/
				},
				init: {
					PostInit: function() {
						$('#filelist').html('');
						$("#uploadfiles").live('click', function () {
							uploader.settings.multipart_params["idReferencia"] = $('#id-referencia').val();
							uploader.settings.multipart_params["origemDocumento"] = $('#origem-documento').val();
							uploader.settings.multipart_params["usuarioID"] = $('#usuario-cadastro-documento-id').val();
							uploader.start();
							return false;
						});
					},
					FilesAdded: function(up, files) {
						html = "<table width='100%' style='float:left;margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>";
						html += "	<tr><td class='tabela-fundo-escuro-titulo' width='70%'>Upload de Arquivos</td>";
						html += "		<td class='tabela-fundo-escuro-titulo' width='15%' align='center'>Tamanho</td>";
						html += "		<td class='tabela-fundo-escuro-titulo' width='15%' align='center'>Porcentagem Upload</td></tr>";
						plupload.each(files, function(file) {
							html += "	<tr><td class='tabela-fundo-claro'>" + file.name + "</td>";
							html += "		<td class='tabela-fundo-claro' align='center'>" + plupload.formatSize(file.size) + "</td>";
							html += "		<td class='tabela-fundo-claro' align='center'><div id='" + file.id + "'><b></b></div></td></tr>";
						});
						html += "</table>";
						$('#filelist').append(html);
						$("#uploadfiles").click();
					},
					UploadProgress: function(up, file) {
						restante = eval(file.percent) - 100;
						document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = "<div Style='width:98%;float:left;border-radius:4px;background-image: linear-gradient( to right, #E5ECF1 "+file.percent+"%, #ffffff "+restante+"%);padding-left:5px;'><span>" + file.percent + "%</span></div>";
					},
					UploadComplete: function(up, files) {
						carregarDocumentos($('#id-referencia').val(),$('#origem-documento').val());
					},
					Error: function(up, err) {
						$('#console').html("Erro: " + err.code + ": " + err.message);
					}
				}
			});
			uploader.init();
		}
	}

	$("#botao-atualiza-documento-anexo").live('click', function () {
		aleatorio = $('#detalhes-documento-aleatorio').val();
		$("#detalhes-documento-" + aleatorio).val(tinyMCE.get('detalhes-documento-' + aleatorio).getContent());
		$.ajax({type: "POST",url: caminhoScript+"/modulos/administrativo/documentos-edita-arquivo-pdf.php", data: $("#frmDefault #div-documentos :input").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			//$("#div-cadastro-documentos").html('');
			//$("#div-documentos-edicao").html('');
			//$("#div-retorno").html(retorno);
			carregarDocumentos($('#id-referencia').val(),$('#origem-documento').val());
		}});
	});

	$(".btn-excluir-documento").live('click', function () {
		excluirCadastroDocumento($(this).attr("anexo-id"),$(this).attr("nome-anexo"));
	});
});

function excluirCadastroDocumento(idAnexo, nomeAnexo){
	var confirma=confirm("Tem certeza que deseja excluir?");
	if (confirma==true){
		retorno = $.ajax(caminhoScript+"/modulos/administrativo/documentos-excluir-anexo.php?&idAnexo="+idAnexo+"&nomeAnexo="+nomeAnexo)
		.done(function(){
			carregarDocumentos($("#id-referencia").val(),$("#origem-documento").val());
		});
	}
}

function carregarCadastroDocumento(){
	$("#botao-novo-documento, .btn-editar-documento-anexo, .btn-excluir-documento").hide();
	caminho = caminhoScript+"/modulos/administrativo/documentos-carregar-cadastro-documento.php?origem="+$("#origem-documento").val()+"&id="+$("#id-referencia").val();
	$.ajax({type: "POST",url: caminho, contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-cadastro-documentos").html(retorno);
			acertaTamanhosDocumentos();
		}
	});
}

function acertaTamanhosDocumentos(){
	if($('#tipo-documento-id').val()==-1){
		$("#botao-selecione-arquivo").show();
		$("#botao-novo-documento-inclui").hide();

	}else{
		$("#botao-selecione-arquivo").hide();
		$("#botao-novo-documento-inclui").show();
	}
}

function carregarDocumentos(id, origem){
	$("#div-documentos").html("<p align='center' style='width:100%'>Aguarde carregando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
	caminho = caminhoScript+"/modulos/administrativo/documentos-carregar-documentos.php?id="+id+"&origem="+origem;
	$.ajax({type: "POST", url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-documentos").html(retorno);
		}
	});
}



$(".botao-enviar-email-geral, .arquivo-anexo-envia-email").live('click', function () {
	$(".div-enviar-email-anexos").show();
	$(".botao-enviar-email-geral").hide();
	emails = "";
	$(".email-workflow").each(function(){
		if (validaEmailString($(this).attr('value'))){
		    emails = emails + $(this).attr('value') + "; ";
		}
	});
	$("#emails-email-enviar-geral").val(emails);
});

$(".botao-cancelar-enviar-email-geral").live('click', function () {
	$(".div-enviar-email-anexos").hide();
	$(".botao-enviar-email-geral").show();
});

$(".botao-enviar-email-geral-submete").live('click', function () {
	if(validarCamposGenerico("#div-documentos .required")){
		$(".div-enviar-email-anexos").hide();
		$(".div-aguarde-email-documento").html("<p align='center' style='width:100%'>Aguarde carregando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>").show();
		aleatorio = $('#editor-aleatorio').val();
		$("#texto-email-enviar-geral-" + aleatorio).val(tinyMCE.get('texto-email-enviar-geral-' + aleatorio).getContent());
		caminho = caminhoScript+"/modulos/administrativo/documentos-enviar-email.php";
		$.ajax({type: "POST", url: caminho, dataType: "html", data: $("#frmDefault #div-documentos :input").serialize(), contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$(".div-aguarde-email-documento").hide();
				$(".div-enviar-email-anexos").show().append(retorno);
				alert(retorno);
				carregarDocumentos($("#id-referencia").val(),$("#origem-documento").val());
			}
		});
	}
});

$(".modelo-email-id").live('change', function () {
	$.ajax({type: "POST",url: caminhoScript+"/modulos/administrativo/documentos-gera-arquivo-pdf.php?email=true", data: $("#frmDefault #div-documentos :input").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			tinyMCE.activeEditor.setContent(retorno);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert("ERRO AO GERAR MODELO DE EMAIL \nStatus: " + textStatus + "\nErro: " + errorThrown);
		}
	});
});



/********* Fim Funções Documentos ***********



/********* FUNÇÕES PARA IMPORTAÇÃO DE LISTAS ***********/
$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");
	slugPagina = $("#slug-pagina").val();



	if ((slugPagina=="cadastro-importar") || (slugPagina=="produtos-importar")){

		/* CADASTRO IMPORTAÇÃO EXPORTAÇÃO */
		if (slugPagina=="cadastro-importar"){
			$("#menu-superior-importar").live('click', function () {
				carregarDocumentos(0,'modulos_listas_cadastros');
			});
		}
		/* PRODUTOS IMPORTAÇÃO EXPORTAÇÃO */
		if (slugPagina=="produtos-importar"){
			$("#menu-superior-importar").live('click', function () {
				carregarDocumentos(0,'modulos_listas_produtos');
			});
		}

		$('.arquivo-importar-base').live('click', function () {
			if ($(this).is(":checked")){
				$("#div-exibir-listagem-importacao").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
				caminho = caminhoScript+"/modulos/administrativo/documentos-importacao-carregar-arquivo.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#div-exibir-listagem-importacao').html(retorno);
					}
				});
			}
			else{
				$('#div-exibir-listagem-importacao').html('');
			}
		});



		$('.campo-coluna').live('change', function () {
			//$(".campo-coluna option[value='"+$(this).val()+"']").attr('disabled',false);
			if ($(this).val()=='Cpf_Cnpj'){
				recarregarTabelaExibindoDuplicados();
			}
		});
		$('.rdCobImport').live('change', function () {
			recarregarTabelaExibindoDuplicados();
		});




		function recarregarTabelaExibindoDuplicados(){
			campos = $("form").serialize();
			$("#bloco-tabela-temporaria").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
			caminho = caminhoScript+"/modulos/administrativo/documentos-importar-recarregar-tabela.php";
			$.ajax({type: "POST",url: caminho, data: campos, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#bloco-tabela-temporaria').html(retorno);
				}
			});
		}
		$('.importar-arquivo-sistema').live('click', function () {
			if ($('#vinculo-cadastro-id').val()!='')
				$('#tipo-vinculo-id').addClass('required');
			else
				$('#tipo-vinculo-id').removeClass('required');

			$('#aviso-importacao').html("");
			if ($('#origem-documento').val()=="modulos_listas_cadastros"){
				flagNome = false;
				flagCpfCnpj = false;
				$(".campo-coluna").each(function(){
					if ($(this).val()=='Nome')
						flagNome = true;
					if ($(this).val()=='Cpf_Cnpj')
						flagCpfCnpj = true;
				});
				if ((flagNome==false) || (flagCpfCnpj==false)){
					$('#aviso-importacao').html("&Eacute; necess&aacute;rio especificar ao menos as colunas 'NOME OU RAZ&Atilde;O SOCIAL' e 'CPF OU CNPJ' &nbsp;&nbsp;&nbsp;");
					validarCamposGenerico('#div-exibir-listagem-importacao .required');
				}
				if ((flagNome) && (flagCpfCnpj) && (validarCamposGenerico('#div-exibir-listagem-importacao .required'))){
					submeterImportacaoLista();
				}
			}
			if ($('#origem-documento').val()=="modulos_listas_produtos"){
				if (validarCamposGenerico('#div-exibir-listagem-importacao .required')){
					submeterImportacaoLista();
				}
			}
		});

		function submeterImportacaoLista(){
			campos = $("form").serialize();
			$("#div-exibir-listagem-importacao").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
			caminho = caminhoScript+"/modulos/administrativo/documentos-importar-arquivo-lista.php";
			$.ajax({type: "POST",url: caminho, data: campos, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//console.log(retorno);
					alert('Listagem Incluída');
					$('#frmDefault').submit();
					//$("#div-exibir-listagem-importacao").html(retorno);
				}
			});
		}

		$('.m-sim').live('click', function () {
			$('.at-s').attr('checked', true);
		});
		$('.m-nao').live('click', function () {
			$('.at-n').attr('checked', true);
		});

		$('.incluir-linha').live('click', function () {
			if ($(this).attr('checked'))
				statusLinha = 1;
			else
				statusLinha = 0;
			linha = $(this).val();
			caminho = caminhoScript+"/modulos/administrativo/documentos-importacao-atualizar-linha.php?linha="+linha+"&statusLinha="+statusLinha;
			$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//alert(retorno);
				}
			});
		});
	}
});