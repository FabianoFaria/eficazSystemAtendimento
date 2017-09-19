<?php $configuracoes = configuracaoAtualizada();
echo "<pre>";
print_r($configuracoes);
echo "</pre>";

if (!(is_array($configuracoes))) { 					//CASO VAZIO
	$btnHome 		= $caminhoSistema."/images/topo/btn-home.png";
	$btnHomeHover 	= $caminhoSistema."/images/topo/btn-home-hover.png";
	$btnSair 		= $caminhoSistema."/images/topo/btn-sair.png";
	$btnSairHover 	= $caminhoSistema."/images/topo/btn-sair-hover.png";
	$btnAjuda 		= $caminhoSistema."/images/topo/btn-ajuda.png";
	$btnAjudaHover 	= $caminhoSistema."/images/topo/btn-ajuda-hover.png";
	$logoEmpresa	= $caminhoSistema."/images/topo/logo.png";
}
else{
	echo "<pre>";
	print_r($configuracoes);
	echo "</pre>";
}


?>
<div id='configuracoes-container'>
	<div class="titulo-container conjunto0">
		<div class="titulo">
			<p>
				Topo
				<input type='button' value='Atualizar Layout' id='atualiza-layout' style='width:150px;'>
			</p>
		</div>
		<div class='conteudo-interno'>
			<div class="uma-coluna" style='height:90px;'>
				<div class="titulo-secundario seis-colunas">
					<p>
						<input type="button" value="Upload" for='botao-home' class='btn-upload-layout' style="float:left;height:15px;width:50px;font-size:10px;margin-top:0px;"/>
						<input type='file' name='botao-home' id='botao-home' class='esconde'/>
						Home
					</p>
					<p><img src="<?php echo $btnHome;?>" height='53px'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>
						Home Hover
						<input type="button" value="Upload" for='botao-home-hover' class='btn-upload-layout' style="float:left;height:15px;width:50px;font-size:10px;margin-top:0px;"/>
						<input type='file' name='botao-home-hover' id='botao-home-hover' class='esconde'/>
					</p>
					<p><img src="<?php echo $btnHomeHover;?>" height='53px'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>
						Sair
						<input type="button" value="Upload" for='botao-sair' class='btn-upload-layout' style="float:left;height:15px;width:50px;font-size:10px;margin-top:0px;"/>
						<input type='file' name='botao-sair' id='botao-sair' class='esconde'/>
					</p>
					<p><img src="<?php echo $btnSair;?>" height='53px'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>
						Sair Hover
						<input type="button" value="Upload" for='botao-sair-hover' class='btn-upload-layout' style="float:left;height:15px;width:50px;font-size:10px;margin-top:0px;"/>
						<input type='file' name='botao-sair-hover' id='botao-sair-hover' class='esconde'/>
					</p>
					<p><img src="<?php echo $btnSairHover;?>" height='53px'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>
						Ajuda
						<input type="button" value="Upload" for='botao-ajuda' class='btn-upload-layout' style="float:left;height:15px;width:50px;font-size:10px;margin-top:0px;"/>
						<input type='file' name='botao-ajuda' id='botao-ajuda' class='esconde'/>
					</p>
					<p><img src="<?php echo $btnAjuda;?>" height='53px'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>
						Ajuda Hover
						<input type="button" value="Upload" for='botao-ajuda-hover' class='btn-upload-layout' style="float:left;height:15px;width:50px;font-size:10px;margin-top:0px;"/>
						<input type='file' name='botao-ajuda-hover' id='botao-ajuda-hover' class='esconde'/>
					</p>
					<p><img src="<?php echo $btnAjudaHover;?>" height='53px'></p>
				</div>
			</div>

			<div class="titulo-secundario uma-coluna" Style='margin-top:5px;height:80px;'>
				<div class="titulo-secundario seis-colunas">
					<p>Familia Fonte</p>
					<p>
						<select name='familia-fonte-topo' id='familia-fonte-topo'>
							<option value='arial'>Arial</option>
						</select>
					</p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>Tamanho Fonte (px)</p>
					<p><input type='text' name='tamanho-fonte-topo' id='tamanho-fonte-topo' class='formata-numero' maxlength='3' value='<?php echo $configuracoes['tamanho-fonte-topo'];?>'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>Cor fonte:</p>
					<p><input type='text' style='width:50%' name='cor-fonte-topo' id='cor-fonte-topo' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></p>
				</div>
				<div class="titulo-secundario tres-colunas">
					<p>
						Logo Empresa
						<input type="button" value="Upload" for='logo-empresa' class='btn-upload-layout' style="height:15px;width:50px;font-size:10px;margin-top:0px;"/>
						<input type='file' name='logo-empresa' id='logo-empresa' value='' class='esconde'/>
					</p>
					<p><img src="<?php echo $logoEmpresa;?>" height='53px'></p>
				</div>
			</div>
		</div>
	</div>
	<div class="titulo-container conjunto1 esconde">
		<div class="titulo">
			<p>
				Menu
				<input type='button' id='atualizar-menus' value='Atualizar Dados Menu' style='width:150px;'/>
			</p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario' Style='width:100%'>
				<div class="titulo-secundario seis-colunas" Style='margin-top:5px'>
					<p>Familia Fonte</p>
					<p>
						<select name='familia-fonte-menu' id='familia-fonte-menu'>
							<option value='arial'>Arial</option>
							<option value='verdana'>Verdana</option>
						</select>
					</p>
				</div>
				<div class="titulo-secundario seis-colunas" Style='margin-top:5px'>
					<p>Tamanho Fonte (px)</p>
					<p><input type='text' name='tamanho-fonte-menu' id='tamanho-fonte-menu' value='<?php echo $configuracoes['tamanho-fonte-menu'];?>'></p>
				</div>
				<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
					<p>Cor fonte:</p>
					<p><input type='text' style='width:50%' name='cor-fonte-menuaa' id='cor-fonte-menu' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-menu'];?>'></p>
				</div>
			</div>
			<div class='titulo-secundario' Style='width:100%'>
			<?php
				$sql = "select distinct m.Modulo_ID, m.Nome, m.Descricao from modulos m
									inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
									where m.Situacao_ID = 1 and m.Modulo_ID > 0
									order by m.Posicao";
				$modulos = mpress_query($sql);
				while($row = mpress_fetch_array($modulos)){
					$i++;
					$dados[colunas][extras][$i][1] = " valign='top' ";
					$dados[colunas][extras][$i][2] = " valign='top' ";
					$dados[colunas][conteudo][$i][1] = "<p style='margin-left:10px;'><input type='text' name='pagina' class='pagina' value='".$row[Nome]."'/></b></p>";
					$dados[colunas][conteudo][$i][2] = "<p style='margin-left:10px;'><textarea style='width:90%; height:100px;'>".$row[Descricao]."</textarea></p>";
					$paginas = mpress_query("select Modulo_Pagina_ID, Titulo from modulos_paginas mp where Situacao_ID = 1 and Modulo_ID = ".$row[Modulo_ID]."  and (Pagina_Pai_ID = '' or Pagina_Pai_ID is null) order by Posicao");
					while($pag = mpress_fetch_array($paginas)){
						$dados[colunas][conteudo][$i][3] .= "<input type='text' name='pagina' class='pagina' id='".$pag[Modulo_Pagina_ID]."' ".$sel[$pag[Modulo_Pagina_ID]]." value='$pag[Titulo]'/><br>";
						$paginas2 = mpress_query("select Modulo_Pagina_ID, Titulo from modulos_paginas mp where Situacao_ID = 1 and Pagina_Pai_ID = '".$pag[Modulo_Pagina_ID]."' $strAcesso order by Posicao");
						while($pag2 = mpress_fetch_array($paginas2)){
							$dados[colunas][conteudo][$i][3] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																	<input type='text' name='pagina' class='pagina' id='".$pag2[Modulo_Pagina_ID]."' ".$sel[$pag2[Modulo_Pagina_ID]]." value='".$pag2[Titulo]."'/><br>";
						}
					}
				}

				$largura = "99%";
				$colunas = "3";

				$dados[colunas][tamanho][1] = "width='15%'";
				$dados[colunas][tamanho][2] = "width='20%'";

				$dados[colunas][titulo][1] = "Módulo";
				$dados[colunas][titulo][2] = "Descrição";
				$dados[colunas][titulo][3] = "Páginas";

				geraTabela($largura,$colunas,$dados);
?>
			</div>
		</div>
	</div>
		<!--
		<div class='conteudo-interno'>
			<div class="titulo-secundario uma-coluna" Style='margin-top:5px;'>
				<div class="titulo-secundario seis-colunas" Style='margin-top:5px'>
					<p>Familia Fonte</p>
					<p>
						<select name='familia-fonte-menu' id='familia-fonte-menu'>
							<option value='arial'>Arial</option>
						</select>
					</p>
				</div>
				<div class="titulo-secundario seis-colunas" Style='margin-top:5px'>
					<p>Tamanho Fonte (px)</p>
					<p><input type='text' name='tamanho-fonte-menu' id='tamanho-fonte-menu' value='<?php echo $configuracoes['tamanho-fonte-menu'];?>'></p>
				</div>
				<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
					<p>Cor fonte:</p>
					<p><input type='text' style='width:50%' name='cor-fonte-menuaa' id='cor-fonte-menu' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-menu'];?>'></p>
				</div>
			</div>
		</div>
		<div class='conteudo-interno' Style=''>
			<div class="titulo-secundario quatro-colunas">
				<p>Fundo n&iacute;vel 1</p>
				<p>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-um' id='fundo-nivel-um' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-um-hover' id='fundo-nivel-um-hover' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
				</p>
			</div>
			<div class="titulo-secundario quatro-colunas" >
				<p>Fundo n&iacute;vel 2</p>
				<p>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-dois' id='fundo-nivel-dois' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-dois-hover' id='fundo-nivel-dois-hover' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
				</p>
			</div>
			<div class="titulo-secundario quatro-colunas" >
				<p>Fundo n&iacute;vel 3</p>
				<p>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-tres' id='fundo-nivel-tres' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-tres-hover' id='fundo-nivel-tres-hover' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
				</p>
			</div>
			<div class="titulo-secundario quatro-colunas" >
				<p>Fundo n&iacute;vel 4</p>
				<p>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-quatro' id='fundo-nivel-quatro' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
					<div style='float:left; width:50%;'><input type='text' style='width:50%' name='fundo-nivel-quatro-hover' id='fundo-nivel-quatro-hover' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-topo'];?>'></div>
				</p>
			</div>
		</div>
	</div>
	-->



	<div class="titulo-container conjunto0">
		<div class="titulo">
			<p>Login</p>
		</div>
		<div class='conteudo-interno' Style='height:80px'>
			<div class="titulo-secundario tres-colunas">
				Imagem Login
				<p><input type='file' name='imagem-login' id='imagem-login'></p>
				<p Style='margin-top:5px;'><input type='file' name='imagem-login-hover' id='imagem-login-hover'></p>
			</div>
			<div class="titulo-secundario tres-colunas">
				Imagem Senha
				<p><input type='file' name='imagem-senha' id='imagem-senha'></p>
				<p Style='margin-top:5px;'><input type='file' name='imagem-senha-hover' id='imagem-senha-hover'></p>
			</div>
			<div class="titulo-secundario tres-colunas">
				Imagem Botão
				<p><input type='file' name='imagem-botao' id='imagem-botao'></p>
				<p Style='margin-top:5px;'><input type='file' name='imagem-botao-hover' id='imagem-botao-hover'></p>
			</div>
		</div>
	</div>
	<div class="titulo-container conjunto0">
		<div class="titulo">
			<p>Conteúdo</p>
		</div>
		<div class='conteudo-interno'>
			<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
				Cor fonte Título:
				<br><input type='text' style='width:50%' name='cor-fonte-titulo-conteudo' id='cor-fonte-titulo-conteudo' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-titulo-conteudo'];?>'>
			</div>
			<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
				Tamanho Fonte Título (px)
				<br><input type='text' name='tamanho-fonte-titulo-conteudo' id='tamanho-fonte-titulo-conteudo' value='<?php echo $configuracoes['tamanho-fonte-titulo-conteudo'];?>'>
			</div>
			<div class="titulo-secundario duas-colunas" Style='margin-top:5px'>
				Familia Fonte Título
				<br>
				<select name='fonte-titulo-conteudo' id='fonte-titulo-conteudo'>
					<option value='arial'>Arial</option>
				</select>
			</div>
		</div>
		<div class='conteudo-interno'>
			<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
				Cor fonte Sub-Título:
				<br><input type='text' style='width:50%' name='cor-fonte-subtitulo-conteudo' id='cor-fonte-subtitulo-conteudo' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-subtitulo-conteudo'];?>'>
			</div>
			<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
				Tamanho Fonte Sub-Título (px)
				<br><input type='text' name='tamanho-fonte-subtitulo-conteudo' id='tamanho-fonte-subtitulo-conteudo' value='<?php echo $configuracoes['tamanho-fonte-subtitulo-conteudo'];?>'>
			</div>
			<div class="titulo-secundario duas-colunas" Style='margin-top:5px'>
				Familia Fonte Sub-Título
				<br>
				<select  name='fonte-subtitulo-conteudo' id='fonte-subtitulo-conteudo'>
					<option value='arial'>Arial</option>
				</select>
			</div>
		</div>
		<div class='conteudo-interno'>
			<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
				Cor fonte conteúdo:
				<br><input type='text' style='width:50%' name='cor-fonte-conteudo' id='cor-fonte-conteudo' class='formata-cor' value='<?php echo $configuracoes['cor-fonte-conteudo'];?>'>
			</div>
			<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
				Tamanho Fonte conteúdo (px)
				<br><input type='text' name='tamanho-fonte-conteudo' id='tamanho-fonte-conteudo' value='<?php echo $configuracoes['tamanho-fonte-conteudo'];?>'>
			</div>
			<div class="titulo-secundario duas-colunas" Style='margin-top:5px'>
				Familia Fonte conteúdo
				<br>
				<select name='fonte-conteudo' id='fonte-conteudo'>
					<option value='arial'>Arial</option>
				</select>
			</div>
		</div>
		<div class='conteudo-interno'>
			<div class="titulo-secundario oito-colunas" Style='margin-top:5px'>
				Cor fonte link:
				<br><input type='text' style='width:50%' name='cor-fonte-link' id='cor-fonte-link'  class='formata-cor'  value='<?php echo $configuracoes['cor-fonte-link'];?>'>
			</div>
			<div class="titulo-secundario oito-colunas" Style='margin-top:5px'>
				Cor fonte link hover:
				<br><input type='text' style='width:50%' name='cor-fonte-link-hover' id='cor-fonte-link-hover'  class='formata-cor'  value='<?php echo $configuracoes['cor-fonte-link-hover'];?>'>
			</div>
			<div class="titulo-secundario quatro-colunas" Style='margin-top:5px'>
				Tamanho Fonte link (px)
				<br><input type='text' name='tamanho-fonte-link' id='tamanho-fonte-link' value='<?php echo $configuracoes['tamanho-fonte-link'];?>'>
			</div>
			<div class="titulo-secundario duas-colunas" Style='margin-top:5px'>
				Familia Fonte link
				<br>
				<select name='fonte-link-conteudo' id='fonte-link-conteudo'>
					<option value='arial'>Arial</option>
				</select>
			</div>
		</div>
	</div>
</div>
<script>
	/*
	$('document').ready(function() {
		$('.cor-fonte').modcoder_excolor();
		$(".titulo-secundario span").css('padding', '').css('width', '97%');
		$(".quatro-colunas span input").css('width', '90%');
		$(".oito-colunas span input").css('width', '78%');
		$(".modcoder_excolor_clrbox").css('height', '20px').css('margin-left', '-4px');
		$("#frmDefault").attr("enctype", "multipart/form-data").attr("encoding", "multipart/form-data");
	});
	*/
</script>

<?php
	function configuracaoAtualizada(){
		global $caminhoSistema;
		if($_POST){
			mpress_query("update tipo set descr_tipo = '".serialize($_POST)."' where tipo_id = 70");
			if($_FILES['botao-home'][name]!="") move_uploaded_file($_FILES['botao-home']['tmp_name'],"images/topo/btn-home.png");
			if($_FILES['botao-home-hover'][name]!="") move_uploaded_file($_FILES['botao-home-hover']['tmp_name'],"images/topo/btn-home-hover.png");
			if($_FILES['botao-sair'][name]!="") move_uploaded_file($_FILES['botao-sair']['tmp_name'],"images/topo/btn-sair.png");
			if($_FILES['botao-sair-hover'][name]!="") move_uploaded_file($_FILES['botao-sair-hover']['tmp_name'],"images/topo/btn-sair-hover.png");
			if($_FILES['logo-empresa'][name]!="") move_uploaded_file($_FILES['logo-empresa']['tmp_name'],"images/topo/logo.png");
			if($_FILES['fundo-nivel-um-fundo'][name]!="") move_uploaded_file($_FILES['fundo-nivel-um-fundo']['tmp_name'],"images/menu/ramificacao01.jpg");
			if($_FILES['fundo-nivel-um'][name]!="") move_uploaded_file($_FILES['fundo-nivel-um']['tmp_name'],"images/menu/btn-principal.jpg");
			if($_FILES['fundo-nivel-um-hover'][name]!="") move_uploaded_file($_FILES['fundo-nivel-um-hover']['tmp_name'],"images/menu/btn-principal-hover.jpg");
			if($_FILES['fundo-nivel-dois-fundo'][name]!="") move_uploaded_file($_FILES['fundo-nivel-dois-fundo']['tmp_name'],"images/menu/ramificacao01.jpg");
			if($_FILES['fundo-nivel-dois'][name]!="") move_uploaded_file($_FILES['fundo-nivel-dois']['tmp_name'],"images/menu/btn-ram01.jpg");
			if($_FILES['fundo-nivel-dois-hover'][name]!="") move_uploaded_file($_FILES['fundo-nivel-dois-hover']['tmp_name'],"images/menu/btn-ram01-hover.jpg");
			if($_FILES['fundo-nivel-tres-fundo'][name]!="") move_uploaded_file($_FILES['fundo-nivel-tres-fundo']['tmp_name'],"images/menu/ramificacao02.jpg");
			if($_FILES['fundo-nivel-tres'][name]!="") move_uploaded_file($_FILES['fundo-nivel-tres']['tmp_name'],"images/menu/btn-ram02.jpg");
			if($_FILES['fundo-nivel-tres-hover'][name]!="") move_uploaded_file($_FILES['fundo-nivel-tres-hover']['tmp_name'],"images/menu/btn-ram02-hover.jpg");
			if($_FILES['fundo-nivel-quatro-fundo'][name]!="") move_uploaded_file($_FILES['fundo-nivel-quatro-fundo']['tmp_name'],"images/menu/ramificacao03.jpg");
			if($_FILES['fundo-nivel-quatro'][name]!="") move_uploaded_file($_FILES['fundo-nivel-quatro']['tmp_name'],"images/menu/btn-ram03.jpg");
			if($_FILES['fundo-nivel-quatro-hover'][name]!="") move_uploaded_file($_FILES['fundo-nivel-quatro-hover']['tmp_name'],"images/menu/btn-ram03-hover.jpg");
			if($_FILES['imagem-login'][name]!="") move_uploaded_file($_FILES['imagem-login']['tmp_name'],"images/login/campo-usuario.png");
			if($_FILES['imagem-login-hover'][name]!="") move_uploaded_file($_FILES['imagem-login-hover']['tmp_name'],"images/login/campo-usuario-focus.png");
			if($_FILES['imagem-senha'][name]!="") move_uploaded_file($_FILES['imagem-senha']['tmp_name'],"images/login/campo-senha.png");
			if($_FILES['imagem-senha-hover'][name]!="") move_uploaded_file($_FILES['imagem-senha-hover']['tmp_name'],"images/login/campo-senha-focus.png");
			if($_FILES['imagem-botao'][name]!="") move_uploaded_file($_FILES['imagem-botao']['tmp_name'],"images/login/btn-login.png");
			if($_FILES['imagem-botao-hover'][name]!="") move_uploaded_file($_FILES['imagem-botao-hover']['tmp_name'],"images/login/btn-login-hover.png");

			$conteudoArquivo = file_get_contents("css/topo-modelo.css");
			$conteudoArquivo = str_replace('[familia-topo]',$_POST['familia-fonte-topo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[tamanho-topo]',$_POST['tamanho-fonte-topo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[cor-topo]',$_POST['cor-fonte-topo'],$conteudoArquivo);
			$arquivo = fopen("css/topo.css", "w");
			fwrite($arquivo, $conteudoArquivo);
			fclose ($arquivo);

			$conteudoArquivo = file_get_contents("css/menu-modelo.css");
			$conteudoArquivo = str_replace('[tamanho-menu]',$_POST['tamanho-fonte-menu'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[cor-menu]',$_POST['cor-fonte-menu'],$conteudoArquivo);
			$arquivo = fopen("css/menu.css", "w");
			fwrite($arquivo, $conteudoArquivo);
			fclose ($arquivo);

			$conteudoArquivo = file_get_contents("css/content-modelo.css");
			$conteudoArquivo = str_replace('[familia-titulo-conteudo]',$_POST['fonte-titulo-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[tamanho-titulo-conteudo]',$_POST['tamanho-fonte-titulo-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[cor-titulo-conteudo]',$_POST['cor-fonte-titulo-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[familia-subtitulo-conteudo]',$_POST['fonte-subtitulo-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[tamanho-subtitulo-conteudo]',$_POST['tamanho-fonte-subtitulo-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[cor-subtitulo-conteudo]',$_POST['cor-fonte-subtitulo-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[familia-conteudo]',$_POST['fonte-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[tamanho-conteudo]',$_POST['tamanho-fonte-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[cor-conteudo]',$_POST['cor-fonte-conteudo'],$conteudoArquivo);


			$conteudoArquivo = str_replace('[familia-link-conteudo]',$_POST['fonte-link-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[tamanho-link-conteudo]',$_POST['tamanho-fonte-link'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[cor-link-conteudo]',$_POST['cor-fonte-link'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[familia-link-conteudo-hover]',$_POST['fonte-link-conteudo'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[tamanho-link-conteudo-hover]',$_POST['tamanho-fonte-link'],$conteudoArquivo);
			$conteudoArquivo = str_replace('[cor-link-conteudo-hover]',$_POST['cor-fonte-link-hover'],$conteudoArquivo);


			$arquivo = fopen("css/content.css", "w");
			fwrite($arquivo, $conteudoArquivo);
			fclose ($arquivo);


		}
		$resultado = mpress_query("select descr_tipo from tipo where tipo_id = 70");
		$row = mysql_fetch_array($resultado);
		return unserialize($row[0]);

	}
?>