<?php
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	global $connSite, $connSite;

	function criaSlugSite($resultado){
		$resultado = str_replace(' ','-', $resultado);
		$resultado = str_replace('º','o', $resultado);
		$resultado = str_replace('ª','a', $resultado);
		$resultado = str_replace('&','e', $resultado);
		$resultado = str_replace('-','-', $resultado);
		$resultado = str_replace('Ç','C', $resultado);
		$resultado = str_replace('ç','c', $resultado);
		$resultado = str_replace('á','a', $resultado);
		$resultado = str_replace('à','a', $resultado);
		$resultado = str_replace('â','a', $resultado);
		$resultado = str_replace('ã','a', $resultado);
		$resultado = str_replace('Á','A', $resultado);
		$resultado = str_replace('À','A', $resultado);
		$resultado = str_replace('Â','A', $resultado);
		$resultado = str_replace('Ã','A', $resultado);
		$resultado = str_replace('é','e', $resultado);
		$resultado = str_replace('è','e', $resultado);
		$resultado = str_replace('ê','e', $resultado);
		$resultado = str_replace('É','e', $resultado);
		$resultado = str_replace('È','e', $resultado);
		$resultado = str_replace('Ê','e', $resultado);
		$resultado = str_replace('ó','o', $resultado);
		$resultado = str_replace('ò','o', $resultado);
		$resultado = str_replace('ô','o', $resultado);
		$resultado = str_replace('ô','o', $resultado);
		$resultado = str_replace('õ','o', $resultado);
		$resultado = str_replace('Ó','O', $resultado);
		$resultado = str_replace('Ò','O', $resultado);
		$resultado = str_replace('Ô','O', $resultado);
		$resultado = str_replace('Õ','O', $resultado);
		$resultado = str_replace('Ô','O', $resultado);
		$resultado = str_replace('í','i', $resultado);
		$resultado = str_replace('Í','I', $resultado);
		$resultado = str_replace('ì','i', $resultado);
		$resultado = str_replace('Ì','I', $resultado);
		$resultado = str_replace('ú','u', $resultado);
		$resultado = str_replace('ù','u', $resultado);
		$resultado = str_replace('û','u', $resultado);
		$resultado = str_replace('Ú','U', $resultado);
		$resultado = str_replace('Ù','U', $resultado);
		$resultado = str_replace('Û','U', $resultado);
		$resultado = str_replace('(','', $resultado);
		$resultado = str_replace(')','', $resultado);
		$resultado = str_replace('__','-', $resultado);
		$resultado = str_replace('_','-', $resultado);
		$resultado = str_replace(' ','', $resultado);
		$resultado = str_replace("'","", $resultado);
		$resultado = str_replace('"','', $resultado);
		$resultado = str_replace("/","-", $resultado);
		$resultado = str_replace("*","", $resultado);
		$resultado = str_replace("$","", $resultado);
		$resultado = str_replace("%","", $resultado);
		$resultado = str_replace("¨","", $resultado);
		$resultado = str_replace("&","e", $resultado);
		$resultado = str_replace("@","", $resultado);
		$resultado = str_replace("|","", $resultado);
		$resultado = str_replace("?","", $resultado);
		$resultado = str_replace("[","", $resultado);
		$resultado = str_replace("]","", $resultado);
		$resultado = str_replace("`","", $resultado);
		$resultado = str_replace("´","", $resultado);
		$resultado = str_replace("#","", $resultado);
		$resultado = str_replace(":","", $resultado);
		$resultado = str_replace(";","", $resultado);
		$resultado = str_replace(">","", $resultado);
		$resultado = str_replace("<","", $resultado);
		$resultado = str_replace("+","", $resultado);
		$resultado = str_replace("~","", $resultado);
		$resultado = str_replace("^","", $resultado);
		$resultado = str_replace("=","", $resultado);
		$resultado = str_replace("}","", $resultado);
		$resultado = str_replace("{","", $resultado);
		$resultado = str_replace("[","", $resultado);
		$resultado = str_replace("]","", $resultado);
		$resultado = str_replace(",","", $resultado);
		$resultado = str_replace("----","-", $resultado);
		$resultado = str_replace("--","-", $resultado);
		$resultado = str_replace("--","-", $resultado);
		$resultado = str_replace(".","", $resultado);
		$resultado = str_replace(",","", $resultado);
		$resultado = strtolower(trim($resultado));
		return $resultado;
		/*
		$str = preg_replace('/[^a-z0-9-]/', '-', $str);
		$str = preg_replace('/-+/', "-", $str);
		return $str;
		*/
	}


	function carregarConexaoSite($siteID){
		global $connSite;
		if($row = mpress_fetch_array(mpress_query("select Site_ID, URL, Empresa_ID, Dados from sites_dados where Site_ID = '$siteID'"))){
			$dadosSite = unserialize($row['Dados']);
		}

		//echo "<pre>";
		//print_r($dadosSite);
		//echo "<pre>";


		$connSite = mysql_connect($dadosSite['db']['host'], $dadosSite['db']['username'], $dadosSite['db']['pwd']) or $erro = "Não é possivel conectar no Servidor";
		mysql_select_db($dadosSite['db']['dbname']) or $erro = "Não é possivel conectar no Banco de Dados";

		if ($erro==""){
			function msite_query($query){
				global $connSite;
				$query = mysql_query($query, $connSite);
				return $query;
			}
			function msite_fetch_array($query){
				global $connSite;
				$row = mysql_fetch_array($query);
				return $row;
			}
			function msite_fetch_object($query){
				global $connSite;
				$row = mysql_fetch_object($query);
				return $row;
			}
			function msite_identity(){
				global $connSite;
				return mysql_insert_id();
			}
			function msite_count($query){
				global $connSite;
				return mysql_num_rows($query);
			}
			return "";
		}
		else
			return $erro;
	}

	function carregarCategoriasSite($siteID){
		$sql = "select t.term_id as id, t.name as Categoria from wp_terms t
					inner join wp_term_taxonomy tt  on t.term_id = tt.term_id  and taxonomy = 'Categorias' and tt.parent = 0";
		$resultSet = msite_query($sql);
		while($rs = msite_fetch_array($resultSet)){
			$h .= "<br>".$rs['id']."-".$rs['Categoria'];
		}
		return $h;
	}

	function exportarLimparDadosSite($siteID){
		global $caminhoSistema;
		msite_query("delete p.* FROM wp_posts p INNER JOIN wp_produtos_produtos_imagens i ON i.Imagem_ID = p.ID and Produto_ID > 0 ");
		msite_query("delete p.* from wp_posts p join (select po.guid from wp_posts po where po.post_type = 'attachment' group by po.guid having count(*) > 1) q on q.guid = p.guid");
		msite_query("delete from wp_term_relationships where term_taxonomy_id in (select term_taxonomy_id from wp_term_taxonomy  where taxonomy = 'Categorias')");
		msite_query("delete from wp_terms where term_id in (select term_id from wp_term_taxonomy where taxonomy = 'Categorias')");
		msite_query("delete from wp_term_taxonomy  where taxonomy = 'Categorias'");
		msite_query("delete from wp_posts where post_type = 'produtos'");
		msite_query("delete from wp_produtos_produtos");
		msite_query("delete from wp_produtos_produtos_variacoes");
		msite_query("delete from wp_produtos_produtos_variacoes_detalhes");
		msite_query("delete from wp_produtos_produtos_variacoes_valores");
		msite_query("delete from wp_produtos_produtos_imagens");

		echo "<form action='".$caminhoSistema."/site/site-gerenciador#menu-superior-4' method='post' name='retorno'> <input type='hidden' name='site-seleciona' value='$siteID'></form><script>document.retorno.submit();</script>";
	}


	function exportarProdutosCategoriasSite($siteID, $produtoID){
		global $caminhoSistema;
		$sqlCond = "";
		if ($produtoID!=""){
			$sqlCond = " and Produto_ID = '$produtoID'";
			msite_query("delete from wp_term_relationships where Object_ID = $produtoID + 1000");
		}
		else{
			msite_query("delete from wp_term_relationships where term_taxonomy_id in (select term_taxonomy_id from wp_term_taxonomy  where taxonomy = 'Categorias')");
		}

		$sql = "select Produto_ID + 1000 as Produto_ID, Categoria_ID + 10000 as Categoria_ID
						from produtos_dados_categorias
						where Situacao_ID = 1
						$sqlCond";
		//echo $sql;
		$resultSet = mpress_query($sql);
		while($rs = mpress_fetch_array($resultSet)){
			$sql = "INSERT INTO wp_term_relationships (object_id, term_taxonomy_id, term_order)
											values (".$rs['Produto_ID'].", ".$rs['Categoria_ID'].",0)";
			//echo "<br>".$sql;
			msite_query($sql);
		}
		//exit();

		$sql = "UPDATE wp_term_taxonomy tt SET count = (SELECT count(p.ID) FROM  wp_term_relationships tr LEFT JOIN wp_posts p ON (p.ID = tr.object_id AND p.post_status = 'publish') WHERE tr.term_taxonomy_id = tt.term_taxonomy_id)";
		msite_query($sql);
	}

	function exportarCategoriasSite($siteID){
		global $caminhoSistema;
		msite_query("delete from wp_term_relationships where term_taxonomy_id in (select term_taxonomy_id from wp_term_taxonomy  where taxonomy = 'Categorias')");
		msite_query("delete from wp_terms where term_id in (select term_id from wp_term_taxonomy where taxonomy = 'Categorias')");
		msite_query("delete from wp_term_taxonomy  where taxonomy = 'Categorias'");



		$sql =" select (pc1.Categoria_ID + 10000) as Categoria_ID,
					if (pc1.Categoria_Pai_ID = 0, 0,(pc1.Categoria_Pai_ID + 10000)) as Categoria_Pai_ID,
					trim(pc1.Nome) as Nome,
					if (pc1.Categoria_Pai_ID = 0, trim(pc1.Nome), trim(concat(trim(coalesce(pc2.Nome,'')),' ',trim(pc1.Nome)))) as Slug
				from produtos_categorias pc1
				left join produtos_categorias pc2 on pc2.Categoria_ID = pc1.Categoria_Pai_ID and pc2.Situacao_ID = 1
				where pc1.Situacao_ID = 1";
		$resultSet = mpress_query($sql);

		/*
		$resultSet = mpress_query("select (Categoria_ID + 10000) as Categoria_ID,  if (Categoria_Pai_ID = 0, 0,(Categoria_Pai_ID + 10000)) as Categoria_Pai_ID, trim(Nome) as Nome, trim(Nome) as Slug, 0
												from produtos_categorias where Situacao_ID = 1");
		*/

		while($rs = mpress_fetch_array($resultSet)){
			$sql = "INSERT INTO wp_terms (term_id, name, slug, term_group)
									values ('".$rs['Categoria_ID']."','".$rs['Nome']."','".criaSlugSite($rs['Slug'])."',0)";
			msite_query($sql);
			$sql = "INSERT INTO wp_term_taxonomy (term_taxonomy_id, term_id, taxonomy, description, parent, count)
										values ('".$rs['Categoria_ID']."', '".$rs['Categoria_ID']."', 'Categorias', '', '".$rs['Categoria_Pai_ID']."', 0)";
			msite_query($sql);
		}
		echo "<form action='".$caminhoSistema."/site/site-gerenciador#menu-superior-4' method='post' name='retorno'> <input type='hidden' name='site-seleciona' value='$siteID'></form><script>document.retorno.submit();</script>";
	}

	function exportarProdutosSite($siteID, $produtoID){
		global $caminhoSistema;
		$rs = mpress_query("select URL, Empresa_ID, Dados from sites_dados where Site_ID = '$siteID'");
		if($row = mpress_fetch_array($rs)){
			$url = $row['URL'];
			$empresaID = $row['Empresa_ID'];
			$dados = unserialize($row['Dados']);
		}
		if ($produtoID!=""){
			$sqlCond = " and p.Produto_ID = '$produtoID'";
			$sqlCondA = " and id = ($produtoID + 1000)";
			$sqlCondB = " where Produto_ID = ($produtoID + 101000)";
		}

		msite_query("delete from wp_posts where post_type = 'produtos' $sqlCondA");
		msite_query("delete from wp_produtos_produtos $sqlCondB");

		/*$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";*/
		$dataHoraAtual = "NOW()";
		$sql = "select Produto_ID + 1000 as ID, Nome, Produto_ID + 1000 as Produto_ID, Descricao_Completa, trim(nome) as Slug, 'publish',
					Codigo, Descricao_Resumida, Destaque, Lancamento
							from produtos_dados p
							where Situacao_ID = 1
							and Produto_ID > 0
							$sqlCond
							and Tipo_Produto in (30,100)";

		$resultSet = mpress_query($sql);
		while($rs = mpress_fetch_array($resultSet)){
			$sql = "INSERT INTO wp_posts
								(ID, post_date, post_date_gmt, post_modified, post_modified_gmt, comment_status, ping_status,
								post_content, post_name, post_title, post_excerpt, post_status, guid, post_type, menu_order)
						VALUES (".$rs['ID'].", $dataHoraAtual, $dataHoraAtual, $dataHoraAtual, $dataHoraAtual, 'closed', 'closed',
								'".$rs['Descricao_Completa']."','".criaSlugSite($rs['Slug'])."','".$rs['Nome']."','','publish','".$url."/produtos/".criaSlugSite($rs['Slug'])."','produtos', 0)";
			//echo "<br><br>$sql";
			msite_query($sql);
			$destaque = $rs['Destaque'];
			$lancamento = $rs['Lancamento'];

			// liberar comentario abaixo
			if ($destaque==1) $destaque = 'S';
			if ($lancamento==1) $lancamento = 'S';

			$nome = $rs['Nome'];
			if ($rs['Descricao_Resumida']=="")
				$rs['Descricao_Resumida'] = $rs['Nome'];

			$sql = "INSERT INTO wp_produtos_produtos
							(Produto_ID, Codigo, Nome, Descricao_Resumida, Descricao_Completa, Situacao,
							Caracteristica_Grupo_ID, Data_Cadastro, Imagens_Automatica, Destaque, Lancamento)
						VALUES
							(".($rs['Produto_ID']+100000).", '".$rs['Codigo']."', '".$rs['Nome']."','".$rs['Descricao_Resumida']."','".$rs['Descricao_Completa']."', 'A',
							0, $dataHoraAtual, '', '".$destaque."', '".$lancamento."')";
			//echo "<br><br>$sql";
			msite_query($sql);
		}
	}

	function exportarProdutosVariacoesSite($siteID, $produtoID){
		$sqlCond = "";
		$sqlCondA = "";
		if ($produtoID!=""){
			$sqlCond = " and pd.Produto_ID = $produtoID";
			$sqlCondA = " and ppv.Produto_ID = ".($produtoID + 101000);
			$sqlCondB = " and Produto_ID = ".($produtoID + 101000);
		}

		global $caminhoSistema;
		msite_query("update wp_produtos_produtos_variacoes_valores as ppvv
						inner join wp_produtos_produtos_variacoes ppv on ppv.Produto_Variacao_ID = ppvv.Produto_Variacao_ID
						set ppvv.Data_Final_Cadastro = now()
						where Data_Final_Cadastro is NULL $sqlCondA");

		msite_query("delete pvd.* from wp_produtos_produtos_variacoes_detalhes pvd
						inner join wp_produtos_produtos_variacoes ppv on ppv.Produto_Variacao_ID = pvd.Produto_Variacao_ID
						where ppv.Produto_ID > 0 $sqlCondA");

		msite_query("delete from wp_produtos_produtos_variacoes where Produto_ID > 0 $sqlCondB");

		//$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		//$dataAtual = "'".retornaDataHora('','Y-m-d')."'";
		$dataHoraAtual = "NOW()";
		$dataAtual = "NOW()";

		$sql = "select pv.Produto_Variacao_ID, pv.Codigo, pv.Produto_ID + 101000 as Produto_ID, pv.Descricao, pv.Data_Inicio_Promocao, pv.Data_Fim_Promocao, pv.Valor_Venda, pv.Valor_Promocao
				from produtos_variacoes pv
					inner join produtos_dados pd on pv.Produto_ID = pd.Produto_ID
				where pv.Situacao_ID = 1 and pd.Situacao_ID = 1 and pv.Forma_Cobranca_ID IN(35,58)
				and pd.Produto_ID > 0
				$sqlCond
				and pd.Tipo_Produto IN (30,100)";
		//echo "<br><br>".$sql;
		$resultSet = mpress_query($sql);
		while($rs = mpress_fetch_array($resultSet)){
			$sql = "INSERT INTO wp_produtos_produtos_variacoes (Produto_Variacao_ID, Produto_ID, Descricao, Situacao, Data_Cadastro, Imagem_Variacao)
					VALUES(".$rs['Produto_Variacao_ID'].", ".$rs['Produto_ID'].", '".$rs['Descricao']."', 'A', $dataHoraAtual, 0)";
			//echo "<br><br>$sql";
			msite_query($sql);

			$sql = "INSERT INTO wp_produtos_produtos_variacoes_detalhes
					(Produto_Variacao_Detalhe_ID, Produto_Variacao_ID, Codigo, Descricao, Data_Cadastro, Quantidade_Inicial, Quantidade_Final, Agrupar_Variacoes)
					VALUES(".$rs['Produto_Variacao_ID'].", ".$rs['Produto_Variacao_ID'].", '".$rs['Codigo']."', '".$rs['Descricao']."', $dataHoraAtual, 0,0, '')";
			//echo "<br><br>$sql";
			msite_query($sql);

			$sql = "INSERT INTO wp_produtos_produtos_variacoes_valores
								(Produto_Variacao_ID, Valor_Normal, Valor_Promocional, Data_Inicio_Promocional, Data_Final_Promocional, Data_Cadastro, Data_Final_Cadastro)
								VALUES(".$rs['Produto_Variacao_ID'].", '".$rs['Valor_Venda']."', '".$rs['Valor_Promocao']."', '".$rs['Data_Inicio_Promocao']."', '".$rs['Data_Fim_Promocao']."', $dataHoraAtual, NULL)";
			//echo "<br><br>$sql";
			msite_query($sql);
		}
		//exit();
	}


	function exportarProdutosImagens($siteID, $produtoID){
		set_time_limit(0);
		global $wpdb, $caminhoSistema, $caminhoFisico;
		$wp_upload_dir = wp_upload_dir();
		$produtoIDAnt = "";
		$sqlCond = "";
		$sqlCondA = "";

		if ($produtoID!=""){
			$sqlCond = " and Produto_ID = $produtoID + 101000 ";
			$sqlCondA = " and pd.Produto_ID = $produtoID + 101000 ";
			$sqlCondB = " and pd.Produto_ID = $produtoID ";
		}
		$wpdb->query("delete p.* FROM wp_posts p INNER JOIN wp_produtos_produtos_imagens i ON i.Imagem_ID = p.ID and Produto_ID > 0 $sqlCond");
		$wpdb->query("delete from wp_produtos_produtos_imagens where Produto_ID > 0 $sqlCond");

		if ($produtoID==""){
			$wpdb->query("delete p.* from wp_posts p join (select po.guid from wp_posts po where po.post_type = 'attachment' group by po.guid having count(*) > 1) q on q.guid = p.guid");
		}

		$fotos = mpress_query("select (Chave_Estrangeira + 101000) as Produto_ID,  Chave_Estrangeira as ID, Nome_Arquivo Arquivo, Complemento, Nome_Arquivo_Original, ma.Anexo_ID
								from modulos_anexos ma
								  inner join produtos_dados pd on ma.Chave_Estrangeira = pd.Produto_ID
							  where ma.tabela_estrangeira = 'produtos' and pd.Situacao_ID = 1
							  $sqlCondB
							  and ma.Situacao_ID = 1
							  order by Produto_ID");

		while($row = mpress_fetch_array($fotos)){
			if ($row['Produto_ID']!=$produtoIDAnt)
				$posicao = 0;
			$posicao++;
			$complemento = unserialize($row['Complemento']);
			if (is_array($complemento)){
				$i++;
				echo "<br>".$i;
				$parent_post_id = $row['ID'];
				//$arquivo = $row['Nome_Arquivo_Original'];
				$arquivo = $row['Arquivo'];

				$fotoSistema = $caminhoFisico."/uploads/".$complemento['large'];
				$fotoSite = $wp_upload_dir['basedir']."/".$arquivo;
				if (file_exists($fotoSistema)){
					if(!@copy($fotoSistema, $fotoSite)){
						$errors= error_get_last();
						echo "COPY ERROR: ".$errors['type'];
						echo "<br />Erro-->\n".$errors['message'];
					}
					/*
					else {
						echo "Copiou!";
						echo "<br>de ".$fotoSistema;
						echo "<br>para ".$fotoSite;
						echo "<br>Caminho: ".$wp_upload_dir['url'] . '/' . $arquivo;
						echo "<br>post_title: ".preg_replace( '/\.[^.]+$/', '', $arquivo);
					}
					*/
					$filetype = wp_check_filetype(basename($fotoSite), null);
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . $arquivo,
						'post_mime_type' => $filetype['type'],
						'post_title'     => preg_replace( '/\.[^.]+$/', '', $arquivo),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);

					$attachID = wp_insert_attachment($attachment, $arquivo, $parent_post_id);
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata($attachID, $fotoSite);
					wp_update_attachment_metadata($attachID, $attach_data);

					$sql = "insert into wp_produtos_produtos_imagens(Produto_ID, Imagem_ID, Posicao_Imagem)values(".$row['Produto_ID'].", $attachID, $posicao)";
					//echo "<br>".$sql;
					$wpdb->query($sql);

					$sql = " update wp_produtos_produtos_variacoes as wpv
								  inner join produtos_variacoes pv on pv.Produto_Variacao_ID = wpv.Produto_Variacao_ID
								  set wpv.Imagem_Variacao = '$attachID'
								  where pv.Imagem_ID = '".$row['Anexo_ID']."'";
					//echo "<br>".$sql;
					$wpdb->query($sql);
				}
			}
			$produtoIDAnt = $row['Produto_ID'];
		}

	}

	function siteProdutosAtualizar(){
		$produtoID = $_POST['produto-id'];
		foreach($_POST['sites-selecionados'] as $siteID){
			carregarConexaoSite($siteID);
			exportarProdutosSite($siteID, $produtoID);
			exportarProdutosVariacoesSite($siteID, $produtoID);
			exportarProdutosCategoriasSite($siteID, $produtoID);
			exportarProdutosImagens($siteID, $produtoID);
		}
	}

	function salvarSite(){
		global $dadosUserLogin, $caminhoSistema;
		$url = $_POST['site-url'];
		$empresaID = $_POST['site-empresa'];
		$siteID = $_POST['site-id'];
		$dados = $_POST['config'];
/*
		foreach($_POST['bloco'] as $chaveBloco => $bloco){
			$dados[$chaveBloco]['bloco'] = $bloco;
			foreach($_POST['exibe'][$chaveBloco] as $chave => $exibe){
				$dados[$chaveBloco][campos][$chave]['name'] = $_POST['name'][$chaveBloco][$chave];
				$dados[$chaveBloco][campos][$chave]['labelpf'] = $_POST['labelpf'][$chaveBloco][$chave];
				$dados[$chaveBloco][campos][$chave]['labelpj'] = $_POST['labelpj'][$chaveBloco][$chave];
				$dados[$chaveBloco][campos][$chave]['class'] = $_POST['class'][$chaveBloco][$chave];
				$dados[$chaveBloco][campos][$chave]['column'] = $_POST['column'][$chaveBloco][$chave];
				$dados[$chaveBloco][campos][$chave]['type'] = $_POST['type'][$chaveBloco][$chave];
			}
		}
*/

/*
		$formularioID = '';
		$rs = mpress_query("select Formulario_ID from modulos_formularios where Modulo = 'cadastros' and Slug = 'formulario-cadastro'");
		if($row = mpress_fetch_array($rs)){
			$formularioID = $row['Formulario_ID'];
		}
		if ($formularioID==''){
			mpress_query("INSERT INTO modulos_formularios (Modulo, Slug, Dados, Situacao_ID, Usuario_Cadastro_ID)
										VALUES ('cadastros', 'formulario-cadastro', '".serialize($dados)."', 1, '".$dadosUserLogin[userID]."')");
		}
		else{
			mpress_query("UPDATE modulos_formularios set Dados = '".serialize($dados)."' where Formulario_ID = '$formularioID'");
		}
*/
		if ($_POST['acao-site']=="I"){
			$sql = "INSERT INTO sites_dados (Empresa_ID, URL, Dados, Situacao_ID, Usuario_Cadastro_ID)
										VALUES ('$empresaID', '$url', '".serialize($dados)."', 1, '".$dadosUserLogin[userID]."')";
			mpress_query($sql);
			$siteID = mpress_identity();
		}
		if ($_POST['acao-site']=="U"){
			$sql = "Update sites_dados set Empresa_ID = '$empresaID', URL = '$url', Dados = '".serialize($dados)."' where Site_ID = '$siteID'";
			mpress_query($sql);
		}

		if ($_POST['acao-site']=="D"){
			$sql = "Update sites_dados set Situacao_ID = 3 where Site_ID = '$siteID'";
			mpress_query($sql);
			$siteID = "";
		}

		echo "<form action='".$caminhoSistema."/site/site-gerenciador' method='post' name='retorno'> <input type='hidden' name='site-seleciona' value='$siteID'></form><script>document.retorno.submit();</script>";

	}



?>