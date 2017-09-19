<?php
	$dadospagina = get_page_content();
	localizaCadastros();
	function localizaCadastros(){
			global $dadosUserLogin;
			if ($dadosUserLogin['grupoID']==3){
				$representanteID = ($dadosUserLogin['userID'] * -1);
				$sqlCond .= "and Fornecedor_ID = ".($representanteID*-1);
			}
			$id = $_POST['localiza-id'];
			$codigo = $_POST['localiza-codigo'];
			$tipoCadastro = $_POST['localiza-tipo-cadastro-id'];
			$nomeCompleto = $_POST['localiza-nome-completo'];
			$cpf = $_POST['localiza-cpf'];
			$cnpj = $_POST['localiza-cnpj'];


			echo "	<div id='cadastros-container'>
						<div class='titulo-container'>
							<div class='titulo'>
								<p>Localizar Cadastro</p>
							</div>
							<div class='conteudo-interno'>
								<div class='titulo-secundario' style='float:left;width:10%'>
									<p>Nº Pedido</p>
									<p><input type='text' id='localiza-id' name='localiza-id'  maxlength='10' style='width:90%' class='formata-numero' value='".$id."'/></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:10%'>
									<p>Protocolo (Ticket)</p>
									<p><input type='text' id='localiza-codigo' name='localiza-codigo'  maxlength='20' style='width:90%' class='formata-numero' value='".$codigo."'/></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:25%'>
									<p>Nome</p>
									<p><input type='text' id='localiza-nome-completo' name='localiza-nome-completo'  style='width:90%' maxlength='250' value='".$nomeCompleto."'/></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:20%'>
									<p>CPF</p>
									<p><input type='text' id='localiza-cpf' name='localiza-cpf' maxlength='14' style='width:95%' class='mascara-cpf' value='".$cpf."'/></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:20%'>
									<p>CNPJ</p>
									<p><input type='text' id='localiza-cnpj'  name='localiza-cnpj' maxlength='18' style='width:95%' style='width:95%'class='mascara-cnpj' value='".$cnpj."'/></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:10%; margin-top:15px;'>
									<p><input type='button' value='Pesquisar' id='botao-pesquisar-cadastros' style='width:90%' class='cadastra-grupos-produtos'/></p>
								</div>
							</div>
						</div>
					</div>
					<input type='hidden' id='localiza-cadastro-id' name='localiza-cadastro-id' value=''>
					<input type='hidden' id='localiza-workflow-id' name='localiza-workflow-id' value=''>";

		if(($_POST)&&(($nomeCompleto!="")||($cpf!="")||($cnpj!="")||($codigo!="")||($id!=""))){
			echo "<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;$style' cellpadding='$cellpadding' cellspacing='$cellspacing' align='center' id='$idTabela'>
					<thead>
						<tr>
							<td class='tabela-fundo-escuro-titulo' width=''>Cadastro</td>
							<td class='tabela-fundo-escuro-titulo' width='130px'>Tipo Pessoa </td>
							<td class='tabela-fundo-escuro-titulo' width='130px'>CPF/CNPJ	</td>
							<td class='tabela-fundo-escuro-titulo' width='090px'>Nº Pedido</td>
							<td class='tabela-fundo-escuro-titulo' width='090px'>Protocolo</td>
							<td class='tabela-fundo-escuro-titulo' width=''>Situa&ccedil;&atilde;o</td>
							<td class='tabela-fundo-escuro-titulo' width='090px'>Data Abertura</td>
							<td class='tabela-fundo-escuro-titulo' width='150px' align='center'>Usu&aacute;rio</td>
							<td class='tabela-fundo-escuro-titulo'>&nbsp;</td>
						</tr>
					</thead>
					<tbody>";

			if ($nomeCompleto != ""){ $sqlCond .= " and (cd.Nome like '%$nomeCompleto%'  or cd.Nome_Fantasia like '%$nomeCompleto%')";}
			if ($cpf != ""){ $sqlCond .= " and cd.Cpf_Cnpj like '%$cpf%'";}
			if ($cnpj != ""){ $sqlCond .= " and cd.Cpf_Cnpj like '%$cnpj%'";}
			if ($id != ""){ $sqlCond .= " and tw.Workflow_ID = '$id'";}
			if ($codigo != ""){ $sqlCond .= " and tw.Codigo = '$codigo'";}

			$sql = "select tw.Workflow_ID as Workflow_ID, tw.Codigo as Codigo, cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, cd.Nome, cd.Nome_Fantasia, cd.Cpf_Cnpj,
					cd.Inscricao_Municipal, cd.Inscricao_Estadual, COALESCE(twp.Descr_Tipo,'Nenhum processo cadastrado') as Tipo_Workflow,
					DATE_FORMAT(tw.Data_Cadastro,'%d/%m/%Y') as Data_Cadastro, coalesce(ts.Descr_Tipo,'N/A') as Situacao, usu.Nome as Usuario
					from cadastros_dados cd
					left join telemarketing_workflows tw on tw.Solicitante_ID = cd.Cadastro_ID
					left join cadastros_dados usu on usu.Cadastro_ID = tw.Usuario_Cadastro_ID
					left join tipo twp on twp.Tipo_ID = tw.Tipo_Workflow_ID
					left join tipo tp on tp.Tipo_ID = cd.Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
					left join tipo tc on tc.Tipo_ID = cd.Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
					left join telemarketing_follows tf on tw.Workflow_ID = tf.Workflow_ID
						and tf.Follow_ID = (select max(tfaux.Follow_ID) from telemarketing_follows tfaux where tf.Workflow_ID = tfaux.Workflow_ID)
					left join tipo ts on ts.Tipo_ID = tf.Situacao_ID
					where cd.Situacao_ID = 1
					$sqlCond
					order by cd.Cadastro_ID";
			//echo $sql ;
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$editarWorkflow = "";
				$nome = $row[Nome];
				if ($row[Nome_Fantasia]!=""){ $nome .= " / ".$row[Nome_Fantasia];}
				//$novoProcesso = "<input type='button' value='Novo Pedido' class='novo-workflow' cadastro-id='".$row[Cadastro_ID]."' Style='height:20px;font-size:10px;margin-top:-3px;width:100px' />";
				$editarWorkflow = "";
				if ($row[Workflow_ID]!=""){$editarWorkflow = "<input type='button' value='Visualizar Pedido' class='editar-workflow' workflow-id='".$row[Workflow_ID]."' Style='height:20px;font-size:10px;margin-top:-3px;width:100px'/>";}
				if ($row[Cadastro_ID]!=$cadastroIDAnt){
					$campos = "	<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'><b class='link-cadastro link' cadastro-id='".$row[Cadastro_ID]."' style='cursor:pointer;'>".$nome."</b></p></td>
								<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'><b>".$row[Tipo_Pessoa]."</b></p></td>
								<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'><b>".$row[Cpf_Cnpj]."</b></p></td>";
				}
				else{
					$campos = "	<td class='fundo-escuro'>&nbsp;</td>
								<td class='fundo-escuro'>&nbsp;</td>
								<td class='fundo-escuro'>&nbsp;</td>";
				}
				echo "	<tr>
							".$campos."
							<td class='fundo-escuro'><p Style='margin:2px 5px 0 20px;float:left;' class='editar-workflow link' workflow-id='".$row[Workflow_ID]."'><b>".$row[Workflow_ID]."</b></p></td>
							<td class='fundo-escuro'><p Style='margin:2px 5px 0 20px;float:left;' class='editar-workflow link' workflow-id='".$row[Workflow_ID]."'><b>".$row[Codigo]."</b></p></td>
							<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Situacao]."</p></td>
							<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Data_Cadastro]."</p></td>
							<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Usuario]."</p></td>
							<td class='fundo-escuro' width='105'><p Style='margin:2px 5px 0 5px;float:left;'>".$editarWorkflow."</p></td>
						</tr>";
				$cadastroIDAnt = $row[Cadastro_ID];
			}
			echo "	</tbody>
				</table>";

			if($i==0){
				echo "	<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro/processo localizado</p><br><br>
						<p Style='margin:2px 5px 0 5px;color:red; text-align:center'><input type='button' id='localizar-incluir-novo-cadastro' name='localizar-incluir-novo-cadastro' value='INCLUIR NOVO CADASTRO'/></p>";
			}
		}
		echo " <div id='div-novo-cadastro' style='position:absolute; width:800px; z-index:100; overflow:hidden;border-radius:15px;' class='esconde'></div>";
	}
?>