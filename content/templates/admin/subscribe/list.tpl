{if $pagesList}{$pagesList}<br>{/if}

<script type="text/javascript">
	function checkSubmit(){
		if(document.getElementById('del').checked)
			return confirm('�������?');
		
		return true;
	}
</script>
<form action="?mod={$smarty.get.mod}&type={$smarty.get.type}&action=groupaction" method="POST" onsubmit="return checkSubmit()">
<table class="contTbl">
<tr>
	<td class="table_h">&nbsp;</td>
	<!--td class="table_h">ID</td-->
	<td class="table_h{if $smarty.get.orderby==name} {$smarty.get.ascdesc}{/if}">
		<a href="{$path}?mod={$smarty.get.mod}&type={$smarty.get.type}&orderby=name&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">��������</a>
	</td>
	<td class="table_h{if $smarty.get.orderby==dateAdd} {$smarty.get.ascdesc}{/if}">
		<a href="{$path}?mod={$smarty.get.mod}&type={$smarty.get.type}&orderby=dateAdd&ascdesc={if $smarty.get.ascdesc=='asc'}desc{else}asc{/if}">����</a>
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
			<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&id={$item->id}" style="display:block;">��������� ID{$item->id}. {$item->message|truncate:200} {$item->name}</a>
		{elseif $item->type==2}
			<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=edit&type={$smarty.get.type}&id={$item->id}" style="display:block;">������ ID{$item->id}. {$item->message|truncate:200} {$item->name}</a>
		{else}	
			<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=edit&type={$smarty.get.type}&id={$item->id}" style="display:block;">������ ID{$item->id}. {$item->message|truncate:200} {$item->name}</a>
		{/if}
		{*<!--a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=edit&id={$item->id}" style="display:block;">{$item->message|truncate:200}{$item->name}</a-->*}
	</td>
	<td>{$item->dateAdd}</td>
	<td>
		{*<!--a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=edit&id={$item->id}"><img src="/admin/i/icons/edit.png" alt="�������������" title="�������������"></a>&nbsp;&nbsp;-->*}
		<a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=del&id={$item->id}" onclick="return confirm('�������?')"><img src="/admin/i/icons/del.png" alt="�������" title="�������"></a>
	</td>
	<td>
		{if !$item->isRead}�� ���������{/if}
		{*if $item->isHidden}�����{/if*}
	</td>
</tr>
{foreachelse}
	<tr><td colspan="6" align="center"><b>������ ����</b></td></tr>
{/foreach}
</table>
<br><b>� �����������:</b> 
<label><input type="radio" name="action" value="del" id="del"> �������</label>

<!--input type="hidden" name="action" value="subscribe"-->
<input type="hidden" name="save" value="1">
<input type="submit" value=" ��������� " class="button">
</form>

<br>
{$pagesList}
{*<!--p><a href="?mod={$smarty.get.mod}&submod={$smarty.get.submod}&type={$smarty.get.type}&action=add">��������</a></p-->*}