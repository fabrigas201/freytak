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
			<td width="150" nowrap>Alias</td>
			<td>
				<input type="text" name="field[{$lang.key}][alias]" value="{if isset($vars['langs'][$lang.key]['alias'])}{$vars['langs'][$lang.key]['alias']}{/if}" class="input" style="width:90%">
			</td>
		</tr>
		<tr>
			<td width="150" nowrap>Ключевые слова</td>
			<td><input type="text" name="field[{$lang.key}][metaK]" value="{if isset($vars['langs'][$lang.key]['metaK'])}{$vars['langs'][$lang.key]['metaK']}{/if}" class="input" style="width:90%"></td>
		</tr>
		<tr>
			<td width="150" nowrap>Описание</td>
			<td><input type="text" name="field[{$lang.key}][metaD]" value="{if isset($vars['langs'][$lang.key]['metaD'])}{$vars['langs'][$lang.key]['metaD']}{/if}" class="input" style="width:90%"></td>
		</tr>
		<tr>
			<td colspan="2">
				<br/>
				<div>Анонс</div>
				{$descr[$lang.key]}
				<br/>
				<div>Описание</div>
				{$descrfull[$lang.key]}
			</td>
		</tr>
	</table>
	{/foreach}
	{/if}
	<table class="contTbl">
		<tr>
			<td colspan="2"><b>Дополнительная информация</b></td>
		</tr>
		<tr>
			<td nowrap><label for="posi">Позиция</label></td>
			<td><input name="posi" type="text" id="posi" value="{$vars.posi}" /></td>
		</tr>
	</table>

	<br>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="goto" value="0" id="goto">
	<input type="submit" value="{$save_and_edit}" class="button">
	<br>или<br>
	<input type="submit" value="{$save_and_list}" class="button" onclick="$('#goto').val(1);">
	</td>
	<td>
	
	{if $forms.imagesForm}
	{foreach $forms.imagesForm as $v}
		{$v}
	{/foreach}
	{/if}
	</td>
	</tr>
</table>
</form>
{/block}