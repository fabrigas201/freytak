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
					<div>Адрес, телефоны, факсы</div>
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
				<td>Категория</td>
				<td>
					<select name="categories" id="categories" style="width:90%">
						<option value="0">[Root<!--Новая-->]</option>
						{foreach from=$menu item=item}
							<option value="{$item->id}" {if isset($vars.categories) &&$vars.categories==$item->id} selected="selected"{/if}>{$item->title}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<fieldset>
						<legend>Yandex карта</legend>
						<a href="http://api.yandex.ru/maps/tools/getlonglat/" target="_blank" style="float:right">Определение координат тут<!--Determination of coordinates here--></a>

						Использовать API:
						<label><input type="radio" name="data[mapApi]" value="yandex"{if $vars.data.mapApi==yandex} checked{/if}> yandex</label>
						<label><input type="radio" name="data[mapApi]" value="google"{if $vars.data.mapApi==google} checked{/if}> google</label>
						<label><input type="radio" name="data[mapApi]" value=""{if !$vars.data.mapApi} checked{/if}> выключить</label>
						<table><tr valign="top">
							<td>
								Координаты центрa карты<!--Coordinates map center-->:<br>
								<table class="contTbl">
									<tr valign="top"><td>Широта<!--Latitude--></td><td><input name="data[mapX]" value="{$vars.data.mapX}" style="width:55px"></td></tr>
									<tr valign="top"><td>Долгота<!--Longitude--></td><td><input name="data[mapY]" value="{$vars.data.mapY}" style="width:55px"></td></tr>
									<tr valign="top"><td>Масштаб<!--Scale--></td><td><input name="data[mapZoom]" value="{$vars.data.mapZoom}" style="width:55px"></td></tr>
								</table>
							</td><td>
								Координаты метки<!--Coordinates label-->:<br>
								<table class="contTbl">
									<tr valign="top"><td>Широта<!--Latitude--></td><td><input name="data[mapMarkX]" value="{$vars.data.mapMarkX}" style="width:55px"></td></tr>
									<tr valign="top"><td>Долгота<!--Longitude--></td><td><input name="data[mapMarkY]" value="{$vars.data.mapMarkY}" style="width:55px"></td></tr>
								</table>
							</td><td>
								<table class="contTbl">
									<tr valign="top"><td>Ширина карты</td><td><input name="data[mapW]" value="{$vars.data.mapW}" style="width:25px"></td></tr>
									<tr valign="top"><td>Высота карты</td><td><input name="data[mapH]" value="{$vars.data.mapH}" style="width:25px"></td></tr>
								</table>
							</td></tr>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>

		<br>
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="goto" value="0" id="goto">
		<input type="hidden" name="id" id="id" value="{$smarty.get.id}">
		<input type="hidden" name="action" value="{$smarty.get.action}">
		<input type="submit" value="{if $smarty.get.id}{$save_and_list}{else}Add{/if}" class="button">
	</td>
</tr>
</table>
</form>
{/block}