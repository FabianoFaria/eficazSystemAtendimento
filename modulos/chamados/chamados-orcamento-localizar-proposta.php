<?php
	session_start();
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
	global $caminhoSistema, $dadosUserLogin;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
<?php
		get_header();
		if ($_GET['modulo']!="") echo "\n <script type='text/javascript' src='$caminhoSistema/modulos/".$_GET['modulo']."/".$_GET['modulo'].".js'></script>";
?>
		</head>
		<body>
		<form name='form-lista-propostas' id='form-lista-propostas' method='post'>
			<input type='hidden' id='orcamento-id' name='orcamento-id' value='<?php echo $_GET['orcamento-id'];?>'/>
			<center><br>
			<div style='width:95%' class='iframe-interno'>
<?php
		if($dadosUserLogin['grupoID'] == -2) $condicoes.= " and o.Representante_ID = '".$dadosUserLogin['userID']."'";

		$sql = "select o.Workflow_ID, o.Codigo, o.Titulo, s.Nome as Solicitante, ts.Descr_Tipo as Situacao, o.Data_Abertura,
					o.Data_Finalizado, of.Data_Cadastro as Data_Interacao, r.Nome as Representante,
					op.Titulo as Titulo_Proposta, tp.Descr_Tipo as Situacao_Proposta, op.Proposta_ID as Proposta_ID,
					SUM(opp.Valor_Venda_Unitario * opp.Quantidade) as Valor,
					coalesce(ptp.Titulo_Tabela,'Tabela Padrão') as Tabela_Preco
					from orcamentos_workflows o
					inner join cadastros_dados s on s.Cadastro_ID = o.Solicitante_ID
					inner join orcamentos_propostas op on op.Workflow_ID = o.Workflow_ID and op.Situacao_ID = 1
					inner join orcamentos_propostas_produtos opp on opp.Proposta_ID = op.Proposta_ID and opp.Situacao_ID = 1
					left join cadastros_dados r on r.Cadastro_ID = o.Representante_ID
					left join tipo ts on ts.Tipo_ID = o.Situacao_ID
					left join orcamentos_follows of on o.Workflow_ID = of.Workflow_ID and of.Follow_ID = (select max(ofaux.Follow_ID) from orcamentos_follows ofaux where ofaux.Workflow_ID = o.Workflow_ID)
					left join tipo tp on tp.Tipo_ID = op.Status_ID
					left join produtos_tabelas_precos ptp on ptp.Tabela_Preco_ID = op.Tabela_Preco_ID
					where o.Workflow_ID > 0
					$condicoes
					group by o.Workflow_ID, o.Codigo, o.Titulo, s.Nome ,ts.Descr_Tipo, o.Data_Abertura, o.Data_Finalizado, of.Data_Cadastro, r.Nome, op.Titulo, tp.Descr_Tipo, op.Proposta_ID
					order by o.Data_Abertura desc ";
		//echo $sql;

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = $row[Workflow_ID];
			$dados[colunas][conteudo][$i][2] = $row[Solicitante];
			$dados[colunas][conteudo][$i][3] = $row[Titulo_Proposta];
			$dados[colunas][conteudo][$i][4] = $row[Situacao_Proposta];
			$dados[colunas][conteudo][$i][5] = $row[Tabela_Preco];
			$dados[colunas][conteudo][$i][6] = "<p align='rigth'>R$ ".number_format($row[Valor], 2, ',', '.')."</p>";
			//$dados[colunas][conteudo][$i][5] = $row[Representante];
			$dados[colunas][conteudo][$i][7] = "<center><input type='button' value='Copiar' proposta-id='".$row['Proposta_ID']."' class='sel-copiar-proposta' style='width:60px; height: 20px; font-size:10px;'></center>";
		}
		$largura = "100%";
		$colunas = "7";
		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Solicitante";
		$dados[colunas][titulo][3] 	= "Proposta";
		$dados[colunas][titulo][4] 	= "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][5]	= "Tabela";
		$dados[colunas][titulo][6]	= "Valor Proposta";
		//$dados[colunas][titulo][5] = "Representante";
		$dados[colunas][tamanho][2] = "width='50%'";

		echo "	<div class='titulo-container'>
					<div class='titulo'>
						<p>Selecione a proposta que deseja copiar:</p>
					</div>
					<div class='conteudo-interno'>";
		geraTabela($largura,$colunas,$dados, null, 'orcamento-localiza-proposta', 2, 2, 100,"");
		echo "		</div>
				</div>";
?>
			</div>
			</center>
		</form>
		</body>
	</html>