<form action="" method="post" enctype="multipart/form-data">
<table width="600"  border="0" cellpadding="4" cellspacing="1">
	<tr>
		<td width="150">Название</td>
		<td><input name="name" type="text" id="name" value="{$vars.name}" class="input" style="width:90%"></td>
	</tr>
	<tr>
		<td>Куда ведет</td>
		<td>
			<select name="module" id="pid">
				<option value="0">[выберите]</option>
				<optgroup label="Модули">
				{foreach from=$moduls item=item}
					<option value="{$item.module}" {if $vars.moduleName==$item.module} selected="selected"{/if}>{$item.name}</option>
					{if $item.module=='producers'}
						{foreach from=$catProd item=item2}
							<option value="{$item.module}_{$item2->id}" {if $vars.moduleName=='producers' && $vars.moduleId==$item2->id} selected="selected"{/if}>|-- {$item2->name}</option>
						{/foreach}
					{/if}
				{/foreach}
				</optgroup>
				<optgroup label="Статьи">
					{foreach from=$articles item=item}
						<option value="articles_{$item->id}" {if $vars.moduleName=='articles' and $vars.moduleId==$item->id} selected="selected"{/if}>
						{if $item->level==2}|--{/if}
						{$item->name}</option>
					{/foreach}
				</optgroup>
				<optgroup label="Без ссылки">
					<option value="nolink_1" {if $vars.moduleName=='nolink' and $vars.moduleId==1} selected="selected"{/if}>Идеи</option>
					<option value="nolink_2" {if $vars.moduleName=='nolink' and $vars.moduleId==2} selected="selected"{/if}>Решения</option>
				</optgroup>
			</select>
		</td>
	</tr>
	{if $menu}
		<tr>
			<td>Родительский раздел</td>
			<td>
				<select name="pid" id="pid">
					<option value="0">[выберите если надо]</option>
					{foreach from=$menu item=item} 
						<option value="{$item->id}" {if $vars.pid==$item->id} selected="selected"{/if}>{$item->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	{/if}
	<tr>
		<td class="table_h"><label for="isHidden">Не показывать</label></td>
		<td><input name="isHidden" type="checkbox" id="isHidden" {if $vars.isHidden} checked{/if}></td>
	</tr>
	</table>
	<br>
  	<input name="menuId" type="hidden" id="menuId" value="{$vars.menuId}">
  	<input name="posi" type="hidden" id="posi" value="{$vars.posi}">
  	<input name="id" type="hidden" id="id" value="{$smarty.get.id}">
	<input name="action" type="hidden" id="action" value="{$smarty.get.action}">
    <input name="save" type="hidden" value="1">        
    <input type="submit" class="button" value="{if $smarty.get.action=="addCat"} Добавить {else} Сохранить {/if}">
</form>