<form action="" method="post" enctype="multipart/form-data">

  <table width="100%"  border="0" cellpadding="4" cellspacing="0">
	<tr valign="top">
		<td>
			<p style="margin-top:0;"><a href="javascript: d.openAll();">��������</a> | <a href="javascript: d.closeAll();">������</a></p>
			<input type="hidden" name="categories" value="0">
			<div id="catTree"></div>
			<script type="text/javascript">
				<!--;
				d=new dTree('d');
				d.mod='{$smarty.get.mod}';
				d.add(0,-1,'<b>������</b>');
				{foreach from=$l item=item}
					d.add({$item->id},{$item->pid},'{$item->name}',{$item->posi});
				{/foreach}
				document.getElementById("catTree").innerHTML=d;
				//-->
			</script>
		</td>
    </tr>
    <tr>
      <td>
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="action" value="savePosi">
        <input type="submit" value=" ��������� " class="button">
        <input onclick="location.href='?mod={$smarty.get.mod}&action=add'" type="button" class="button" value=" �������� ">
      </td>
    </tr>
  </table>
</form>


{*<!--
<form action="" method="post" name="pr_form" id="totaledform">
<table border="0" cellspacing="1" cellpadding="4" bgcolor="#D2D5D6">
<tr>
	<td class="table_h">��������</td>
	<td class="table_h"></td>
</tr>

{foreach from=$articles item=item}
<tr onmouseover="highligthrow(this,1)" onmouseout="highligthrow(this,0)">
	<td>{$item.name}</td>
	<td>
		<a href="?mod={$smarty.get.mod}&action=edit&id={$item.id}"><img src="/admin/i/icons/edit.png" alt="�������������" title="�������������"></a>&nbsp;&nbsp;
		<a href="?mod={$smarty.get.mod}&action=del&id={$item.id}" onclick="return confirm('�������?')"><img src="/admin/i/icons/del.png" alt="�������" title="�������"></a>
	</td>
</tr>
{foreachelse}
	<tr><td colspan="4"><b>������ ����</b></td></tr>
{/foreach}
</table>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="id" value="{$smarty.get.id}">
	<input type="hidden" name="action" value="{$smarty.get.action}">
</form>
<p><a href="?mod={$smarty.get.mod}&action=add">��������</a></p>
-->*}