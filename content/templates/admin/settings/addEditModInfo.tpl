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
		<td width="150" class="table_h">��������</td>
		<td><input type="text" name="name" value="{$vars.name}" class="input" style="width:90%"></td>
	</tr>
	<tr>
		<td colspan="2">��������<br>
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
	<input type="submit" class="button" value="{if $smarty.get.action=="addCat"} �������� {else} ��������� {/if}">
  
</td>
{if $forms.imageForm}
	<td width="250">
		{$forms.imageForm}
		
		{*<!-- �������� ����� ��� mbtm -->*}
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
			<legend>�������� �����</legend>
			
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
		<input type="submit" value=" ��������� " class="button">
	</form>		
			
			
			
			
			
{if $smarty.get.allsettings}
<script type="text/javascript">
function addBlock(){
	this.parentIndex=1;
	this.blockIndex=0;
	
	this.add=function(contentSourse,contentDest,data){
		var content=$('#'+contentSourse).html();
		content=content.replace(/disabled/g,'');
		
		content=content.replace(/__INDEX__/g,this.blockIndex);
		content=content.replace(/__PARENTiNDEX__/g,this.parentIndex);
		content=content.replace(/__INDEXNUM__/g,this.blockIndex+1);
		if(data){
			for(var i=0;i<data.length;i++){
				content=content.replace('__VALUE__',data[i]);
			}
		}else{
			content=content.replace(/__VALUE__/g,'');
		}
		
		$('#'+contentDest).append(content);
		this.blockIndex++;
	}
}
</script>


<div style="border-top:1px dashed gray;margin:10px 0 0 0;">
	<div style="margin:10px 0;">������� ��������� ��� ����� �����</div>

	
	<form action="" method="POST">
		<fieldset>
			<legend><b>��������</b></legend>
				
			{$sett="{$smarty.get.submod}_adm"}
			
			<!--//////////////////////////////////////\\-->
			<div id="thumbsAddFormSourse" class="dN">
				<table>
				<tr valign="top">
						<td>__INDEXNUM__.</td>
						<td><input name="settings[images][g__PARENTiNDEX__][upload][thumbs][__INDEX__][pref]" value="__VALUE__" disabled style="width:25px"> _</td>
						<td><input name="settings[images][g__PARENTiNDEX__][upload][thumbs][__INDEX__][w]" value="__VALUE__" disabled style="width:25px"> x</td>
						<td><input name="settings[images][g__PARENTiNDEX__][upload][thumbs][__INDEX__][h]" value="__VALUE__" disabled style="width:25px"></td>
						<td><label><input type="checkbox" name="settings[images][g__PARENTiNDEX__][upload][thumbs][__INDEX__][del]" value="1" disabled> �������</label></td>
					</tr>
				</table>
			</div>
			
			<div id="imagesGroupeSourse" class="dN">
			<fieldset>
			<legend>������ __INDEXNUM__</legend>
			
				<div class="fL"><label><input type="checkbox" name="settings[images][g__INDEXNUM__][isActive]" value="1" disabled checked onclick="$('#imgSettG__INDEXNUM__').toggle();"> ��������</label></div>
				<div class="fL"><label><input type="checkbox" name="settings[images][g__INDEXNUM__][del]" value="1" disabled> �������</label></div>
				
				<table id="imgSettG__INDEXNUM__" style="clear:both">
					<tr valign="top">
						<td>
							<b>��������� Upload</b><br>
							
							<div>
								saveDir<br>
								<input name="settings[images][g__INDEXNUM__][upload][saveDir]" value="" disabled> i/news/
							</div>
							
							<div style="margin:5px 0;">
								<label><input type="checkbox" name="settings[images][g__INDEXNUM__][upload][useDB]" value="1" disabled> useDB</label>
							</div>
							<div>
								��������� �������� (thumbs size)<br>
								<small>��� �-��� 'resizeTo'=>array('tm_460x360','tm2_150x130')</small><br>
								<!--input name="settings[images][g__INDEXNUM__][upload][thumbs]" value="{$settings[$sett]['images']['g1']['upload']['thumbs']}"><br-->
								
								<div id="thumbsAddFormBl__INDEXNUM__"></div>
								<div><span onclick="addBlock__INDEXNUM__.add('thumbsAddFormSourse','thumbsAddFormBl__INDEXNUM__');" style="cursor:pointer;font-size:18px;">+ ��������</span></div>
								<script type="text/javascript">
									addBlock__INDEXNUM__=new addBlock();
									addBlock__INDEXNUM__.parentIndex=__INDEXNUM__;
								</script>

							</div>
						</td>
						<td width="50%">
							<b>��������� imageForm</b><br>
	
							<div>
								name<br>
								<input name="settings[images][g__INDEXNUM__][form][name]" value="" disabled>
							</div>
							<div>
								descr<br>
								<input name="settings[images][g__INDEXNUM__][form][descr]" value="" disabled> ������ �������� �� ����� 200�200px
							</div>
							<div>
								label<br>
								<input name="settings[images][g__INDEXNUM__][form][label]" value="" disabled> �������
							</div>
							
							<div><label><input type="checkbox" name="settings[images][g__INDEXNUM__][form][multy]" value="1" disabled> multy</label></div>
							<div><label><input type="checkbox" name="settings[images][g__INDEXNUM__][form][showRadio]" value="1"disabled> showRadio</label></div>
							<div><label><input type="checkbox" name="settings[images][g__INDEXNUM__][form][showDescr]" value="1" disabled> showDescr</label></div>
						</td>
					</tr>
				</table>
			</fieldset>
			</div>
			
			<div id="filesGroupeSourse" class="dN">
			<fieldset>
			<legend>������ __INDEXNUM__</legend>
			
				<div class="fL"><label><input type="checkbox" name="settings[files][g__INDEXNUM__][isActive]" value="1" disabled checked onclick="$('#imgSettG__INDEXNUM__').toggle();"> ��������</label></div>
				<div class="fL"><label><input type="checkbox" name="settings[files][g__INDEXNUM__][del]" value="1" disabled> �������</label></div>
				
				<table id="imgSettG__INDEXNUM__" style="clear:both">
					<tr valign="top">
						<td>
							<b>��������� Upload</b><br>
							
							<div>
								saveDir<br>
								<input name="settings[files][g__INDEXNUM__][upload][saveDir]" value="" disabled> i/news/
							</div>
							<div>
								extensions<br>
								<input name="settings[files][g__INDEXNUM__][upload][extension]" value="" disabled> pdf, doc, xls
							</div>
							
							<div style="margin:5px 0;">
								<label><input type="checkbox" name="settings[files][g__INDEXNUM__][upload][useDB]" value="1" disabled> useDB</label>
							</div>
						</td>
						<td width="50%">
							<b>��������� imageForm</b><br>
	
							<div>
								name<br>
								<input name="settings[files][g__INDEXNUM__][form][name]" value="" disabled>
							</div>
							<div>
								descr<br>
								<input name="settings[files][g__INDEXNUM__][form][descr]" value="" disabled> ������ �������� �� ����� 200�200px
							</div>
							<div>
								label<br>
								<input name="settings[files][g__INDEXNUM__][form][label]" value="" disabled> �������
							</div>
							
							<div><label><input type="checkbox" name="settings[files][g__INDEXNUM__][form][multy]" value="1" disabled> multy</label></div>
							<div><label><input type="checkbox" name="settings[files][g__INDEXNUM__][form][showRadio]" value="1"disabled> showRadio</label></div>
							<div><label><input type="checkbox" name="settings[files][g__INDEXNUM__][form][showDescr]" value="1" disabled> showDescr</label></div>
						</td>
					</tr>
				</table>
			</fieldset>
			</div>
			<!--//////////////////////////////////////\\-->


			
{foreach $settings[$sett]['images'] as $group=>$v}	
<fieldset>
	<legend>������ {$v@iteration}</legend>
	
		<div class="fL"><label><input type="checkbox" name="settings[images][g{$v@iteration}][isActive]" value="1"{if $v['isActive']} checked{/if} onclick="$('#imgSettG{$v@iteration}').toggle();"> ��������</label></div>
		<div class="fL"><label><input type="checkbox" name="settings[images][g{$v@iteration}][del]" value="1"> �������</label></div>
		
		<table{if !$v['isActive']} class="dN"{/if} id="imgSettG{$v@iteration}" style="clear:both">
			<tr valign="top">
				<td>
					<b>��������� Upload</b><br>
					
					<div>
						saveDir<br>
						<input name="settings[images][g{$v@iteration}][upload][saveDir]" value="{$v['upload']['saveDir']}"> i/news/
					</div>
					<div style="margin:5px 0;">
						<label><input type="checkbox" name="settings[images][g{$v@iteration}][upload][useDB]" value="1"{if $v['upload']['useDB']} checked{/if}> useDB</label>
					</div>
					<div>
						��������� �������� (thumbs size)<br>
						<small>��� �-��� 'resizeTo'=>array('tm_460x360','tm2_150x130')</small><br>
						<!--input name="settings[images][g{$v@iteration}][upload][thumbs]" value="{$v['upload']['thumbs']}"><br-->
<div id="thumbsAddFormBl{$v@iteration}"></div>
<div><span onclick="addBlock{$v@iteration}.add('thumbsAddFormSourse','thumbsAddFormBl{$v@iteration}');" style="cursor:pointer;font-size:18px;">+ ��������</span></div>
<script type="text/javascript">
	addBlock{$v@iteration}=new addBlock();
	addBlock{$v@iteration}.parentIndex={$v@iteration};
	
	{foreach $v['upload']['thumbs'] as $k=>$v2}
		addBlock{$v@iteration}.add('thumbsAddFormSourse','thumbsAddFormBl{$v@iteration}',['{$v2.pref}','{$v2.w}','{$v2.h}']);
	{/foreach}
	/*addBlock('thumbsAddFormSourse','thumbsAddFormBl');*/
</script>

					</div>
				</td>
				<td width="50%">
					<b>��������� imageForm</b><br>

					<div>
						name<br>
						<input name="settings[images][g{$v@iteration}][form][name]" value="{$v['form']['name']}">
					</div>
					<div>
						descr<br>
						<input name="settings[images][g{$v@iteration}][form][descr]" value="{$v['form']['descr']}"> ������ �������� �� ����� 200�200px
					</div>
					<div>
						label<br>
						<input name="settings[images][g{$v@iteration}][form][label]" value="{$v['form']['label']}"> �������
					</div>
					
					<div><label><input type="checkbox" name="settings[images][g{$v@iteration}][form][multy]" value="1"{if $v['form']['multy']} checked{/if}> multy</label></div>
					<div><label><input type="checkbox" name="settings[images][g{$v@iteration}][form][showRadio]" value="1"{if $v['form']['showRadio']} checked{/if}> showRadio</label></div>
					<div><label><input type="checkbox" name="settings[images][g{$v@iteration}][form][showDescr]" value="1"{if $v['form']['showDescr']} checked{/if}> showDescr</label></div>
					<div><label><input type="checkbox" name="settings[images][g{$v@iteration}][form][showDel]" value="1"{if $v['form']['showDel']} checked{/if} disabled> showDel</label> --> showDel=1 ������</div>
				</td>
			</tr>
		</table>
	</fieldset>
{/foreach}			
			
<div id="imagesGroupsBl"></div>
<div><span onclick="addBlock_imgField.add('imagesGroupeSourse','imagesGroupsBl');" style="cursor:pointer;font-size:18px;">+ �������� ������</span></div>
<script type="text/javascript">
	addBlock_imgField=new addBlock();
	addBlock_imgField.blockIndex={$v@total};
	/*addBlock_imgField.add('imagesGroupeSourse','imagesGroupsBl');*/
</script>
		</fieldset>
		
		
		
		
<fieldset>
	<legend><b>�����</b></legend>			
{foreach $settings[$sett]['files'] as $group=>$v}	
<fieldset>
	<legend>������ {$v@iteration}</legend>
	
		<div class="fL"><label><input type="checkbox" name="settings[files][g{$v@iteration}][isActive]" value="1"{if $v['isActive']} checked{/if} onclick="$('#fileSettG{$v@iteration}').toggle();"> ��������</label></div>
		<div class="fL"><label><input type="checkbox" name="settings[files][g{$v@iteration}][del]" value="1"> �������</label></div>
		
		<table{if !$v['isActive']} class="dN"{/if} id="fileSettG{$v@iteration}" style="clear:both">
			<tr valign="top">
				<td>
					<b>��������� Upload</b><br>
					
					<div>
						saveDir<br>
						<input name="settings[files][g{$v@iteration}][upload][saveDir]" value="{$v['upload']['saveDir']}"> i/news/
					</div>
					<div>
						extensions<br>
						<input name="settings[files][g{$v@iteration}][upload][extension]" value="{$v['upload']['extension']}"> pdf, doc, xls
					</div>
					<div style="margin:5px 0;">
						<label><input type="checkbox" name="settings[files][g{$v@iteration}][upload][useDB]" value="1"{if $v['upload']['useDB']} checked{/if}> useDB</label>
					</div>
				</td>
				<td width="50%">
					<b>��������� filesForm</b><br>

					<div>
						name<br>
						<input name="settings[files][g{$v@iteration}][form][name]" value="{$v['form']['name']}">
					</div>
					<div>
						descr<br>
						<input name="settings[files][g{$v@iteration}][form][descr]" value="{$v['form']['descr']}"> ������ �������� �� ����� 200�200px
					</div>
					<div>
						label<br>
						<input name="settings[files][g{$v@iteration}][form][label]" value="{$v['form']['label']}"> �������
					</div>
					
					<div><label><input type="checkbox" name="settings[files][g{$v@iteration}][form][multy]" value="1"{if $v['form']['multy']} checked{/if}> multy</label></div>
					<div><label><input type="checkbox" name="settings[files][g{$v@iteration}][form][showRadio]" value="1"{if $v['form']['showRadio']} checked{/if}> showRadio</label></div>
					<div><label><input type="checkbox" name="settings[files][g{$v@iteration}][form][showDescr]" value="1"{if $v['form']['showDescr']} checked{/if}> showDescr</label></div>
				</td>
			</tr>
		</table>
	</fieldset>
{/foreach}			
			
<div id="filesGroupsBl"></div>
<div><span onclick="addBlock_fileField.add('filesGroupeSourse','filesGroupsBl');" style="cursor:pointer;font-size:18px;">+ �������� ������</span></div>
<script type="text/javascript">
	addBlock_fileField=new addBlock();
	addBlock_fileField.blockIndex={$v@total};
	/*addBlock_imgField.add('imagesGroupeSourse','imagesGroupsBl');*/
</script>
		</fieldset>
		
		
		
		
		
		
		
		
		<br>
		<input type="hidden" name="save" value="1">
		<input type="hidden" name="type" value="admin_settings">
		<input type="submit" value=" ��������� " class="button">
	</form>
	
</div>
{/if}