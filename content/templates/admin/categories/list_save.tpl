<form action="" method="post" enctype="multipart/form-data">
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="5">
			<p><a href="javascript: d.openAll();">Раскрыть</a> | <a href="javascript: d.closeAll();">Скрыть</a></p>
			<!--p><a href="javascript: d.openAll();">Open</a> | <a href="javascript: d.closeAll();">Close</a></p-->
			<input type="hidden" name="categories" value="0">
			<div id="catTree"></div>
			<script type="text/javascript">
				<!--;
				d=new dTree('d');
				d.mod='{$smarty.get.mod}';
				d.submod='{$smarty.get.submod}';
				{if $smarty.get.submod==categories2}
					d.addChildBt=false;
				{/if}
				d.add(0,-1,'<b>Список</b>');
				/*d.add(0,-1,'<b>Root</b>');*/
				{foreach from=$l item=item}
					d.add({$item->id},{$item->pid},'{$item->name}',{$item->posi});
				{/foreach}
				document.getElementById('catTree').innerHTML=d;
				//-->
			</script>
		</td>
	</tr>
	</table>
	
	<br>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="action" value="savePosi">
	<input type="submit" value=" сохранить " class="button">
	<input onclick="location.href='?mod={$smarty.get.mod}&submod={$smarty.get.submod}&action=add'" type="button" class="button" value=" добавить ">
</form>