{extends "../_main.tpl"}
{block name="content"}
{if $pagesList}
	{$pagesList}<br>
{/if}

<form action="" method="post">
<table class="contTbl listingTbl">
<tr>
	<th class="{if $smarty.get.orderby==name}{$smarty.get.ascdesc}{/if}"><a href="{$path}?mod={$smarty.get.mod}&orderby=name&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">Название</a></th>
	<th class="{if $smarty.get.orderby==dateAdd}{$smarty.get.ascdesc}{/if}"><a href="{$path}?mod={$smarty.get.mod}&orderby=dateAdd&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">Дата</a></th>
	<th width="55"></th>
</tr>

{foreach $l as $v}
<tr>
	<td><a href="?mod={$smarty.get.mod}&action=edit&id={$v->id}">{$v->title}</a></td>
	<td>{$v->dateAdd|date_format:"%d.%m.%Y"}</td>
	<td>
		<a href="?mod={$smarty.get.mod}&action=del&id={$v->id}" class="del"></a>
		<a href="?mod={$smarty.get.mod}&action=edit&id={$v->id}" class="edit"></a>
	</td>
</tr>
{foreachelse}
	<tr><td colspan="2"><b>Список пуст</b></td></tr>
{/foreach}
</table>
	
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="id" value="{$smarty.get.id}">
	<input type="hidden" name="action" value="{$smarty.get.action}">
	{*	<input type="button" class="input_b" value="Сохранить" OnClick="subform('fform',this)">*}
</form>

{if $pagesList}
	<br>{$pagesList}
{/if}
<div class="addItem"><a href="?mod={$smarty.get.mod}&action=add">Добавить</a></div>
{/block}