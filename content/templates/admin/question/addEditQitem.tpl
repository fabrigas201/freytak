<div style="width:300px">

<form action="" method="post" enctype="multipart/form-data">
<table width="400" class="contTbl">
	<tr valign="top"><td>
<fieldset><legend>Ответ на опрос</legend>
	<div>
		Название
			<br>
			<input type="text" name="name" value="{$vars.name}" style="width:100%">
	</div>
</fieldset>

	<input type="submit" class="button" value="{if $smarty.get.action=="add"} Добавить {else} Сохранить {/if}">
	<input name="id" type="hidden" id="id" value="{$smarty.get.id}">
	<input name="qid" type="hidden" id="id" value="{$smarty.get.qid}">
	<input name="action" type="hidden" id="action" value="{$smarty.get.action}">
	<input name="submod" type="hidden" id="action" value="{$smarty.get.submod}">
	<input name="save" type="hidden" value="1">        

</td>{*<!--td width="200">
	{$forms.imagesForm}
</td-->*}</tr>
</table>
</form>
<br>


</div>