<form action="" method="POST" enctype="multipart/form-data">
	<table class="contTbl" width="600">
	{*<!--tr valign="top">
		<td class="table_h">Наименование</td>
		<td><input type="text" name="name" value="{$vars.name}" size="100"></td>
	</tr-->*}
	<tr>
		<td>
			<fieldset>
			<legend>Отправитель</legend>
			<div>
				Имя: {$l->userinfo.fio}
			</div>
			<div>
				Email: {$l->userinfo.email}
			</div>
			<div>
				Телефон: {$l->userinfo.phone}
			</div>
			</fieldset>
			<fieldset>
			<legend>Сообщение</legend>
				<div>{$l->message}</div>
			</fieldset>
			{if $l->file}
				<fieldset>
				<legend>Файл</legend>
					<div><a href="{$l->file.path}{$l->file.name}" target="_blank">{$l->file.name}</a></div>
				</fieldset>
			{/if}
			<!--label><input name="isHidden" type="checkbox" {if $vars.isHidden} checked{/if}> <b>Не показывать</b></label-->
		</td>
	</tr>
	</table>
	
	<br>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="type" value="{$smarty.get.type}">
	<input type="hidden" name="id" value="{$smarty.get.id}">
	<input type="hidden" name="action" value="{$smarty.get.action}">
	<!--input type="submit" value="Сохранить" class="button"-->
</form>