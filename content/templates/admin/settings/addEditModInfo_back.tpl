<form action="" method="post" enctype="multipart/form-data">
<table class="contTbl">
	<tr valign="top">
		<td>
		
		{if $forms.metainfoForm}
			{$forms.metainfoForm}
			<br>
		{/if}
		
<table class="contTbl">
	<tr>
		<td width="150" class="table_h">Название</td>
		<td><input type="text" name="name" value="{$vars.name}" class="input" style="width:90%"></td>
	</tr>
	<tr>
		<td colspan="2">Описание<br>
		{*if $fck.descr}
			{$fck.descr}
		{else}
			<textarea name="descr" rows="8" id="descr" style="width:100%">{$vars.descr}</textarea></td>
		{/if*}
	</tr>
	</table>
	<br>
	<input type="hidden" name="save" value="1"> 
	<input type="hidden" name="id" value="{$smarty.get.id}">
	<input type="hidden" name="posi" value="{$vars.posi}">
	<input type="hidden" name="action" value="{$smarty.get.action}">
	<input type="submit" class="button" value="{if $smarty.get.action=="addCat"} Добавить {else} Сохранить {/if}">
  
</td>
{if $forms.imagesForm}
	<td width="250">
		{$forms.imagesForm}
		
		{*<!-- Ключевые слова для mbtm -->*}
		{$forms.categoriesForm}
	</td>
{/if}
</tr>
</table>
</form>


<br>
<br>

	<form action="" method="POST">
		<fieldset>
			<legend>Натройки сайта</legend>
			
			{$sett="{$smarty.get.submod}"}
			
			<div>
				pageTitle<br>
				<input name="settings[pageTitle]" value="{$settings[$sett]['pageTitle']}">
			</div>
			
			<div>
				page limit<br>
				<input name="settings[limit]" value="{$settings[$sett]['limit']}">
			</div>
		</fieldset>
		<br>
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="type" value="public_settings">
		<input type="submit" value=" сохранить " class="button">
	</form>		
			
			
			
			
			
{if $smarty.get.allsettings}
<div style="border-top:1px dashed gray;margin:10px 0 0 0;">
	<div style="margin:10px 0;">скрытые настройки для админ части</div>

	
	<form action="" method="POST">
		<fieldset>
			<legend>Картинки</legend>
				
			{$sett="{$smarty.get.submod}_adm"}
			
			<fieldset>
			<legend>Группа 1</legend>
			
				<div><label><input type="checkbox" name="settings[images][g1][isActive]" value="1"{if $settings[$sett]['images']['g1']['isActive']} checked{/if} onclick="$('#imgSettG1').toggle();"> включена</label></div>
				<table{if !$settings[$sett]['images']['g1']['isActive']} class="dN"{/if} id="imgSettG1">
					<tr valign="top">
						<td>
							<b>Настройки размеров</b><br>
							
							<div>
								saveDir<br>
								<input name="settings[images][g1][upload][saveDir]" value="{$settings[$sett]['images']['g1']['upload']['saveDir']}"> i/news/
							</div>
							<div>
								<br>
								thumbs size<br>
								для ф-ции 'resizeTo'=>array('tm_460x360','tm2_150x130')<br>
								<!--input name="settings[images][g1][upload][thumbs]" value="{$settings[$sett]['images']['g1']['upload']['thumbs']}"><br-->
								
<div id="thumbsAddFormSourse" class="dN">
	<table>
	<tr valign="top">
			<td>__INDEXNUM__.</td>
			<td><input name="settings[images][g1][upload][thumbs][__INDEX__][pref]" value="__VALUE__" disabled style="width:25px"> _</td>
			<td><input name="settings[images][g1][upload][thumbs][__INDEX__][w]" value="__VALUE__" disabled style="width:25px"> x</td>
			<td><input name="settings[images][g1][upload][thumbs][__INDEX__][h]" value="__VALUE__" disabled style="width:25px"></td>
			<td><label><input type="checkbox" name="settings[images][g1][upload][thumbs][__INDEX__][del]" value="1" disabled> удалить</label></td>
		</tr>
	</table>
</div>
<div id="thumbsAddFormBl"></div>
<div><span onclick="addBlock('thumbsAddFormSourse','thumbsAddFormBl');" style="cursor:pointer;font-size:18px;">+ Добавить</span></div>
<script type="text/javascript">
	function addBlock(contentSourse,contentDest,data){
		var content=$('#'+contentSourse).html();
		content=content.replace(/disabled/g,'');
		
		content=content.replace(/__INDEX__/g,blockIndex);
		content=content.replace(/__INDEXNUM__/g,blockIndex+1);
		if(data){
			for(var i=0;i<data.length;i++){
				content=content.replace('__VALUE__',data[i]);
			}
		}else{
			content=content.replace(/__VALUE__/g,'');
		}
		
		$('#'+contentDest).append(content);
		blockIndex++;
		
	}
</script>
<script type="text/javascript">
	var blockIndex=0;
	{foreach $settings[$sett]['images']['g1']['upload']['thumbs'] as $k=>$v}
		addBlock('thumbsAddFormSourse','thumbsAddFormBl',['{$v.pref}','{$v.w}','{$v.h}']);
	{/foreach}
	/*addBlock('thumbsAddFormSourse','thumbsAddFormBl');*/
</script>

							</div>
						</td>
						<td width="50%">
							<b>Настройки imagesForm</b><br>
	
							<div>
								name<br>
								<input name="settings[images][g1][imageForm][name]" value="{$settings[$sett]['images']['g1']['imageForm']['name']}">
							</div>
							<div>
								descr<br>
								<input name="settings[images][g1][imageForm][descr]" value="{$settings[$sett]['images']['g1']['imageForm']['descr']}"> размер картинки не более 200х200px
							</div>
							<div>
								label<br>
								<input name="settings[images][g1][imageForm][label]" value="{$settings[$sett]['images']['g1']['imageForm']['label']}"> Логотип
							</div>
							
							<div><label><input type="checkbox" name="settings[images][g1][imageForm][multy]" value="1"{if $settings[$sett]['images']['g1']['imageForm']['multy']} checked{/if}> multy</label></div>
							<div><label><input type="checkbox" name="settings[images][g1][imageForm][showRadio]" value="1"{if $settings[$sett]['images']['g1']['imageForm']['showRadio']} checked{/if}> showRadio</label></div>
							<div><label><input type="checkbox" name="settings[images][g1][imageForm][showDescr]" value="1"{if $settings[$sett]['images']['g1']['imageForm']['showDescr']} checked{/if}> showDescr</label></div>
							<div><label><input type="checkbox" name="settings[images][g1][imageForm][showDel]" value="1"{if $settings[$sett]['images']['g1']['imageForm']['showDel']} checked{/if} disabled> showDel</label> --> showDel=1 всегда</div>
						</td>
					</tr>
				</table>
			</fieldset>
			
			
		</fieldset>
		<br>
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="type" value="admin_settings">
		<input type="submit" value=" сохранить " class="button">
	</form>
	
</div>
{/if}