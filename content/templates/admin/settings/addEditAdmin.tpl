<!-- скрытые настройки -->

<fieldset style="width:400px">
	<legend>Почта</legend>
	<form action="" method="POST">
	<table>
	<tr valign="top"><td width="50%">
	<fieldset>
		<legend><small>Обратная связь</small></legend>
		<small>Основной email</small>
		<input name="fb[mail]" value="{$vars.mail.fb.mail}" class="input">
		<small>Копия</small>
		<input name="fb[mailcc]" value="{$vars.mail.fb.mailcc}" class="input">
		<small>Скрытая копия</small>
		<input name="fb[mailbcc]" value="{$vars.mail.fb.mailbcc}" class="input">
		<input type="submit" value="Сохранить" class="button">
	</fieldset>
	</td>
	<td>
	<fieldset>
		<legend><small>Магазин</small></legend>
		<small>Основной email</small>
		<input name="shop[mail]" value="{$vars.mail.shop.mail}" class="input">
		<small>Копия</small>
		<input name="shop[mailcc]" value="{$vars.mail.shop.mailcc}" class="input">
		<small>Скрытая копия</small>
		<input name="shop[mailbcc]" value="{$vars.mail.shop.mailbcc}" class="input">
		<input type="submit" value="Сохранить" class="button">
	</fieldset>
	</td></tr></table>
	<input type="hidden" name="save" value="1">
	<input type="hidden" name="action" value="mails">
	</form>
</fieldset>

<fieldset style="width:400px">
	<legend>Информирование</legend>
	<form action="" method="POST">
		<input type="checkbox" name="mailpdf" {if $vars.pdf.mailpdf}checked="checked"{/if} id="mailpdf" /> <label for="mailpdf">отправить pdf клиента на email админа</label>
		<input type="submit" value="Сохранить" class="button">
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="action" value="pdfs">
	</form>
</fieldset>


<div style="width:400px;">
	<form action="" method="post">
		{$forms.categoriesForm}
	
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="action" value="blockArticles">
		<input type="submit" value="Сохранить" class="button">
	</form>
</div>