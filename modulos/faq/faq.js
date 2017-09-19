
$(document).ready(function(){

	/********* Inicio Funções localiza Perguntas ***********/
	$("#botao-pesquisar-perguntas").live('click', function () {
		$("#frmDefault").submit();
	});

	$(".botao-incluir-pergunta").live('click', function () {
		caminho = caminhoScript+"/faq/faq-gerenciar-pergunta";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});

	$(".pergunta-localiza").live('click', function () {
		$('#localiza-pergunta-id').val($(this).attr("pergunta-id"));
		caminho = caminhoScript+"/faq/faq-gerenciar-pergunta";
		$('#frmDefault').attr("action",caminho);
		$("#frmDefault").submit();
	});

	$(".pergunta-ordem-localiza").live('blur', function () {
		caminho = caminhoScript+"/modulos/faq/fac-atualiza-ordem-pergunta.php?pergunta-id="+$(this).attr("pergunta-id")+"&ordem-pergunta="+$(this).val();
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8"});
	});


	/********* Inicio Funções Inclusão e alteração de Perguntas ***********/
	$("#botao-salvar-pergunta").live('click', function () {
		caminho = caminhoScript+"/modulos/faq/fac-salvar-pergunta.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$('#localiza-pergunta-id').val(retorno);
				caminho = caminhoScript+"/faq/faq-gerenciar-pergunta";
				$('#frmDefault').attr("action",caminho);
				$("#frmDefault").submit();
			}
		});
	});

	$("#botao-excluir-pergunta").live('click', function () {
		excluirPergunta();
	});

	/********* Inicio Funções Inclusão e alteração de Respostas ***********/
	if($("#pergunta-id").val()!=""){
		carregarRespostas();
	}
	$("#botao-nova-resposta").live('click', function () {
		carregarResposta("");
	});
	$(".btn-editar-resposta").live('click', function () {
		carregarResposta($(this).attr("resposta-id"));
	});

	$(".btn-excluir-resposta").live('click', function () {
		excluirResposta($(this).attr("resposta-id"));
	});


	$(".resposta-ordem").live('blur', function () {
		caminho = caminhoScript+"/modulos/faq/fac-atualiza-ordem-resposta.php?resposta-id="+$(this).attr("resposta-id")+"&ordem-resposta="+$(this).val();
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8"});
	});


	$(".botao-cancelar-resposta").live('click', function () {
		$('#div-incluir-alterar-respostas').html("");
		$('#div-incluir-alterar-respostas').hide();
		$('#div-respostas').show();
	});

	$(".botao-salvar-resposta").live('click', function () {
		$("#resposta-descricao").val(tinyMCE.get('resposta-descricao').getContent());
		caminho = caminhoScript+"/modulos/faq/fac-salvar-resposta.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				carregarRespostas();
				$('#div-incluir-alterar-respostas').html("");
				$('#div-incluir-alterar-respostas').hide();
				$('#div-respostas').show();
			}
		});
	});

});

function carregarRespostas(){
	caminho = caminhoScript+"/modulos/faq/faq-carregar-respostas.php?pergunta-id="+$("#pergunta-id").val();
	$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#div-respostas').html(retorno);
			$('#div-respostas').show();
		}
	});
}

function carregarResposta(id){
	caminho = caminhoScript+"/modulos/faq/faq-carregar-resposta.php?pergunta-id="+$("#pergunta-id").val()+"&resposta-id="+id;
	$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#div-incluir-alterar-respostas').html(retorno);
			$('#div-incluir-alterar-respostas').show();
			$('#div-respostas').hide();
		}
	});
}


function excluirResposta(id){
	var confirma=confirm("Tem certeza que deseja excluir a Resposta?");
	if (confirma==true){
		caminho = caminhoScript+"/modulos/faq/faq-excluir-resposta.php?resposta-id="+id;
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				carregarRespostas();
			}
		});
	}
}

function excluirPergunta(){
	var confirma=confirm("Tem certeza que deseja excluir a Pergunta?");
	if (confirma==true){
		caminho = caminhoScript+"/modulos/faq/fac-excluir-pergunta.php?pergunta-id="+$("#pergunta-id").val();
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				caminho = caminhoScript+"/faq/faq-gerenciar";
				$('#frmDefault').attr("action",caminho);
				$("#frmDefault").submit();
			}
		});
	}
}

