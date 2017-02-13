<?php
if(!function_exists('metainfoForm')){
	function metainfoForm($data = [],  $attr = []){
		$meta = [];
		
		$legend = isset($attr['legend']) ? $attr['legend'] : 'Метаинформация';
		//$legend = $attr['legend'] ? $attr['legend'] : 'Meta information';
		
		if(isset($data['metaT'])){
			$meta['T'] = $data['metaT'];
		}else{
			$meta['T'] = isset($_POST['metaT']) ? $_POST['metaT'] : '';
		}
		if(isset($data['metaK'])){
			$meta['K'] = $data['metaK'];
		}else{
			$meta['K'] =isset($_POST['metaK']) ? $_POST['metaK'] : '';
		}
		if(isset($data['metaD'])){
			$meta['D'] = $data['metaD'];
		}else{
			$meta['D'] = isset($_POST['metaD']) ? $_POST['metaD'] : '';
		}
		
		
		$alias = isset($_POST['alias']) ? $_POST['alias'] : (isset($data['alias']) ? $data['alias'] : '');
		$showAlias = (isset($attr['showAlias']) && $attr['showAlias'] === false ? false : true);
		
		
		if($_POST){
			foreach($meta as $k=>$v){
				$meta[$k]=htmlspecialchars($v,ENT_QUOTES);
			}
			$alias=htmlspecialchars($alias,ENT_QUOTES);
		}
		
		$result='
		<fieldset style="margin:0 0 10px 0">
			<legend>'.$legend.'</legend>
			<div>
				Заголовок (Title)<br>
				<!--Meta Title<br-->
				<input type="text" name="metaT" value="'.$meta['T'].'" class="input" style="width:90%"><br>
			</div>
			<div style="margin-top:5px;">
				Ключевые слова (meta Keywords)<br>
				<!--Meta Keywords<br-->
				<input type="text" name="metaK" value="'.$meta['K'].'" class="input" style="width:90%"><br>
			</div>
			<div style="margin-top:5px;">
				Описание (meta Description)<br>
				<!--Meta Description<br-->
				<input type="text" name="metaD" value="'.$meta['D'].'" class="input" style="width:90%"><br>
			</div>'.
			($showAlias?'<div style="margin-top:5px;">
				alias<br>
				<input type="text" name="alias" value="'.$alias.'" class="input" style="width:90%"><br>
			</div>':'').
		'</fieldset>';
		
		return $result;
	}
}

/*
 параметры массива $attr аналогичны параметрам функции {html_select_date} в smarty (http://smarty.php.net/manual/ru/language.function.html.select.date.php)
*/
function dateForm($attr=array()){
	global $l,$config;
	
	if($attr['dateCur']){
		$time=strtotime($attr['dateCur']);
	}else{
		$time=date('U');
	}
	
	
	$blockname=$attr['blockname']?$attr['blockname']:'Дата';
	$formname=$attr['field_array']?$attr['field_array']:'date';
    
	if($attr['month_empty']) $months['00']=$attr['month_empty'];
	for($i=1;$i<=12;$i++){
		$months[date('m',mktime(0,0,0,$i))]=ucfirst(strftime('%B',mktime(0,0,0,$i)));
        //$months[date('m',mktime(0,0,0,$i))]=ucfirst(date('M',mktime(0,0,0,$i)));
    }
    
    if($attr['day_empty']) $days['00']=$attr['day_empty'];
	for($i=1;$i<=31;$i++){
		$days[$i]=$i;
    }
    
	if($attr['year_empty']) $years['00']=$attr['year_empty'];
	for($i=2000;$i<=2020;$i++){
		$years[$i]=$i;
    }
    
	$result='<fieldset id="date"><legend>'.$blockname.'</legend>';
	//$result.='<input type="text" name="day" maxlength="2" style="width: 19px;" value="'.date('d',$time).'" title="День">';
	$result.=makeDropDown($days,$formname.'[day]',date('d',$time)).' ';
    $result.=makeDropDown($months,$formname.'[month]',date('m',$time)).' ';
    $result.=makeDropDown($years,$formname.'[year]',date('Y',$time)).' ';
    //$result.='<input type="text" name="year" maxlength="4" style="width: 32px;" value="'.date('Y',$time).'" title="Год">';
    //$result.='&nbsp;<input type="text" name="hour" maxlength="2" style="width: 19px;" value="'.date('H',$time).'" title="Час">';
    //$result.=':<input type="text" name="minute" maxlength="2" style="width: 19px;" value="'.date('i',$time).'" title="Минута">';
    //$result.=':<input type="text" name="second" maxlength="2" style="width: 19px;" value="'.date('s',$time).'" title="Секунда">';
    $result.='</fieldset>';
return $result;
}

function categoriesForm($attr=array()){
	global $categ;
	
	$label=$attr['label']?$attr['label']:'категория';
	$name=$attr['name']?$attr['name']:'categories';	
	$curcategories=explode(',',$attr['categories']);
	if(!$attr['id']) $attr['id']=0;
	
	$uniqid=uniqid();
	
	//$categories=array();//getFullRec($categories);
	$categories=$categ->getFullRec();
	//getFullTree(0,'',$categories);//getChilds(0,'',$categories);
	
	$result.='<fieldset id="date"><legend>'.$label.'</legend>';
	//$result.='<p><a href="javascript: d'.$uniqid.'.openAll();">Open</a> | <a href="javascript: d'.$uniqid.'.closeAll();">Close</a></p>
	$result.='<p><a href="javascript: d'.$uniqid.'.openAll();">Раскрыть</a> | <a href="javascript: d'.$uniqid.'.closeAll();">Скрыть</a></p>
			<input type="hidden" name="'.$name.'" value="0">
			<div id="catTree'.$uniqid.'"></div>
			<script type="text/javascript">
				<!--
				chebox=true;
				categories = new Array();
				d'.$uniqid.' = new dTree("d'.$uniqid.'");
				d'.$uniqid.'.fieldName = "'.$name.'";
				d'.$uniqid.'.add(0,-1,"<b>Список</b>");';
	foreach($curcategories as $k=>$v){
		//if($v) $result.='categories['.$v.']=1;';
		if($v) $result.='d'.$uniqid.'.selectedItems['.$v.']=1;';
	}	
	
	foreach($categories as $v){
		$result.='d'.$uniqid.'.add('.$v->id.','.$v->pid.',"'.$v->name.'");';
		//$result.=$v->parent_id;
		/*$result.='<div>
			<input type="checkbox" name="categories[]" value="'.$v->catid.'"'.(in_array($v->catid,$l['categories'])?' checked="checked"':'').'>'.
			$v->catid.' - '.$v->visible_title.'</div>';*/
		//$result.='<div><input type="checkbox" name="categores[]" value="'.$v['id'].'"'.(in_array($v['id'],$l['category'])?' checked="checked"':'').'>-'.str_repeat('--',$v['level']).$v['name'].
		//'</div>';
	}	
	$result.='document.getElementById("catTree'.$uniqid.'").innerHTML=d'.$uniqid.';//document.write(d'.$uniqid.');//--></script>';
	$result.='</fieldset>';
	
return $result;
}

function filesImagesForm($attr=array()){
	$type=$attr['type'];
	$pref=$attr['pref'];
	$label=$attr['label']?$attr['label']:'картинка';
	$name=$attr['name']?$attr['name']:'imageHDD';
	$multy=$attr['multy']?true:false;
	$descr=$attr['descr']?$attr['descr']:'';//:'загрузить c жесткого диска:';
	$images=is_array($attr['images'])?$attr['images']:'';
		
	$showRadio=isset($attr['showRadio'])?intval($attr['showRadio']):1;
		$showRadio=$multy?$showRadio:0;
		//$showRadio=$multy?1:0;
	$showDescr=$attr['showDescr']?1:0;//комментарий к картинке
	
	$imagesView='';

	if($images){
		foreach($images as $k=>$v){
			if(!$v) continue;
			
			if(is_array($v)){
				$v1->path=$v['path'];
				$v1->name=$v['name'];
				$v1->w=$v['w'];
				$v1->descr=$v['descr'];
				$v1->id=$k;
				$v1->isCover=$v['isCover'];
			}else{
				$v1=$v;
			}
			
			$imagesView.='<div class="imageEditBl">'.
				'<div class="i">'.
					'<a href="'.$v1->path.$v1->name.'" target="_blank">'.
						'<img src="'.$v1->path.$pref.$v1->name.'" width="'.($v1->w>190?190:'').'">'.
					'</a>'.
				'</div>'.
				($showRadio?'<div style="float:right"><label><input type="radio" name="newCoverId'.$blockNum.'" value="'.$v1->id.'" '.($v1->isCover?' checked':'').'><small> главная</small></label></div>':'').
				'<label><input type="checkbox" name="imgdel['.$name.']['.($v1->id?$v1->id:$k).']"  value="1" '.($_POST['imgdel'][($v1->id?$v1->id:$k)]?' checked':'').'><small> удалить!</small></label>'.
				($showDescr?'<input type="text" name="imgdescr['.$name.']['.($v1->id?$v1->id:$k).']" value="'.htmlspecialchars($v1->descr,ENT_QUOTES).'" class="input" style="width:100%">':'').
			'</div>';
		}
		$imagesView.='';
	}
		
	$uniqid=uniqid();
	
	$result='<fieldset id="image"><legend>'.$label.'</legend>';
	
	$result.='<script language="JavaScript">
				function addBlock'.$uniqid.'(id){
					var content="";
					
					obj=document.getElementById(id);
					
					content=\'<div style="border-bottom:1px dashed grey;padding:0 0 5px 0;margin:0 0 5px 0;">'.
						//'<small></small><br>'.
						($descr?'<small>'.$descr.'</small><br>':'').
						'<input type="file" name="'.$name.'[]" style="width:100%"><br>'.
						//($showDescr?'<input type="text" name="imgdescr'.$blockNum.'[]" value="" class="input" style="width:100%">':'').
		//				'<input type="radio" name="isCover"> главная'.
					'</div>\';
					
					newdiv=document.createElement("DIV");
					newdiv.innerHTML=content;
					
					obj.appendChild(newdiv);
				}
			</script>'.
	$imagesView;
		
	$result.='<div id="i'.$uniqid.'" style="clear:both;"></div>';
	$result.='<div style="margin:5px;"><a href="#" onclick="addBlock'.$uniqid.'(\'i'.$uniqid.'\');'.($multy?'':'this.style.display=\'none\';').'return false;"><small>'.
		($multy?'добавить':($images?'заменить':'добавить')).($type=='images'?' картинку':' файл').
		//en ($multy?'add':($images?'change':'add')).' image'.
		'</small></a></div>';
		
	$result.='</fieldset>';
return $result;
}

function imageForm($attr = []){
	if(isset($attr['blockNum'])){
		$blockNum = $attr['blockNum'];
	}else{
		$blockNum = '';
	}
	
	if(isset($attr['descr'])){
		$descr = $attr['descr'];//:'загрузить c жесткого диска:';
	}else{
		$descr = '';//:'загрузить c жесткого диска:';
	}
	
	if(isset($attr['images']) && is_array($attr['images'])){
		$images = $attr['images'];
	}else{
		$images = '';
	}
	
	$showDel_new = isset($attr['showDel_new']) ? 1 : 0;
	$showDescr_new = isset($attr['showDescr_new']) ? 1 : 0;

	$label=$attr['label']?$attr['label']:'картинка';
	$name=$attr['name']?$attr['name']:'imageHDD';
	$multy=$attr['multy']?true:false;
	
	
	//$showFields=is_numeric($attr['showFields'])?$attr['showFields']:1;
	//$showDel=$attr['showDel']?1:0;
	$showDel=1;
	
		
	$showRadio=isset($attr['showRadio'])?intval($attr['showRadio']):1;
		$showRadio=$multy?$showRadio:0;
		//$showRadio=$multy?1:0;
	$showDescr=$attr['showDescr']?1:0;//комментарий к картинке
		
	//$images=is_array($l['images'])?$l['images']:'';
	$imagesView='';
	
	if($images){
		foreach($images as $k=>$v){
			if(!$v) continue;
			
			if(is_array($v)){
				$v1->path=$v['path'];
				$v1->name=$v['name'];
				$v1->w=$v['w'];
				$v1->descr=$v['descr'];
				$v1->id=$k;
				$v1->isCover=$v['isCover'];
			}else{
				$v1=$v;
			}
			
			$imagesView.='<div class="imageEditBl">'.
				'<div class="i">'.
					//($v['isBig']?'<a href="'.$v['path'].$v['name'].'" target="_blank">':'').
					'<a href="'.$v1->path.$v1->name.'" target="_blank">'.
						'<img src="'.asset_cache($v1 -> name, $attr).'" width="'.($v1->w>190?190:'').'">'.
					//($v['isBig']?'</a>':'').
					'</a>'.
				'</div>'.
				($showRadio?'<div style="float:right"><label><input type="radio" name="newCoverId'.$blockNum.'" value="'.$v1->id.'" '.($v1->isCover?' checked':'').'><small> главная</small></label></div>':'').
				//($showRadio?'<div style="float:right"><label><input type="radio" name="newCoverId'.$blockNum.'" value="'.$v1->id.'" '.($v1->isCover?' checked':'').'><small> primary</small></label></div>':'').
				($showDel_new?
					'<label><input type="checkbox" name="imgdel['.$name.']['.($v1->id?$v1->id:$k).']"  value="1" '.($_POST['imgdel'][($v1->id?$v1->id:$k)]?' checked':'').'><small> удалить!</small></label>'
				:
					($showDel?'<label><input type="checkbox" name="imgdel'.$blockNum.'['.($v1->id?$v1->id:$k).']"  value="'.$v1->path.$v1->name.'" '.(isset($_POST['imgdel'][($v1->id?$v1->id:$k)])?' checked':'').'><small> удалить</small></label>':'')
				).
				//en ($showDel?'<label><input type="checkbox" name="imgdel'.$blockNum.'['.($v1->id?$v1->id:$k).']"  value="1" '.($_POST['imgdel'][($v1->id?$v1->id:$k)]?' checked':'').'><small> delete</small></label>':'').
				($showDescr?'<input type="text" name="imgdescr'.$blockNum.'['.$v1->id.']" value="'.htmlspecialchars($v1->descr,ENT_QUOTES).'" class="input" style="width:100%">':'').
				($showDescr_new?'<input type="text" name="imgdescr['.$name.']['.($v1->id?$v1->id:$k).']" value="'.htmlspecialchars($v1->descr,ENT_QUOTES).'" class="input" style="width:100%">':'').
			'</div>';
		}
		$imagesView.='';
	}
		
	$uniqid=uniqid();
	
	$result='<fieldset id="image"><legend>'.$label.'</legend>';
	
	$result.='<script language="JavaScript">
				function addBlock'.$uniqid.'(id){
					var contentHDD="";var contentURL="";
				
					obj=document.getElementById(id);
					
					//currentItems=obj.getElementsByTagName("DIV").length;
					//alert(a.length)
					
					contentHDD=\'<div style="border-bottom:1px dashed grey;padding:0 0 5px 0;margin:0 0 5px 0;">'.
						//'<small></small><br>'.
						($descr?'<small>'.$descr.'</small><br>':'').
						'<input type="file" name="'.$name.'[]" style="width:100%"><br>'.
						//($showDescr?'<input type="text" name="imgdescr'.$blockNum.'[]" value="" class="input" style="width:100%">':'').
		//				'<input type="radio" name="isCover"> главная'.
					'</div>\';
					//contentURL=\'<div>'.
						'<small>или URL</small><br>'.
						'<input type="text" name="imageURL[]" value="" style="width:100%"><br>'.
					'</div>\';
 	
					content=contentHDD+contentURL;
						
					//obj.innerHTML+=content;
				
					newdiv=document.createElement("DIV");
					newdiv.innerHTML=content;
					
					obj.appendChild(newdiv);
				}
			</script>'.
	//'<small>загружать надо большую картинку - превьюха создасться сама</small><br>'.
	$imagesView;
		//'<small>'.$descr.'</small><br>';
		//'<small>(картинка шириной не менее 300px)</small><br>';
	/*
	for($i=0;$i<$showFields;$i++){
		$result.='<input type="file" name="'.$name.'[]" style="width:100%"><br>';
		if($showDescr){
			$result.='<small>комментарий</small><br>'.
					'<input type="text" name="imgdescr[]" value="" class="input" style="width:100%">';
		}
	}*/
	//'<input type="radio" name="isCover"> главная<br>'.
//	'<small>или URL</small><br>'.
//	'<input type="text" name="imageURL[]" value="'.($_REQUEST['imageURL'][0]?$_REQUEST['imageURL'][0]:'').'" style="width:100%"><br>'.
	
	/*
	if($multy){
		$result.='<div id="i'.$uniqid.'"></div>';
		$result.='<div style="margin:5px;"><a href="#" onclick="addBlock'.$uniqid.'(\'i'.$uniqid.'\');return false;"><small>добавить картинку</small></a></div>';
	}*/
	
	$result.='<div id="i'.$uniqid.'" style="clear:both;"></div>';
	$result.='<div style="margin:5px;"><a href="#" onclick="addBlock'.$uniqid.'(\'i'.$uniqid.'\');'.($multy?'':'this.style.display=\'none\';').'return false;"><small>'.
		($multy?'добавить':($images?'заменить':'добавить')).' картинку'.
		//en ($multy?'add':($images?'change':'add')).' image'.
		'</small></a></div>';
		
	$result.='</fieldset>';
return $result;
}


function linksForm(){
	global $l;
	
	if(!is_array($l['linksInfo'])) unset($l['linksInfo']);
	
	//echo'<pre>';print_r($l['linksInfo']);echo'</pre>';
	if($_SESSION['usergroup']==1){}
	$result='';
	$result.='<fieldset><legend>Ссылки</legend>'.
		'<div id="links">';
			
	for($i=0,$j=0;$i<count($l['linksInfo']);$i++){
		if($l['linksInfo'][$i]['link']){
			//при редактировании этой формы - такие же измения внести в script.js функция addBlock
			$result.='<div style="border-bottom:1px dashed grey;padding:5px 0;"><small>Пароль на архив (если надо)</small><br>'.
				'<input type="text" name="linksInfo['.$j.'][pass]" value="'.($l['linksInfo'][$i]['pass']).'"><br>'.
				'<small>Ссылки (если несколько - каждая в отдельной строке)<br>'.
				'<i>старайтесь соблюдать формат: &laquo;адрес ссылки&raquo; &laquo;размер файла Мб&raquo;</i></small><br>'.
				'<textarea rows="6" name="linksInfo['.$j.'][link]" style="width:100%">'.
				strip_tags($l['linksInfo'][$i]['link']).
				'</textarea></div>';	
			$j++;
		}
	}
	$result.='</div>';
	$result.='<div><a href="#" onclick="addBlock(\'links\');return false;"><small>добавить источник</small></a></div>';
	$result.='</fieldset>';

return $result;
}