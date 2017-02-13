
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#D2D5D6">
	<tr valign="top">
		<td width="10">&nbsp;</td>
		<th>
			<a href="{$path}?mod={$smarty.get.mod}&orderby=uname&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}"></a>Опрос
		</th>
		<th>На главной</th>
		<td>&nbsp;</td>
	</tr>

{foreach from=$l item=v}
	{assign var=id value=$v->uid}
	<tr valign="top">
	<td width="10">{counter}</td>
	<td><a href="{$path}?mod={$smarty.get.mod}&submod=qitem&action=list&qid={$v->qgroup_id}">{$v->qgroup_name}</a></td>
	<td>
		{if $v->qgroup_main}Да{else}{/if}
	</td>
	<td>
		<a href="{$path}?mod={$smarty.get.mod}&submod=qitem&action=list&qid={$v->qgroup_id}"><img src="/i/icons/add.png" alt=""></a> 
		<a href="{$path}?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=edit&id={$v->qgroup_id}"><img src="/i/icons/edit.png" alt=""></a> 
		<a href="{$path}?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=del&id={$v->qgroup_id}" onclick="return confirm('Удалить?')"><img src="/i/icons/del.png" alt=""></a>
	</td>
{foreachelse}
<tr>
<td colspan=3>
		<b>Список пуст</b>
</td>
</tr>
{/foreach}
</table>

<p><a href="?mod={$smarty.get.mod}&action=add">Добавить</a></p>