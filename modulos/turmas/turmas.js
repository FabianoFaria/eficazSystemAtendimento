$(document).ready(function(){
	/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
	$("#menu-superior-3").live('click', function () {
		carregarDocumentos($("#id-turma").val(),'turmas');
	});
	$(".mascara-cep").mask("99999-999");


	slugPagina = $("#slug-pagina").val();
	if (slugPagina=="turmas-localizar-turma"){
		$(document).keypress(function(event) {if(event.keyCode==13){ $("#turma-localiza").click(); }});
	}

	$(".workflow-localiza").live('click', function () {
		$("#workflow-id").val($(this).attr('workflow-id'));
		$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-cadastro-chamado");
		$("#frmDefault").submit();
	});
});



/* Função Upload Inicio */
$(function(){
    $('#iframe-upload-cadastro').load(function(){
		arquivo = $("#iframe-upload-cadastro")[0].contentWindow.document.body.innerHTML;
		$("#div-foto").html("<img src='"+caminhoScript+"/uploads/"+arquivo+"' width='100%' id='imagem-foto' Style='cursor:pointer'><input type='hidden' name='arquivo-imagem' id='arquivo-imagem' value='"+arquivo+"'>");
		//salvarCadastroDados();
    });
});
 $('#arquivo-upload-cadastro').live('change', function(){
	extensao = $('#arquivo-upload-cadastro').val().substring( $('#arquivo-upload-cadastro').val().lastIndexOf(".")).toLowerCase();
	if ((extensao=='.png')||(extensao=='.jpg')){
		uploadArquivo();
	}else{
		alert("Arquivo Inválido");
		$('#arquivo-upload-cadastro').val("");
	}
});

$("#imagem-foto").live('click keypress', function () {
	$("#arquivo-upload-cadastro").click();
});

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
		//salvarCadastroDados();
    });
});
/* Função Upload Final*/



$("#turma-localiza").live('click', function () {
	$('#frmDefault').submit();
});

/*
$("#turma-inclui-nova").live('click', function () {
	open('./turmas-cadastrar-turma', '_self');
});
*/
/*
$("#form-cadastra-nova-turma #botao-cadastrar-turma").live('click', function () {
	var erro = 0;
	$("#form-cadastra-nova-turma .required").each(function(){
		if($(this).val() == ""){
			$("#"+$(this).attr('name').replace('-','_').replace('-','_')+"_chosen a").css('background-color', '#FFE4E4');
			$(this).css('background-color', '#FFE4E4');
			erro = 1;
		}
	})
	$nomeTurma = $("#turma-instituicao :selected").text()+" - "+$("#turma-campus :selected").text()+" - "+$("#turma-curso :selected").text()+" - "+$("#turma-periodo :selected").text()+" - "+$("#turma-turno :selected").text()+" - "+$("#titulo-turma").val();
	$("#nome-turma").val($nomeTurma);

	if(erro == 0){
		$("#frmDefault").attr("action",caminhoScript+"/modulos/turmas/cadastrar-nova-turma.php");
		$("#frmDefault").submit();
	}
});
*/

$("#form-cadastra-nova-turma .required").live('keyup blur change', function () {
	if($(this).val() != "")
		$(this).css('background-color', '').css('outline', '');
});

$(".turma-mostrar-detalhes").live('click', function () {
	//if (!($("#id-turma").length)){
	//	$("#frmDefault").append("<input type='hidden' id='id-turma' name='id-turma' value='" + $("#id-turma").val() + "'/>")
	//}
	//alert($(this).attr("turma-id"));
	$("#id-turma").val($(this).attr("turma-id"));
	$("#frmDefault").attr("action",caminhoScript+"/turmas/turmas-gerenciar-turma/");
	$("#frmDefault").submit();
});


$(".chosen-container a").live('click', function () {
	$(this).css('background-color', '');
})

$("#botao-atualizar-turma, #botao-cadastrar-turma").live('click', function () {
	botao = $(this).attr('id');
	var erro = 0;
	$("#form-detalhes-turma .required").each(function(){
		if($(this).val() == ""){
			$("#"+$(this).attr('name').replace('-','_').replace('-','_')+"_chosen a").css('background-color', '#FFE4E4');
			$(this).css('background-color', '#FFE4E4');
			$(this).focus();
			erro = 1;
		}
	});
	$nomeTurma = $("#turma-instituicao :selected").text()+" - "+$("#turma-campus :selected").text()+" - "+$("#turma-curso :selected").text()+" - "+$("#turma-periodo :selected").text()+" - "+$("#turma-turno :selected").text()+" - "+$("#titulo-turma").val();
	$("#nome-turma").val($nomeTurma);
	if(erro == 0){
		if (botao=='botao-atualizar-turma')
			$("#frmDefault").attr("action",caminhoScript+"/modulos/turmas/atualizar-dados-turma.php");
		if (botao=='botao-cadastrar-turma')
			$("#frmDefault").attr("action",caminhoScript+"/modulos/turmas/cadastrar-nova-turma.php");
		$("#frmDefault").submit();
	}
});
$("#form-detalhes-turma .required").live('keyup blur change', function () {
	if($(this).val() != "")
		$(this).css('background-color', '');
});
$("#turma-inclui-aluno").live('click', function () {
	$("#botao-cadastra-aluno-turma").show();
	$("#botao-atualiza-aluno-turma").hide();
	$("#alunos-novo-aluno").show();
	$("#form-alunos-cadastrados").hide();
	$("#nome-completo").focus();
});

$(".turma-edita-aluno").live('click', function () {
	var i = 0;
	$("#id-aluno").val($(this).attr('attr-id'));
	$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/localiza-dados-aluno.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			campos = retorno.split("|");
//			$("#div-foto").html("<img src='"+caminhoScript+"/"+campos[0]+"'>");
			$("#form-cadastra-nova-turma input[type=text], #form-cadastra-nova-turma select").each(function(){
				i = i + 1;
				if ($(this).attr("name")!="undefined")
					$(this).val(campos[i]);
			});
			$("#botao-cadastra-aluno-turma").hide();
			$("#form-alunos-cadastrados").hide();
			$("#botao-atualiza-aluno-turma").show();
			$("#alunos-novo-aluno").show();
			//alert(campos[4]);
			//alert(campos[16]);
			$("#sexo").val(campos[4]).trigger('chosen:updated');
			$("#cargo-turma").val(campos[16]).trigger('chosen:updated');
		}
	});
});

$(".turma-exclui-aluno").live('click', function () {
	if (confirm("Tem certeza que deseja excluir?")){
		$("#id-aluno").val($(this).attr('attr-id'));
		$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/turmas-excluir-aluno-turma.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#frmDefault").attr("action",caminhoScript+"/modulos/turmas/atualizar-dados-turma.php");
				$("#frmDefault").submit();
				//$("#form-alunos-cadastrados").append(retorno);
			}
		});
	}
});

$(".orcamento-localiza").live('click', function () {
	$("#workflow-id").val($(this).attr('workflow-id'));
	$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
	$("#frmDefault").submit();
});

$("#botao-cadastra-aluno-turma-cancela").live('click', function () {
	$("#id-aluno").val('');
	$("#form-cadastra-nova-turma input[type=text], #form-cadastra-nova-turma select").each(function(){$(this).val('').css('background-color', '');})
	$(".chosen-container a").css('background-color', '');

	$("#alunos-novo-aluno").hide();
	$("#form-alunos-cadastrados").show();
});

$("#botao-cadastra-aluno-turma, #botao-atualiza-aluno-turma").live('click', function () {
	var erro = 0;
	$("#alunos-novo-aluno .required").each(function(){
		if($(this).val() == ""){
			$("#"+$(this).attr('name').replace('-','_').replace('-','_')+"_chosen a").css('background-color', '#FFE4E4');
			$(this).focus();
			$(this).css('background-color', '#FFE4E4');
			erro = 1;
		}
	});
	if(erro == 0){
		$(this).hide();
		if($("#id-aluno").val()=="")
			$("#frmDefault").attr("action",caminhoScript+"/modulos/turmas/cadastrar-novo-aluno-turma.php");
		else
			$("#frmDefault").attr("action",caminhoScript+"/modulos/turmas/atualizar-dados-aluno.php");
		$("#frmDefault").submit();
	}
});


$(".mascara-cep").live('blur', function () {
	if((soNumero($("#cep-endereco").val()).length)==8){
		localizaEndereco($("#cep-endereco").val(),'');
	}
});


$("#botao-exclui-turma").live('click', function () {
	if (confirm('Tem certeza que deseja excluir a turma?')){
		$("#frmDefault").attr("action",caminhoScript+"/modulos/turmas/excluir-dados-turma.php");
		$("#frmDefault").submit();
	}
});


$(".novo-orcamento-turma").live('click', function () {
	$("#frmDefault").attr("action",caminhoScript+"/chamados/chamados-orcamento");
	$("#frmDefault").submit();
});



$(".turma-gerencia-evento").live('click', function () {
	eventoID = $(this).attr('evento-id')
	$("#evento-id").val(eventoID);
	$("#form-eventos-cadastrados").hide();
	$("#eventos-gerenciar").show();
	if (eventoID!=""){
		$("#tipo-evento").val($("#tipo-evento-id-" + eventoID).val()).trigger('chosen:updated');
		$("#data-evento").val($("#data-evento-" + eventoID).html());
		$("#participantes-evento").val($("#participantes-evento-" + eventoID).html());
		$("#local-evento").val($("#local-evento-id-" + eventoID).val()).trigger('chosen:updated');
		$("#descricao-evento").val($("#descricao-evento-" + eventoID).html());
	}
});

$(".turma-exclui-evento").live('click', function () {
	if (confirm("Tem certeza que deseja excluir o evento?")){
		eventoID = $(this).attr('evento-id')

		$("#evento-id").val(eventoID);
		$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/turmas-excluir-evento.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#frmDefault").submit();
			}
		});
	}
});

$(".botao-cancelar-evento").live('click', function () {
	$("#eventos-gerenciar").hide();
	$("#form-eventos-cadastrados").show();
});

$(".botao-salvar-evento").live('click', function () {
	erro = validarCamposGenerico("#form-cadastra-evento .required");
	if(erro){
		$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/turmas-salvar-evento.php", data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				$("#frmDefault").submit();
			}
		});
	}
});

$(".localiza-filho").live('change', function () {
	campoFilho = '#'+$(this).attr('campo-filho');
	$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/localiza-campo-filho.php?grupo="+$(this).attr('filho-id')+"&id="+$(this).val(), data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$(campoFilho).empty().append(retorno).trigger('chosen:updated');
		}
	});
});



$(document).ready(function(){
	$(".calcula-valores-projecao").live('keyup click blur', function () {
		semestres = parseFloat($("#tot-semestres").val()) - 1;
		planos = parseFloat($("#tot-planos").val()) - 1;
		totalGeralMensalidade = 0;
		totalAtividade = 0;
		totalGeralTudo = 0;
		totQtdMensalidades = 0;
		totalGeralAtividade = 0;
		var totalGeralPlano = [];
		totalGeralPlano[0] = 0;
		totalGeralPlano[1] = 0;
		totalGeralPlano[2] = 0;
		totalGeralPlano[3] = 0;
		totalGeralPlano[4] = 0;
		totalGeralPlano[5] = 0;
		totalGeralPlano[6] = 0;
		totalGeralPlano[7] = 0;
		totalGeralPlano[8] = 0;
		totalGeralPlano[9] = 0;
		for (i=0; i <= semestres; i++){
			qtdMensalidades = parseFloat(desformataValor($("#campo-mensalidade-"+i).val()));
			totalMensalidade = 0;
			totalAtividade = 0;
			for (ii = 0; ii <= planos; ii++){
				participantes = $("#campo-mensalidade-participantes-"+ii).val();
				valorMensalidade = parseFloat(desformataValor($("#campo-valor-mensalidade-"+ii+"-"+i).val()));
				totalMensalidade += parseFloat(qtdMensalidades * valorMensalidade * participantes);
				totalGeralMensalidade += parseFloat(qtdMensalidades * valorMensalidade * participantes);
				totalGeralPlano[ii] += parseFloat(qtdMensalidades * valorMensalidade * participantes);
			}
			$("#campo-total-mensalidade-"+i).val(number_format(totalMensalidade,'2',',','.'));
			valorAtividade = parseFloat(desformataValor($("#campo-lucro-atividades-"+i).val()));
			qtdAtividade = parseFloat(desformataValor($("#qtd-atividade-lucrativa-"+i).val()));
			totalGeralAtividade += parseFloat(valorAtividade * qtdAtividade);
			totalAtividade = parseFloat(valorAtividade * qtdAtividade);
			totalGeral = parseFloat(totalMensalidade + totalAtividade);
			totalGeralTudo += totalGeral;
			totQtdMensalidades += qtdMensalidades;

			$('#campo-total-periodo-'+i).val(number_format(totalGeral,'2',',','.'));
			$("#campo-total-arrecadacoes").val(number_format(totalGeralMensalidade,'2',',','.'));
		}

		$("#campo-total-geral-tudo").val(number_format(totalGeralTudo,'2',',','.'));
		totalAtingir = parseFloat(desformataValor($("#campo-total-atingir").val()));
		$("#campo-total-atividades").val(number_format(totalGeralAtividade,'2',',','.'));
		$("#campo-total-saldo").val(number_format(parseFloat(totalGeralTudo - totalAtingir) ,'2',',','.'));
		$("#campo-total-mensalidades").val(totQtdMensalidades);

		for (iii = 0; iii <= planos; iii++){
			/*
			totalPlano = 0;
			$(".mensal-plano-"+iii).each(function(){
				plano = parseFloat(desformataValor($(this).val()));
				plano = parseFloat(desformataValor($(this).val()));
				console.log(totalPlano);
			});
			*/
			//console.log("campo-valor-total-plano-mensalidade-" + iii);
			//console.log(totalGeralPlano[iii]);
			$("#campo-valor-total-plano-mensalidade-" + iii).val(number_format(totalGeralPlano[iii],'2',',','.'));
		}


	});
	$(".calcula-valores-projecao").click();

	$(".salvar-projecao").live('click', function () {
		$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/projecao-salvar.php", data: $("#frmProjecao").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				alert("Projeção Salva");
			}
		});
	});

	$(".gerar-documento").live('click', function () {
		$("#frmProjecao").attr('action', caminhoScript+"/modulos/turmas/projecao.php?orcamento="+$("#proposta-id").val()+"&geraDocumento=S");
		$("#frmProjecao").submit();
		//parent.$.fancybox.close();
		//parent.$("#menu-superior-3").click();
		/*
		alert($('#conteudo-geral-projecao').serialize());
		$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/projecao-gerar-documento.php", data: $('#conteudo-geral-projecao').serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				alert("Projeção Salva");
			}
		});
		*/

		/*
		$('.botoes-acoes').hide();
		window.print();
		$('.botoes-acoes').show();
		*/
	});


	$(".gerar-codigo-turma").live('click', function () {
		//alert($("#frmDefault").serialize());
		$.ajax({type: "POST",url: caminhoScript+"/modulos/turmas/turmas-gerar-codigo-turma.php", data: $("#frmDefault").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			success: function(retorno){
				//alert(retorno);
				$('#turma-codigo').val(retorno);
			}
		});
	});
});
