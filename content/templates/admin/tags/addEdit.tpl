{extends "../_main.tpl"}
{block name="content"}
<form action="" method="POST" enctype="multipart/form-data">
<table class="contTbl">
<tr valign="top">
	<td>
	<ul class="tab">
		{if $langs|count > 0}
		{foreach $langs as $lang}
		<li><a {if $lang.key == config('lang.base')} id="defaultOpen" {/if} href="javascript:void(0)" class="tablinks" onclick="openTab(event, 'tab-{$lang.key}')">{$lang.text}</a></li>
		{/foreach}
		{/if}
	</ul>
	
	{if $langs|count > 0}
	{foreach $langs as $lang}
	<table id="tab-{$lang.key}" class="contTbl tabcontent">
		<tr>
			<td colspan="2"><b>Основная информация</b></td>
		</tr>
		<tr>
			<td width="150" nowrap>Название</td>
			<td>
				<input type="text" name="field[{$lang.key}][name]" value="{if isset($vars['langs'][$lang.key]['name'])}{$vars['langs'][$lang.key]['name']}{/if}" class="input" style="width:90%">
				{if isset($errors[$lang.key]['noName'])}<br/><small style="color:red;">{$errors[$lang.key]['noName']}</small>{/if}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br/>
				<div>Описание</div>
				{$descr[$lang.key]}
			</td>
		</tr>
	</table>
	{/foreach}
	{/if}

	
	<table class="contTbl">
		<tr>
			<td nowrap><label for="posi">Alias</label></td>
			<td>
				<input name="alias" type="text" id="alias" value="{$vars.alias}" />
				{if isset($errors.noAlias)}<br/><small style="color:red;">{$errors.noAlias}</small>{/if}
			</td>
		</tr>
		
		<tr>
			<td nowrap><label for="posi">Позиция</label></td>
			<td><input name="posi" type="text" id="posi" value="{$vars.posi}" /></td>
		</tr>
		<tr>
			<td><label for="isHidden">Не показывать</label></td>
			<td><input name="isHidden" type="checkbox" id="isHidden" {if $vars.isHidden} checked{/if} /></td>
		</tr>
	</table>

	<br>
	<input type="hidden" name="save" value="1" />
	<input type="hidden" name="goto" value="0" id="goto" />

	<input type="submit" value="{$save_and_edit}" class="button" />
	<br>или<br>
	<input type="submit" value="{$save_and_list}" class="button" onclick="$('#goto').val(1);" />
	</td>
	<td>
	</td>
</tr>
</table>
</form>
{/block}