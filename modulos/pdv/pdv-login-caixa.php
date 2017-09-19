<?php
global $configPDV;
if ($configPDV['quantidade-caixas']==''){
	echo "	<p align='center'>
				<b>
					É NECESSÁRIO CONFIGURAR A QUANTIDADE DE PDV'S DISPONÍVEIS NO GERENCIADOR DO MÓDULO
				</b>
			</p>";
	exit($configPDV['quantidade-caixas']);
}
?>
<link rel='stylesheet' type='text/css' href='<?php echo $caminhoSistema?>/css/login.css' />
<style>
	#div-titulo-pagina{
		display:none;
	}
</style>
<div id='login-container'>
	<input type='hidden' id='tela-login' value='1'/>
	<!--
	<table width="538" height='109' cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:20px'>
		<tr>
			<td align='center'><img src='<?php echo $caminhoSistema;?>/images/login/m-press-logo.png'></td>
		</tr>
	</table>
	-->
	<table width="538" cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:20px' id='tabela-login'>
		<tr>
			<td align='center'>
				<input type='hidden' name='idCaixa' id='numero-caixa' value=''/>
				<table width='95%' border='0' height='100%' cellspacing='0' cellpadding='0' id='texto-senha' Style='margin-bottom:10px'>
					<tr height='35' style='background-color:#c9c9c9;'>
						<td colspan='2' align='center' valign='middle'><b>Selecione o PDV:</b></td>
					</tr>
<?php
		for($i=1;$i<=$configPDV['quantidade-caixas'];$i++){
			$sql = "select v.Atendente_ID, c.Nome as Atendente from pdv v
						inner join cadastros_dados c on c.Cadastro_ID = v.Atendente_ID
						where v.Caixa_Numero = '".$i."' and v.Situacao_ID = 97
						order by v.PDV_ID";
			//echo $sql;
			$resultset = mpress_query($sql);
			$statusPDV = "<span style='color:green;'>Livre</span>";
			if($rs = mpress_fetch_array($resultset)){
				$statusPDV = "<span style='color:red;'>Ocupado (".$rs['Atendente'].")</span>";
			}
			echo "	<tr height='48' class='lnk sel-pos-pdv' atendente-id='".$rs['Atendente_ID']."' pos='$i'>
						<td style='border-bottom:1px solid #c9c9c9;'>Posi&ccedil;&atilde;o $i</td>
						<td style='border-bottom:1px solid #c9c9c9;'>$statusPDV</td>
					</tr>";
		}
?>
						<!--
						<td colspan='2' class='' id='login-campo-senha'>
							<select name='idCaixa'  id='numero-caixa' Style='height:35px;width:100%;'>
								<option value=''></option>
<?php for($i=1;$i<=$configPDV['quantidade-caixas'];$i++)
		echo 					"<option value='$i'>Posi&ccedil;&atilde;o $i</option>";
?>
							</select>
						</td>
						-->
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
