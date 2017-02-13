<form action="" method="post" enctype="multipart/form-data">
<table width="100%"  border="0" cellpadding="4" cellspacing="1">
	<tr>
		<td width="150">Название</td>
		<td><input name="name" type="text" id="name" value="{$vars.name}" style="width:100%"></td>
	</tr>
	<tr>
		<td width="150">Родительская категория</td>
		<td>
			<select name="pid" id="pid">
				<option value="0">[Новая]</option>
				{foreach from=$categories item=item} 
					<option value="{$item->id}" {if $vars.pid==$item->id} selected="selected"{/if}>{$item->name}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
      <td colspan="2">Описание<br>
      <textarea name="descr" rows="8" id="descr" style="width:100%">{$vars.descr}</textarea></td>
    </tr>
     {* <tr>
      <td width="150" class="firstrow">{$langadmin.autobr}</td>
      <td class="secondrow"><input name="autobr" type="checkbox" id="autobr" value="1"></td>
    </tr>
	*}
    <tr>
      <td colspan="2">
	  	<input name="posi" type="hidden" id="posi" value="{$vars.posi}">
	  	<input name="id" type="hidden" id="id" value="{$smarty.get.id}">
		<input name="action" type="hidden" id="action" value="{$smarty.get.action}">
        <input name="save" type="hidden" value="1">        
        <input type="submit" class="button" value="{if $smarty.get.action=="addCat"} Добавить {else} Сохранить {/if}">
      </td></tr>
  </table>
</form>