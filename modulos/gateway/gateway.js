function acertadata(entrada){
  var meses, dias, anos;
  valor = entrada.value;

   if((valor.length==2)||(valor.length==5)){
	    entrada.value = valor + "/";
	    if(valida_data(valor,valor.length)==false){
	       alert('Data invalida');
	       entrada.value = "";
	       return false;
	    }
    }
//    if(valor.length==10){
//	    entrada.value = valor + " ";
//    }
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


function mascaraVal(formato, keypress, objeto){
	campo = eval (objeto);
	if((event.keyCode >= 48)&&(event.keyCode <= 57)){
		event.returnValue = true;
	}else{
	  event.returnValue = false;
	}
}

function mascaraCampo(formato, keypress, objeto){
    campo = eval (objeto);
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
	 ||((event.keyCode == 38))||((event.keyCode == 95))){
	 		event.returnValue = true;
	 	}else{
	 		event.returnValue = false;}
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

function formataValor(campo){
  valor = campo.value;
  tamanho =valor.length;
  decimal = tamanho - 2;

  antes= valor.indexOf(",");
  depois = antes+1

  inteiro = valor.substring(0,antes)+valor.substring(tamanho,depois);
  tamanho = inteiro.length;

  if(tamanho>=3)
  	campo.value = inteiro.substring(0,tamanho-2)+','+inteiro.substring(tamanho-2,tamanho);

  if(tamanho<3)
  	campo.value = inteiro.substring(0,tamanho-2)+inteiro.substring(tamanho-2,tamanho);

}

function alteraPrecoData(id){
	total = document.getElementById('hdValoresTotal').value;
	for(i=1;i<=total;i++){
		if(i==id){
			document.getElementById('divValoresAltera'+i).style.display = 'block';
			document.getElementById('divValores'+i).style.display = 'none';
		}else{
			document.getElementById('divValoresAltera'+i).style.display = 'none';
			document.getElementById('divValores'+i).style.display = 'block';
		}
	}
}

function valida_cpf(entrada,campo){
	if(entrada=='') return false;
	soma = 0;
	for (i=0; i < 9; i ++)
		soma += parseInt(entrada.charAt(i)) * (10 - i);
	resto = 11 - (soma % 11);
	if (resto == 10 || resto == 11)
		resto = 0;
	if (resto != parseInt(entrada.charAt(9)))
		{
		alert("Cpf Invalido");
		campo.value='';
		campo.focus();
		return false;
		}
	soma = 0;
	for (i = 0; i < 10; i ++)
		soma += parseInt(entrada.charAt(i)) * (11 - i);
	resto = 11 - (soma % 11);
	if (resto == 10 || resto == 11)
		resto = 0;
	if (resto != parseInt(entrada.charAt(10))){
		alert("Cpf Invalido");
		campo.value='';
		campo.focus();
		return false;
	}
	return true;
}


function voltaCor(campo){
	if(campo.value != ""){
		campo.style.backgroundColor = '#fff';
		campo.style.borderColor= 'silver';
	}
}

function voltaCorTransparente(campo){
	if(campo.value != ""){
		campo.style.backgroundColor = 'tranparent';
		campo.style.borderColor= 'tranparent';
	}
}

function validaEmail(email){
	valor = email.value
	Arroba= valor.indexOf("@");
	Ponto= valor.lastIndexOf(".");
	espaco= valor.indexOf(" ");

	if (valor == '')
		return false;

	if  ((Arroba != -1) && (Ponto > Arroba +1) && (espaco==-1)){
		return true;
	}else{
		email.focus();
		return false;
	}
}

function validaCNPJ(cgc,campo){
	if(cgc == '') return false;

	if(cgc == "00000000000000"){
		alert('CNPJ Inválido.');
		campo.value='';
		campo.focus();
		return false;
  	}

	CGC1 = cgc.substring(0,12);
	CGC2 = cgc.substring(12,14);
	controle = "";
	ContIni = 1;
	ContFim = 12;
	K = 0;
	for (j=1;j<=2;j++){
		Soma= 0;
		for (i = ContIni; i <=ContFim; i++){
			Mult = (ContFim + 1 + j - i);
			if(Mult>9) Mult=Mult - 8;
			Soma = Soma + (parseInt(CGC1.substring(i - j, i-K)) * Mult);
		}
		if (j == 2)
			Soma = Soma + (2 * Digito);
		Digito = (Soma * 10) % 11;
		if (Digito == 10)
			Digito = 0;
		controle = controle + Digito;
		ContIni = 2;
		K=1;
		ContFim = 13;
	}
	if (controle == CGC2)
	  return true;
	else{
	  alert('CNPJ Invalido.');
	  campo.value=''
	  campo.focus();
	}
}

function salvaconfiguracao(tipo){
		document.frmPagamento.action = "?page=produtos_pagamento&post_type=produtos&tp="+tipo+"&acao=salva";
		document.frmPagamento.submit();
}

function finalizaPedidoPagamento(chave, caminho, pedido){
	var selecionado = '0';
	total = document.getElementById('hdTotForma').value;

	for(i=1;i<=20;i++){
		if(document.getElementById('frmFormaPagamento'+i))
			if(document.getElementById('frmFormaPagamento'+i).checked)
				selecionado = document.getElementById('frmFormaPagamento'+i).value;
	}
	if(selecionado == '0'){
		alert("A forma de pagamento deve ser selecionada!!");
		return false;
	}

	if(document.getElementById('frmPagamento')){
		document.frmPagamento.action ="./?"+chave+"&tp=envia&pgto="+selecionado+"&pid="+pedido
		document.frmPagamento.submit();
	}else{
		document.frmPedido.action ="./?"+chave+"&tp=envia&pgto="+selecionado+"&pid="+pedido
		document.frmPedido.submit();
	}
}
