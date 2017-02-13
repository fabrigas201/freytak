{extends "../_main.tpl"}
{block name="content"}
<table>
<tr valign="top"><td>

<form action="" method="POST" enctype="multipart/form-data">
<table class="contTbl">
	<tr valign="top">
		<td>
		
		
			<div>
				<b>Название сайта</b><br>
				<input name="title" class="input" value="{$vars.title}">
			</div>
			<br />
			
			<div>
				<b>Desciption</b><br>
				<textarea name="description" cols="44" rows="7">{$vars.description}</textarea>
			</div>
			<br />
			<div>
				<b>Keywords</b><br>
				<textarea name="keywords" cols="44" rows="7">{$vars.keywords}</textarea>
			</div>
			<br />
			<div>
				<b>Email</b><br>
				<input name="email" class="input" value="{$vars.email}">
			</div>
			<br />
			<div>
				<b>Дополнительные ящики</b><br>
				<textarea name="emails" cols="44" rows="7">{$vars.emails}</textarea>
			</div>
			<div>
				<b>CC</b><br>
				<textarea name="emails_cc" cols="44" rows="7">{$vars.emails_cc}</textarea>
			</div>
			<div>
				<b>BCC</b><br>
				<textarea name="emails_bcc" cols="44" rows="7">{$vars.emails_bcc}</textarea>
			</div>
			<br>
			<input type="hidden" name="save" value="1">
			<input type="submit" value="Сохранить" class="button">
		</td>
	</tr>
</table>
</form>

</td><td>



</td></tr>
</table>
<br>

<table class="dN">
<tr valign="top"><td>

<form action="" method="POST" enctype="multipart/form-data">
<table class="contTbl">
<tr valign="top"><td>
		<div><b>—хема проезда</b></div>
		
		{$forms.uploadMapForm}
		<br>
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="action" value="uploadMap">
		<input type="submit" value="—охранить" class="button">
	</td></tr>
</table>
</form>

</td><td>


</td></tr>
</table>
<br>
{/block}