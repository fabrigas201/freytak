<!-- ������� ��������� -->

<fieldset style="width:400px">
	<legend>�����</legend>
	<form action="" method="POST">
	<table>
	<tr valign="top"><td width="50%">
	<fieldset>
		<legend><small>�������� �����</small></legend>
		<small>�������� email</small>
		<input name="fb[mail]" value="{$vars.mail.fb.mail}" class="input">
		<small>�����</small>
		<input name="fb[mailcc]" value="{$vars.mail.fb.mailcc}" class="input">
		<small>������� �����</small>
		<input name="fb[mailbcc]" value="{$vars.mail.fb.mailbcc}" class="input">
		<input type="submit" value="���������" class="button">
	</fieldset>
	</td>
	<td>
	<fieldset>
		<legend><small>�������</small></legend>
		<small>�������� email</small>
		<input name="shop[mail]" value="{$vars.mail.shop.mail}" class="input">
		<small>�����</small>
		<input name="shop[mailcc]" value="{$vars.mail.shop.mailcc}" class="input">
		<small>������� �����</small>
		<input name="shop[mailbcc]" value="{$vars.mail.shop.mailbcc}" class="input">
		<input type="submit" value="���������" class="button">
	</fieldset>
	</td></tr></table>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="action" value="mails">
	</form>
</fieldset>

<fieldset style="width:400px">
	<legend>��������������</legend>
	<form action="" method="POST">
		<input type="checkbox" name="mailpdf" {if $vars.pdf.mailpdf}checked="checked"{/if} id="mailpdf" /> <label for="mailpdf">��������� pdf ������� �� email ������</label>
		<input type="submit" value="���������" class="button">
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="action" value="pdfs">
	</form>
</fieldset>


<div style="width:400px;">
	<form action="" method="post">
		{$forms.categoriesForm}
	
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="action" value="blockArticles">
		<input type="submit" value="���������" class="button">
	</form>
</div>