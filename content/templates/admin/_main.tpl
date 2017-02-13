<!DOCTYPE HTML>
<html>
<head>
	<title>Администрирование::{$title}</title>
	<meta charset="utf-8">

	<!--script type="text/javascript" src="{$smarty.const.ADM_PATH}js/jquery-1.7.2.min.js"></script-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

	<script src="{$smarty.const.ADM_PATH}js/script.js" type="text/javascript"></script>
	<link href="{$smarty.const.ADM_PATH}css/admin.css" rel="stylesheet" type="text/css">

	<script src="{$smarty.const.ADM_PATH}js/dtree.js" type="text/javascript"></script>
	<link href="{$smarty.const.ADM_PATH}css/dtree.css" rel="stylesheet" type="text/css">
	<script src="{$smarty.const.ADM_PATH}js/calendar.js" type="text/javascript"></script>
	<link href="{$smarty.const.ADM_PATH}css/calendar-win2k-2.css" rel="stylesheet" type="text/css">

	{if isset($wysiwygScript)}{$wysiwygScript}{/if}

	<script type="text/javascript">
		$(document).ready(function(){
			$('#leftMenu div[class!=t], .listingTbl tr:gt(0)').
				mouseover(function(){
					highligthrow(this,1);
				}).
				mouseout(function(){
					highligthrow(this,0);
				});

			$('.edit').attr('title','Редактировать');
			$('.del').attr('title','Удалить').click(function(){
				return confirm('Удалить?');
			});
		});
	</script>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="4" border="0" height="100%">
<tr valign="top">
	<td style="padding:35px 0 0 10px;width:200px;">
		{if isset($smarty.session.uname)}
		<p align="center">Вы вошли как <b>{$smarty.session.uname}</b><br>
		<a href="{get_url('admin/logout')}">выйти</a>
		</p>
		{/if}

		<div id="leftMenu">
			{include file="./navi.tpl"}
			<br><br><br><br><br>
			<br><br><br><br><br>
			<div class="t" align="center">
				Разработка сайта<br>
				<a href="http://www.vencedor.ru/" target="_blank">Компания «Венседор»</a><br>
				<a href="http://www.vencedor.ru/" target="_blank">+7 (495) 728-3314</a><br>
				<a href="http://www.vencedor.ru/" target="_blank"><img src="http://www.vencedor.ru/img/logo.png"></a>
			</div>
		</div>
	</td>
	<td style="padding:50px 10px;">
	{if isset($errors)}
		<div class="errorsBl">
			<p style="color:red;">Проверье форму на наличие ошибок!</p>
		</div>
	{/if}

	{if isset($smarty.get.m) && $smarty.get.m == 1}
		<p class="msgBl">Added</p>
	{elseif isset($smarty.get.m) && $smarty.get.m == 2}
		<p class="msgBl">Saved</p>
	{elseif isset($smarty.get.m) && $smarty.get.m == 3}
		<p class="msgBl">Deleted</p>
	{elseif isset($smarty.get.m) && $smarty.get.m == 4}
		<p class="msgBl">Загружено</p>
	{/if}

	{block name="content"}{/block}

	</td>
</tr>
</table>

<script type="text/javascript">
function openTab(evt, cityName) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
</body>
</html>