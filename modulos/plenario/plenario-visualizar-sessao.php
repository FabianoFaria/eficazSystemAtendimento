<?php
require_once("functions.php");
global $caminhoSistema, $configPlenario;
acertaFollowsAbertos();
$query = mpress_query("select Sessao_ID from sessao_workflows where Situacao_ID = '148' order by Sessao_ID desc");
if($rs = mpress_fetch_array($query))
	$sessaoID = $rs['Sessao_ID'];

if ($sessaoID==""){
	echo "	<div id='container-geral'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Sessão</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left; width:100%; height:200px'>
							<p align='center'><br><br><br><br><br><br> NENHUMA SESSÃO EM ANDAMENTO NO MOMENTO</p>
						</div>
					</div>
				</div>
			</div>";
}
else{
	echo "	<script type='text/javascript' src='".$caminhoSistema."/modulos/plenario/inc/TimeCircles.js'></script>
			<link rel='stylesheet' href='".$caminhoSistema."/modulos/plenario/inc/TimeCircles.css'/>";

	$sql = "select s.Titulo, ts.Descr_Tipo as Situacao, s.Situacao_ID, Data_Cadastro
			from sessao_workflows s
			inner join tipo ts on ts.Tipo_ID = s.Situacao_ID
			where s.Sessao_ID = '$sessaoID'";
	//echo $sql;
	if($rs = mpress_fetch_array(mpress_query($sql))){
		$titulo = $rs['Titulo'];
		$situacao = $rs['Situacao'];
		$situacaoID = $rs['Situacao_ID'];
	}
	echo "	<style>#menu-container, #titulo-principal, #topo-container{display:none;}</style>";
	echo "	<div id='container-geral'>";
	echo "		<div class='titulo-container'>
					<div class='titulo'>
						<p>
							Sessão - $titulo <input type='hidden' name='sessao-id' id='sessao-id' value='$sessaoID'/>
							<input type='button' class='sair-visualiza-sessao' value='Sair'/>
						</p>
					</div>
					<div class='conteudo-interno'>
						<div id='conteudo-andamento-sessao'>";
	carregarAndamentoSessao($sessaoID,"");
	echo "				</div>
					</div>
				</div>";
	echo "	</div>";

/*

	echo "		<div class='titulo-container'>
					<div class='titulo'>
						<p>Histórico:</p>
					</div>
					<div class='conteudo-interno'>
						<div id='conteudo-historico-follows'>";
	carregarSessaoFollows($sessaoID);
	echo "				</div>
					</div>
				</div>";
	echo "	</div>";
*/

}
