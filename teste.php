<?php include('config.php');?>
<?php
/* script excluindo duplicidade no TELEMARKETING
$sql = "select Cadastro_ID, count(*) cont from tele_workflows
where Campanha_ID = 9
group by Cadastro_ID
having cont > 1";

$resultado = mpress_query($sql);
$i = 0;
while($rs = mpress_fetch_array($resultado)){
	if ($rs['cont']>1){
		//$sql = "select Workflow_ID from tele_workflows where Cadastro_ID = ".$rs['Cadastro_ID']." limit ".($rs['cont']-1);
		$sql = "delete from tele_workflows where Cadastro_ID = ".$rs['Cadastro_ID']." limit ".($rs['cont']-1);
		mpress_query($sql);
	}
}
echo "Excluido !";
*/
?>
<!--<iframe class="embed-responsive-item" src="https://caikron.com.br/iCaikron/3v/player/index.php?id=10" frameborder="0" scrolling="no"></iframe>-->
<iframe class="embed-responsive-item" height='100%' width='478' src="http://caikron.com.br/player/ricmais/pr/maringa.html" frameborder="0" scrolling="no"></iframe>