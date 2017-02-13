{if $l}
	<table border="0" cellspacing="2" cellpadding="0" width="">
		<tr valign="top" align="left">
			<td>login</td>
			<td>{$l->uname}</td>
		</tr>
		<tr valign="top" align="left">
			<td>email</td>
			<td>{$l->email}</td>
		</tr>
		<tr valign="top" align="left">
			<td>страна</td>
			<td>{$l->country}</td>
		</tr>
		<tr valign="top" align="left">
			<td>last IP</td>
			<td>{$l->ip}</td>
		</tr>
		<tr valign="top" align="left">
			<td>last_login</td>
			<td>{$l->last_login}</td>
		</tr>
		<tr valign="top" align="left">
			<td>regdate</td>
			<td>{$l->user_regdate}</td>
		</tr>
		<tr valign="top" align="left">
			<td>status</td>
			<td>{if $l->status==1}<b style="color:green">активирован</b>{else}<b style="color:red">не активирован</b>{/if}</td>
		</tr>
		
	</table>
{/if}
		
<p><a href="javascript:history.back()">Ќазад</a></p>