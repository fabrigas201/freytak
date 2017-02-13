<form action="" method="post" enctype="multipart/form-data">
<table class="contTbl">
	<tr valign="top">
		<td>

		{$forms.metainfoForm}

<table class="contTbl">
	<tr>
		<td width="150">Название</td>
		<td><input type="text" name="name" value="{$vars.name}" class="input" style="width:90%"></td>
	</tr>
	<tr>
		<td width="150">Альтернативаня ссылка</td>
		<td><input type="text" name="alt_link" value="{$vars.alt_link}" class="input" style="width:90%"></td>
	</tr>
	<tr>
		<td width="150"><label for="main">На главной</label></td>
		<td><input type="checkbox" name="main" id="main" {if $vars.main}checked="checked"{/if} style="width:auto"  class="input"></td>
	</tr>
	<tr>
		<td width="150"><label for="main">Не показывать</label></td>
		<td><input type="checkbox" name="isHidden" id="isHidden" {if $vars.isHidden}checked="checked"{/if} style="width:auto"  class="input"></td>
	</tr>
	{if $smarty.get.submod!=categories2}
		<tr>
			<td>Родительская категория</td>
			<td>
				<select name="pid" id="pid">
					<option value="0">[Root<!--Новая-->]</option>
					{foreach from=$categories item=item}
						<option value="{$item->id}" {if $vars.pid==$item->id} selected="selected"{/if}>{$item->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>

	{/if}

	{if $fck.descr}
		<tr>
			<td colspan="2">Описание<br>
				{$fck.descr}
			</td>
		</tr>
	{/if}


	{*<!--tr>
		<td>ID категории в 1C</td>
		<td><input name="custom_1" type="text" id="custom_1" value="{$vars.custom_1}" class="input" style="width:90%"></td>
	</tr-->*}
	{*<!--tr>
		<td>Показывать на главной</td>
		<td><input name="custom_2" type="text" id="custom_2" value="{$vars.custom_2}" class="input"></td>
	</tr-->*}
	{*<!--tr>
		<td>Название категории в прайсе</td>
		<td><input name="custom_3" type="text" id="custom_3" value="{$vars.custom_3}" class="input"></td>
	</tr-->*}
		<tr>
			<td colspan="2">Описание внизу страницы<br>
				{$fck.descr_bottom}
			</td>
		</tr>
		<tr>
			<td colspan=2>
				Доп ссылки в список подразделов<br>
				<textarea name='alt_links'>{$vars.alt_links}</textarea>
			</td>
		</tr>
	</table>
	<br>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="goto" value="0" id="goto">
	<input type="hidden" name="id" value="{$smarty.get.id}">
	<input type="hidden" name="posi" value="{$vars.posi}">
	<input type="hidden" name="action" value="{$smarty.get.action}">

	<input type="submit" value="{if $smarty.get.id}Сохранить{else}Добавить{/if} и продолжить редактирование" class="button">
	<br>или<br>
	<input type="submit" value="{if $smarty.get.id}Сохранить{else}Добавить{/if} и вернуться к списку" class="button" onclick="$('#goto').val(1);">
</td>
{if $forms.imagesForm}
	<td width="250">
		{foreach $forms.imagesForm as $v}
			{$v}
		{/foreach}
	</td>
{/if}
</tr>
</table>
</form>