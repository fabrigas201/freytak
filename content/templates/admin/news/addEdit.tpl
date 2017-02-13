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
			<td width="150">Для кого (например Коммерсант)</td>
			<td><input type="text" name="field[{$lang.key}][for_smi]" value="{if isset($vars['langs'][$lang.key]['for_smi'])}{$vars['langs'][$lang.key]['for_smi']}{/if}" class="input" style="width:90%"></td>
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
		
	<br>
	<table class="contTbl">
		<tr>
			<td colspan="2"><b>Дополнительная информация</b></td>
		</tr>
		<tr>
			<td nowrap><label for="posi">Позиция</label></td>
			<td><input name="posi" type="text" id="posi" value="{$vars.posi}" /></td>
		</tr>
		<tr>
			<td>Категория</td>
			<td>
				<select name="categories" id="categories" style="width:90%">
					<option value="0">[Root<!--Новая-->]</option>
					{foreach from=$menu item=item}
						<option value="{$item->id}" {if isset($vars.categories) && $vars.categories==$item->id} selected="selected"{/if}>{$item->title}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="inCalendar">Добавить в календарь</label></td>
			<td>
				<input type="checkbox" name="inCalendar" id="inCalendar"{if $vars.inCalendar} checked{/if}> <br>
			</td>
		</tr>
		
		<tr>
			<td>Дата публикации</td>
			<td>
				<input type="text" name="dateAdd" value="{$vars.updated_at}" id="dateAdd" readonly>
				<a href="#" id="calendar-trigger"><img src="/i/icons/calendar.png" alt="" border="0" align="middle">
				<script type="text/javascript">
					Calendar.setup( {
						"ifFormat":"%Y-%m-%d %H:%M:%S",
						"daFormat":"%Y/%m/%d",
						"firstDay":1,
						"showsTime":true,
				//		"showOthers":false,
						"timeFormat":24,
						"inputField":"dateAdd",
						"button":"calendar-trigger"});
				</script>
			</td>
		</tr>
		
		<tr>
			<td>Дата события</td>
			<td>
				<input type="text" name="eventDate" value="{$vars.eventDate}" id="eventDate" readonly>
				<a href="#" id="calendar-trigger1"><img src="/i/icons/calendar.png" alt="" border="0" align="middle">
				<script type="text/javascript">
					Calendar.setup( {
						"ifFormat":"%Y-%m-%d %H:%M:%S",
						"daFormat":"%Y/%m/%d",
						"firstDay":1,
						"showsTime":true,
						//"showOthers":false,
						"timeFormat":24,
						"inputField":"eventDate",
						"button":"calendar-trigger1"});
				</script>
			</td>
		</tr>
		<tr>
			<td><label for="isHidden">Не показывать</label></td>
			<td><input name="isHidden" type="checkbox" id="isHidden" {if $vars.isHidden} checked{/if}></td>
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

			{*$forms.categoriesForm*}
	{/if}
	</td>
</tr>
</table>
</form>
{/block}