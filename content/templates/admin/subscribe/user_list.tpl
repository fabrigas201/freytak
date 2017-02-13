<div>Всего подписок: {$count}</div><br>

{if $pagesList}{$pagesList}<br>{/if}

<table class="contTbl">
<tr>
	<!--td class="table_h">ID</td-->
	<td class="table_h{if $smarty.get.orderby==name} {$smarty.get.ascdesc}{/if}">
		<a href="{$path}?mod={$smarty.get.mod}&type={$smarty.get.type}&orderby=name&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}"></a>email
	</td>
	<td>
		дата
	</td>
	<!--td class="table_h" width="">&nbsp;</td>
	<td class="table_h">&nbsp;</td-->
</tr>

{foreach from=$l item=item key=key}
<tr onmouseover="highligthrow(this,1)" onmouseout="highligthrow(this,0)">
	<!--td>{$item->id}</td-->
	<td>
		{*<!--a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=edit&id={$item->id}" style="display:block;">{$item->message|truncate:200}{$item->name}</a-->*}
		{$item->email}
	</td>
	<td>{$item->dateAdd}</td>
	{*<!--td>
		<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=edit&id={$item->id}"><img src="/admin/i/icons/edit.png" alt="Редактировать" title="Редактировать"></a>&nbsp;&nbsp;
		<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=del&id={$item->id}" onclick="return confirm('Удалить?')"><img src="/admin/i/icons/del.png" alt="Удалить" title="Удалить"></a>
	</td-->*}
</tr>
{foreachelse}
	<tr><td colspan="6" align="center"><b>Список пуст</b></td></tr>
{/foreach}
</table>

<br>
{$pagesList}
{*<!--p><a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=add">Добавить</a></p-->*}