<?php
/*
	добавл€ем/удал€ем картинки дл€ модулей designers, producers и categories
	картинки хран€тс€ в таблице в поле images (напр. $l->images['logo'])

	$images - список текущих картинок (дл€ удалени€ и т.п)
*/
function uploadImages($opt,$curImages){
	global $l;

	if(!$images) $images=array();

	$imgdel=is_array($_POST['imgdel'])?$_POST['imgdel']:'';
	$imgdescr=is_array($_POST['imgdescr'])?$_POST['imgdescr']:'';

	//echo'<pre>';print_r($opt);echo'</pre>';
	//echo'<pre>';print_r($_FILES);echo'</pre>';
	//echo'<pre>';print_r($images);echo'</pre>';
	//echo'<pre>';print_r($imgdel);echo'</pre>';
	//die();

	/*
		если картинки в отдельной таблице - удаление и другие действи€, кроме самой загрузки
		$shop->addEditImagesInfo()
	*/


	$curImages = $curImages? $curImages : array();

	if(!$opt['useDB']){
		//комментарий к картинке
		if($imgdescr[$opt['name']]){
			foreach($imgdescr[$opt['name']] as $k=>$v){
				if($curImages[$k])
					$curImages[$k]['descr']=$v;
			}
		}

		//удаление
		if($imgdel[$opt['name']]){
			foreach($imgdel[$opt['name']] as $k=>$v){
				foreach($opt['prefixes'] as $pref){
					@unlink(SITE_DIR.$curImages[$k]['path'].$pref.'_'.$curImages[$k]['name']);
				}
				@unlink(SITE_DIR.$curImages[$k]['path'].$curImages[$k]['name']);
				unset($curImages[$k]);
			}
			$curImages=@array_merge(array(),$curImages);
		}
	}
//print_r($_POST);
	if (!$opt['useDB'])
	{
		for ($i=0;$i<count($curImages); $i++)
		{
			$curImages[$i]['isCover'] = isset($_POST['newCoverId']) && $_POST['newCoverId']==$i;
		}
	}


//	print_r($curImages);die();

	//загрузка новой картинки
	$image_tmp=new uploadImages($_FILES[($opt['name']?$opt['name']:'image')]);
	$image_tmp->savedir=($opt['saveDir']?$opt['saveDir']:SITE_DIR.'i/producers/');

	$image_tmp->moveFiles();
	if($image_tmp->uplImages){
		resizeImages($image_tmp->uplImages,
			($opt['resizeTo']?$opt['resizeTo']:
				array('800x0','tm_85x80')
			));


		$newImages=$image_tmp->uplImages;

		if (!$opt['useDB'])
		{
			for ($i=0;$i<count($curImages); $i++)
			{
				$curImages[$i]['isCover'] = false;
			}
		}
//		print_r($newImages);die();


		//print_r($image_tmp->errors);
		//echo'<pre>';print_r($image_tmp->uplImages);echo'</pre>';

		/*
			катринка одна. «амена существующей картинки загруженной. существующа€ удал€етс€
			если картинки в отдельной таблице, это делаетс€ в
			$shop->addEditImagesInfo()
		*/
		if(!$opt['useDB']){
			if(!$opt['multy']){
				if($curImages[0]){
					foreach($opt['prefixes'] as $pref){
						@unlink(SITE_DIR.$curImages[0]['path'].$pref.'_'.$curImages[0]['name']);
					}
					@unlink(SITE_DIR.$curImages[0]['path'].$curImages[0]['name']);
				}

				//комментарий к картинке
				if($imgdescr[$opt['name']])
					$newImages[0]['descr']=$imgdescr[$opt['name']][0];

				/*
				foreach($images[$opt['name']] as $k=>$v){
					if($l->images[$opt['name']][$k])
						foreach($opt['prefixes'] as $pref){
							@unlink(SITE_DIR.$l->images[$opt['name']][$k]['path'].$pref.'_'.$l->images[$opt['name']][$k]['name']);
						}
						@unlink(SITE_DIR.$l->images[$opt['name']][$k]['path'].'tm_'.$l->images[$opt['name']][$k]['name']);
					$images[$opt['name']]['images'][$k]=$v;
				}
				*/
			}else{
				//ƒобавление загруженных картинок к существующим
				$newImages=array_merge($curImages,$newImages);
				//$images['logo']=$l->images['logo']?$l->images['logo']:array();
			}
		}
	}else{
		/*
			если картинки не в отдельной таблице и ничего не загрузили нового
			возвращаем текущие картинки
		*/
		if(!$opt['useDB']){
			$newImages=$curImages;
		}
	}


	//if($images)
	//	$images=addslashes(serialize($images));

	return $newImages;
}

/*
$forms['imagesForm2']=imageForm(array(
    'blockNum'=>2,
	'pref'=>'tm_',
	'name'=>'image2',
	'descr'=>'картинка',
	'label'=>'¬ид сбоку',
	'images'=>($l->data['images2']?$l->data['images2']:''),
	'showDel'=>1,
	'showRadio'=>0,
	'showDescr'=>0,
	'multy'=>false
));

	$type= images || files
*/
function getImagesFormOpt($type='images'){
	global $settings;

	//$settSection='news_adm';
	if(isset($_GET['submod']) && $_GET['submod'])
		$settSection=$_GET['submod'].'_adm';
	else
		$settSection=$_GET['mod'].'_adm';
//print_r($settings);
	$opt=array();
	if($settings[$settSection][$type]){
		foreach($settings[$settSection][$type] as $group=>$v){
			if($v['isActive']){
				//опредл€ем префикс картинки, если такой есть дл€ формы в админке
				if($v['upload']['thumbs']){
					foreach($v['upload']['thumbs'] as $v2){
						//$uploadedImages['resizeTo'][]=$v['pref'].'_'.$v['w'].'x'.$v['h'];
						if($v2['pref']) $imagePref=$v2['pref'];
					}
				}

				$opt[$v['form']['name']]=array(
		    		'type'=>$type,
		    		'pref'=>($imagePref?$imagePref.'_':''),
		    		'name'=>$v['form']['name'],
		    		'descr'=>$v['form']['descr'].
		    			($v['upload']['extension']?' –асширени€: '.$v['upload']['extension']:''),
		    		'label'=>$v['form']['label'],
		    		'showDescr'=>($v['form']['showDescr']?1:0),
		    		'showRadio'=>($v['form']['showRadio']?1:0),
		    		'multy'=>$v['form']['multy'],

		    		'useDB'=>$v['upload']['useDB']
				);
			}

		}
	}

	/*
	if(
		$settings[$settSection]['images']['g1'] &&
		$settings[$settSection]['images']['g1']['isActive']
	){
		//опредл€ем префикс картинки, если такой есть дл€ формы в админке
		if($settings[$settSection]['images']['g1']['upload']['thumbs']){
			foreach($settings[$settSection]['images']['g1']['upload']['thumbs'] as $v){
				//$uploadedImages['resizeTo'][]=$v['pref'].'_'.$v['w'].'x'.$v['h'];
				if($v['pref']) $imagePref=$v['pref'];
			}
		}

		$opt=array(
    		'pref'=>($imagePref?$imagePref.'_':''),
    		'name'=>$settings[$settSection]['images']['g1']['form']['name'],
    		'descr'=>$settings[$settSection]['images']['g1']['form']['descr'],
    		'label'=>$settings[$settSection]['images']['g1']['form']['label'],
    		//использовать дл€ чекбокса удалени€ новое им€
    		'showDel_new'=>1,
    		'showDescr_new'=>($settings[$settSection]['images']['g1']['form']['showDescr']?1:0),
    		'showRadio'=>($settings[$settSection]['images']['g1']['form']['showRadio']?1:0),
    		'multy'=>$settings[$settSection]['images']['g1']['form']['multy']
		);
	}*/
	//echo'<pre>';print_r($opt);echo'</pre>';
	//echo'<pre>';print_r($settings);echo'</pre>';
	//echo'<pre>';print_r($uploadedImages);echo'</pre>';

	return $opt;
}
function getUploadImagesOpt(){
	global $settings;
	

	//$settSection='news_adm';
	if(isset($_GET['submod']))
		$settSection=$_GET['submod'].'_adm';
	else
		$settSection=$_GET['mod'].'_adm';


	$opt=array();
	
// exit;
	if($settings[$settSection]['images']){

		foreach($settings[$settSection]['images'] as $group=>$v){

			if($v['isActive']){

				//им€ загружаемых файлов $_FILES['name']
				if($v['form']['name'])
					$uploadName=$v['form']['name'];

				$opt[$uploadName]=$v['upload'];
				$opt[$uploadName]['name']=$uploadName;

				if($v['form']['multy'])
					$opt[$uploadName]['multy']=true;

				if($v['upload']['saveDir'])
					$opt[$uploadName]['saveDir']=SITE_DIR.$v['upload']['saveDir'];
				if($v['upload']['thumbs']){
					$opt[$uploadName]['resizeTo']=array();
					$opt[$uploadName]['prefixes']=array();
					foreach($v['upload']['thumbs'] as $v2){
						$opt[$uploadName]['resizeTo'][]=($v2['pref']?$v2['pref'].'_':'').$v2['w'].'x'.$v2['h'];

						//дл€ удалени€
						if($v2['pref']) $opt[$uploadName]['prefixes'][]=$v2['pref'];
					}
				}
			}
		}
	}

	/*
	if(
		$settings[$settSection]['images']['g1'] &&
		$settings[$settSection]['images']['g1']['isActive']
	){
		//им€ загружаемых файлов $_FILES['name']
		if($settings[$settSection]['images']['g1']['form']['name'])
			$opt['name']=$settings[$settSection]['images']['g1']['form']['name'];

		if($settings[$settSection]['images']['g1']['form']['multy'])
			$opt['multy']=true;

		if($settings[$settSection]['images']['g1']['upload']['saveDir'])
			$opt['saveDir']=SITE_DIR.$settings[$settSection]['images']['g1']['upload']['saveDir'];
		if($settings[$settSection]['images']['g1']['upload']['thumbs']){
			$opt['resizeTo']=array();
			$opt['prefixes']=array();
			foreach($settings[$settSection]['images']['g1']['upload']['thumbs'] as $v){
				$opt['resizeTo'][]=($v['pref']?$v['pref'].'_':'').$v['w'].'x'.$v['h'];

				//дл€ удалени€
				if($v['pref']) $opt['prefixes'][]=$v['pref'];
			}
		}
	}
	*/
	//echo'<pre>';print_r($opt);echo'</pre>';
	//echo'<pre>';print_r($settings);echo'</pre>';
	//echo'<pre>';print_r($uploadedImages);echo'</pre>';

	return $opt;
}

function getUploadFilesOpt(){
	global $settings;

	//$settSection='news_adm';
	if($_GET['submod'])
		$settSection=$_GET['submod'].'_adm';
	else
		$settSection=$_GET['mod'].'_adm';

	$opt=array();
	if($settings[$settSection]['files']){
		foreach($settings[$settSection]['files'] as $group=>$v){

			if($v['isActive']){
				//им€ загружаемых файлов $_FILES['name']
				if($v['form']['name'])
					$uploadName=$v['form']['name'];

				$opt[$uploadName]=$v['upload'];
				$opt[$uploadName]['name']=$uploadName;

				if($v['form']['multy'])
					$opt[$uploadName]['multy']=true;

				if($v['upload']['saveDir'])
					$opt[$uploadName]['saveDir']=SITE_DIR.$v['upload']['saveDir'];
				if($v['upload']['extension']){
					$v['upload']['extension']=str_ireplace(' ','',$v['upload']['extension']);
					$opt[$uploadName]['extension']=explode(',',$v['upload']['extension']);
				}
			}
		}
	}
	//echo'<pre>';print_r($opt);echo'</pre>';
	//echo'<pre>';print_r($settings);echo'</pre>';
	//echo'<pre>';print_r($uploadedImages);echo'</pre>';

	return $opt;
}
//echo'<pre>';print_r(getUploadFilesOpt());echo'</pre>';
?>