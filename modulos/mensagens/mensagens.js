$(document).ready(function(){
	$("#salva-mensagem").click(function() {
		var required = 0
		$(".required-save").each(function(){
			if (($(this).val()=="")||($(this).val()==null)){
				$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				required = 1;
			}
		});
		if(required ==0){
			$("#mensagem-conteudo").val(tinyMCE.get('mensagem-conteudo').getContent());
			caminho = caminhoScript+"/modulos/mensagens/mensagem-cadastra.php?tipo=69";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					open(caminhoScript+"/mensagens/mensagem-rascunho", "_self");
				}
			});
		}
	});
	$("#envia-mensagem").click(function() {
		var required = 0
		$(".required-save").each(function(){
			if (($(this).val()=="")||($(this).val()==null)){
				$(this).css('background-color', '#FFE4E4').css('outline', '1px solid #FFCDCD');
				required = 1;
			}
		});
		if(required ==0){
			$("#mensagem-conteudo").val(tinyMCE.get('mensagem-conteudo').getContent());
			caminho = caminhoScript+"/modulos/mensagens/mensagem-cadastra.php?tipo=68";
			$.ajax({type: "POST",url: caminho, data: $("form").serialize(), dataType: "html", contentType: "application/x-www-form-urlencoded;charset=UTF-8",
				success: function(retorno){
					open(caminhoScript+"/mensagens/mensagem-enviada", "_self");
				}
			});
		}
	});
	$(".required").blur(function() {
		if (($(this).val()!="")&&($(this).val()!=null))
			$(this).css('background-color', '').css('outline', '');
	});

	$(".mensagem-rascunho-edita").click(function() {
		$("#edita-mensagem").val($(this).attr('attr-id'));
		$("#frmDefault").attr("action",caminhoScript+"/mensagens/mensagem-nova");
		$("#frmDefault").submit();
	});

	$("#responde-mensagem").click(function() {
		$("#mensagem-pai").val($(this).attr('attr-id'));
		$("#frmDefault").attr("action",caminhoScript+"/mensagens/mensagem-nova");
		$("#frmDefault").submit();
	});

	$(".mensagem-recebida-visualiza").click(function() {
		$("#edita-mensagem").val($(this).attr('attr-id'));
		$("#frmDefault").attr("action",caminhoScript+"/mensagens/mensagem-visualizar");
		$("#frmDefault").submit();
	});

	$(".mensagem-enviada-visualiza").click(function() {
		$("#edita-mensagem").val($(this).attr('attr-id'));
		$("#frmDefault").attr("action",caminhoScript+"/mensagens/mensagem-visualizar");
		$("#frmDefault").submit();
	});
});