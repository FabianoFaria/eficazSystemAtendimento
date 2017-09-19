<div id='importar-container'>
	<div class='titulo-container'>
		<div class='titulo' style="min-height:25px">
			<p style="margin-top:2px;">
			Importa&ccedil;&atilde;o Arquivo Bancário
			<input type="button" value="Importar" id="botao-importacao" class='esconde' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:100px;">
			</p>
		</div>
		<div class='conteudo-interno titulo-secundario'>
			<div style='width:30%;float:left;'>
				<p>
					<input type='file' name='arquivo-upload' id='arquivo-upload'/>
					<input type='submit' value='Importar dados' id='botao-selecione-arquivo' style='width:150px;'>
				</p>
			</div>
			<div style='width:30%;float:left;'>
				<p>&nbsp;</p>
				<p><div id='nome-arquivo-tmp'><b style='color:red'><?php if($_POST){?>Arquivo Importado com sucesso!<?}?></b></div></p>
			</div>
			<div style='width:40%;float:left;'>
				&nbsp;
				<p id='mensagem-aguarde' class='esconde' style='text-align:left;'> </p>
				<input type='hidden' name='arquivo-importar' id='arquivo-importar' value=''/>
			</div>
		</div>
	</div>
	<div class='titulo-container esconde' id='div-arquivo-aberto'>
		<div class='titulo' style="min-height:25px">
			<p style="margin-top:2px;">Arquivo</p>
		</div>
		<div class='conteudo-interno titulo-secundario' id='div-retorno'>
		</div>
	</div>

</div>

