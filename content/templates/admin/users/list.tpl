{extends "../_main.tpl"}
{block name="content"}
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#D2D5D6">
	<tr valign="top">
		<td width="10">&nbsp;</td>
		<th>
			<a href="{get_url('admin?mod=')}{$smarty.get.mod}&orderby=uname&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}"></a>login
		</th>
		<!--th>должность</th>
		<th width="10">email</th>
		<th width="10">
			<a href="{$path}?mod={$smarty.get.mod}&orderby=ip&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">ip</a>
		</th>
		<th width="10">
			<a href="{$path}?mod={$smarty.get.mod}&orderby=last_login&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">last_login</a>
		</th>
		<th width="10">
			<a href="{$path}?mod={$smarty.get.mod}&orderby=user_regdate&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">regdate</a>
		</th>
		<th width="10">
			<a href="{$path}?mod={$smarty.get.mod}&orderby=status&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">status</a>
		</th-->
		<td>&nbsp;</td>
	</tr>

{foreach from=$l item=v}
	{assign var=id value=$v->uid}
	<tr valign="top">
	<td width="10">{counter}</td>
	<td><a href="{get_url('admin?mod=')}{$smarty.get.mod}&action=edit&id={$v->uid}">{$v->uname}</a></td>
	<!--td>{$v->job}</td>
	<td>{$v->email}</td>
	<td>{$v->ip}</td>
	<td>{$v->last_login}</td>
	<td>{$v->user_regdate}</td>
	<td>
		{if $v->status==1}<b style="color:green">активирован</b>
		{elseif $v->status==3}<b style="color:red">заблокирован</b>
		{else}<b style="color:red">не активирован</b>{/if}
	</td-->
	<td>
		<a href="{get_url('admin?mod=')}{$smarty.get.mod}&action=edit&id={$v->uid}"><img src="/i/icons/edit.png" alt=""></a> 
		<a href="{get_url('admin?mod=')}{$smarty.get.mod}&action=del&id={$v->uid}" onclick="return confirm('Удалить?')"><img src="/i/icons/del.png" alt=""></a>
	</td>
{foreachelse}
<tr>
<td>
	{if $smarty.session.uid}
		<b>Список пуст</b>
	{else}
		<b>Список доступен после авторизации</b>
	{/if}
</td>
</tr>
{/foreach}
</table>

<p><a href="?mod={$smarty.get.mod}&action=add">Добавить</a></p>
{/block}