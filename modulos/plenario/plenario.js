$(document).ready(function(){


	$(".salvar-configuracoes-plenario").live('click', function () {
		$.ajax({type: "POST",url: caminhoScript+"/modulos/plenario/plenario-configuracoes-gerais-salvar.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				alert("Configurações atualizadas com sucesso");
				//$("#frmDefault").submit();
			}
		});
	});

	$(".salvar-sessao").live('click', function () {
		if(validarCamposGenerico(".required")){
			$("#frmDefault").attr('action', caminhoScript+'/modulos/plenario/plenario-salvar-sessao.php');
			$("#frmDefault").submit();
		}
	});


	$(".iniciar-fase").live('click', function () {
		$(".aviso-aguardando-inicio-fase, .iniciar-fase").hide();
		$(".bloco-nova-fase").show();
	});


	$(".abrir-todos-canais").live('click', function () {
		$(".canais-geral").each(function(){
			caminho = $("#host").val()+"?action=send&channel="+$("#channel-midi").val()+"&device="+$('#device-midi').val()+"&command="+$(this).attr('command')+"&value="+$(this).attr('actionOpen');
			$.ajax({type: "GET",url: caminho});
		});
	});
	/*
	$(".aumenta-volumes").live('click', function () {
		$(".canais-geral").each(function(){
			caminho = $("#host").val()+"?action=send&channel="+$("#channel-midi").val()+"&device="+$('#device-midi').val()+"&command="+$(this).attr('command')+"&value="+$(this).attr('actionOpen');
			$.ajax({type: "GET",url: caminho});
		});
	});
	$(".abaixa-volumes").live('click', function () {
		$(".canais-geral").each(function(){
			caminho = $("#host").val()+"?action=send&channel="+$("#channel-midi").val()+"&device="+$('#device-midi').val()+"&command="+$(this).attr('command')+"&value="+$(this).attr('actionOpen');
			$.ajax({type: "GET",url: caminho});
		});
	});
	*/



	$(".fechar-todos-canais").live('click', function () {
		$(".canais-geral").each(function(){
			caminho = $("#host").val()+"?action=send&channel="+$("#channel-midi").val()+"&device="+$('#device-midi').val()+"&command="+$(this).attr('command')+"&value="+$(this).attr('actionClose');
			$.ajax({type: "GET",url: caminho});
		});
	});

	$(".incluir-nova-fase").live('click', function () {
		if(validarCamposGenerico("#tipo-nova-fase-id")){
			$("#frmDefault").attr('action', caminhoScript+'/modulos/plenario/plenario-salvar-fase.php');
			$("#frmDefault").submit();
		}
	});
	$(".cancelar-incluir-nova-fase").live('click', function () {
		$(".aviso-aguardando-inicio-fase, .iniciar-fase").show();
		$(".bloco-nova-fase").hide();
	});

	$(".quantidade-canais").live('change', function () {
		qtd = $(this).val();
		$(".bloco-organizacao-plenario").html("<p align='center' style='width:100%'>Aguarde carregando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
		$.ajax({type: "POST",url: caminhoScript+"/modulos/plenario/plenario-carregar-organizacao-plenario.php?quantidadeCanais="+qtd, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$(".bloco-organizacao-plenario").html(retorno);
			}
		});
	});

	$(".botao-acao-plenario").live('click', function () {
		$("#nova-situacao-id").val($(this).attr('acao'));
		$("#frmDefault").attr('action', caminhoScript+'/modulos/plenario/plenario-atualizar-sessao.php');
		$("#frmDefault").submit();
	});

	$(".botao-acao-falar").live('click', function () {
		realizarAcao($(this),'','');
	});

	$(".botao-finalizar").live('click', function () {
		canal = $(this).attr('canal');
		usuario = $(this).attr('usuario');
		command = $(this).attr('command');
		action = $(this).attr('action');
		follow = $("#cronometro-"+usuario+"-"+canal).attr('follow');
		posicao = $("#cronometro-"+usuario+"-"+canal).attr('posicao');
		$("#cronometro-"+usuario+"-"+canal).TimeCircles().end().fadeOut();

		$(".botao-pausa-"+usuario+"-"+canal).hide();
		$(".botao-finalizar-"+usuario+"-"+canal).hide();
		$(this).parent().parent().parent().children(':eq(1)').children().children(':eq(0)').css('opacity','1');
		$("#cronometro-"+usuario+"-"+canal).removeClass('em-andamento');

		flagAndamento = 0;
		$(".em-andamento").each(function(){
			canalAux = $(this).attr('canal');
			usuarioAux = $(this).attr('usuario');
			followAux = $(this).attr('follow');
			if ((canal!=canalAux) || (usuario!=usuarioAux)){
				//alert(caminhoScript+"/modulos/plenario/plenario-salvar-acao-geral-sessao.php?follow="+followAux);
				/*
				$.ajax({type: "POST",url: caminhoScript+"/modulos/plenario/plenario-salvar-acao-geral-sessao.php?follow="+followAux, data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						carregarFollowsSessao($('#sessao-id').val());
					}
				});
				*/
/*
				caminho = caminhoScript+"/modulos/plenario/plenario-salvar-acao-geral-sessao.php?canal="+canalAux+"&usuario="+usuarioAux+"&tempo="+segundos+"&tipo=P&descricao=Pausa&posicao="+posicao;
				$.ajax({type: "POST",url: caminho, data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						//alert(retorno);
					}
				});
*/
				$("#cronometro-"+usuarioAux+"-"+canalAux).TimeCircles().start();
				$(".botao-finalizar-"+usuarioAux+"-"+canalAux).show();
				flagAndamento = 1;
			}
		});
		if (flagAndamento==1){
			$(".botao-acao-falar").hide();
			$(".a-parte").show();
			$(".acoes-tempo-"+usuarioAux+"-"+canalAux).each(function(){
				if ($(this).hasClass('a-parte')){
					$(this).hide();
				}
			});
		}
		else{
			$(".botao-acao-falar").show();
			$(".a-parte").hide();
		}


		atualizarFollow(follow,command, action);
		setTimeout(function(){ reabrirCanal(canal,usuario); }, 5000);
		exibeEscondeBtnFinaliza();
	});

	$(".botao-pausa").live('click', function () {
		canal = $(this).attr('canal');
		usuario = $(this).attr('usuario');
		if ($(this).val()=='Pausa'){
			$("#cronometro-"+usuario+"-"+canal).TimeCircles().stop();
			$(this).val('Reiniciar');
		}
		else{
			$("#cronometro-"+usuario+"-"+canal).TimeCircles().start();
			$(this).val('Pausa');
		}
	});

	$(".finalizar-fase").live('click', function () {
		if (confirm('Tem certeza que deseja finalizar a fase?')){
			$("#frmDefault").attr('action', caminhoScript+'/modulos/plenario/plenario-salvar-fase.php');
			$("#frmDefault").submit();
		}
	});


	$(".sair-visualiza-sessao").live('click', function () {
		$("#frmDefault").attr('action', caminhoScript+'/plenario/');
		$("#frmDefault").submit();
	});



	if ($("#slug-pagina").val()=='plenario-visualizar-sessao'){
		var recarregaSessao = function () {
			//carregarFollowsSessao($('#sessao-id').val());
			carregarAndamentoSessao($('#sessao-id').val());
			setTimeout(recarregaSessao,3000);
		}
		setTimeout(recarregaSessao,3000);
	}

	function carregarAndamentoSessao(sessaoID){
		dataCadastro = $('#dataCadastro').val();
		//console.log(dataCadastro);
		$.ajax({type: "POST",url: caminhoScript+"/modulos/plenario/plenario-carregar-andamento-sessao.php?sessaoID="+sessaoID+"&dataCadastro="+dataCadastro, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				if ($.trim(retorno)!=""){
					//alert("FOI!");
					$("#conteudo-andamento-sessao").html(retorno);
				}
			}
		});
	}
	exibeEscondeBtnFinaliza()
});

function reabrirCanal(canal,usuario){
	command = $('.acoes-tempo-'+usuario+'-'+canal).attr('command');
	action = $('.acoes-tempo-'+usuario+'-'+canal).attr('action');
	caminho = $("#host").val();
	if (caminho!=""){
		caminho += "?action=send&channel="+$("#channel-midi").val()+"&device="+$('#device-midi').val()+"&command="+command+"&value="+action;
		$.ajax({type: "GET",url: caminho});
		/*
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				console.log(new Date()+ " --- " + caminho);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				console.log(new Date() + " --- " + caminho);
			}
		});
		*/
	}
}

function carregarFollowsSessao(sessaoID){
	//$("#conteudo-historico-follows").html("<p align='center' style='width:100%'>Aguarde carregando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
	$.ajax({type: "POST",url: caminhoScript+"/modulos/plenario/plenario-carregar-follows.php?sessaoID="+sessaoID, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#conteudo-historico-follows").html(retorno);
		}
	});
}

function atualizarFollow(follow, command, action){
	$.ajax({type: "POST",url: caminhoScript+"/modulos/plenario/plenario-salvar-acao-geral-sessao.php?follow="+follow, data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			carregarFollowsSessao($('#sessao-id').val());
		}
	});

	caminho = $("#host").val();
	if (caminho!=""){
		caminho += "?action=send&channel="+$("#channel-midi").val()+"&device="+$('#device-midi').val()+"&command="+command+"&value="+action;
		$.ajax({type: "GET",url: caminho});
		/*
		$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				console.log(new Date()+ " --- " + caminho);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				console.log(new Date() + " --- " + caminho);
			}
		});
		*/
	}
	/*
	caminho = "http://localhost:3000/?action=send&channel=0&command=21&value=0&device=4";
	$.ajax({type: "GET",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			carregarFollowsSessao($('#sessao-id').val());
		}
	});
	*/
}

function realizarAcao(objeto, followID, tempoRestante){
	var canal = objeto.attr('canal');
	var usuario = objeto.attr('usuario');
	var tempo = objeto.attr('tempo');
	var pausa = objeto.attr('pausa');
	var posicao = objeto.attr('posicao');
	var tipo = objeto.attr('tipo');
	var command = objeto.attr('command');
	var action = objeto.attr('action');

	descricao = objeto.val();
	$("#cronometro-"+usuario+"-"+canal).attr('posicao',posicao);
	if (followID!=""){
		$("#cronometro-"+usuario+"-"+canal).attr('follow',followID);
	}


	flagAndamento = 0;
	$(".em-andamento").each(function(){
		canalAux = $(this).attr('canal');
		usuarioAux = $(this).attr('usuario');
		if ((canal!=canalAux) || (usuario!=usuarioAux)){
			flagAndamento = 1;
			$(".botao-finalizar-"+usuarioAux+"-"+canalAux).hide();
		}
	});
	if (tempoRestante==""){
		t = tempo.split(':');
		segundos = (parseInt(t[0]) * 60) + (parseInt(t[1]));
	}
	else{
		segundos = tempoRestante;
	}
	//segundos = 10;
	if (flagAndamento==1){
		if(pausa==1){
			$("#cronometro-"+usuarioAux+"-"+canalAux).TimeCircles().stop();
			caminho = caminhoScript+"/modulos/plenario/plenario-salvar-acao-geral-sessao.php?canal="+canalAux+"&usuario="+usuarioAux+"&tempo="+segundos+"&tipo=P&descricao=Pausa&posicao="+posicao;
			$.ajax({type: "POST",url: caminho, data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					//alert(retorno);
				}
			});
		}
		else{
			segundosAndamento = $("#cronometro-"+usuarioAux+"-"+canalAux).TimeCircles().getTime();
			if (segundos>segundosAndamento)
				segundos = segundosAndamento;
		}
	}
	objeto.parent().parent().parent().children(':eq(1)').children().children(':eq(0)').css('opacity','0.4');

	/**/
	$(".botao-acao-falar").hide();
	if (!(objeto.hasClass("a-parte"))){
		$(".a-parte").show();
	}


	$("#cronometro-"+usuario+"-"+canal).attr('data-timer',segundos);
	$("#cronometro-"+usuario+"-"+canal).addClass('em-andamento');
	$("#cronometro-"+usuario+"-"+canal).TimeCircles({ time: { Days: { show: false }, Hours: { show: false }},start: true, count_past_zero: false}).addListener(
	function (unit, value, total){
		//console.log(unit + " --- " + value + " ---- " , total);
		if ((total==0)&&($("#cronometro-"+usuario+"-"+canal).is(":visible"))){
			$(".botao-finalizar-"+usuario+"-"+canal).click();
		}
	});

	$("#cronometro-"+usuario+"-"+canal).data('timer',segundos);
	$("#cronometro-"+usuario+"-"+canal).TimeCircles().restart();
	$(".botao-pausa-"+usuario+"-"+canal).show();
	$(".botao-finalizar-"+usuario+"-"+canal).show();

	$(".acoes-tempo-"+usuario+"-"+canal).hide();
	$("#cronometro-"+usuario+"-"+canal).show();
	if (tempoRestante==""){
		caminho = $("#host").val();
		if (caminho!=""){
			caminho += "?action=send&channel="+$("#channel-midi").val()+"&device="+$('#device-midi').val()+"&command="+command+"&value="+action;
			$.ajax({type: "GET",url: caminho});
		}
		caminho = caminhoScript+"/modulos/plenario/plenario-salvar-acao-geral-sessao.php?canal="+canal+"&usuario="+usuario+"&tempo="+segundos+"&tipo="+tipo+"&descricao="+descricao+"&posicao="+posicao;
		$.ajax({type: "POST",url: caminho, data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#cronometro-"+usuario+"-"+canal).attr('follow',retorno);
			}
		});

	}
	carregarFollowsSessao($('#sessao-id').val());
	exibeEscondeBtnFinaliza();
}

function exibeEscondeBtnFinaliza(){
	if ($(".em-andamento").length)
		$(".finalizar-fase, .abrir-todos-canais, .fechar-todos-canais, .aumenta-volumes, .abaixa-volumes").hide();
	else
		$(".finalizar-fase, .abrir-todos-canais, .fechar-todos-canais, .aumenta-volumes, .abaixa-volumes").show();

}

