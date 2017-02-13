{extends "../_main.tpl"}
{block name="content"}
<table class="contTbl">
<tr>
	<th class="dN">ID</th>
	<th></th>
	<td>&nbsp;</td>
	<td class="dN">&nbsp;</td>
</tr>

{foreach $result as $v}
{$id=$v->id}
<tr onmouseover="highligthrow(this,1)" onmouseout="highligthrow(this,0)" {if $v->isHidden}style="background:#cfcfcf;"{/if}>
	<td class="dN">{$v->id}</td>
	<td>
		<a href="?mod={$smarty.get.mod}&action=edit&id={$v->id}">{$v->title}</a>
	</td>
	<td>
		<a href="?mod={$smarty.get.mod}&action=edit&id={$v->id}"><img src="/i/icons/edit.png" alt="Редактировать" title="Редактировать"></a>
		<!--&nbsp;&nbsp;-->
		<a class="dN" href="?mod={$smarty.get.mod}&action=del&id={$v->id}" onclick="return confirm('Удалить?')"><img src="/i/icons/del.png" alt="Удалить" title="Удалить"></a>
		<!--&nbsp;&nbsp;-->
	</td>
	<td class="dN">
		{if $v->isHidden}скрыт {/if}
	</td>
</tr>
{/foreach}
</table>

{if $pagesList}<br><br>{$pagesList}{/if}
<p class="dN"><a href="?mod={$smarty.get.mod}&action=add">Добавить</a></p>
{/block}
