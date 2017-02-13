<!DOCTYPE HTML>
<html>
<head>
	<title>Администрирование::{$title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">

	<!--script type="text/javascript" src="{$smarty.const.ADM_PATH}js/jquery-1.7.2.min.js"></script-->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

	<script src="{$smarty.const.ADM_PATH}js/script.js" type="text/javascript"></script>
	<link href="{$smarty.const.ADM_PATH}css/admin.css" rel="stylesheet" type="text/css">

	<script src="{$smarty.const.ADM_PATH}js/dtree.js" type="text/javascript"></script>
	<link href="{$smarty.const.ADM_PATH}css/dtree.css" rel="stylesheet" type="text/css">
	<script src="{$smarty.const.ADM_PATH}js/calendar.js" type="text/javascript"></script>
	<link href="{$smarty.const.ADM_PATH}css/calendar-win2k-2.css" rel="stylesheet" type="text/css">

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


			/*onmouseover="highligthrow(this,1)" onmouseout="highligthrow(this,0)"*/
		});
	</script>
</head>
<body>
<form action="{get_url('admin')}" method="POST">
<table class="contTbl listingTbl" align="center" width="400" cellspacing="0" cellpadding="4" border="0" height="100%">
<tr>
<td> Логин </td>
<td> <input type="text" name="login" /> </td>
</tr>
<tr>
<td> Пароль </td>
<td> <input type="password" name="password" /> </td>
</tr>
<tr>
<td colspan="2"> <input type="submit" name="submit" value="Войти" /></td>
</tr>
</table>
</form>
</body>
</html>