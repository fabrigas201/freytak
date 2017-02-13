
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#D2D5D6">
	<tr valign="top">
		<td width="10">&nbsp;</td>
		<th>
			<a href="{$path}?mod={$smarty.get.mod}&orderby=uname&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}"></a>Ответы на опрос {$q->qgroup_name}
		</th>
		<th>Голосов</th>
		<td>&nbsp;</td>
	</tr>

{foreach from=$l item=v}
	{assign var=id value=$v->uid}
	<tr valign="top">
	<td width="10">{counter}</td>
	<td><a href="{$path}?mod={$smarty.get.mod}&submod={$smarty.get.submod}&qid={$q->qgroup_id}&action=edit&id={$v->qitem_id}">{$v->qitem_text}</a></td>
	<td width="10">{$v->qitem_count}</td>
	<td>
		<a href="{$path}?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=edit&qid={$q->qgroup_id}&id={$v->qitem_id}"><img src="/i/icons/edit.png" alt=""></a> 
		<a href="{$path}?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=del&qid={$q->qgroup_id}&id={$v->qitem_id}" onclick="return confirm('Удалить?')"><img src="/i/icons/del.png" alt=""></a>
	</td>
{foreachelse}
<tr>
<td colspan=3>
		<b>Список пуст</b>
</td>
</tr>
{/foreach}
</table>

<p><a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&qid={$q->qgroup_id}&action=add">Добавить</a></p>