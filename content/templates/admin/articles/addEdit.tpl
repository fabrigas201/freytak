<form action="" method="POST" enctype="multipart/form-data">
<table class="contTbl">
<tr valign="top">
	<td>
	{if $smarty.get.action==edit}
		<div align="right" style="margin:0 0 10px 0;"><a href="/?mod={$smarty.get.mod}&id={$smarty.get.id}" target="_blank">���������� �� �����</a> <small style="color:gray">(��������� � ����� ����)</small></div>
	{/if}
	{$forms.metainfoForm}

	<table class="contTbl">
	<tr>
		<td width="150" nowrap>��������</td>
		<td><input type="text" name="name" value="{$vars.name|escape:'html'}" class="input" style="width:90%"></td>
	</tr>
	
	<td>������������ ���������</td>
		<td>
		<select name="pid" id="pid" style="width:90%">
			<option value="0">[Root<!--�����-->]</option>
			{foreach from=$articles item=item}
				<option value="{$item->id}" {if $vars.pid==$item->id} selected="selected"{/if}>{$item->name}</option>
			{/foreach}
		</select>
	</td>
	<!--tr>
		<td nowrap><label for="isIndex">�� �������</label></td>
		<td><input name="isIndex" id="isIndex" type="checkbox" {if $vars.isIndex} checked{/if}></td>
	</tr-->
	<tr>
		<td><label for="isHidden">�� ����������</label></td>
		<td><input name="isHidden" type="checkbox" id="isHidden" {if $vars.isHidden} checked{/if}></td>
	</tr>
	<tr>
		<td><label for="isHidden">� sitemap</label></td>
		<td><input name="in_sitemap" type="checkbox" id="in_sitemap" {if $vars.in_sitemap} checked{/if}></td>
	</tr>
	<tr>
		<td colspan="2">
			<div>�����</div>
			{$fck.descr}

			<br>
			<div>������</div>
			{$fck.descrfull}
			{*$fck.descrfullEn*}
		</td>
	</tr>

	{*<!--tr>
	<td colspan="2">
		<div>������ (����)</div>
		{$fck.descrfullEn}
	</td>
	</tr-->*}
	</table>

	<br>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="goto" value="0" id="goto">
	<input type="hidden" name="id" value="{$smarty.get.id}">
	<input type="hidden" name="posi" value="{$vars.posi}">
	<input type="hidden" name="action" value="{$smarty.get.action}">

	<input type="submit" value="{if $smarty.get.id}���������{else}��������{/if} � ���������� ��������������" class="button">
	<br>���<br>
	<input type="submit" value="{if $smarty.get.id}���������{else}��������{/if} � ��������� � ������" class="button" onclick="$('#goto').val(1);">
	</td>
	{*<td>
		{$forms.imagesForm2}

		{if $forms.imagesForm3}
			���
			{$forms.imagesForm3}
		{/if}
		{$forms.imagesForm}
	</td>*}
	</tr>
</table>

</form>