$(document).ready(function(){
	$("#passo-2").hide();
	$('#configuracao-inicial-1').click(function(){
		open("#passo2", '_top');
		$("#passo-1").hide();
		$("#passo-2").show();
	});
	$('#configuracao-inicial-2').click(function(){
		$("#form-inicio").submit();
	});
	$('#configuracao-inicial-3').click(function(){
		open("./", "_top");
	});
});
