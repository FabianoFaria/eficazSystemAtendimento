/* 	FUNCOES DE MOUSE */
$(document).mousedown(function(e){
	mouseTopClick = e.pageY;
	mouseLeftClick = e.pageX;
});
/*
$(document).ready(function(){
	$("input[type=button]").bind('click keypress', function () {
		$.ajax({type: "POST",url: caminhoScript+"/includes/valida-session-login.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				return true;
			}
		});
		return false;
	});
});
*/

/* 	DESABILITAR BACKSPACE */
$(document).keydown(function(e) {
	var element = e.target.nodeName.toLowerCase();
	if (element != 'input' && element != 'textarea') {
		if (e.keyCode === 8) {
			return false;
		}
	}
});
/* desabilita botao direito do mouse*/

$(document).bind("contextmenu",function(e){
	return false;
});

var objetoClicado;
$(document).ready(function(){
	$(".link, .lnk").live('mousedown', function (e) {
		if(e.which==3){
			objetoClicado = $(this);
			$("#div-janela-mouse").remove();
			sHTML = "<div id='div-janela-mouse' class='esconde' Style='position:absolute; left:0px; top: 0px; right: 0px; width:220px; height:35px;' valign='middle' align='center'>" +
					"<input type='button' id='abrir-nova-janela' style='font-size:12px;width:220px; height:30px' value='Abrir link em uma nova janela'/>" +
					"</div>";
			$(sHTML).appendTo("body");
			$("#div-janela-mouse").delay("fast").fadeIn();
			//$("#div-janela-mouse").show();
			$("#div-janela-mouse").css("left",e.pageX);
			$("#div-janela-mouse").css("top",e.pageY);
		}
	});
	$('#abrir-nova-janela').live('click', function () {
		$('#frmDefault').attr("target","_blank");
		objetoClicado.click();
		$('#frmDefault').attr("target","_top");
		$('#frmDefault').attr("action","");
		$("#div-janela-mouse").remove();
	});

	url = window.location.href;
	var hash = url.substring(url.indexOf("#"));
	if(url.indexOf("#") != -1)
		$(hash).click();
});
/**/


$(document).mousedown(function(e){
	if(e.which==1){$("#div-janela-mouse").delay("fast").fadeOut();}
});

$(document).ready(function(){
	if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
		$(window).load(function(){
			//alert(1);
			$('input:-webkit-autofill').each(function(){
				//alert(1);
				$(this).val("");
				$(this).after(this.outerHTML).remove();
			});
		});
	}
});


$(document).ready(function(){
	event= window.event;
	$(".localiza-mascara-cpf, .localiza-mascara-cnpj, .mascara-cpf, .mascara-cnpj").bind('paste', function(e) {
		$(this).val(e.originalEvent.clipboardData.getData('Text'));
	});

	$("#password").focus(function() {
		$("#login-campo-senha").removeClass().addClass('login-campo-senha-focus');
	});
	$("#password").blur(function() {
		$("#login-campo-senha").removeClass().addClass('login-campo-senha');
		$("#password").removeClass();
	});
	$("#login").focus(function() {
		$("#login-campo-login").removeClass().addClass('login-campo-login-focus');
	});
	$("#login").live('click keypress', function () {
		if(event.keyCode == 13){
			loginSistema();
		}
	});
	$("#password").live('click keypress', function () {
		if(event.keyCode == 13){
			loginSistema();
		}
	});
	$("#login").blur(function() {
		$("#login-campo-login").removeClass().addClass('login-campo-login');
		$("#login").removeClass();
	});
	$("#botao-login").click(function() {
		loginSistema();
	});
});

function login(){
	$("#menu").hide();
	$("#topo-esquerdo").hide();
}
function loginSistema(){
	if($("#login").val()==""){
		$("#login").removeClass().addClass('erro-login');
		$("#login-campo-login").removeClass().addClass('login-campo-login-focus');
		$("#login").focus();
		return false;
	}
	if($("#password").val()==""){
		$("#password").removeClass().addClass('erro-login');
		$("#login-campo-password").removeClass().addClass('login-campo-password-focus');
		$("#password").focus();
		return false;
	}
	$("#frmDefault").attr("action",caminhoScript+"/includes/validalogin.php");
	$("#frmDefault").submit();
}


/********* Final Funções Login ***********/

/********* Inicio Funções Esqueci Senha ***********/
$(document).ready(function(){
	$("#esqueci-senha-ref").click(function() {
		$("#tabela-login-senha").show();
		$("#tabela-login").hide()
	});
	$("#loginS").focus(function() {
		$("#login-campo-login-senha").removeClass().addClass('login-campo-login-focus');
	});
	$("#loginS").blur(function() {
		$("#login-campo-login-senha").removeClass().addClass('login-campo-login');
		$("#loginS").removeClass();
	});
	$("#botao-recupera-senha").click(function() {
		if($("#loginS").val()==""){
			$("#loginS").removeClass().addClass('erro-login');
			$("#login-campo-login-senha").removeClass().addClass('login-campo-login-focus');
			$("#loginS").focus();
			return false;
		}
		$("#erro-login").load(caminhoScript+"/includes/localiza-senha.php?login="+$("#loginS").val(),function(responseTxt,statusTxt,xhr){
			$("#tabela-login-senha").hide();
			$("#tabela-login").show()
			$("#erro-login").show();
		});
	});
});
/********* Final Funções Esqueci Senha ***********/

/********* Inicio Funções Menu ***********/
$(document).ready(function(){
	$(".interno-nivel-1, .interno-nivel-1-selecionado").click(function() {
		$(".interno-nivel-1-selecionado").addClass("interno-nivel-1");
		$(".interno-nivel-1-selecionado").removeClass("interno-nivel-1-selecionado");
		$(this).addClass("interno-nivel-1-selecionado");
		valor = this.id.split('|');
		$(".interno-nivel").hide();
		$("#"+valor[0]+"-2-"+valor[1]).show();
		$(".menu-2").show();
		$(".menu-3").hide();
	});

	$(".interno-nivel-2, .interno-nivel-2-selecionado").click(function() {
		$(".interno-nivel-2-selecionado").addClass("interno-nivel-2");
		$(".interno-nivel-2-selecionado").removeClass("interno-nivel-2-selecionado");
		$(this).addClass("interno-nivel-2-selecionado");
		$(".interno-nivel-tres").hide();
		$(".menu-3").hide();
		valor = this.id.split('|');
		$("#"+valor[0]+"-1").show();
		if($("#"+valor[0]+"-1").html() != "")
			$(".menu-3").show();

	});

	$(".menu-ramificado").click(function() {
		if (($(this).attr('id')!='menu-ramificado-1')&&($(this).attr('id')!='menu-ramificado-2')&&($(this).attr('id')!='menu-ramificado-3')&&($(this).attr('id')!='menu-ramificado-4')&&($(this).attr('id')!='menu-ramificado-5'))
			$("#conteudo").html('');
	});
	$(".menu-interno").click(function() {
		$("#conteudo").html('');
	});
});
function menuRamificado(slug, id, pos, nivel){
	open(caminhoScript+"/funcoes/ajax.montaMenu.php?slug="+slug+"&id="+id+"&pos="+pos+"&nivel="+nivel, '_menu');
}
/********* Final Funções menu ***********/




/********* Inicio Funções Drag and drop ***********/
$(function(){
	paginaAtual = window.location.href.replace("#","");
	$('#cartcontent').datagrid({
		singleSelect:true
	});
	$('.item').draggable({
		revert:true,
		proxy:'clone',
		onStartDrag:function(){
			$(this).draggable('options').cursor = 'not-allowed';
			$(this).draggable('proxy').css('z-index',10);
		},
		onStopDrag:function(){
			$(this).draggable('options').cursor='move';
		}
	});

	$('.cart').droppable({
		onDragEnter:function(e,source){
			$(source).draggable('options').cursor='auto';
		},
		onDragLeave:function(e,source){
			$(source).draggable('options').cursor='not-allowed';
		},
		onDrop:function(e,source){
			var id = $(source).find('p:eq(0)').html();
			if((this.id).substring(0,15)=='exclui-dragable'){
				open(caminhoScript+"/funcoes/exclui-item.php?id="+id+"&slug="+paginaAtual+"&principal="+$("#vinculo-principal").val()+"&secundario="+$("#vinculo-secundario").val(), "_top");
			}else{
				interno = this.id.replace('bloco-','')
				open(caminhoScript+"/funcoes/vincula-item.php?id="+id+"&bloco="+interno+"&slug="+paginaAtual+"&principal="+$("#vinculo-principal").val()+"&secundario="+$("#vinculo-secundario").val(), "_top");
			}

		}
	});
	$('.cart-formulario').droppable({
		onDragEnter:function(e,source){
			$(source).draggable('options').cursor='auto';
		},
		onDragLeave:function(e,source){
			$(source).draggable('options').cursor='not-allowed';
		},
		onDrop:function(e,source){
			var id = $(source).find('p:eq(0)').html();
			detalhesCampo= $.ajax(caminhoScript+"/funcoes/monta-formulario.php?id="+id)
			.done(function(){
				$("#campos-formulario").html($("#campos-formulario").html()+detalhesCampo.responseText);
			});

		}
	});

});

$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");
	$(".vincula-grupos-chamados").click(function() {
		var selecionado = "1";
		if($("#select-principal").val()==""){
			$("#select-principal").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			selecionado -1;
		}
		$("#select-secundario option:selected").each(function() {
			if(this.text=="Sem vinculo"){
				selecionado = 0;
				$("#select-secundario").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			}
		});
		if(selecionado >=1){
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/vincula-tipo.php");
			$("#frmDefault").submit();
		}
	});
	$(".exclui-vinculo").click(function() {
		id = this.id.replace('exclui-vinculo-','');
		open(caminhoScript+"/funcoes/desvincula-item.php?id="+id+"&slug="+paginaAtual+"&principal="+$("#vinculo-principal").val()+"&secundario="+$("#vinculo-secundario").val(), "_top");
	});
	$(".exclui-vinculo-tipo").click(function() {
		valor = this.id.split("-");
		open(caminhoScript+"/funcoes/exclui-vinculo-tipo.php?tipoID="+valor[2]+"&slug="+paginaAtual, '_top');
	});
	$(".cadastra-grupos").click(function() {
		if($("#titulo").val()==""){
			$("#titulo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			return false;
		};
		open(caminhoScript+"/funcoes/cadastra-grupo.php?titulo="+$('#titulo').val()+"&slug="+paginaAtual, '_top');
	});
	$("#titulo").blur(function() {
		$("#titulo").css('background-color', '');
		$("#titulo").css('outline', '');
	});
	$("#titulo").keyup(function() {
		if($("#titulo").val()!=""){
			$("#titulo").css('background-color', '');
			$("#titulo").css('outline', '');
		}
	});
	$("#select-secundario").change(function() {
		$("#select-secundario").css('background-color', '').css('outline', '');
		$("#select-secundario option:selected" ).each(function() {
			if(this.text=="Sem vinculo"){
				$("#select-secundario").val('');
		  	}
		});
	});
	$("#select-principal").change(function() {
		if($("#select-principal").val()!=""){
			$("#select-principal").css('background-color', '').css('outline', '');
		}
	});
	/*
	$("exclui-dragable").click(function(){

	});
	*/
});
/********* Final Funções Drag and drop ***********/


/********* Inicio Funções Lixeira ***********/
$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");
	$(".lixeira-cheia").click(function(){
		//item = this.id.split("-");
		//$("#id-lixeira").val(item[2]);
		$("#slug").val(paginaAtual);
		$("#frmDefault").attr("action",caminhoScript+"/lixeira");
		$("#frmDefault").submit();
	});

	$(".recupera-item-lixeira").click(function(){
		item = this.id.split("-");
		slug = $("#slug").val();
		$("#tipoID").val(item[2]);
		$("#frmDefault").attr("action",caminhoScript+"/funcoes/reativa.php");
		$("#frmDefault").submit();
		//open(caminhoScript+"/funcoes/reativa.php?tipoID="+item[2]+"&slug="+slug+"&principal="+$("#vinculo-principal").val()+"&secundario="+$("#vinculo-secundario").val(),'_top');
	});

	$(".exclui").click(function(){
		item = this.id.split("-");
		$("#slug-lixeira").val(paginaAtual);
		$("#tipoID").val(item[2]);
		$("#frmDefault").attr("action",caminhoScript+"/funcoes/exclui-lixeira.php");
		$("#frmDefault").submit();

		//lixeira = $("#lixeira").val();
		//open(caminhoScript+"/funcoes/exclui-lixeira.php?tipoID="+item[2]+"&slug="+slug+"&lixeira="+lixeira,'_top');
	});

});

/********* Final Funções Lixeira ***********/


/********* Inicio Funções cadastrar e vincular campo ***********/
$(document).ready(function(){
	$("#opcoes-campo").change(function(){
		var campo = "";
		for(i=1;i<=$("#opcoes-campo").val();i++){
			if(i==1) campo = "<div class='titulo-container' style='float:left;width:99.5%;'><div class='titulo'><p>Detalhes dos campos</p></div>";
			campo += "<div class='titulo-secundario quatro-colunas' Style='margin-top:5px;margin-bottom:5px;'><p Style='margin-left:5px;'>Titulo do Campo "+i+"</p><p><input type='text' name='campo-"+i+"' id='campo-"+i+"' Style='margin-left:5px;'/></p></div>";
		}
		campo += "</div>";
		$("#campos-secundarios").html(campo);
	});
	$(".cadastra-novo-campo").click(function(){
		var selecionado = "1";
		$("#titulo").css('background-color', '').css('outline', '');
		$("#tipo-campo").css('background-color', '').css('outline', '');
		$("#opcoes-campo").css('background-color', '').css('outline', '');
		$("#mascara-campo").css('background-color', '').css('outline', '');

		if($("#titulo").val()==""){
			$("#titulo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			selecionado--;
		}
		if($("#tipo-campo").val()==""){
			$("#tipo-campo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			selecionado--;
		}
		if($("#opcoes-campo").val()==""){
			$("#opcoes-campo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			selecionado--;
		}
		if($("#mascara-campo").val()==""){
			$("#mascara-campo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			selecionado--;
		}
		if(selecionado==1){
			for(j=1;j<=$("input").length-3;j++){
				if($("#campo-"+j).val()==""){
					$("#campo-"+j).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
					selecionado--;
				}
			}
			if(selecionado==1){
				item 	= this.id.split("-");
				slug 	= window.location.href;
				$("#frmDefault").attr("action",caminhoScript+"/funcoes/cadastra-novo-campo.php?id="+item[item.length-1]+"&slug="+slug);
				$("#frmDefault").submit();
			}
		}else{
			return false;
		}
	});

	$(".exclui-campo-personalizado").click(function(){
		item 	= this.id.split("-");
		slug 	= window.location.href;
		open(caminhoScript+"/funcoes/exclui-novo-campo.php?id="+item[2]+"&slug="+slug+"&lixeira="+lixeira,'_top');
	});

	$("#vinculo-secundario").change(function(){
		$("#frmDefault").attr("action",window.location.href);
		if($("#vinculo-principal").val() != "")
			if($("#vinculo-secundario").val() != "")
				$("#frmDefault").submit();
	});
});

/********* Final Funções cadastrar e vincular campo ***********/
/* Scritp Tipo Fase Sessão Módulo Plenario*/
$(".incluir-tempos-fase-sessao").live('click', function () {
	contTempo = parseInt($('#contadorTempos').val());
	contTempo++;
	//alert(contTempo);
	htmlAux = 	"	<div style='float:left; width:10%;' id='div-bloco-tempos-"+contTempo+"'>" +
				"		<p><b>Descri&ccedil;&atilde;o</b>" +
				"			<span style='float:right' class='link excluir-tempo-fase-sessao' posicao='"+contTempo+"'>x &nbsp;&nbsp;&nbsp;&nbsp;</span>" +
				"			<input type='text' name='descricao[]' style='width:90%' value=''/> " +
				"		</p> " +
				"		<p><b>Tempo</b><input type='text' name='tempo[]' class='formata-horas' style='width:90%'/></p>" +
				"	</div>" +
				"	<script type='text/javascript' src='"+caminhoScript+"/javascript/funcoes.formatacao.js'></script>";
	$('#contadorTempos').val(contTempo);
	$('.tempos-fases-sessao').append(htmlAux);
});


$(".excluir-tempo-fase-sessao").live('click', function () {
	posicao = $(this).attr('posicao');
	//alert('#div-bloco-tempo-'+posicao);
	$('#div-bloco-tempo-'+posicao).remove();
	//$(this).index().html();
	//alert("Wait....");
	//$(this).find('div:first').remove();
});

/**/


/********* Inicio Funções Monta Formulario ***********/

$(document).ready(function(){
	$("#cadastra-novo-formulario").click(function(){
		selecionado = 0;
		if($("#titulo").val()==""){
			$("#titulo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			selecionado = 1;
		}
		if($("#tipo-envio").val()==""){
			$("#tipo-envio").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			selecionado = 1;
		}
		if($("#formulario-superior").val()==""){
			$("#formulario-superior").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			selecionado = 1;
		}
		if(selecionado == 0){
			$("#frmDefault").attr("action",caminhoScript+"/funcoes/cadastra-formulario.php?slug="+window.location.href);
			$("#frmDefault").submit();
		}
	});
});


/********* Final Funções Monta Formulario ***********/


/********* Inicio Funções Cadastra Tipo ***********/

$(document).ready(function(){
	paginaAtual = window.location.href.replace("#","");
	$(".cadastra-tipo").click(function() {

		if($("#titulo").val()==""){
			$("#titulo").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			return false;
		};

		//Validação dos campos adicionais
		if($("#valor_modificado").val()==""){
			$("#valor_modificado").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			return false;
		};

		if($("#valor_modificado").val() > 99){
			$("#valor_modificado").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			return false;
		};

		if($("#tipo-bonus-disponivel").val()==""){
			$("#tipo-bonus").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
			return false;
		};	

		$("#frmDefault").attr("action",caminhoScript+"/funcoes/cadastra-tipo.php?slug="+paginaAtual);
		$("#frmDefault").submit();
	});

	$(".editar-tipo").live('click', function () {
		//alert($(this).attr("tipo-id"));
		$('#tipo-id').val($(this).attr("tipo-id"));
		$("#frmDefault").submit();
	});

	$('.cart-tipos').droppable({
		onDragEnter:function(e,source){
			$(source).draggable('options').cursor='auto';
		},
		onDragLeave:function(e,source){
			$(source).draggable('options').cursor='not-allowed';
		},
		onDrop:function(e,source){
			var id = $(source).find('p:eq(0)').html();
			if (id<1000)
				alert("Operação não permitida");
			else
				open(caminhoScript+"/funcoes/exclui-tipo.php?id="+id+"&tipo-grupo="+$('#tipo-grupo').val()+"&slug="+paginaAtual, "_top");
		}
	});

	$('.cart-tipos-editar').droppable({
		onDragEnter:function(e,source){
			$(source).draggable('options').cursor='auto';
		},
		onDragLeave:function(e,source){
			$(source).draggable('options').cursor='not-allowed';
		},
		onDrop:function(e,source){
			var id = $(source).find('p:eq(0)').html();
			$('#tipo-id').val(id);
			$("#frmDefault").submit();
		}
	});

});


function mascaraCampo(objeto){
    campo = eval (objeto);
	/*
	if(((event.keyCode >= 65)&&(event.keyCode <= 90))
	 ||((event.keyCode >= 97)&&(event.keyCode <= 122))
	 ||((event.keyCode >= 192)&&(event.keyCode <= 255))
	 ||((event.keyCode >= 32)&&(event.keyCode <= 33))
	 ||((event.keyCode >= 35)&&(event.keyCode <= 38))
	 ||((event.keyCode >= 40)&&(event.keyCode <= 47))
	 ||((event.keyCode >= 58)&&(event.keyCode <= 64))
	 ||((event.keyCode >= 91)&&(event.keyCode <= 95))
	 ||((event.keyCode >= 123)&&(event.keyCode <= 255))
	 ||((event.keyCode <= 57)&&(event.keyCode >= 48))
	 ||((event.keyCode >= 44)&&(event.keyCode <= 47))
	 ||((event.keyCode == 38))||((event.keyCode == 95))){*/

	if(typeof event != 'undefined'){

		if(event.keyCode != 39){
	 		event.returnValue = true;
	 	}else{
	 		event.returnValue = false;
	 	}	
	}
	
}

function ValidarCNPJ(cnpj){
	if((soNumero(cnpj).length)==14){
        var valida = new Array(6,5,4,3,2,9,8,7,6,5,4,3,2);
        var dig1= new Number;
        var dig2= new Number;
        exp = /\.|\-|\//g
        cnpj = cnpj.toString().replace( exp, "" );
        var digito = new Number(eval(cnpj.charAt(12)+cnpj.charAt(13)));

        for(i = 0; i<valida.length; i++){
                dig1 += (i>0? (cnpj.charAt(i-1)*valida[i]):0);
                dig2 += cnpj.charAt(i)*valida[i];
        }
        dig1 = (((dig1%11)<2)? 0:(11-(dig1%11)));
        dig2 = (((dig2%11)<2)? 0:(11-(dig2%11)));

        if(((dig1*10)+dig2) != digito)
			return(false);
		else
			return(true);
	}
	else
		return(false);
}


function ValidarCPF(entrada){
	entrada = soNumero(entrada);
	soma = 0;
	for (i=0; i < 9; i ++)
		soma += parseInt(entrada.charAt(i)) * (10 - i);
	resto = 11 - (soma % 11);
	if (resto == 10 || resto == 11)
		resto = 0;
	if (resto != parseInt(entrada.charAt(9)))
		return false;
	soma = 0;
	for (i = 0; i < 10; i ++)
		soma += parseInt(entrada.charAt(i)) * (11 - i);
	resto = 11 - (soma % 11);
	if (resto == 10 || resto == 11)
		resto = 0;
	if (resto != parseInt(entrada.charAt(10)))
		return false;
	return true;
}

function soNumero(conteudo){
	var numeros = "";
	for (var i=0;i<conteudo.length;i++){
		if ((conteudo.charCodeAt(i) > 47) && (conteudo.charCodeAt(i) < 58)){
			numeros = numeros + conteudo.substring(i,i+1);
		}
	}
	return numeros;
}

function localizaEndereco(cep,tipoCampo){
	if (tipocampo='undefined'){
		tipoCampo = "";
	}
	if(cep != ""){
		stringEndereco = $.ajax(caminhoScript+"/funcoes/localiza-endereco.php?cep="+cep)
		.done(function(){
			if (stringEndereco.responseText.length > 0){
				var campos = stringEndereco.responseText.split(",");
				$("#logradouro-endereco"+tipoCampo).val(campos[1]+" "+campos[2]);

				$("#logradouro-endereco"+tipoCampo).val(campos[1]+" "+campos[2]);
				$("#bairro-endereco"+tipoCampo).val(campos[3]);
				$("#cidade-endereco"+tipoCampo).val(campos[4]);
				$("#uf-endereco"+tipoCampo).val(campos[5]);
				$("#numero-endereco"+tipoCampo).focus();
			}else{
				$("#logradouro-endereco"+tipoCampo).val("");
				$("#bairro-endereco"+tipoCampo).val("");
				$("#cidade-endereco"+tipoCampo).val("");
				$("#uf-endereco"+tipoCampo).val("");
				$("#numero-endereco"+tipoCampo).focus();
			}
		});
	}
}

/********* Fim Funções Gerais de Cadastro ***********/

/********* Inicio Funções Gerais ***********/

function formataTelefone(campo){
	valor = campo.value
	valor = valor.replace("(","");
	valor = valor.replace(")","");
	valor = valor.replace("-","");
	valor = valor.replace(".","");
	valor = valor.replace("(","");
	valor = valor.replace(")","");
	valor = valor.replace("-","");
	valor = valor.replace(".","");
	valor = valor.replace(" ","");
	valor = valor.replace(" ","");

	if(valor != ""){
		formatado = "("+valor.substring(0,2) + ") ";
		formatado += valor.substring(2,6)+"-";
		formatado += valor.substring(6,10);
		campo.value = formatado;
	}
}


function formataValor(campo){
	valor = campo.value;
	tamanho =valor.length;
	decimal = tamanho - 2;
	antes= valor.indexOf(",");
	depois = antes+1
	inteiro = valor.substring(0,antes)+valor.substring(tamanho,depois);
	tamanho = inteiro.length;
	if(tamanho>=3){
		campo.value = eval(inteiro.substring(0,tamanho-2))+','+inteiro.substring(tamanho-2,tamanho);
	}
	if(tamanho<3)  {
		campo.value = eval(inteiro.substring(0,tamanho-2))+inteiro.substring(tamanho-2,tamanho);
	}
}


function ReplaceAll(sStr, sDe, sPara){
	while (sStr.indexOf(sDe) != -1)
		sStr = sStr.replace(sDe, sPara)
	return sStr;
}

function desformataValor(valor){
	valor = ReplaceAll(ReplaceAll(valor, ".", ""),",",".");
	return valor;
}


function number_format( number, decimals, dec_point, thousands_sep ) {
    // %        nota 1: Para 1000.55 retorna com precisão 1 no FF/Opera é 1,000.5, mas no IE é 1,000.6
    // *     exemplo 1: number_format(1234.56);     				retorno 1: '1,235'
    // *     exemplo 2: number_format(1234.56, 2, ',', ' ');    	retorno 2: '1 234,56'
    // *     exemplo 3: number_format(1234.5678, 2, '.', '');		retorno 3: '1234.57'
    // *     exemplo 4: number_format(67, 2, ',', '.');				retorno 4: '67,00'
    // *     exemplo 5: number_format(1000);						retorno 5: '1,000'
    // *     exemplo 6: number_format(67.311, 2);					retorno 6: '67.31'

    var n = number, prec = decimals;
    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    var sep = (typeof thousands_sep == "undefined") ? ',' : thousands_sep;
    var dec = (typeof dec_point == "undefined") ? '.' : dec_point;

    var s = (prec > 0) ? n.toFixed(prec) : Math.round(n).toFixed(prec); //fix for IE parseFloat(0.55).toFixed(0) = 0;

    var abs = Math.abs(n).toFixed(prec);
    var _, i;

    if (abs >= 1000) {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;

        _[0] = s.slice(0,i + (n < 0)) +
              _[0].slice(i).replace(/(\d{3})/g, sep+'$1');

        s = _.join(dec);
    } else {
        s = s.replace('.', dec);
    }

    return s;
}


function acertaFoneDDD(campo){
	valor = campo.value;
	ddd = valor.substring(1,3);
	var primeiroDigito = '';

	if($("#tipo-telefone-id").val() == 102){ // 0800
	}else if($("#tipo-telefone-id").val() == 103){ // nextel
	}else{
		primeiroDigito = valor.substring(5,6);
		if(valor.length==2)
			campo.value = "("+valor+") ";
		if(valor.length==3)
			campo.value = valor+") ";
		if(ddd==11){
			if((primeiroDigito == '9')||(primeiroDigito == '8')){
				if(valor.length==10){
					if(event.keyCode != 8)
						campo.value = valor + "-";
					else
						campo.value = valor.substring(0,9);
				}
			}else{
				if(valor.length==9){
					if(event.keyCode != 8)
						campo.value = valor + "-";
					else
						campo.value = valor.substring(0,8);
				}
			}
		}else{
			if(valor.length==9){
				if(event.keyCode != 8)
					campo.value = valor + "-";
				else
					campo.value = valor.substring(0,8);
			}
		}
	}
}

function limitaFoneDDD(campo){
	valor = campo.value;

	ddd = valor.substring(1,3);
	var primeiroDigito = '';
	primeiroDigito = valor.substring(5,6);
 	if(ddd==11){
		if((primeiroDigito==9)||(primeiroDigito==8)){
			if(valor.length >= 14){
				return false;
			}
		}else
			if(valor.length >= 13){
				campo.value = valor.substring(0,13);
				return false;
			}
	}else{
		if(valor.length >= 13){
			campo.value = valor.substring(0,13);
			return false;
		}
	}

}

function validaEmail(email){
	valor = email.value;
	Arroba= valor.indexOf("@");
	Ponto= valor.lastIndexOf(".");
	espaco= valor.indexOf(" ");

	if (valor == '')
		return false;

	if  ((Arroba != -1) && (Ponto > Arroba +1) && (espaco==-1)){
		return true;
	}else{
		alert("E-mail Inválido");
		email.focus();
		return false;
	}
}

function validaEmailString(email){
	valor = email;
	Arroba= valor.indexOf("@");
	Ponto = valor.lastIndexOf(".");
	espaco = valor.indexOf(" ");
	if (valor == '')
		return false;
	if  ((Arroba != -1) && (Ponto > Arroba + 1) && (espaco == -1)){
		return true;
	}else{
		return false;
	}
}

function mascaraValAsterisco(objeto){
	campo = eval (objeto);
	if(((event.keyCode >= 48)&&(event.keyCode <= 57))||(event.keyCode == 42)){
		event.returnValue = true;
	}else{
	  event.returnValue = false;
	}
}

function mascaraVal(objeto){
	campo = eval (objeto);

	if(typeof event != 'undefined'){

		if((event.keyCode >= 48)&&(event.keyCode <= 57)){
			event.returnValue = true;
		}else{
		  event.returnValue = false;
		}

	}

}

function mascaraNum(objeto){
	campo = eval (objeto);
	if((event.keyCode >= 40)&&(event.keyCode <= 57)){
		event.returnValue = true;
	}else{
	  event.returnValue = false;
	}
}



function mascaraLetraNumero(formato, keypress, objeto){
    campo = eval (objeto);
	if(((event.keyCode >= 48)&&(event.keyCode <= 57))
	 ||((event.keyCode >= 65)&&(event.keyCode <= 90))
	 ||((event.keyCode >= 97)&&(event.keyCode <= 122))
	 ||(event.keyCode == 32)){
	 		event.returnValue = true;
	 	}else{
	 		event.returnValue = false;}
}

function acertaHora(campo){
	valor 	= campo.value;
	tamanho 	= valor.length;
	if(tamanho==2)
		campo.value = valor+':';
}

function formataMesAno(campo){
	valor 	= campo.value;
	tamanho 	= valor.length;
	if(tamanho==2)
		campo.value = valor+'/';
}

function validaHora(campo){
	if (campo.value.length>0){
		flag = 1;
		if (campo.value.length==5){
			arr = campo.value.split(":");
			if (arr.length==2){
				if ((parseInt(arr[0])<=23) && (parseInt(arr[0])<=59)){
					flag = 0;
				}
			}
		}
		if (flag==1){
			campo.focus();
			campo.select();
			alert("Hora Inválida");
		}
	}
}

function validaHoras(campo){
	if ((campo.value!="0:00")||(campo.value!="")){
		flag = 1;
		arr = campo.value.split(":");
		if (arr.length==2){
			if (parseInt(arr[1])<=59){
				flag = 0;
			}
		}
		if (flag==1){
			campo.focus();
			campo.select();
			alert("Horas Inválidas");
		}
	}
}


function acertaData(entrada){
	var meses, dias, anos;
	valor = entrada.value;
	//campo = eval(entrada);

	if(typeof event != 'undefined'){

		if ((event.keyCode!=46)&&(event.keyCode!=8)){
			if((valor.length==2)||(valor.length==5)){
				entrada.value = valor + "/";
				if(valida_data(valor,valor.length)==false){
				   alert('Data inválida');
				   entrada.value = "";
				   return false;
				}
			}
			if(valor.length==11){
				entrada.value = valor.substring(0,10)+ " " + valor.substring(10,11);
			}
			if(valor.length==13){
				entrada.value = valor + ":";
				if(valor.substring(11,13)>23){
					alert('Hora Inválida');
				entrada.value = valor.substring(0,11);
				return false;
			   }
			}
			if(valor.length==16){
				if(valor.substring(14,16)>59){
					alert('Hora Inválida');
				entrada.value = valor.substring(0,11);
				return false;
			   }
			}
			if(valor.length==10)
				if(valida_data(valor,valor.length)==false){
					alert('Data inválida');
				entrada.value = "";
				return false;
			}else{
					idade=(parseInt(anos)-parseInt(valor.substring(6,14)));
					if((parseInt(6)-parseInt(valor.substring(3,5)))<1)
					   idade = parseInt(idade) - 1;
					dia=(parseInt(6)-parseInt(valor.substring(0,2)));
					mes=parseInt(6)-parseInt(valor.substring(3,5));
						mes_dig= parseInt(valor.substring(3,5))
					if((parseInt(6)-parseInt(valor.substring(3,5)))==0)
						if(dia > 0)
					idade = parseInt(idade) + 1;
			}
			return true;
		}
	}

	
}

function valida_data(entrada, posicao){
	if(posicao ==2){
	  if(parseInt(entrada.charAt(0))+parseInt(entrada.charAt(1)) < 1)
	    return(false);
      if(parseInt(entrada)>31)
	      return(false);
	}
  if(posicao == 5){
	  if(parseInt(entrada.charAt(4))+parseInt(entrada.charAt(3)) < 1)
	    return(false);
    if(parseInt(entrada.charAt(3)+entrada.charAt(4)) > 12)
      return(false);
    if(parseInt(entrada.charAt(3))+parseInt(entrada.charAt(4))%2==0)
      if((parseInt(entrada.charAt(0))+parseInt(entrada.charAt(1))>30)&&(parseInt(entrada.charAt(3))+parseInt(entrada.charAt(4))!=12))
      	return(false);
      if((parseInt(entrada.charAt(3))+parseInt(entrada.charAt(4))==2))
         if((parseInt(entrada.charAt(0))+parseInt(entrada.charAt(1))>29))
            return(false);
   }
   if(posicao == 10){
      if((parseInt(entrada.charAt(8))+parseInt(entrada.charAt(9)))<0)
        return false;
      if(((parseInt(entrada.charAt(3))==0)&(parseInt(entrada.charAt(4))==2)))
         if(entrada.charAt(0)+entrada.charAt(1)>28)
	        if((parseInt(entrada.charAt(9)+entrada.charAt(9))%4)!=0)
          	return(false);
   }
}

/********* Inicio Funções Ordena tabela ***********/
$(document).ready(function(){
	$(".ordena-tabela").click(function() {
		ordena = $("#ordena-tipo").val();
		if(ordena == "") $("#ordena-tipo").val('asc');
		if(ordena == "asc") $("#ordena-tipo").val('desc');
		if(ordena == "desc") $("#ordena-tipo").val('asc');
		$("#ordena-tabela").val($(this).attr('id'));
		$("#frmDefault").submit();
	});
	validaModulo();
});



function validaModulo(){
	if($("#pagina-modulo").attr('modulo') >= 1){
		setTimeout("paginaModulo()",100);
		setTimeout("paginaModulo()",1000);
		setTimeout("paginaModulo()",3000);
		setTimeout("paginaModulo()",5000);
	}
}
function paginaModulo(){
	$("input").attr('disabled','disabled');
	$("select").attr('disabled','disabled');
	$("textarea").attr('disabled','disabled');
	$(".btn-novo").hide();
	$(".btn-excluir").hide();
	$(".btn-editar").hide();
}

function validaData(objeto){
	erro = false;
	idObjeto = objeto.id;
	$("#"+idObjeto).css('background-color', '').css('outline', '');
	if (objeto.value!=""){
		arr = objeto.value.split("/");
		if (arr.length<3){
			erro = true;
		}
		else{
			var dia = arr[0];
			var mes = arr[1];
			var ano = arr[2];
			if(isNaN(dia) || isNaN(mes) || isNaN(ano)) erro = true;
			if(mes > 12 || mes < 1 || ano < 1900 || dia < 1) erro = true;
			if(mes == 2 ){
				maiorDia = (((!(ano % 4)) && (ano % 100) ) || (!(ano % 400)))? 29: 28;
				if( dia > maiorDia ) erro = true;
			}
			else{
				if(mes == 4 || mes == 6 || mes == 9 || mes == 11){
					if(dia > 30) erro = true;
				}
				else {
					if(dia > 31) erro = true;
				}
			}
		}
	}
	if (erro){
		$("#"+idObjeto).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD').focus();
		$("#"+idObjeto).select();
		$("#"+idObjeto).focus();
	}
}

function pad(num, size) {
    var s = "000000000" + num;
    return s.substr(s.length-size);
}

function adicionarMesData(d, m){
	dia = parseInt(d.substring(0,2));
	mes = parseInt(d.substring(3,5));
	ano = d.substring(6,10);
	anos = 0;
	meses = parseInt(mes) + parseInt(m);
	if (meses>12){
		anos = parseInt(meses/12);
		meses = parseInt(meses%12);
		ano = parseInt(ano) + parseInt(anos);
	}
	mes = meses;
	novaData = pad(dia,2) + '/'+ pad(mes,2) + '/' + pad(ano,4);
	return(novaData);
}

/*
function addMonth(valor){
	var dtHoje = new Date();
	dtHoje.setMonth(dtHje.getMonth() + valor);//lembrando que o mes é um inteiro de 0-11
}
*/

function atualizaCoordenada(){
	logradouro 	= document.frmProdutos.txtLogradouro.value;
	bairro 		= document.frmProdutos.txtBairro.value;
	cidade		= document.frmProdutos.txtCidade.value
	uf 			= document.frmProdutos.txtUF.value
	numero		= document.frmProdutos.txtNumero.value;

	endereco =  logradouro+" "+numero+" "+bairro+" "+cidade+" "+uf;

	geocoder = new google.maps.Geocoder();
	var address = endereco
	geocoder.geocode( {
		'address' : address
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			document.getElementById('hdCoordenada').value = results[0].geometry.location;
		}
	});
}

$(".menu-interno-superior").live('click keypress', function () {
	idMenu  = $(this).attr('attr-div');
	posMenu = $(this).attr('attr-pos');
	grupo	= ".grupo"+$(this).attr('attr-pos');
	$(".menu-interno-modulo").removeClass('menu-interno-superior-selecionado').addClass('menu-interno-superior');
	$("#menu-superior-"+posMenu).removeClass('menu-interno-superior').addClass('menu-interno-superior-selecionado');
	$(".titulo-container").hide();
	$(idMenu).show();
	$(grupo).show();
});


function enviaOrcamento(id){
	if(confirm("                    Confirma o envio do orçamento?\n\n Se confirmado este orçamento não poderá ser alterado.")){
		$("form").attr('action', "./?oc="+$("#attr-oc").val()+"&status=ok");
		$("form").submit();
	}
}


var delay = (function(){
	var timer = 0;
		return function(callback, ms){
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
	};
})();

/*
$('input').keyup(function() {
    delay(function(){
      alert('Time elapsed!');
    }, 1000 );
});
*/


$(document).ready(function(){
	$('.fancybox').fancybox({
		width: '90%',
	});

	$("#fancybox-manual-b").click(function() {
		$.fancybox.open({
			href : 'iframe.html',
			type : 'iframe',
			width: '90%',
			padding : 2
		});
	});

	$(document).bind('keydown keyup', function(e) {
	    if(e.which === 112)
			return false;
	})
})


function abreOpcoesSelect(idSelect) {
    if (document.createEvent) {
        var e = document.createEvent("MouseEvents");
        e.initMouseEvent("mousedown", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        idSelect[0].dispatchEvent(e);
    } else if (element.fireEvent) {
        idSelect[0].fireEvent("onmousedown");
    }
}

function iniciaContador(d, campo){
	if(d.length==16){
		dia = (parseInt(d.substring(0,2)));
		mes = (parseInt(d.substring(3,5))-1);
		ano = (parseInt(d.substring(6,10)));
		hora = (parseInt(d.substring(11,13)));
		minuto = (parseInt(d.substring(14,16)));
		var dataContador = new Date(ano, mes, dia, hora, minuto, 0, 0);
		$('#'+campo).html("Carregando...");
		$('#'+campo).countdown('destroy');
		$('#'+campo).countdown({until: dataContador, serverSync: serverTime, compact: true,  format: 'DHMS', description: ''});
	}
}

function serverTime() {
    var time = null;
    formato = 'M j, Y H:i:s O';
    $.ajax({url: caminhoScript+"/funcoes/carregar-data-atual.php?tipo=d&formato="+formato,
        async: false, dataType: 'text',
        success: function(text) {
            time = new Date(text);
        }, error: function(http, message, exc) {
            time = new Date();
    }});
    return time;
}


$(".paginador").live('click keypress', function () {
	$(".paginador-selecionado").removeClass('paginador-selecionado').addClass('paginador');
	$(this).removeClass('paginador').addClass('paginador-selecionado');
	$("#posicao-paginador").remove();
	$("#paginador-container").append("<input type='hidden' name='posicao-paginador' value='"+$(this).attr('attr-pagina')+"' id='posicao-paginador'>");
	var idTabela = "#"+$(this).attr("attr-tabela");
	$('html, body').animate({ scrollTop: 130 }, 500);
	caminho = caminhoScript+"/funcoes/ajax-paginador-gera-tabela.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$(idTabela).html(retorno);
		}
	});
});


/*
var myTimer = setInterval(function(){decrementa()}, 1000);
function decrementa(){
	$('#timer-count').val($('#timer-count').val()-1);
	if($('#timer-count').val()<=0){
		$('#timer-count').val(document.getElementById('timer-count').defaultValue)
		caminho = caminhoScript+"/includes/renova-login.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				return true;
			}
		});
	}
}
*/


$('.filtra-coluna-tabela').live('click', function () {
	coluna = "."+$(this).val();
	if($(this).attr('checked')=='checked'){
		$(coluna).show();
	}else{
		$(coluna).hide();
	}
});



$(document).ready(function(){
	alturaFiltro = $('#tabela-filtro-lateral').height();
	$('#tabela-filtro-lateral').css('bottom', ($('#tabela-filtro-lateral').height()*-1)+35);
	$('#tabela-filtro-lateral').show();
});


$('#tabela-filtro-lateral').live('click', function () {
	if($(this).attr('attr-status')=='fechada'){
		$('#tabela-filtro-lateral' ).animate({marginBottom: ($('#tabela-filtro-lateral').height())-25,}, 500 );
		$(this).attr('attr-status','aberta');
		$("#tabela-filtro-lateral p").attr('id','fechar-filtro-lateral');
		return false;
	}
});


$('#fechar-filtro-lateral').live('click', function () {
	$('#tabela-filtro-lateral').animate({marginBottom: '0',}, 500 );
	$('#tabela-filtro-lateral').attr('attr-status','fechada');
	$("#tabela-filtro-lateral p").attr('id','');
	return false;
});

$('#salvar-filtro-lateral').live('click', function () {
	$.ajax({type: "POST",url: caminhoScript+"/funcoes/salva-configuracao-relatorio.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$('#tabela-filtro-lateral').animate({marginBottom: '0',}, 500 );
			$('#tabela-filtro-lateral').attr('attr-status','fechada');
		}
	});
});


/* INICIO FUNCOES DE EDIÇÃO DE TIPOS MULTINIVEL */

$("#cadastra-nova-tarefa").live('click', function () {
	if ($('#tipo-grupo').val()=='28'){
		$('#pai').addClass('required');
	}
	if (validarCamposGenerico('.required')){
		$("#frmDefault").submit();
	}
});

$("#nome").live('keyup', function () {
	if($("#nome").val() != "")
		$("#nome").css('background-color', '').css('outline', '');
});
$(".btn-excluir-tipo").live('click', function () {
	$("#tipo-excluir").val($(this).attr('id'))
	$("#frmDefault").submit();
});
$(".btn-editar-tipo").live('click', function () {
	$("html, body").stop().animate({scrollTop:0}, '500', 'swing', function() { });
	$("#tipo-id").val($(this).attr('id'));
	$("#nome").val($(this).attr('descr')).select().focus();
	$("#pai").val($(this).attr('pai')).trigger('chosen:updated');
	$("#cadastra-nova-tarefa").attr('value', 'Atualizar');
	$("#cancela-edita-tarefa").show();
});
$("#cancela-edita-tarefa").live('click', function () {
	$("#tipo-id").val('');
	$("#nome").val('');
	$("#pai").val('').trigger('chosen:updated');
	$("#cadastra-nova-tarefa").attr('value', 'Cadastrar');
	$("#cancela-edita-tarefa").hide();
});

$("#tarefa-seleciona-tarefa-id").live('change', function () {
	tarefaID = $(this).val();
	tempoExecucao = $("#tarefa-seleciona-tarefa-id option:selected").attr("tempo-execucao");
	/*
	arrGrupo = $("#tarefa-seleciona-tarefa-id option:selected").attr("grupos").split(",");
	if(arrGrupo.length==0)
		$("#tarefa-grupo-responsavel option").attr('disabled',false);
	else{
		$("#tarefa-grupo-responsavel option").attr('disabled',true);
		for (i=0; i <= arrGrupo.length; i++){
			$("#tarefa-grupo-responsavel option[value='"+arrGrupo[i]+"']").attr('disabled',false);
		}
	}
	*/
	$("#tempo-execucao").val($("#seleciona-tarefa-id option:selected").attr("tempo-execucao"));
	$.ajax({type: "POST",url: caminhoScript+"/modulos/projetos/projetos-tarefa-carregar-dados.php?tarefa-id="+tarefaID, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			aleatorio = $('#tarefa-aleatorio').val();
			tinyMCE.get("texto-descricao-tarefa-" + aleatorio).setContent(retorno);
		}
	});
	$("#tarefa-grupo-responsavel").trigger('chosen:updated');
});

/* INICIO - Funcoes de Usuarios Grupos*/

$(".select-grupo-atualiza-usuarios").live('change', function () {
	campo = $(this).attr('campo');
	//alert(campo)
	$.ajax({type: "POST",url: caminhoScript+"/funcoes/localiza-usuarios-grupo.php?grupo-id="+$(this).val(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#" + campo).empty().append(retorno).trigger('chosen:updated');
		}
	});
});

/* FIM - Funcoes de Usuarios Grupos*/

/* FIM FUNCOES DE EDIÇÃO DE TIPOS MULTINIVEL */

/********* Inicio Funções tarefas ***********/
$("#select-grupo-tarefa").live('change', function () {
	campo = $(this).attr('campo');
	$("#div-select-usuario-tarefa").load(caminhoScript+"/modulos/chamados/chamados-localiza-usuarios-grupo.php?grupo="+$(this).val()+"&campo="+campo);
});

$(".botao-incluir-tarefa").live('click', function () {
	projetoVinculoID = $(this).attr('projeto-vinculo-id');
	$("#projeto-vinculo-id").val(projetoVinculoID);
	$(".bloco-incluir-tarefa-geral").show();
	$(".botao-incluir-tarefa, .botao-incluir-projeto").hide();
});

$(".incluir-tarefa-geral").live('click', function () {
	tinyMCE.triggerSave();
	if (validarCamposGenerico(".bloco-incluir-tarefa-geral .required")){
		//$(".bloco-incluir-tarefa-geral").hide();
		$.ajax({type: "POST",url: caminhoScript+"/modulos/projetos/projetos-cadastra-tarefa-geral.php", data: $("form .campos-tarefas, form .campos-projeto").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				alert('Tarefa Incluida com sucesso');
				if ($('#sinalizador-pagina').length){
					//$('body').append(retorno);
					parent.$.fancybox.close();
					$("#frmDefault").submit();
				}
				else{
					chaveEstrangeira = $("#chave-estrangeira-tarefa").val();
					tabelaEstrangeira = $("#tabela-estrangeira-tarefa").val();
					campoEstrangeiro = $("#campo-estrangeiro-tarefa").val();
					cadastroAlvoID = $("#cadastro-alvo-id-tarefa").val();
					carregarTarefasProcessoGeral(chaveEstrangeira, tabelaEstrangeira, campoEstrangeiro, cadastroAlvoID);
				}
				//$(".div-retorno").html(retorno);
			}
		});
	}
	//$(".bloco-incluir-tarefa-geral").hide();
	//$(".botao-incluir-tarefa, .botao-incluir-projeto").show();
});

$(".cancelar-incluir-tarefa").live('click', function () {
	if ($('#sinalizador-pagina').length){
		parent.$.fancybox.close()
	}
	else{
		$(".bloco-incluir-tarefa-geral").hide();
		$(".botao-incluir-tarefa, .botao-incluir-projeto").show();
	}
});
$(".botao-incluir-projeto").live('click', function () {
	$(".bloco-incluir-projeto-geral").show();
	$(".botao-incluir-projeto").hide();
});
$(".botao-cancelar-incluir-projeto-geral").live('click', function () {
	$(".bloco-incluir-projeto-geral").hide();
	$(".botao-incluir-projeto").show();
});

$(".botao-incluir-projeto-geral").live('click', function () {
	if (validarCamposGenerico(".bloco-incluir-projeto-geral .required")){
		//alert($("form .campos-projeto").serialize());
		$.ajax({type: "POST",url: caminhoScript+"/modulos/projetos/projetos-cadastra-projeto-geral.php", data: $("form .campos-projeto").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				alertify.alert('<b>Aviso</b>','Projeto Inclu&iacute;do com Sucesso');
				chaveEstrangeira = $("#chave-estrangeira-tarefa").val();
				tabelaEstrangeira = $("#tabela-estrangeira-tarefa").val();
				campoEstrangeiro = $("#campo-estrangeiro-tarefa").val();
				cadastroAlvoID = $("#cadastro-alvo-id-tarefa").val();
				carregarTarefasProcessoGeral(chaveEstrangeira, tabelaEstrangeira, campoEstrangeiro, cadastroAlvoID);
				//$(".div-retorno").html(retorno);
			}
		});
	}
});


$("#botao-adiciona-tarefa-chamado").live('click', function () {
	var erro = 0;
	$(".required").each(function( i ) {
		$(this).css('background-color', '').css('outline', '');
		if($(this).val()==""){
			$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			erro = 1;
		}
	});
	if(erro==0){
		$.ajax({type: "POST",url: caminhoScript+"/modulos/chamados/chamados-cadastra-tarefa.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$(".required").each(function( i ) {
					$(this).val('');
				});
				$("#select-usuario-tarefa").val('');
				$("#lista-tarefas-cadastradas").html(retorno);
			},
		});
	}
});
$(".required").live('change keyup', function () {
	if($(this).val() != ""){
		$(this).css('background-color', '').css('outline', '');
		$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen a").css('background-color', '').css('outline', '');
		$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen ul").css('background-color', '').css('outline', '');
	}
});


$("#botao-finaliza-tarefa").live('click', function () {
	var erro = 0;
	$(".required").each(function( i ) {
		$(this).css('background-color', '').css('outline', '');
		if($(this).val()==""){
			$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			if(erro==0) $(this).focus();
			erro = 1;
		}
	});
	if(erro==0){
		$.ajax({type: "POST",url: caminhoScript+"/modulos/projetos/projetos-cadastra-follow-tarefa.php?finaliza=s", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				parent.$.fancybox.close();
				chaveEstrangeira = parent.$("#chave-estrangeira-tarefa").val();
				tabelaEstrangeira = parent.$("#tabela-estrangeira-tarefa").val();
				campoEstrangeiro = parent.$("#campo-estrangeiro-tarefa").val();
				cadastroAlvoID = parent.$("#cadastro-alvo-id-tarefa").val();
				parent.carregarTarefasProcessoGeral(chaveEstrangeira, tabelaEstrangeira, campoEstrangeiro, cadastroAlvoID);
				
				/*
				$(parent.document.getElementById('horas-utilizadas')).load(caminhoScript+"/modulos/projetos/projetos-horas-utilizadas-tarefa.php?wID="+parent.document.getElementById('workflow-id').value);
				if(parent.document.getElementById('lista-tarefas-cadastradas'))
					parent.document.getElementById('lista-tarefas-cadastradas').innerHTML = retorno;

				campo = "tarefa-edita-"+$("#tarefa-id").val()
				parent.document.getElementById(campo).innerHTML = "<img src='../images/geral/disponivel.png' class='tarefa-finalizada' title='Visualizar Tarefa Finalizada'>";
				parent.$.fancybox.close();
				*/
				//parent.location.reload();
			},
		});
	}
});
$("#botao-adiciona-observacao-follow").live('click', function () {
	var erro = 0;
	$(".required").each(function( i ) {
		$(this).css('background-color', '').css('outline', '');
		if($(this).val()==""){
			$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			if(erro==0) $(this).focus();
			erro = 1;
		}
	});
	if(erro==0){
		$.ajax({type: "POST",url: caminhoScript+"/modulos/projetos/projetos-cadastra-follow-tarefa.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				parent.$.fancybox.close();
				chaveEstrangeira = parent.$("#chave-estrangeira-tarefa").val();
				tabelaEstrangeira = parent.$("#tabela-estrangeira-tarefa").val();
				campoEstrangeiro = parent.$("#campo-estrangeiro-tarefa").val();
				cadastroAlvoID = parent.$("#cadastro-alvo-id-tarefa").val();
				parent.carregarTarefasProcessoGeral(chaveEstrangeira, tabelaEstrangeira, campoEstrangeiro, cadastroAlvoID);
			},
		});
	}
});

$("#botao-cancela-tarefa").live('click', function () {
	$.ajax({type: "POST",url: caminhoScript+"/modulos/chamados/chamados-cancela-tarefa.php?wID="+parent.document.getElementById('workflow-id').value, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			parent.$.fancybox.close();
		},
	});
});


/*
$(".calendario-mes").live('click', function () {
	data = $(this).attr('attr-mes-ano').split("|");
	$("#mes-calendario").val(data[0]);
	$("#ano-calendario").val(data[1]);
	$("#frmDefault").submit();
});

$("#botao-pesquisar-calendario").live('click', function () {
	$("#frmDefault").submit();
});
*/
/*
$(".finaliza-edita-chamado, .altera-data-retorno-tarefa").live('hover', function () {
	$(this).attr('src',caminhoScript+'/images/geral/ico-editar-var-produto-hover.png');
});

$(".finaliza-edita-chamado, .altera-data-retorno-tarefa").live('mouseout', function () {
	$(this).attr('src',caminhoScript+'/images/geral/ico-editar-var-produto.png');
});

$(".altera-data-retorno-tarefa-confirma").live('hover', function () {
	$(this).attr('src',caminhoScript+'/images/geral/ico-atualizar-var-produto-hover.png');
});

$(".altera-data-retorno-tarefa-confirma").live('mouseout', function () {
	$(this).attr('src',caminhoScript+'/images/geral/ico-atualizar-var-produto.png');
});
*/
/* COLOCAR ABAIXO DENTRO DE PROJETOS*/
$(".altera-tarefa").live('click', function () {
	$(".bloco-acoes-tarefas").hide();
	$(".dados-estatico").addClass('esconde');
	$(".dados-alteravel").removeClass('esconde');
	$("#campo-data-retorno-tarefa, #descricao-inicial").attr('readonly', false);
	$("#campo-data-retorno-tarefa").click();
});
$(".altera-tarefa-cancela").live('click', function () {
	$(".bloco-acoes-tarefas").show();
	$(".dados-estatico").removeClass('esconde');
	$(".dados-alteravel").addClass('esconde');
	$("#campo-data-retorno-tarefa, #descricao-inicial").attr('readonly', true);
});

$(".altera-tarefa-confirma").live('click', function () {
	if (validarCamposGenerico('.dados-gerais-tarefa .required, #descricao-tarefa-follow')){
		$.ajax({type: "POST",url: caminhoScript+"/modulos/projetos/projetos-tarefa-alterar-dados.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){			
				chaveEstrangeira = parent.$("#chave-estrangeira-tarefa").val();
				tabelaEstrangeira = parent.$("#tabela-estrangeira-tarefa").val();
				campoEstrangeiro = parent.$("#campo-estrangeiro-tarefa").val();
				cadastroAlvoID = parent.$("#cadastro-alvo-id-tarefa").val();
				parent.carregarTarefasProcessoGeral(chaveEstrangeira, tabelaEstrangeira, campoEstrangeiro, cadastroAlvoID);				
				parent.$.fancybox.close();
			}
		});
	}
});

/*
function carregarTarefasProcesso(chaveEstrangeira, tabelaEstrangeira){
	caminho = caminhoScript+"/modulos/projetos/projetos-carregar-tarefas.php?chaveEstrangeira=" + chaveEstrangeira + "&tabelaEstrangeira=" + tabelaEstrangeira;
	$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			parent.document.getElementById('div-tarefas').innerHTML = retorno;
		}
	});
}
*/

function carregarTarefasProcessoGeral(chaveEstrangeira, tabelaEstrangeira, campoEstrangeiro, cadastroAlvoID){
	$('#div-tarefas-cadastradas-geral').hide();
	caminho = caminhoScript+"/modulos/projetos/projetos-carregar-tarefas.php?chaveEstrangeira=" + chaveEstrangeira + "&tabelaEstrangeira=" + tabelaEstrangeira + "&campoEstrangeiro=" + campoEstrangeiro + "&cadastroAlvoID=" + cadastroAlvoID;
	console.log(caminho);
	$.ajax({type: "POST",url: caminho, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			//alert('OK');
			$('#div-tarefas-cadastradas-geral').html(retorno).delay("fast").fadeIn();
        }
		/*
		, 
		error: function(http, message, exc){
			alert('FALSE');
			console.log(http);
			console.log(message);
			console.log(exc);
		}
		*/
	});
}


// TONI, FUNÇÃO ABAIXO TA DANDO CONFLITO EM ALGUMAS TELAS QUE POSSUI CAMPOS COM REQUIRED, E O FANCY BOX É USADO PARA ABRIR DADOS DA PESSOA TB
/*
$(".fancybox-overlay, .fancybox-close, .fancybox-close").live('click', function () {
	$.ajax({type: "POST",url: caminhoScript+"/modulos/chamados/chamados-cadastra-tarefa.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$(".required").each(function( i ) {
				$(this).val('');
			});
			$("#select-usuario-tarefa").val('');
			$("#lista-tarefas-cadastradas").html(retorno);
			campoHoras = parent.document.getElementById('horas-utilizadas');
			$(campoHoras).load(caminhoScript+"/modulos/chamados/chamados-horas-utilizadas-tarefa.php?wID="+parent.document.getElementById('workflow-id').value);
		},
	});
});
*/

$(document).ready(function(){
	if ($("#slug-pagina").val()=="chamados-relatorio-tarefas"){
		exibeBotaoGraficos();
		$("#select-agrupar").live('change', function () {
			exibeBotaoGraficos();
		});
	}
});

function exibeBotaoGraficos(){
	if (($("#select-agrupar").val()=="chamado") || ($("#select-agrupar").val()=="solicitante")||($("#select-agrupar").val()=="responsavel"))
		$(".exibe-graficos").show();
	else
		$(".exibe-graficos").hide();
}


$("#botao-encaminha-tarefa").live('click', function () {
	var erro = 0;
	$("#descricao-tarefa-follow").css('background-color', '').css('outline', '');
	if($("#descricao-tarefa-follow").val()==""){
		$("#descricao-tarefa-follow").css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
		if(erro==0) $("#descricao-tarefa-follow").focus();
		erro = 1;
	}
	if(erro==0){
		$("#cadastra-observacoes-tarefa").hide();
		$("#observacoes-cadastradas-tarefa").hide();
		$("#container-encaminha-tarefa").show();
	}
})

$(".required-e").live('change keyup', function () {
	if($(this).val() != "")$(this).css('background-color', '').css('outline', '');
});

$("#botao-encaminha-tarefa-finaliza").live('click', function () {
	var erro = 0;
	$(".required-e").each(function( i ) {
		$(this).css('background-color', '').css('outline', '');
		if($(this).val()==""){
			$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
			if(erro==0) $(this).focus();
			erro = 1;
		}
	});
	if(erro==0){
		$.ajax({type: "POST",url: caminhoScript+"/modulos/chamados/chamados-cadastra-follow-tarefa.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$.ajax({type: "POST",url: caminhoScript+"/modulos/chamados/chamados-cadastra-follow-tarefa.php?finaliza=s&encaminhada=s", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$.ajax({type: "POST",url: caminhoScript+"/modulos/chamados/chamados-cadastra-tarefa.php?encaminhada=s", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
							success: function(retorno){
								parent.document.getElementById('lista-tarefas-cadastradas').innerHTML = retorno;
								campoHoras = parent.document.getElementById('horas-utilizadas');
								$(campoHoras).load(caminhoScript+"/modulos/chamados/chamados-horas-utilizadas-tarefa.php?wID="+parent.document.getElementById('workflow-id').value);
								parent.$.fancybox.close();
							},
						});
					},
				});
			},
		});
	}
});

$("#botao-cancela-tarefa-finaliza").live('click', function () {
		$("#cadastra-observacoes-tarefa").show();
		$("#observacoes-cadastradas-tarefa").show();
		$("#container-encaminha-tarefa").hide();
});



/* Função generica para validar campos*/
function validarCamposGenerico(campos){
    var flag = true;
    $(campos).each(function(i) {
		if (($(this).attr('type')=='radio') || ($(this).attr('type')=='checkbox')){

		}
		else{
			// se for select multiple
			if (($(this).is('select')==true) && ($(this).attr('multiple')=='multiple')) {
				if (($("#" + $(this).attr('id') + " :selected").length) == 0){
					$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen ul").css('background-color', '#FFE4E4');
					flag = false;
				}
			}
			else{
				//console.log($(this).attr('type') + " | " + " | " + $(this).is('multiple'));
				$(this).css('background-color', '').css('outline', '');
				if($(this).val().trim()==""){
					$(this).css('background-color', '#FFE4E4');
					if($(this).is('select')==true){
						$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen a").css('background-color', '#FFE4E4');
					}
					flag = false;
				}
				else{
					if ($(this).hasClass('zero-nao')){
						if (desformataValor($(this).val())==0){
							$(this).css('background-color', '#FFE4E4');
							if($(this).is('select')==true) {
								$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen a").css('background-color', '#FFE4E4');
							}
							flag = false;
						}
					}
					if ($(this).hasClass('valida-email')){
						if(!(validateEmail($(this).val()))){
							$(this).css('background-color', '#FFE4E4');
							flag = false;
						}
					}
				}
			}
		}
		//console.log($(this).attr('id') + " -- " + $(this).attr('name') + "é esse:" + flag);
    });
    return flag;
}
/*
CAMPOS GENERICA ANTIGA
function validarCamposGenerico(campos){
	var flag = true;
	$(campos).each(function(i) {
		$(this).css('background-color', '').css('outline', '');
		if($(this).val().trim()==""){
			$(this).css('background-color', '#FFE4E4');
			if($(this).is('select')==true) {
				//alert("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen a");
				$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen a").css('background-color', '#FFE4E4');
			}
			flag = false;
		}
	});
	return flag;
}
*/

/*
function verificarDuplicidadeCadastro(campo, excessao){
	retorno = "";
	if((soNumero(campo.val()).length)>0){
		caminho = caminhoScript+"/modulos/cadastros/cadastro-validar-duplicidade.php?campo="+campo.val()+"&cadastro-id="+excessao;
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				//retorno = retorno;
				return retorno;
			}
		});
	}
}
*/

$(document).ready(function(){
	if (typeof arrayCamposForm !== "undefined"){
		for (var i=0; i < arrayCamposForm.length; i++) {
			$("#"+arrayCamposForm[i]).addClass('required');
		}
	}
});

function validaCamposObrigatorios(){
	var erro = 0;
	$(".required").each(function(i) {
		$(this).css('background-color', '').css('outline', '');
		if($(this).val().trim()==""){
			$(this).css('background-color', '#FFE4E4');
			if($(this).is('select')==true) {
				$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen a").css('background-color', '#FFE4E4');
				if($(this).attr('multiple')=='multiple') {
					$("#" + ReplaceAll($(this).attr('id'),"-","_") + "_chosen ul").css('background-color', '#FFE4E4');
				}
			}
			erro = 1;
		}
	});
	if(erro==1)
		return false;
	else
		return true;
}

$(document).ready(function(){
	var abriuCamera = false;
	$(".btn-abrir-camera").live('click', function () {
		if (!(abriuCamera)){
			$(".div-aviso-camera").show();
			var canvas = document.getElementById("canvas"),
				context = canvas.getContext("2d"),
				video = document.getElementById("video"),
				videoObj = { "video": true },
				errBack = function(error) {
					$(".div-aviso-camera").show();
				};
			// Put video listeners into place
			if(navigator.getUserMedia) { // Standard
				navigator.getUserMedia(videoObj, function(stream) {
					video.src = stream;
					video.play();
					carregarCamera();
				}, errBack);
			} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
				navigator.webkitGetUserMedia(videoObj, function(stream){
					video.src = window.webkitURL.createObjectURL(stream);
					video.play();
					carregarCamera();
				}, errBack);
			} else if(navigator.mozGetUserMedia) { // WebKit-prefixed
				navigator.mozGetUserMedia(videoObj, function(stream){
					video.src = window.URL.createObjectURL(stream);
					video.play();
					carregarCamera();
				}, errBack);
			}
		}
		else{
			carregarCamera();
		}
	});

	function carregarCamera(){
		abriuCamera = true;
		$(".bloco-camera").show();
		$(".btn-abrir-camera").hide();
		$(".div-aviso-camera").hide();
	}

	$(".foto-capturar").live('click', function () {
		$(".bloco-foto-captura").hide();
		$(".bloco-foto-capturada").show();
		$("#foto-flag").val("1");
		var canvas = document.getElementById("canvas"),
						context = canvas.getContext("2d"),
						video = document.getElementById("video");
		context.drawImage(video, 143, 4, 640, 480, 0, 0, 640, 480);
	});
	$(".foto-descartar").live('click', function () {
		$("#foto-flag").val("");
		$(".bloco-foto-capturada").hide();
		$(".bloco-foto-captura").show();
	});
	$(".foto-cancelar").live('click', function () {
		$(".bloco-camera").hide();
		$(".btn-abrir-camera").show();
	});

	$(".salvar-dados-usuario-basico").live('click', function () {
		obj = document.getElementById('canvas').toDataURL("image/png");
		$("#image-hidden").val(obj);
		caminho = caminhoScript+"/funcoes/dados-basicos-salvar.php";
		$('#frmAux').attr("action",caminho);
		$('#frmAux').submit();
	});

});
/*
$(document).ready(function(){
	var ajaxLoading;
	ajaxTimerCount();
	clearTimeout(ajaxLoading);
	ajaxLoading = setInterval("ajaxTimerCount()", 30000);
});

function ajaxTimerCount(){
	$.ajax({type: "POST",url: caminhoScript+"/funcoes/functions-loading.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
//			$("#rodape-container").html(retorno);
		}
	});
	return false;
}
*/
$(document).ready(function(){
	$("#tarefa-data-limite").click();
});

/* SALVAR CONFIGURACOES GERAIS TODOS MODULOS */
$(".botao-salvar-configuracoes-gerais").live('click', function () {
	caminho = caminhoScript+"/funcoes/salvar-configuracoes-gerais.php";
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			//$("#div-retorno").html(retorno);
			alertify.alert('<b>Aviso</b>','Configurações atualizadas!');
			$("#frmDefault").submit();
		}
	});
});

/**/
/* GERANDO PDF EXCEL E HTML IMPRESSO PARA CONSULTAS QUE USEM O FILTRO LATERAL*/
$(".btn-gerar-all").live('click', function () {
	//oldAction = $("#frmDefault").attr("action");
	//alert(oldAction);
	$("#frmDefault").attr("target","_blank");
	$("#frmDefault").attr("action",caminhoScript+"/modulos/administrativo/documentos-gerar-arquivos.php?tipo="+$(this).attr('tipo'));
	$("#frmDefault").submit();
	$("#frmDefault").attr("target","");
	$("#frmDefault").attr("action","");
	//$("#frmDefault").attr("action",oldAction);
});
/**/

$(document).ready(function(){
	/* Atualiza a session */
	if (!($('#botao-login').length)){
		var reloadSessao = function () {
			console.log("validandoSessao");
			$.ajax({type: "POST", url: caminhoScript+"/includes/valida-session-login.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					if (retorno=='false'){
						$("#frmDefault").attr("action",caminhoScript+"/sair.php");
						$("#frmDefault").submit();
					}
				}
			});
			setTimeout(reloadSessao,60000);
		}
		reloadSessao();
	}
});


/* FUNCOES GERA TABELA */

$(document).ready(function(){
	$(".lnk").live({
		mouseover: function() {
			$(this).addClass("over");
			$(this).find('td').addClass("lnk-over");
		},
		mouseout: function() {
			$(this).removeClass("lnk-over");
			$(this).find('td').removeClass("lnk-over");
		}
	});
});


/* FUNCOES FORMULARIOS DINAMICOS */

$('.salvar-formulario-dinamico-generico').live('click', function () {
	salvarValidarFormulariosDinamicos('reload');
});

function salvarValidarFormulariosDinamicos(fluxo){
	if ($('#slug-pagina').val()=="formularios-dinamicos-gerenciar"){
		alertify.alert('<b>Aviso</b>','Curiosidade? Ou vc não sabe o que esta fazendo aqui?');
		return false;
	}
	$('.salvar-formulario-dinamico-generico').hide();
	if (validarCamposGenerico(".form-formulario-dinamico-generico .required")){
		caminho = caminhoScript+"/modulos/administrativo/formularios-dinamicos-salvar-formulario.php";
		$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$('.salvar-formulario-dinamico-generico').hide();
				if (fluxo=='reload'){
					$("#frmDefault").submit();
				}
				else{
					return true;
				}
			}
		});
	}
	else{
		$('.salvar-formulario-dinamico-generico').show();
		return false;
	}
}
