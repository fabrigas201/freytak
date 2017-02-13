{extends "../_main.tpl"}
{block name="content"}
<div style="width:300px">

<form action="" method="post" enctype="multipart/form-data">
<table width="400" class="contTbl">
	<tr valign="top"><td>
	<fieldset><legend>Пользователь</legend>
		<div>
			логин
			{if $smarty.get.action=="add"}
				<br>
				<input type="text" name="name" value="{$vars.name}" style="width:100%">
			{else}
				- <b>{$vars.name}</b>
			{/if}
		</div>
		
		<div>Email<br>
			<input name="email" type="text" id="email" value="{$vars.email}" style="width:100%">
		</div>
		
		<div>
			группа<br>
			<select name="ugroup">
			{foreach from=$vars.ugroups item=item key=key}
				<option value="{$key}"{if isset($vars.ugroup) && $vars.ugroup==$key} selected{/if}>{$item}</option>
			{/foreach}
			</select>
		</div>
		
		
		
		{*<!--div>
			email<br>
			<input type="text" name="email" value="{$vars.email}" style="width:100%">
		<div>
		
		<div>
			регион<br>
			<select name="region_id">
				<option value="0">выберите регион</option>
				{html_options options=$regions selected=$vars.region_id}
			</select>
		</div>
		
		{if $smarty.get.action=="edit"}
			<div style="margin:5px 0 0 0">
				<input type="checkbox" name="status" value="3"{if $vars.status==3} checked{/if}> блокировать
			</div>
		{/if}
		-->*}
	</fieldset>
	

	

{*<!--fieldset><legend>доп инфа</legend>
	<div>ФИО<br>
		<input name="fio" type="text" id="fio" value="{$vars.fio}" style="width:100%">
	</div>
	<div>
		icq<br>
		<input name="icq" type="text" id="icq" value="{$vars.icq}" style="width:100%">
	</div>
	<div>
		тел. рабочий<br>
		<input name="phone" type="text" id="phone" value="{$vars.phone}" style="width:100%">
	</div>
	<div>
		тел. мобильный<br>
		<input name="phone_mobile" type="text" id="phone_mobile" value="{$vars.phone_mobile}" style="width:100%">
	</div>
</fieldset-->*}

	<fieldset><legend>Пароль</legend>
		<div>	
			пароль<br>
			<input type="text" name="pass" value="{$vars.pass}" style="width:100%">
		</div>
		<div>
			пароль еще раз<br>
			<input type="text" name="repass" value="{$vars.repass}" style="width:100%">
		</div>
	</fieldset>
	<br>
	<input name="save" type="hidden" value="1">        
	<input type="submit" class="button" value="{$save}">

</td>{*<!--td width="200">
	{$forms.imagesForm}
</td-->*}</tr>
</table>
</form>
<br>

</div>
{/block}