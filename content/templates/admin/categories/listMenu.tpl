<form action="" method="post" enctype="multipart/form-data">

	<p><a href="javascript: d.openAll();">Раскрыть</a> | <a href="javascript: d.closeAll();">Скрыть</a></p>
	<input type="hidden" name="categories" value="0">
	<div id="catTree"></div>
	<script type="text/javascript">
		d=new dTree('d');
		d.mod='{$smarty.get.mod}';
		d.submod='{$smarty.get.submod}';
		d.add(0,-1,'<b>Список</b>');
		{foreach from=$l item=item}
			d.add({$item->id},{$item->pid},'{$item->name}',{$item->posi});
		{/foreach}
		document.getElementById("catTree").innerHTML=d;
	</script>
	<br>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="action" value="savePosi">
	<input type="submit" value=" сохранить " class="button">
	<input onclick="location.href='?mod={$smarty.get.mod}&menuId={$smarty.get.menuId}&action=add'" type="button" class="button" value=" добавить ">
</form>