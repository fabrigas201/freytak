{if $pagesList}{$pagesList}<br>{/if}

<script type="text/javascript">
	function checkSubmit(){
		if(document.getElementById('del').checked)
			return confirm('Удалить?');
		
		return true;
	}
</script>
<form action="?mod={$smarty.get.mod}&type={$smarty.get.type}&action=groupaction" method="POST" onsubmit="return checkSubmit()">
<table class="contTbl">
<tr>
	<td class="table_h">&nbsp;</td>
	<!--td class="table_h">ID</td-->
	<td class="table_h{if $smarty.get.orderby==name} {$smarty.get.ascdesc}{/if}">
		<a href="{$path}?mod={$smarty.get.mod}&type={$smarty.get.type}&orderby=name&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">Название</a>
	</td>
	<td class="table_h{if $smarty.get.orderby==dateAdd} {$smarty.get.ascdesc}{/if}">
		<a href="{$path}?mod={$smarty.get.mod}&type={$smarty.get.type}&orderby=dateAdd&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">Дата</a>
	</td>
	<td class="table_h" width="">&nbsp;</td>
	<td class="table_h">&nbsp;</td>
</tr>

{foreach from=$l item=item key=key}
{*<!--tr onmouseover="highligthrow(this,1)" onmouseout="highligthrow(this,0)" {if $item->isHidden}style="background:#cfcfcf;"{/if}-->*}
<tr onmouseover="highligthrow(this,1)" onmouseout="highligthrow(this,0)" {if !$item->isRead}style="background:#edf1f5;"{/if}>
	<td><input type="checkbox" name="ids[]" value="{$item->id}"></td>
	<!--td>{$item->id}</td-->
	<td>
		{if $item->type==1}
			<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&id={$item->id}" style="display:block;">Сообщение ID{$item->id}. {$item->message|truncate:200} {$item->name}</a>
		{elseif $item->type==2}
			<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=edit&type={$smarty.get.type}&id={$item->id}" style="display:block;">Вопрос ID{$item->id}. {$item->message|truncate:200} {$item->name}</a>
		{else}	
			<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=edit&type={$smarty.get.type}&id={$item->id}" style="display:block;">Вопрос ID{$item->id}. {$item->message|truncate:200} {$item->name}</a>
		{/if}
		{*<!--a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=edit&id={$item->id}" style="display:block;">{$item->message|truncate:200}{$item->name}</a-->*}
	</td>
	<td>{$item->dateAdd}</td>
	<td>
		{*<!--a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=edit&id={$item->id}"><img src="/admin/i/icons/edit.png" alt="Редактировать" title="Редактировать"></a>&nbsp;&nbsp;-->*}
		<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=del&id={$item->id}" onclick="return confirm('Удалить?')"><img src="/admin/i/icons/del.png" alt="Удалить" title="Удалить"></a>
	</td>
	<td>
		{if !$item->isRead}не прочитано{/if}
		{*if $item->isHidden}скрыт{/if*}
	</td>
</tr>
{foreachelse}
	<tr><td colspan="6" align="center"><b>Список пуст</b></td></tr>
{/foreach}
</table>
<br><b>С отмеченными:</b> 
<label><input type="radio" name="action" value="del" id="del"> удалить</label>

<!--input type="hidden" name="action" value="subscribe"-->
<input type="hidden" name="save" value="1">
<input type="submit" value=" сохранить " class="button">
</form>

<br>
{$pagesList}
{*<!--p><a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=add">Добавить</a></p-->*}