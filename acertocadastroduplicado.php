<?php
include('config.php');
$sql = "update cadastros_dados cd set Cpf_Cnpj = trim(replace(replace(replace(Cpf_Cnpj,'/',''),'-',''),'.',''))";
mpress_query($sql);

$sql = "select cd.Nome, cd.Cpf_Cnpj, count(*) cont, 
			(select cc.Cadastro_ID from cadastros_dados cc where cc.Cpf_Cnpj = cd.Cpf_Cnpj and Situacao_ID = 1 order by Cadastro_ID asc limit 1) as Old_Cadastro_ID,
			(select cc.Cadastro_ID from cadastros_dados cc where cc.Cpf_Cnpj = cd.Cpf_Cnpj and Situacao_ID = 1 order by Cadastro_ID desc limit 1) as Novo_Cadastro_ID
			from cadastros_dados cd 
			where cd.Situacao_ID = 1
			and cd.Cpf_Cnpj <> ''
			group by cd.Cpf_Cnpj, cd.Nome
			having cont > 1";
//echo $sql;			
$resultado = mpress_query($sql);
while($rs = mpress_fetch_array($resultado)){	
	$cpfCnpj = $rs['Cpf_Cnpj'];
	$sql = 	"		select 'Telemarketing' as Modulo, tf.Workflow_ID as ID, ts.Descr_Tipo as Situacao, tf.Data_Cadastro as Data_Cadastro, tf.Descricao as Descricao,
							r.Nome as Responsavel, c.Cadastro_ID, c.Nome as Cadastro
						from tele_follows tf
						inner join tele_workflows tw on tw.Workflow_ID = tf.Workflow_ID
						inner join cadastros_dados r on r.Cadastro_ID = tw.Usuario_Cadastro_ID
						inner join cadastros_dados c on c.Cadastro_ID = tw.Cadastro_ID
						left join tipo ts on ts.Tipo_ID = tf.Situacao_ID
						where c.Cpf_Cnpj = '".$cpfCnpj."'
					union all
					select 'Orcamentos' as Modulo, of.Workflow_ID as ID, tf.Descr_Tipo as Situacao, of.Data_Cadastro as Data_Cadastro, of.Descricao as Descricao,
							r.Nome as Responsavel, c.Cadastro_ID, c.Nome as Cadastro
						from orcamentos_follows of
						inner join orcamentos_workflows ow on ow.Workflow_ID = of.Workflow_ID
						inner join cadastros_dados r on r.Cadastro_ID = of.Usuario_Cadastro_ID
						inner join cadastros_dados c on c.Cadastro_ID = ow.Solicitante_ID
						left join tipo tf on tf.Tipo_ID = of.Situacao_ID
						where c.Cpf_Cnpj = '".$cpfCnpj."'
					union all
					select 'Chamados' as Modulo, cf.Workflow_ID as ID, tf.Descr_Tipo as Situacao, cf.Data_Cadastro as Data_Cadastro, cf.Descricao as Descricao,
							r.Nome as Responsavel, c.Cadastro_ID, c.Nome as Cadastro
						from chamados_follows cf
						inner join chamados_workflows cw on cw.Workflow_ID = cf.Workflow_ID
						inner join cadastros_dados r on r.Cadastro_ID = cf.Usuario_Cadastro_ID
						inner join cadastros_dados c on c.Cadastro_ID = cw.Solicitante_ID
						left join tipo tf on tf.Tipo_ID = cf.Situacao_ID
						where c.Cpf_Cnpj = '".$cpfCnpj."'
					order by Data_Cadastro desc";
	$resultado1 = mpress_query($sql);
	if ($rs1 = mpress_fetch_array($resultado1)){
		if ($rs1['Modulo']=='Telemarketing'){
			$sql  = " update tele_workflows set Cadastro_ID = ".$rs['Novo_Cadastro_ID']." where Cadastro_ID = ".$rs1['Cadastro_ID'];
			mpress_query($sql);
			echo "<br>".$sql;
		}
		if ($rs1['Modulo']=='Orcamentos'){
			$sql  = " update orcamentos_workflows set Solicitante_ID = ".$rs['Novo_Cadastro_ID']." where Solicitante_ID = ".$rs1['Cadastro_ID'];
			mpress_query($sql);
			echo "<br>".$sql;
		}
		if ($rs1['Modulo']=='Chamados'){
			$sql  = " update chamados_workflows set Solicitante_ID = ".$rs['Novo_Cadastro_ID']." where Solicitante_ID = ".$rs1['Cadastro_ID'];
			mpress_query($sql);
			echo "<br>".$sql;
		}
	}
	else{
		$sql  = " update cadastros_dados set Situacao_ID = 3 where Cadastro_ID = ".$rs1['Cadastro_ID'];
		mpress_query($sql);
		echo "<br>".$sql;
	}
	
	if ($rs['Old_Cadastro_ID']<>$rs['Novo_Cadastro_ID']){
		$sql  = " update  cadastros_dados set Situacao_ID = 3 where Cadastro_ID = ".$rs['Old_Cadastro_ID'];
		mpress_query($sql);
		echo "<br>".$sql;
	}
}
echo "<br>Atualizado<br>";

$sql = "select c.Cpf_Cnpj, count(*) as cont
		from cadastros_dados c
		where Cpf_Cnpj != ''
		/*and Cpf_Cnpj = '00325400000177'*/
		group by Cpf_Cnpj
		having cont > 1";
echo $sql;
$resultado = mpress_query($sql);
while($rs = mpress_fetch_array($resultado)){	
	$sql = "select * from cadastros_dados where Cpf_Cnpj = '".$rs['Cpf_Cnpj']."' order by Cadastro_ID desc";
	echo "<br>".$sql;		
	$resultado1 = mpress_query($sql);
	while($rs1 = mpress_fetch_array($resultado1)){	
		echo "<br> ".$rs['Cpf_Cnpj']." - Quantidade Registros: ".$rs['cont'];
		/* PRIMEIRO REGISTRO */
		echo "<br>-".$rs1['Cpf_Cnpj']."!=".$CpfCnpjAnt;
		if ($rs1['Cpf_Cnpj']!=$CpfCnpjAnt){
			$sql = "update cadastros_dados set Situacao_ID = 1 where Cadastro_ID = '".$rs1['Cadastro_ID']."'";
			$cadastroIDFirst = $rs1['Cadastro_ID'];
			echo "<br>".$sql;
		}
		else{
			$sql = "update cadastros_dados set Situacao_ID = 3 where Cadastro_ID = '".$rs1['Cadastro_ID']."'";
			echo "<br>".$sql;
			mpress_query($sql);
			$sql = "update chamados_workflows set Solicitante_ID = ".$cadastroIDFirst." where Solicitante_ID = ".$rs1['Cadastro_ID'];
			echo "<br>".$sql;
			mpress_query($sql);
			$sql = "update orcamentos_workflows set Solicitante_ID = ".$cadastroIDFirst." where Solicitante_ID = ".$rs1['Cadastro_ID'];
			echo "<br>".$sql;
			mpress_query($sql);
			$sql = "update tele_workflows set Cadastro_ID = ".$cadastroIDFirst." where Cadastro_ID = ".$rs1['Cadastro_ID'];			
			echo "<br>".$sql;
			mpress_query($sql);
		}
		$CpfCnpjAnt = $rs1['Cpf_Cnpj'];
	}
}
?>