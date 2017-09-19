<?php
	
	function carregarConfiguracoesSite(){
		$rs = mpress_query("SELECT Site_ID, Empresa_ID, URL, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID FROM sites_dados where Situacao_ID = 1");
		if($row = mpress_fetch_array($rs)){
			$retorno = unserialize($row['Dados']);
		}
		return $retorno;	
	}
	
	function carregarModulosSistema(){
		$rs = mpress_query("select Nome, Descricao, Slug from modulos where Situacao_ID = 1 order by Nome");
		while($row = mpress_fetch_array($rs)){
			$retorno[$row['Slug']] = $row['Nome'];
			//$retorno[$row['Slug']]['descricao'] = $row['Descricao'];
		}
		return $retorno;	
	}	
	
	
	function carregarTiposSistema($tipoGrupoID, $selecionado){
		if ($tipoGrupoID!=""){
			$sqlCond .= " and t.Tipo_Grupo_ID = '$tipoGrupoID'";
		}
		$rs = mpress_query("select t.Tipo_ID, t.Tipo_Grupo_ID, t.Descr_Tipo, t.Tipo_Auxiliar, tg.Descr_Tipo_Grupo from tipo t
							left join tipo_grupo tg on tg.Tipo_Grupo_ID = t.Tipo_Grupo_ID
							where t.Situacao_ID = 1
							$sqlCond
							order by t.Tipo_Grupo_ID, t.Tipo_ID");
		$i = 0;
		while($row = mpress_fetch_array($rs)){
			$i++;
			$retorno[$row['Tipo_ID']] = $row['Descr_Tipo'];
		}
		return $retorno;	
	}	
	
	
	function carregarTiposNiveisSistema($tipoGrupoID, $selecionado){
		$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $tipoGrupoID and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
		$i = 0;
		while($rs1 = mpress_fetch_array($query)){
			$i++;
			$retorno[$i]['tipoID'] = $rs1['Tipo_ID'];
			$retorno[$i]['descricao'] = $rs1['Descr_Tipo'];
			$retorno[$i]['nivel'] = 1;
			$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $tipoGrupoID and Tipo_Auxiliar ='".$rs1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
			while($rs2 = mpress_fetch_array($query2)){
				$i++;
				$retorno[$i]['tipoID'] = $rs2['Tipo_ID'];
				$retorno[$i]['descricao'] = $rs2['Descr_Tipo'];
				$retorno[$i]['nivel'] = 2;
				$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $tipoGrupoID and Tipo_Auxiliar ='".$rs2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($rs3 = mpress_fetch_array($query3)){
					$i++;
					$retorno[$i]['tipoID'] = $rs3['Tipo_ID'];
					$retorno[$i]['descricao'] = $rs3['Descr_Tipo'];
					$retorno[$i]['nivel'] = 3;
				}
			}
		}
		return $retorno;
	}

	
		

	function carregarFormulario($dados){
		$rs = mpress_query("select Dados from modulos_formularios where Modulo = '".$dados['modulo']."' and Slug = '".$dados['slug']."'");
		if($row = mpress_fetch_array($rs)){
			$retorno = unserialize($row['Dados']);
		}
		echo serialize($retorno);
	}	
	
	
	
?>




