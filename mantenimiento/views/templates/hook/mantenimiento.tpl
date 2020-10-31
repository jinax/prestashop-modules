{if isset($confirmation)}
	<div class="alert alert-success">Mensaje enviado</div>
{/if}
<fieldset id="mantenimiento" style="border: 0px;">
	<div class="block">
		{if isset($mantenimiento_img)}
			<div class="block_content logo">
				<img src="{$mantenimiento_img}" width="400" />
			</div>
		{/if}
		{if isset($mantenimiento_desc)}
			<div class="block_content page-content page-maintenance">
				<h3>{$mantenimiento_desc}</h3>
			</div>
		{/if}	
		<div class="block_content">
			<form method="post" action="">
				<div class="form-group col">
					<input type="text" class="form-control" name="Name" id="Name" style="margin: 5px;" placeholder="Nombre" /><br />	
				</div>	
				<div class="form-group col">
					<input type="text" class="form-control" name="Email" id="Email" style="margin: 5px;" placeholder="Email"/><br />
				</div>
				<div class="form-group col">
					<textarea name="Message" class="form-control" rows="15" cols="26" id="Message" style="margin: 5px;" placeholder="Mensaje"></textarea><br />
				</div>
				<button type="submit" class="btn btn-primary" name="mantenimiento_form" style="margin: 5px;" value="submit">Enviar</button>
			</form>
		</div>
	</div>
</fieldset>
