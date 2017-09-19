/********* Inicio Funções Gerais de Formatação ***********/
$(document).ready(function(){
	$(".mascara-cpf").mask("999.999.999-99");
	$(".mascara-cnpj").mask("99.999.999/9999-99");

	$("input[type=text]").live('click keypress', function () {
		mascaraCampo(this);
	});
	$("input[type=text]").live('blur', function () {
		this.value = ReplaceAll(this.value,"'","`");
	});
	$("input[type=password]").live('click keypress', function () {
		mascaraCampo(this);
	});

	$("select").chosen({
		width: "99%",
		no_results_text: "Nenhum registro encontrado!",
		placeholder_text_single: " ",
		placeholder_text_multiple: " ",
		allow_single_deselect: true
	});

	//$("select").show();

	$("input[type=pass]").live('blur', function () {
		this.value = ReplaceAll(this.value,"'","`");
	});
	$("textarea").live('click keypress', function () {
		mascaraCampo(this);
	});
/*
	$("textarea").live('blur', function () {
		this.value = ReplaceAll(this.value,"'","`");
	});
*/
	$('.formata-cor').each(function() {
		$(this).modcoder_excolor();
	});


	//
    //$.datepicker.setDefaults( $.datepicker.regional[ "pt-BR" ]);
	$(".formata-data").datepicker({
		dateFormat: 'dd/mm/yy',
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
		monthNames: ['Janeiro','Fevereiro','Mar\u00e7o','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		nextText: 'Próximo',
		prevText: 'Anterior',
	});

	$(".formata-data-hora").live('click', function () {
		$('.formata-data-hora').datetimepicker({
		  format:'d/m/Y H:i',
		  lang:'pt'
		});
	});



	$(".formata-data-meia-hora").live('click', function () {
		$('.formata-data-meia-hora').datetimepicker({
		  format:'d/m/Y H:i',
		  lang:'pt',
		  allowTimes:['00:00', '00:30','01:00', '01:30','02:00', '02:30','03:00', '03:30','04:00', '04:30','05:00', '05:30','06:00', '06:30','07:00', '07:30',
			'08:00', '08:30','09:00', '09:30','10:00', '10:30','11:00', '11:30','12:00', '12:30','13:00', '13:30','14:00', '14:30','15:00', '15:30','16:00', '16:30',
			'17:00', '17:30','18:00', '18:30','19:00', '19:30','20:00', '20:30','21:00', '21:30','22:00', '22:30','23:00', '23:30']
		});
	});

	$(".data-inicio, .data-final").live('click', function () {
		$("#msg-horas-utilizadas").html('')
	});

    $(".data-inicio").datetimepicker({
		onClose: function(selectedDate) {
			if($(".data-inicio").val()!=""){
				if($(".data-final").val()!=""){
					horasUtilizadas = $.ajax(caminhoScript+"/modulos/projetos/projetos-calcula-horas-tarefa.php?inicio="+$(".data-inicio").val()+"&final="+$(".data-final").val())
					.done(function(){
						if(horasUtilizadas.responseText < 0){
							$("#msg-horas-utilizadas").html('Data inicial menor que a data final!')
							$(".data-inicio").val('');
							$(".data-final").val('');
						}
					})
				}
			}
		}
    });
    $(".data-final").datetimepicker({
		onClose: function(selectedDate) {
			if($(".data-inicio").val()!=""){
				if($(".data-final").val()!=""){
					horasUtilizadas = $.ajax(caminhoScript+"/modulos/projetos/projetos-calcula-horas-tarefa.php?inicio="+$(".data-inicio").val()+"&final="+$(".data-final").val())
					.done(function(){
						if(horasUtilizadas.responseText < 0){
							$("#msg-horas-utilizadas").html('Data inicial menor que a data final!')
							$(".data-inicio").val('');
							$(".data-final").val('');
						}
					})
				}
			}
		}
    });


	//$(".formata-data-meia-hora").click();

	$(".formata-data-meia-hora").live('click keyup', function () {
		acertaData(this);
	});
	$(".formata-data-meia-hora").live('click keypress', function () {
		mascaraVal(this);
	});

	$(".formata-data-hora").live('click keyup', function () {
		acertaData(this);
	});
	$(".formata-data-hora").live('click keypress', function () {
		mascaraVal(this);
	});

	$(".formata-data").live('click keyup', function () {
		acertaData(this);
	});
	$(".formata-data").live('click keypress', function () {
		mascaraVal(this);
	});
	$(".formata-data").live('blur', function () {
		validaData(this);
	});

	/* FUNÇÕES HORAS */
	$('.formata-horas').maskMoney({
		allowNegative: false, thousands:'', decimal:':', affixesStay: false
	});
	$(".formata-horas").live('click keypress', function () {
		mascaraVal(this);
	});
	$(".formata-horas").live('blur', function () {
		validaHoras(this);
	});

	/* ************ */
	/* FUNÇÕES HORA */
	$(".formata-hora").live('click keyup', function () {
		acertaHora(this);
	});
	$(".formata-hora").live('click keypress', function () {
		mascaraVal(this);
	});
	$(".formata-hora").live('blur', function () {
		validaHora(this);
	});
	/* ************ */

	$(".formata-valor").live('click keypress', function () {
		mascaraVal(this);
	});
	$('.formata-valor').maskMoney({
		allowNegative: false, allowZero:true, thousands:'.', decimal:',', affixesStay: false
	});

	$(".formata-valor-pos-neg").live('click keypress', function () {
		mascaraVal(this);
	});
	$('.formata-valor-pos-neg').maskMoney({
		allowNegative: true, allowZero:true, thousands:'.', decimal:',', affixesStay: false
	});

	$(".formata-valor-decimal-3").live('click keypress', function () {
		mascaraVal(this);
	});
	$('.formata-valor-decimal-3').maskMoney({
		allowNegative: false, thousands:'.', decimal:',', affixesStay: false, precision:3
	});

	$(".formata-valor-moeda-real").live('click keypress', function () {
		mascaraVal(this);
	});
	$('.formata-valor-moeda-real').maskMoney({
		prefix:'R$ ', allowNegative: false, thousands:'.', decimal:',', affixesStay: false
	});

	$(".formata-valor-percentual").live('click keypress', function () {
		mascaraVal(this);
	});
	$('.formata-valor-percentual').maskMoney({
		prefix:'% ', allowNegative: false, thousands:'.', decimal:',', affixesStay: false
	});
	$(".formata-numero").live('click keypress', function () {
		mascaraVal(this);
	});
	/*
	$(".formata-numero").live('blur', function () {
		if ($(this).val()=="") $(this).val(0);
	});
	*/
	$(".formata-campo").live('click keypress', function () {
		mascaraCampo(this);
	});
	$("#atualiza-permissao").live('click keypress', function () {
		$("#frmDefault").attr("action","./");
		$("#frmDefault").submit();
	});

	$(".formata-telefone").live('click keypress', function () {
		if($("#tipo-telefone-id").val() == 102){ // 0800
			mascaraVal(this);
		}else if($("#tipo-telefone-id").val() == 103){ // nextel
			mascaraValAsterisco(this);
		}else{
			mascaraVal(this);
		}
	});
	$(".formata-telefone").live('click keypress', function () {
		limitaFoneDDD(this);
	});
	$(".formata-telefone").live('click keyup', function () {
		acertaFoneDDD(this);
	});

	$(".formata-mes-ano").live('click keyup', function () {
		formataMesAno(this);
	});

});
