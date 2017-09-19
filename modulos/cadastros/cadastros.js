/********* Inicio Funções Tela de Localizar Cadastros ***********/

$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");
	heaPagina = $("#slug-pagina").val();
	if (slugPagina=="cadastro-localiza"){
		$("#localiza-nome-completo").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#botao-pesquisar-cadastros").click(); }});
		$(".localiza-mascara-cpf").mask("?999.999.999-99");
		$(".localiza-mascara-cnpj").mask("?99.999.999/9999-99");
		$("#botao-pesquisar-cadastros").live('click', function () {
			//$("#frmDefault").attr("action",paginaAtual);
			$("#frmDefault").submit();
		});
	}
	/*
	$(".localiza-mascara-cpf").live('click', function () {
		$(this).select();
	});
	*/
});
/********* Fim Funções Tela de Localizar Cadastros ***********/

/********* Inicio Funções Tela Relatorio Aniversariantes ***********/
$(document).ready(function(){
	if (slugPagina=="cadastro-relatorio-aniversariantes"){
		$("#botao-pesquisar-aniver").live('click', function () {
			$("#frmDefault").attr("action",paginaAtual);
			$("#frmDefault").attr("target","_top");
			$("#frmDefault").submit();
		});
		/*
		$("#botao-salvar-excel").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-excel-generico-session.php");
			$("#frmDefault").submit();
		});
		$("#botao-imprimir").live('click', function () {
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/gerar-impressao-generico-session.php");
			$("#frmDefault").attr("target","_blank");
			$("#frmDefault").submit();
		});
		*/
	}
});
/********* Fim Funções Tela Relatorio Aniversariantes ***********/


/********* Inicio Funções Tela Relatorio Geral Dinamico ***********/
$(document).ready(function(){
	if (slugPagina=="cadastro-relatorio-geral"){
		$("#botao-pesquisar-cadastros").live('click', function () {
			$("#frmDefault").attr("action",paginaAtual);
			$("#frmDefault").attr("target","_top");
			$("#frmDefault").submit();
		});

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
});
/********* Fim Funções Tela Relatorio Geral Dinamico ***********/



/********* Inicio Funções Tela de Cadastro ***********/
$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");
	slugPagina = $("#slug-pagina").val();
	if (slugPagina=="cadastro-dados"){
		if(($(".radio-tipo-grupo-8:checked").val())=="24") $("#nome-completo").focus();
		if(($(".radio-tipo-grupo-8:checked").val())=="25") $("#razao-social").focus();
		exibirTipoPessoa();
		/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
		$("#menu-superior-2").live('click', function () {
			carregarDocumentos($("#cadastroID").val(),'cadastros');
		});
		$("#ie-isento").live('change', function () {
			if ($(this).is(":checked")) $("#inscricao-estadual").val("ISENTO"); else $("#inscricao-estadual").val("");
		});
		$("#inscricao-estadual").live('blur', function () {
			if ($(this).val().toUpperCase()=="ISENTO"){ $("#inscricao-estadual").val("ISENTO"); $("#ie-isento").attr("checked",true); }
			else $("#ie-isento").attr("checked",false);
		});
		$("#im-isento").live('change', function () {
			if ($(this).is(":checked")) $("#inscricao-municipal").val("ISENTO"); else $("#inscricao-municipal").val("");
		});
		$("#inscricao-municipal").live('blur', function () {
			if ($(this).val().toUpperCase()=="ISENTO"){ $("#inscricao-municipal").val("ISENTO"); $("#im-isento").attr("checked",true); }
			else $("#im-isento").attr("checked",false);
		});
		$("#grupo-id").live('change', function () {
			exibirTipoPessoa();
		});


		$(".radio-tipo-grupo-8").live('click', function () {
			exibirTipoPessoa();
		});

		/* CADASTROS */
		$('#botao-cadastro-novo').live('click', function () {
			salvarCadastroDados();
		});

		/* ENDERECOS */
		$("#botao-novo-endereco").live('click', function () {
			$("#div-enderecos-cadastrados").html("");
			carregarCadastroEndereco('');
		});
		$('#botao-cancelar-endereco').live('click', function () {
			$("#div-cadastro-endereco").html("");
			carregarCadastroEnderecos($("#cadastroID").val());
		});

		$("#botao-salvar-endereco").live('click', function () {
			if ($("#cadastroID").val()==""){salvarCadastroDados();}
			else{ salvarCadastroEndereco();}
		});

		$(".btn-excluir-endereco").live('click', function () {
			excluirCadastroEndereco($(this).attr('cadastro-endereco-id'));
		});

		$(".btn-editar-endereco").live('click', function () {
			carregarCadastroEndereco($(this).attr('cadastro-endereco-id'));
		});

		/* TELEFONES */
		$("#botao-novo-telefone").live('click', function () {
			carregarCadastroTelefone('');
		});
		$('#botao-cancelar-telefone').live('click', function () {
			$("#div-cadastro-telefone").html("");
			carregarCadastroTelefones($("#cadastroID").val());
		});

		$("#botao-salvar-telefone").live('click', function () {
			if ($("#cadastroID").val()==""){
				salvarCadastroDados();
			}
			else{
				salvarCadastroTelefone();
			}
		});
		if ($('#cadastro-classificacao').length){
			$("#check-tipo-grupo-9").live('change', function () {
				flagCliente = false;
				$('#check-tipo-grupo-9 :selected').each(function(){
					if ($(this).val()=='153'){
						flagCliente = true;
					}
				});
				if (flagCliente){
					$('#cadastro-classificacao').show();
					/* se é cliente tem que ter gerente de conta */
					$("#check-tipo-grupo-12 option[value='101']").attr('selected',true).trigger('chosen:updated');
				}
				else{
					$('#cadastro-classificacao').hide();
					/* se é não e cliente não tem que ter gerente de conta */
					$("#check-tipo-grupo-12 option[value='101']").attr('selected',false).trigger('chosen:updated');
				}
			});
		}

		/* UPLOAD */
		 $('#arquivo-upload-cadastro').live('change', function(){
			extensao = $('#arquivo-upload-cadastro').val().substring( $('#arquivo-upload-cadastro').val().lastIndexOf(".")).toLowerCase();
			if ((extensao=='.png')||(extensao=='.jpg')){
				uploadArquivo();
			}else{
				alert("Arquivo Inválido");
				$('#arquivo-upload-cadastro').val("");
			}
		});
		$("#imagem-foto").live('click', function () {
			$("#arquivo-upload-cadastro").click();
		});


	/*
	$("#imagem-foto").live('dblclick', function () {
		$("#arquivo-upload-cadastro").click();
	});
	$("#imagem-foto").live('click', function () {
		//alert($(this).attr("src"));
		$.fancybox.open({
			href : $(this).attr("src"),
			type : 'iframe',
			padding : 2
		});
	});
	*/

		/* INICIO VINCULOS */
		$(".btn-vinculos").live('click', function () {
			$(".conteudo-interno-vinculos").html('');
			tipoVinculoID = $(this).attr('tipo-vinculo-id');
			carregarCadastroVinculo($("#cadastroID").val(),tipoVinculoID);
		});

		$(".botao-incluir-vinculo").live('click', function () {
			$(".div-vincular-usuarios").show();
		});

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

		/* FIM VINCULOS */

		$("#botao-excluir-cadastro").live('click', function () {
			situacaoID = $(this).attr('situacao');
			var mensagem;
			if (situacaoID=='1'){ mensagem = "Tem certeza que deseja re-ativar o cadastro?";}
			if (situacaoID=='2'){ mensagem = "Tem certeza que excluir em definitivo o cadastro?";}
			if (situacaoID=='3'){ mensagem = "Tem certeza que deseja enviar o cadastro para lixeira?";}
			var confirma=confirm(mensagem);
			if (confirma==true){
				retorno = $.ajax(caminhoScript+"/modulos/cadastros/cadastro-atualizar-situacao.php?cadastro-id="+$("#cadastro-id").val()+"&situacao-id="+situacaoID).done(function(){
					//$("#frmDefault").attr("action",caminhoScript+"/cadastros/cadastro-localiza");
					if (situacaoID=='2')
						open(caminhoScript+"/cadastros/cadastro-localiza", "_top");
					else
						$("#frmDefault").submit();
				});
			}
		});
		/*
		$(".mascara-cpf").live('blur', function () {
			if((soNumero($("#cpf").val()).length)==11){
				if(!(ValidarCPF($("#cpf").val()))){
					alert("CPF Inválido");
					$("#cpf").focus();
				}else{
					retorno = $.ajax(caminhoScript+"/modulos/cadastros/cadastro-validar-cadastro.php?campo="+$("#cpf").val())
					.done(function(){
						if ($.trim(retorno.responseText)>1){
							alert("Já existe um Cadastro com o CPF Informado");
							$("#cpf").focus();
						}
					});
				}
			}
		});
		$(".mascara-cnpj").live('blur', function () {
			if((soNumero($("#cnpj").val()).length)==14){
				if(!(ValidarCNPJ($("#cnpj").val()))){
					alert("CNPJ Inválido");
					$("#cnpj").focus();
				}else{
					retorno = $.ajax(caminhoScript+"/modulos/cadastros/cadastro-validar-cadastro.php?campo="+$("#cnpj").val())
					.done(function(){
						if ($.trim(retorno.responseText)>1){
							$("#cnpj").focus();
							alert("Já existe um Cadastro com o CNPJ Informado");
						}
					});
				}
			}
		});
		*/


		$(".valida-cadastro-documento").live('blur', function () {
			campo = $(this);
			erro = false;
			aux = "";
			if((soNumero(campo.val()).length)==11){
				if(ValidarCPF(campo.val())==false){
					erro = true;
					aux = "CPF";
				}
			}
			if((soNumero(campo.val()).length)==14){
				if(ValidarCNPJ(campo.val())==false){
					erro = true;
					aux = "CNPJ";
				}
			}
			if (erro){
				alert(aux + " Inválido");
				campo.focus();
			}
			else{
				caminho = caminhoScript+"/modulos/cadastros/cadastro-validar-duplicidade.php?campo="+soNumero(campo.val())+"&cadastro-id="+campo.attr("excessao");
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						if (retorno!=""){
							if (confirm("Ja existe(m) cadastro(s) com o mesmo "+aux+" cadastrados no sistema\nDeseja Visualizar?")){
								caminho = caminhoScript+"/modulos/cadastros/cadastro-listar-cadastros-ids.php?ids="+retorno+"&modulo=cadastros";
								$.fancybox.open({
									href : caminho,
									type : 'iframe',
									width: '90%',
									padding : 2
								});
							}
						}
					}
				});
			}
		});


	}

	$(".cadastro-lista").live('click', function () {
		cadastroID = $(this).attr("cadastro-id");
		parent.document.getElementById('cadastroID').value = cadastroID;
		parent.$.fancybox.close();
		parent.document.getElementById("frmDefault").submit();
	});
	$(".mascara-cep").live('blur', function () {
		if((soNumero($("#cep-endereco").val()).length)==8){
			localizaEndereco($("#cep-endereco").val(),'');
		}
	});

	/********* Inicio Funções Relatórios ***********/
	$("#botao-pesquisar-relatorio-regional").live('click', function () {
		$("#frmDefault").submit();
	});
	/********* Fim Funções Relatórios ***********/

	$(".workflow-localiza").live('click', function () {
		$("#workflow-id").val($(this).attr('workflow-id'));
		$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-cadastro-chamado");
		$("#frmDefault").submit();
	})

});


function carregarCadastrosCidade(cidade){
	caminho = caminhoScript+"/modulos/cadastros/carregar-cadastros-relatorio-regional.php?cidade="+cidade;
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#div-cadastros-listagem").html(retorno);
			$('html, body').animate({scrollTop: $('#div-cadastros-listagem').offset().top+500 }, 1000);
			//return false;
		}
	});
}


/********* Inicio Funções Cadastro ***********/

function exibirTipoPessoa(){
	if(($(".radio-tipo-grupo-8:checked").val())=="24"){
		$(".div-pf").show();
		$(".div-pj").hide();
	}
	if(($(".radio-tipo-grupo-8:checked").val())=="25"){
		$(".div-pj").show();
		$(".div-pf").hide();
	}

	if($("#grupo-id").val()==""){
		$(".div-acesso").hide();
	}else{
		$(".div-acesso").show();
	}
}

/********* Fim Funções Cadastro ***********/

/********* Inicio Funções Vinculos ***********/


function carregarCadastroVinculo(cadastroID,tipoVinculoID){
	$("#div-cadastro-vinculo-"+tipoVinculoID).load(caminhoScript+"/modulos/cadastros/carregar-vinculo.php?cadastroID="+cadastroID+"&tipoVinculoID="+tipoVinculoID,function(responseTxt,statusTxt,xhr){
		$(".div-vincular-usuarios").hide();
	});
}


/********* Fim Funções Vinculos ***********/

/********* Inicio Funções Endereço ***********/

function carregarCadastroEnderecos(id){
	$("#div-enderecos-cadastrados").load(caminhoScript+"/modulos/cadastros/carregar-enderecos.php?cadastroID="+id,function(responseTxt,statusTxt,xhr){
		$("#botao-novo-endereco").show();
		validaModulo();
	});
}

function carregarCadastroEndereco(id){
	$("#botao-novo-endereco").hide();
	$("#div-cadastro-endereco").load(caminhoScript+"/modulos/cadastros/carregar-endereco.php?enderecoID="+id,function(responseTxt,statusTxt,xhr){
		$("#div-enderecos-cadastrados").html("");
	});
}

/********* Fim Funções Endereço ***********/

/********* Inicio Funções Telefones ***********/

function carregarCadastroTelefones(id){
	$("#div-telefones-cadastrados").load(caminhoScript+"/modulos/cadastros/carregar-telefones.php?cadastroID="+id,function(responseTxt,statusTxt,xhr){
		$("#botao-novo-telefone").show();
		validaModulo();
	});
}

function carregarCadastroTelefone(id){
		$("#botao-novo-telefone").hide();
	$("#div-cadastro-telefone").load(caminhoScript+"/modulos/cadastros/carregar-telefone.php?telefoneID="+id,function(responseTxt,statusTxt,xhr){
		$("#div-telefones-cadastrados").html("");
	});
}

/********* Fim Funções Telefones ***********/


/********* Inicio Funções Localiza Cadastro ***********/
$(document).ready(function(){
	$(".cadastro-localiza").live('click', function () {
		$("#cadastroID").val($(this).attr("cadastro-id"));
		$("#frmDefault").attr("action",caminhoScript+"/cadastros/cadastro-dados");
		$("#frmDefault").submit();
	});
});

/********* Fim Funções Localiza Cadastro ***********/

/********* Fim Funções UPLOAD ***********/

function uploadArquivo(){
	var arquivo = "";
	$('#frmDefault').attr('action',caminhoScript+'/funcoes/upload-imagem.php');
	$('#frmDefault').attr('target','iframe-upload-cadastro');
	$('#frmDefault').attr('enctype','multipart/form-data');
	$('#frmDefault').attr('encoding','multipart/form-data');
	$('#frmDefault').submit();
	$("#div-foto").html("<img src='../../images/geral/ajax-loader.gif' Style='margin-top:15px;'><p Style='margin-top:10px;margin-left:5px;'>carregando</p>");
	$('#frmDefault').attr('action','');
	$('#frmDefault').attr('target','');
	$('#frmDefault').attr('enctype','');
	$('#frmDefault').attr('encoding','');
}

$(function(){
    $('#iframe-upload-cadastro').load(function(){
		arquivo = $("#iframe-upload-cadastro")[0].contentWindow.document.body.innerHTML;
		$("#div-foto").html("<img src='"+caminhoScript+"/uploads/"+arquivo+"' width='100%' id='imagem-foto' Style='cursor:pointer'><input type='hidden' name='arquivo-imagem' id='arquivo-imagem' value='"+arquivo+"'>");
		salvarCadastroDados('atualizaFoto');
    });
});

/*
$("#iframe-upload-cadastro").ready(function(){
	alert('Carregou');
});
*/
/********* Inicio Funções de Cadastros BD***********/
function salvarCadastroDados(acaoExtra){
	$('#botao-cadastro-novo').hide();
	flag = 0;
	$("#nome-completo").css('background-color', '').css('outline', '');
	$("#razao-social").css('background-color', '').css('outline', '');
	$(".radio-tipo-grupo-8").css('background-color', '').css('outline', '');
	$("#cadastro-email").css('background-color', '').css('outline', '');
	$("#cadastro-senha").css('background-color', '').css('outline', '');
/*
	if($("#grupo-id").val()!=""){
		$("#cadastro-email").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		$("#cadastro-senha").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		flag=1;
	}
*/
	if ($('.radio-tipo-grupo-8').val()==""){$(".radio-tipo-grupo-8").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if(($(".radio-tipo-grupo-8:checked").val())=="24"){
		if ($('#nome-completo').val()==""){ $("#nome-completo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}
	if(($(".radio-tipo-grupo-8:checked").val())=="25"){
		if ($('#razao-social').val()==""){ $("#razao-social").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	}
	if ($("#tipo-endereco-id").length){
		flag = validarCamposGenerico("#div-cadastros-enderecos .required");
		//if (validarEndereco()==false) flag = 1;
	}
	if ($("#tipo-telefone-id").length){
		flag = validarCamposGenerico("#div-cadastros-telefones .required");
		//if (validarTelefone()==false) flag = 1;
	}
	if (flag==0){
		$('#botao-cadastro-novo').hide();
		if ($("#cadastro-id").val()=="")
			caminho = caminhoScript+"/modulos/cadastros/cadastro-insert.php?acaoExtra="+acaoExtra;
		else
			caminho = caminhoScript+"/modulos/cadastros/cadastro-update.php?acaoExtra="+acaoExtra;

		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				retorno = retorno.trim();
				$("#cadastro-id").val(retorno);
				if ($("#tipo-endereco-id").length){salvarCadastroEndereco();}
				if ($("#tipo-telefone-id").length){salvarCadastroTelefone();}
				$("#cadastroID").val(retorno);
				$("#frmDefault").submit();
			}
		});
	}
	else{
		$('#botao-cadastro-novo').show();
	}
}

function salvarCadastroEndereco(){
	if ($("#cadastro-endereco-id").val()=="")
		caminho = caminhoScript+"/modulos/cadastros/cadastro-endereco-insert.php";
	else
		caminho = caminhoScript+"/modulos/cadastros/cadastro-endereco-update.php";

	if (validarCamposGenerico("#div-cadastros-enderecos .required")){
	//if (validarEndereco()){
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#div-cadastro-endereco").html("");
				carregarCadastroEnderecos($("#cadastroID").val());
			}
		});
	}
}

/*
function validarEndereco(){
	$("#tipo-endereco-id").css('background-color', '').css('outline', '');
	$("#logradouro-endereco").css('background-color', '').css('outline', '');
	if ($('#tipo-endereco-id :selected').val()==""){ $("#tipo-endereco-id").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if ($('#logradouro-endereco').val()==""){ $("#logradouro-endereco").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if (flag==0){
		return true;
	}else{
		return false;
	}
}
*/

function salvarCadastroTelefone(){
	if ($("#cadastro-telefone-id").val()=="")
		caminho = caminhoScript+"/modulos/cadastros/cadastro-telefone-insert.php";
	else
		caminho = caminhoScript+"/modulos/cadastros/cadastro-telefone-update.php";
	if (validarCamposGenerico("#div-cadastros-telefones .required")){
	//if (validarTelefone()){
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#div-cadastro-telefone").html("");
				carregarCadastroTelefones($("#cadastroID").val());
			}
		});
	}
}
/*
function validarTelefone(){
	flag = 0;
	$("#tipo-telefone-id").css('background-color', '').css('outline', '');
	$("#telefone-telefone").css('background-color', '').css('outline', '');
	if ($('#tipo-telefone-id :selected').val()==""){ $("#tipo-telefone-id").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if ($('#telefone-telefone').val()==""){ $("#telefone-telefone").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');flag=1;}
	if (flag==0){
		return true;
	}else{
		return false;
	}
}
*/
function excluirCadastroEndereco(id){
	$("#div-cadastro-endereco").html("");
	$("#div-novo-endereco").show();
	var confirma=confirm("Tem certeza que deseja excluir o endereço?");
	if (confirma==true){
		retorno = $.ajax(caminhoScript+"/modulos/cadastros/cadastro-endereco-excluir.php?enderecoID="+id)
		.done(function(){
			carregarCadastroEnderecos($("#cadastroID").val());
			$("#div-cadastro-endereco").html();
		});
	}
}


function excluirCadastroTelefone(id){
	$("#div-cadastro-telefone").html("");
	//$("#div-novo-telefone").show();
	var confirma=confirm("Tem certeza que deseja excluir o telefone?");
	if (confirma==true){
		retorno = $.ajax(caminhoScript+"/modulos/cadastros/cadastro-telefone-excluir.php?telefoneID="+id)
		.done(function(){
			carregarCadastroTelefones($("#cadastroID").val());
			$("#div-cadastro-telefone").html();
		});
	}
}

function excluirCadastroVinculo(id){
	var confirma=confirm("Tem certeza que deseja excluir?");
	if (confirma==true){
		retorno = $.ajax(caminhoScript+"/modulos/cadastros/cadastro-vinculo-excluir.php?cadastro-vinculo-id="+id)
		.done(function(){
			carregarCadastroVinculo($("#cadastroID").val(),tipoVinculoID);
		});
	}
}

/********* Fim Funções de Cadastros BD***********/


/********* Inicio Funções Imprime Cadastros ***********/

$(document).ready(function(){
	$('#botao-imprime-cadastro').live('click', function () {
		caminho = caminhoScript+"/modulos/cadastros/cadastro-imprime.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
			}
		});
	});
});
/********* Final Funções Imprime Cadastros ***********/



$(".turma-mostrar-detalhes").live('click', function () {
	$("#id-turma").val($(this).attr('turma-id'))
	$("#frmDefault").attr("action",caminhoScript+"/turmas/turmas-gerenciar-turma/");
	$("#frmDefault").submit();
});


$(".orcamento-localiza").live('click', function () {
	$("#workflow-id").val($(this).attr('workflow-id'));
	$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
	$("#frmDefault").submit();
});

/********* Classifica Valores ***********/
$(".clasifica-valor").live('click', function () {
	elemento = $(this);
	//console.log($(this).attr('valor')+ "-" + $('#classificacao-cadastro').val());
	if (($(this).hasClass('estrela-ativa')) && ($(this).attr('valor')==$('#classificacao-cadastro').val())){
		$('.clasifica-valor').addClass('estrela-inativa').removeClass('estrela-ativa');
		$('#classificacao-cadastro').val('');
		$('#classificacao-id').val('');
	}
	else{
		i = 0;
		$(".clasifica-valor").removeClass('estrela-ativa').addClass('estrela-inativa');
		$('.clasifica-valor').each(function(){
			i++;
			$(this).addClass('estrela-ativa').removeClass('estrela-inativa');
			if (parseFloat($(this).attr('valor')) == parseFloat(elemento.attr('valor'))){
				valorClicado = $(this).attr('valor');
				return false;
			}
		});
		$('#classificacao-cadastro').val(valorClicado);
		$('#classificacao-id').val(elemento.attr('classificacao-id'));
	}
/*
	$("#workflow-id").val($(this).attr('workflow-id'));
	$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
	$("#frmDefault").submit();
*/
});

$(".salvar-cadastro-follow").live('click', function () {
	if ($('#div-tarefas-cadastradas-geral').length){
		camposTarefaRequired = "";
	}
	if ($('#cadastro-follow-incluir-tarefa').attr("checked"))
		campos = "#div-cadastrar-follow .required, .bloco-incluir-tarefa-geral .required";
	else
		campos = "#div-cadastrar-follow .required";

	if(validarCamposGenerico(campos)){
		caminho = caminhoScript+"/modulos/cadastros/cadastro-salvar-follow.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$('#cadastro-descricao-follow').val('');
				$('.cadastro-follows-historico').html(retorno);
				alertify.alert("<b>Aviso</b>", "Follow inclu&iacute;do com sucesso!");
				carregarFollows();
			}
		});
	}
});

$("#cadastro-follow-incluir-tarefa").live('click', function () {
	alertify.alert("<b>Aviso</b>", "Em desenvolvimento!");
});

