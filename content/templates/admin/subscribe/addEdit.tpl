<form action="" method="POST" enctype="multipart/form-data">
	<table class="contTbl" width="600">
	<tr valign="top">
		<td class="table_h">От кого (имя)</td>
		<td><input type="text" name="from_name" value="{$vars.from_name}" size="100"></td>
	</tr>
	<tr valign="top">
		<td class="table_h">От кого (email)</td>
		<td><input type="text" name="from_email" value="{$vars.from_email}" size="100"></td>
	</tr>
	<tr valign="top">
		<td class="table_h">Заголовок</td>
		<td><input type="text" name="subject" value="{$vars.subject}" size="100"></td>
	</tr>	
	<tr>
		<td colspan="2">
		<b>Тест</b><br>
		{if $fck.message}
			{$fck.message}
		{else}
			<textarea name="message" rows="10" style="width:99%;">{$vars.message}</textarea>
		{/if}
	</td>
	</tr>
	</table>
	
	<br>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="id" value="{$smarty.get.id}">
	<input type="hidden" name="action" value="send">
	<input type="submit" value="Разослать" class="button">
</form>