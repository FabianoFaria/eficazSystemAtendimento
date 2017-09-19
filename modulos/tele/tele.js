$(document).ready(function(){

/*******************************/
/********* OPERACOES ***********/
/*******************************/

	$(".inc-alt-operacao").live('click', function () {
		$('#operacao-id').val($(this).attr('operacao-id'));
		caminho = caminhoScript+"/tele/tele-gerenciar-modulo/tele-operacoes-gerenciar";
		$("#frmDefault").attr('action',caminho);
		$("#frmDefault").submit();
	});

	$(".salvar-operacao").live('click', function () {
		if(validarCamposGenerico('.required')){
			$(".salvar-operacao").hide();
			caminho = caminhoScript+"/modulos/tele/tele-operacoes-salvar.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#operacao-id').val(retorno.trim());
					if ($('#situacao-id').val()==2)
						$("#frmDefault").attr('action',caminhoScript+'/tele/tele-gerenciar-modulo/tele-operacoes/');
					$("#frmDefault").submit();
				}
			});
		}
	});

/*******************************/
/********* CAMPANHAS ***********/
/*******************************/

	if ($('#slug-pagina').val()=='tele-campanhas'){
		$("#localiza-campanha").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $(".botao-localizar-campanha").click(); }});

		$(".botao-localizar-campanha").live('click', function () {
			caminho = caminhoScript+"/tele/tele-campanhas";
			$("#frmDefault").attr('action',caminho);
			$("#frmDefault").submit();
		});

		$(".inc-alt-campanha").live('click', function () {
			$('#campanha-id').val($(this).attr('campanha-id'));
			caminho = caminhoScript+"/tele/tele-campanhas-gerenciar";
			$("#frmDefault").attr('action',caminho);
			$("#frmDefault").submit();
		});
	}
	if ($('#slug-pagina').val()=='tele-campanhas-gerenciar'){
		verificarVariaveisCampanha();

		/* NÃO PERMITIR ALTERAR TIPO DE CAMPANHA UMA VEZ CRIADA*/
		if ($('#campanha-id').val()!=''){
			$('#tipo-campanha-id option').prop('disabled', true).trigger("chosen:updated");
			$('#tipo-campanha-id option:selected').prop('disabled', false).trigger("chosen:updated");
		}

		/*
		$("#menu-superior-2").live('click', function () {
			carregarDocumentos($("#campanha-id").val(),'campanhas');
		});
		*/


		$(".salvar-campanha").live('click', function () {
			if(validarCamposGenerico('.required')){
				$(".salvar-campanha").hide();
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-salvar.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#campanha-id').val(retorno.trim());
						$("#frmDefault").submit();
					}
				});
			}
		});

		$('.btn-incluir-novo-formulario').live('click', function () {
			dados = $('#frmDefault').serialize();
			caminho = caminhoScript+"/administrativo/formularios/formularios-dinamicos-gerenciar?tipo=direto&"+dados+"&tabela-estrangeira=tele_workflows";
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				afterClose : function(){
					carregarFormularios();
				},
				helpers: {
					overlay: {
						locked: false
					}
				},
			});
		});

		/**/
		$(".incluir-motivo-campanha, .editar-motivo-campanha").live('click', function () {
			$(".incluir-motivo-campanha, .editar-motivo-campanha, .excluir-motivo-campanha").hide();
			campanhaID = $('#campanha-id').val();
			motivoCampanhaID = $(this).attr('motivo-campanha-id');
			caminho = caminhoScript+"/modulos/tele/tele-campanhas-motivo.php?campanha-id="+campanhaID+"&motivo-campanha-id="+motivoCampanhaID;
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#div-incluir-motivo-campanha').html(retorno);
				}
			});
		});

		$(".excluir-motivo-campanha").live('click', function () {
			if (confirm('Tem certeza que deseja excluir?')){
				motivoCampanhaID = $(this).attr('motivo-campanha-id');
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-motivo-salvar.php?excluir-motivo-campanha-id="+motivoCampanhaID;
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#div-incluir-motivo-campanha').html('');
						$('#div-carregar-motivo-campanha').html(retorno);
						$(".incluir-motivo-campanha, .editar-motivo-campanha, .excluir-motivo-campanha").show();
					}
				});
			}
		});

		$("#salvar-motivo-campanha").live('click', function () {
			if(validarCamposGenerico('#div-motivo .required')){
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-motivo-salvar.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#div-incluir-motivo-campanha').html('');
						$('#div-carregar-motivo-campanha').html(retorno);
						$(".incluir-motivo-campanha, .editar-motivo-campanha, .excluir-motivo-campanha").show();
					}
				});
			}
		});
		$("#cancelar-salvar-motivo-campanha").live('click', function () {
			$(".incluir-motivo-campanha, .editar-motivo-campanha, .excluir-motivo-campanha").show();
			$('#div-incluir-motivo-campanha').html('');
		});
		/**/

		/**/
		$(".incluir-situacao-campanha, .editar-situacao-campanha").live('click', function () {
			$(".incluir-situacao-campanha, .editar-situacao-campanha, .excluir-situacao-campanha").hide();
			campanhaID = $('#campanha-id').val();
			situacaoCampanhaID = $(this).attr('situacao-campanha-id');
			caminho = caminhoScript+"/modulos/tele/tele-campanhas-situacao.php?campanha-id="+campanhaID+"&situacao-campanha-id="+situacaoCampanhaID;
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#div-incluir-situacao-campanha').html(retorno);
				}
			});
		});

		$(".excluir-situacao-campanha").live('click', function () {
			if (confirm('Tem certeza que deseja excluir?')){
				situacaoCampanhaID = $(this).attr('situacao-campanha-id');
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-situacao-salvar.php?excluir-situacao-campanha-id="+situacaoCampanhaID;
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#div-incluir-situacao-campanha').html('');
						$('#div-carregar-situacoes-campanha').html(retorno);
						$(".incluir-situacao-campanha, .editar-situacao-campanha, .excluir-situacao-campanha").show();
					}
				});
			}
		});

		$("#salvar-situacao-campanha").live('click', function () {
			if(validarCamposGenerico('#div-situacao .required')){
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-situacao-salvar.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#div-incluir-situacao-campanha').html('');
						$('#div-carregar-situacoes-campanha').html(retorno);
						$(".incluir-situacao-campanha, .editar-situacao-campanha, .excluir-situacao-campanha").show();
					}
				});
			}
		});
		$("#cancelar-salvar-situacao-campanha").live('click', function () {
			$(".incluir-situacao-campanha, .editar-situacao-campanha, .excluir-situacao-campanha").show();
			$('#div-incluir-situacao-campanha').html('');
		});
		/**/

		$('.btn-incluir-nova-listagem').live('click', function () {
			dados = $('#frmDefault').serialize();
			caminho = caminhoScript+"/cadastros/cadastros-tipos/cadastro-importar?tipo=direto&"+dados;
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				afterLoad: function() {
					this.wrap.find('.fancybox-inner').css({
					'overflow-y': 'auto',
					'overflow-x': 'auto'
					});
				},
				helpers: {
					overlay: {
						locked: false
					}
				}
			});
		});


		function carregarFormularios(){
			caminho = caminhoScript+"/modulos/administrativo/formularios-carregar-options.php?tabela-estrangeira=tele_workflows";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#formulario-id').empty().append(retorno).trigger('chosen:updated');
				}
			});
		}

		$('#situacao-id, #tipo-campanha-id').live('change', function () {
			verificarVariaveisCampanha();
		});

		function verificarVariaveisCampanha(){
			$('.bloco-formulario-campanha, .bloco-cobranca, .bloco-listagem-campanha').hide();
			if ($('#tipo-campanha-id').val()=='157'){
				$('.bloco-formulario-campanha, .bloco-listagem-campanha').show();
			}
			if ($('#tipo-campanha-id').val()=='154'){
				$('.bloco-cobranca, .bloco-listagem-campanha').show();
			}
			if ($('#tipo-campanha-id').val()=='158'){
				$('.bloco-formulario-campanha, .bloco-listagem-campanha').show();
			}
		}

		$('.incluir-colaborador-campanha').live('click', function () {
			caminho = caminhoScript+"/modulos/tele/tele-campanhas-carregar-incluir-usuario.php";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('.incluir-colaborador-campanha').hide();
					$('#div-incluir-usuario-campanha').html(retorno).delay("fast").fadeIn();
				}
			});
		});

		$('.botao-incluir-usuario-campanha').live('click', function () {
			if(validarCamposGenerico('#div-incluir-usuario-campanha .required')){
				$('.botao-incluir-usuario-campanha').hide();
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-usuario-salvar.php";
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('.incluir-colaborador-campanha').hide();
						//$('#div-incluir-usuario-campanha').html(retorno).delay("fast").fadeIn();
						$("#frmDefault").submit();
					}
				});
			}
		});
		$('.usuario-campanha-excluir').live('click', function () {
			if(confirm('Tem certeza que deseja desalocar o operador desta operação?')){
				campanhaUsuarioID = $(this).attr('campanha-usuario-id');
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-usuario-salvar.php?acao=D&campanha-usuario-id="+campanhaUsuarioID;
				$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$("#frmDefault").submit();
					}
				});
			}
		});

		$('.botao-cancelar-usuario-campanha').live('click', function () {
			$('.incluir-colaborador-campanha').delay("fast").fadeIn();
			$('#div-incluir-usuario-campanha').html('').hide();
		});

		/*
		$('.arquivo-importar-base').live('click', function () {
			if ($(this).is(":checked")){
				$("#div-exibir-listagem-importacao").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
				caminho = caminhoScript+"/modulos/tele/tele-campanhas-carregar-arquivo-importacao.php";
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
			if ($(this).val()=='Cpf_Cnpj'){
				recarregarTabelaExibindoDuplicados();
			}
		});

		function recarregarTabelaExibindoDuplicados(){
			campos = $("form").serialize();
			$("#bloco-tabela-temporaria").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
			caminho = caminhoScript+"/modulos/tele/tele-campanhas-importar-recarregar-tabela.php";
			$.ajax({type: "POST",url: caminho, data: campos, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					$('#bloco-tabela-temporaria').html(retorno);
				}
			});
		}
		$('.importar-arquivo-sistema').live('click', function () {
			$('#aviso-importacao').html("");
			flagNome = false;
			flagCpfCnpj = false;
			$(".campo-coluna").each(function(){
				if ($(this).val()=='Nome')
					flagNome = true;
				if ($(this).val()=='Cpf_Cnpj')
					flagCpfCnpj = true;
			});
			if ((flagNome==false) || (flagCpfCnpj==false)){
				$('#aviso-importacao').html("&Eacute; necess&aacute;rio especificar ao menos as colunas 'NOME OU RAZ&Atilde;O SOCIAL' e 'CPF OU CNJP' &nbsp;&nbsp;&nbsp;");
				validarCamposGenerico('#div-exibir-listagem-importacao .required');
			}
			else{
				if ((flagNome) && (flagCpfCnpj) && (validarCamposGenerico('#div-exibir-listagem-importacao .required'))){
					campos = $("form").serialize();
					//$("#div-exibir-listagem-importacao").html("<p align='center' style='width:100%'>Aguarde processando</p><p align='center' style='width:100%' class='barra-carregando'>&nbsp;</p>");
					caminho = caminhoScript+"/modulos/tele/tele-campanhas-importar-arquivo-lista.php";
					$.ajax({type: "POST",url: caminho, data: campos, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							alert(retorno);
							$('#div-exibir-listagem-importacao').prepend(retorno);
						}
					});
				}
			}
		});
		*/
	}
	if ($('#slug-pagina').val()=='tele-operacao'){
		/* FUNCAO INCLUI ABA DOCUMENTOS E ANEXOS */
		$("#menu-superior-3").live('click', function () {
			carregarDocumentos($("#cadastro-id").val(), 'cadastros');
		});

		$(".operar-campanha").live('click', function () {
			$('#campanha-id').val($(this).attr('campanha-id'));
			$('#tipo-interacao').val($(this).attr('tipo-interacao'));
			if ($(this).attr('tipo-interacao')=='ativo'){
				caminho = caminhoScript+"/tele/tele-operacao?tipo-fluxo=direto&tipo-interacao=ativo&campanha-id="+$('#campanha-id').val();
				$.fancybox.open({
					href : caminho,
					type : 'iframe',
					width: '90%',
					padding : 2,
					helpers: {
						overlay: {
							locked: false
						}
					},
					beforeClose: function() {
						alertify.confirm('', "Tem certeza que deseja sair sem salvar o registro?",
							function(){
								$('#frmDefault').append("<input type='text' name='confim-aux' id='confim-aux' value='1'>");
								parent.$.fancybox.close();
								$("#confim-aux").remove();
							},
							function(){}).set('labels', {ok:'Sim', cancel:'Cancelar'});
						if ($('#confim-aux').length==0){
							return false;
						}
					}
				});
			}
			else{
				$("#frmDefault").attr('action',caminhoScript+"/tele/tele-operacao");
				$("#frmDefault").submit();
			}
		});

		/*
		$("#situacao-id").live('change', function () {
			if ($(this).attr('acao')){
				alert(123);
			}
		});
		*/

		$(".atualizar-workflow").live('click', function () {
			if (validarCamposGenerico('.div-dados-workflow-telemarketing .required')){
				flag = true;
				if ($('.form-formulario-dinamico-generico').length){
					flag = salvarValidarFormulariosDinamicos('');
				}
				if (flag!=false){
					campos = $("form").serialize();
					caminho = caminhoScript+"/modulos/tele/tele-operacao-workflow-salvar.php";
					$.ajax({type: "POST",url: caminho, data: campos, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							$('#registro-atualizado').val(1);
							$("#frmDefault").submit();
						}
					});
				}
			}
		});

		$(".workflow-andar").live('click', function () {
			if ($('#registro-atualizado').val()!='1'){
				alertify.alert('','Não é permitido ir para o próximo registro sem interagir com o atual registro');
				return false;
			}
			else{
				campos = $("form").serialize();
				caminho = caminhoScript+"/modulos/tele/tele-operacao-workflow-atualizar-chave.php";
				$.ajax({type: "POST",url: caminho, data: campos, dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					success: function(retorno){
						$('#registro-atualizado').val('');
						$("#frmDefault").submit();
					}
				});
			}
		});

		$(".link-workflow").live('click', function () {
			//$('#tipo-interacao').val('direta');
			//$('#workflow-id').val($(this).attr('workflow-id'));
			caminho = caminhoScript+"/tele/tele-operacao?tipo-fluxo=direto&tipo-interacao=direta&workflow-id="+$(this).attr('workflow-id')+"&campanha-id="+$('#campanha-id').val();
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				beforeClose: function() {
					alertify.confirm('Alerta', "Tem certeza que deseja sair sem salvar o registro?",
						function(){
							$('#frmDefault').append("<input type='text' name='confim-aux' id='confim-aux' value='1'>");
							parent.$.fancybox.close();
							$("#confim-aux").remove();
						},
						function(){}).set('labels', {ok:'Sim', cancel:'Cancelar'});
					if ($('#confim-aux').length==0){
						return false;
					}
				},
				helpers: {
					overlay: {
						locked: false
					}
				}
			});
		});
		function atualizarRegistroLista(){
			//alert(123);
		}


		$(".relatorios-campanha").live('click', function () {
			//$('#workflow-id').val('');
			//$('#tipo-interacao').val('listagem');
			//$("#frmDefault").attr('action',caminhoScript+"/tele/tele-operacao");
			//$("#frmDefault").submit();
			alert('Aguarde em desenvolvimento - ' + $(this).attr('campanha-id'));
		});



		$(".voltar-listagem-campanha").live('click', function () {
			$('#workflow-id').val('');
			$('#tipo-interacao').val('listagem');
			$("#frmDefault").attr('action',caminhoScript+"/tele/tele-operacao");
			$("#frmDefault").submit();
		});


		$(".voltar-listagem-operacoes").live('click', function () {
			$('#workflow-id').val('');
			$('#tipo-interacao').val('');
			$("#frmDefault").attr('action',caminhoScript+"/tele/tele-operacao");
			$("#frmDefault").submit();
		});


		$(".localizar-registro-workflow").live('click', function () {
			caminho = caminhoScript+"/tele/tele-operacao?tipo-fluxo=direto&campanha-id="+$('#campanha-id').val()+"&tipo-interacao=listagem";
			$.fancybox.open({
				href : caminho,
				type : 'iframe',
				width: '90%',
				padding : 2,
				afterClose : function(){
					carregarFormularios();
				},
				helpers: {
					overlay: {
						locked: false
					}
				},
			});
			/*
			$('#workflow-id').val('');
			$('#tipo-interacao').val('');
			$("#frmDefault").attr('action',caminhoScript+"/tele/tele-operacao");
			$("#frmDefault").submit();
			*/
		});

		$(".excluir-workflow").live('click', function () {
			alertify.confirm('Aviso', "<center>Tem certeza que deseja excluir o registro? <br>Não será possivel recuperar o mesmo posteriormente.</center>",
				function(){
					caminho = caminhoScript+'/modulos/tele/tele-operacao-workflow-excluir.php';
					$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
						success: function(retorno){
							$("#bloco-titulos-cadastro").html($.trim(retorno));
						}
					});
					$('#frmDefault').attr('action', caminho).submit();
					//parent.$.fancybox.close();
				},
				function(){

				}).set('labels', {ok:'Sim', cancel:'Não'});
		});



		$(document).keypress(function(event) {if(event.keyCode==13){ $(".localizar-workflow-tele").click(); }});

		$(".localizar-workflow-tele").live('click', function () {
			$("#frmDefault").submit();
			$("#frmDefault").attr('action',caminhoScript+"/tele/tele-operacao");
		});

		verificaVariaveiOperacao();
		function verificaVariaveiOperacao(){
			$('#motivo-id, #motivo-outros').removeClass('required');
			$('.bloco-motivos-cancelmento, .bloco-motivo-outros').hide();

			if ($('#situacao-id').val()=='166'){
				$('.bloco-motivos-cancelmento').show();
				$('#motivo-id').addClass('required');
			}
			if ($('#motivo-id').val()=='169'){
				$('.bloco-motivo-outros').show();
				$('#motivo-outros').addClass('required');
			}
		}

		$("#situacao-id, #motivo-id").live('change', function () {
			verificaVariaveiOperacao();
		});
	}


	/******************/
	/* TELE CAMPANHAS */
	/******************/
	if ($('#slug-pagina').val()=='tele-operacoes'){
		$("#localiza-operacao").focus();
		$(document).keypress(function(event) {if(event.keyCode==13){ $(".botao-localizar-operacao").click(); }});
		$(".botao-localizar-campanha").live('click', function () {
			caminho = caminhoScript+"/tele/tele-campanhas";
			$("#frmDefault").attr('action',caminho);
			$("#frmDefault").submit();
		});
		$(".botao-localizar-operacao").live('click', function () {
			caminho = caminhoScript+"/tele/tele-gerenciar-modulo/tele-operacoes/";
			$("#frmDefault").attr('action',caminho);
			$("#frmDefault").submit();
		});
	}


	/* OPORTUNIDADES */


	$(".incluir-oportunidade").live('click', function () {
		caminho = caminhoScript+"/chamados/oportunidade?tipo-fluxo=direto&cadastro-id="+$('#cadastro-id').val();
		$.fancybox.open({
			href : caminho,
			type : 'iframe',
			width: '90%',
			padding : 2,
//			afterClose : function(){
//				carregarFormularios();
//			},
			helpers: {
				overlay: {
					locked: false
				}
			},
		});
	});
});




/* Funções de Boleto */
$(".gerar-boleto").live('click', function () {
	tituloID = $(this).attr('titulo-id');
	cadastroID = $(this).attr('cadastro-id');
	caminho = caminhoScript+"/modulos/financeiro/financeiro-gerar-boleto.php?titulo-id="+tituloID;
	$.fancybox.open({
		href : caminho,
		type : 'iframe',
		width: '90%',
		padding : 2,
		afterClose : function(){
			carregarTitulosCadastro(cadastroID);
		},
		helpers: {
			overlay: {
				locked: false
			}
		}
	});
});
/* Funções de Boleto */

function carregarTitulosCadastro(cadastroID){
	caminho = caminhoScript+"/modulos/tele/tele-operacao-carregar-titulos-cadastro.php?cadastro-id="+cadastroID;
	$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
		success: function(retorno){
			$("#bloco-titulos-cadastro").html($.trim(retorno));
		}
	});
}