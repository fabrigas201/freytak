{extends "../_main.tpl"}
{block name="content"}

{*if isset($errors)}
	<p class="errorsBl">
	{foreach from=$errors item=item}
		<div style="color:red;">{$item}</div>
	{/foreach}
	</p>
{/if*}

<form action="" method="POST" enctype="multipart/form-data">
<table class="contTbl">
<tr valign="top">
	<td>
	{if $smarty.get.action==edit}
		<div align="right" style="margin:0 0 10px 0;"><a href="{get_url($vars.typeMenu)}/{$vars.alias}" target="_blank">посмотреть на сайте</a> <small style="color:gray">(откроется в новом окне)</small></div>
	{/if}
	
	
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
				<div>Текст</div>
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
			<td><label for="isIndex">Ведет на главную</label></td>
			<td><input name="isIndex" type="checkbox"  id="isIndex" {if $vars.isIndex} checked{/if}></td>
		</tr>

		<tr>
			<td>Тип меню</td>
			<td>
				<select name="typeMenu" id="typeMenu" style="width:90%">
					<option {if $vars.typeMenu == 'page'} selected {/if} value="page">По умолчанию (страница)</option>
					<option {if $vars.typeMenu == 'articles'} selected {/if} value="articles">Список</option>
					<option {if $vars.typeMenu == 'news'} selected {/if} value="news">Новости</option>
					<option {if $vars.typeMenu == 'contact'} selected {/if} value="contact">Контакты</option>
					<option {if $vars.typeMenu == 'vacancy'} selected {/if} value="vacancy">Вакансии</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>Родительская категория</td>
			<td>
				<select name="pid" id="pid" style="width:90%">
					<option value="0">[Root<!--Новая-->]</option>
					{foreach from=$articles item=item}
						<option value="{$item->id}" {if isset($vars.pid) && $vars.pid==$item->id} selected="selected"{/if}>{$item->title}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="isHidden">Не показывать</label></td>
			<td><input name="isHidden" type="checkbox" id="isHidden" {if $vars.isHidden} checked{/if}></td>
		</tr>
		<tr>
			<td><label for="in_sitemap">В sitemap</label></td>
			<td><input name="in_sitemap" type="checkbox" id="in_sitemap" {if $vars.in_sitemap} checked{/if}></td>
		</tr>
	</table>
	

	<input type="hidden" name="save" value="1">
	<input type="hidden" name="goto" value="0" id="goto">
	<input type="hidden" name="posi" value="{$vars.posi}">

	<input type="submit" value="{if $save_and_edit}Сохранить{else}Добавить{/if} и продолжить редактирование" class="button">
	<br>или<br>
	<input type="submit" value="{if $save_and_list}Сохранить{else}Добавить{/if} и вернуться к списку" class="button" onclick="$('#goto').val(1);">
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