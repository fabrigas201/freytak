<form action="" method="post" enctype="multipart/form-data">
<table class="contTbl">
	<tr valign="top">
		<td>

		{$forms.metainfoForm}

<table class="contTbl">
	<tr>
		<td width="150">��������</td>
		<td><input type="text" name="name" value="{$vars.name}" class="input" style="width:90%"></td>
	</tr>
	<tr>
		<td width="150">�������������� ������</td>
		<td><input type="text" name="alt_link" value="{$vars.alt_link}" class="input" style="width:90%"></td>
	</tr>
	<tr>
		<td width="150"><label for="main">�� �������</label></td>
		<td><input type="checkbox" name="main" id="main" {if $vars.main}checked="checked"{/if} style="width:auto"  class="input"></td>
	</tr>
	<tr>
		<td width="150"><label for="main">�� ����������</label></td>
		<td><input type="checkbox" name="isHidden" id="isHidden" {if $vars.isHidden}checked="checked"{/if} style="width:auto"  class="input"></td>
	</tr>
	{if $smarty.get.submod!=categories2}
		<tr>
			<td>������������ ���������</td>
			<td>
				<select name="pid" id="pid">
					<option value="0">[Root<!--�����-->]</option>
					{foreach from=$categories item=item}
						<option value="{$item->id}" {if $vars.pid==$item->id} selected="selected"{/if}>{$item->name}</option>
					{/foreach}
				</select>
			</td>
		</tr>

	{/if}

	{if $fck.descr}
		<tr>
			<td colspan="2">��������<br>
				{$fck.descr}
			</td>
		</tr>
	{/if}


	{*<!--tr>
		<td>ID ��������� � 1C</td>
		<td><input name="custom_1" type="text" id="custom_1" value="{$vars.custom_1}" class="input" style="width:90%"></td>
	</tr-->*}
	{*<!--tr>
		<td>���������� �� �������</td>
		<td><input name="custom_2" type="text" id="custom_2" value="{$vars.custom_2}" class="input"></td>
	</tr-->*}
	{*<!--tr>
		<td>�������� ��������� � ������</td>
		<td><input name="custom_3" type="text" id="custom_3" value="{$vars.custom_3}" class="input"></td>
	</tr-->*}
		<tr>
			<td colspan="2">�������� ����� ��������<br>
				{$fck.descr_bottom}
			</td>
		</tr>
		<tr>
			<td colspan=2>
				��� ������ � ������ �����������<br>
				<textarea name='alt_links'>{$vars.alt_links}</textarea>
			</td>
		</tr>
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
{if $forms.imagesForm}
	<td width="250">
		{foreach $forms.imagesForm as $v}
			{$v}
		{/foreach}
	</td>
{/if}
</tr>
</table>
</form>