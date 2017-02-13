<?php
header('Content-Type: text/html;Charset=utf-8');
include_once('config.php');
include_once(CMS_DIR.'cms_init.php');




function level($id,$arr=1){

	$q= "SELECT id,pid FROM a_shop_categ WHERE id=".$id;
	$dd=mysql_query($q) or die(mysql_error()."<br>" .$q);
	$row=mysql_fetch_assoc($dd);
	
	
	if ($row[pid]!=0){
		$arr+=level($row[pid]);
	}
	else return 0; 
	
	return $arr;
	
}


if ($_GET['dd']==111) {

	$dd=mysql_query("SELECT id FROM a_shop_categ");
	while($row=mysql_fetch_assoc($dd)){
	
		mysql_query("UPDATE a_shop_categ SET level=".level($row[id])." WHERE id=".$row[id]);
		
	}
	
}

//универсальное меню
$result=mysql_query("SELECT id, pid, name, hidden_url,with_items FROM a_shop_categ WHERE isHidden='0' order by posi");
$cats = array();
while($cat =  mysql_fetch_assoc($result)){
    $cats[$cat['pid']][] =  $cat;
}
// print_r($cats);
 
function  build_tree($cats,$pid,$cur_cat='') {
	
	if(is_array($cats) and count($cats[$pid])>0) {
	
		$tree = '<ul data-pid="'.$pid.'" id="tree'.$pid.'" '.($cat[$pid][pid]==0?"	class='treeview".($cur_cat?'_in':'')." menu'":"").'>';
		
		foreach($cats[$pid] as $cat) {
		
			if ($cat[with_items])
				$tree .= '<li '.($cur_cat==$cat['id']?" class='active_tree'":"").'><a href="/catalog'.$cat['hidden_url'].'">'.$cat['name'].'</a>';
			else
				$tree .= '<li '.($cur_cat==$cat['id']?" class='active_tree'":"").'><a href="/catalog'.$cat['hidden_url'].'">'.$cat['name'].'</a>';
			//$tree .= '<li><span>'.$cat['name'].'</span>';
			$tree .=  build_tree($cats,$cat['id'],$cur_cat);
			$tree .= '</li>';
			
		}
		
		$tree .= '</ul>';
	}
	else return null;          
	
	return $tree; 
}


//картинки для банера
$dd=mysql_query("SELECT path,a.name name,b.descr descr,a.descr alt  FROM a_shop_images a JOIN a_news b ON a.modid=b.id WHERE a.mod='news3'");

if (mysql_num_rows($dd)){
	while($row=mysql_fetch_assoc($dd)){
		$b_baner[]=$row;
		
	}

	$smarty->assign('b_baner',$b_baner);
}

//разделы уровень 0
$q='SELECT * FROM `a_shop_categ` WHERE isHidden="0" AND pid=0 ORDER BY posi';
$dd=mysql_query($q);

if (mysql_num_rows($dd)) {
	
	$zindex=3;
	
	$i=1;
	
	while($row=mysql_fetch_assoc($dd)){
		
		if($i<=3){
			$row[zindex]=$zindex*2;
		}
		else{
			$row[zindex]=$zindex;
			
		}
		$zindex--;
		
		if ($i%3==0) {
			$zindex=3;
		}
		
		
		if (($i+1)%3==0) $row[second]=1;
		else $row[second]=0;
		
		$categoryes[$row[id]]=$row;
		$ids[]=$row[id];
		
		$i++;
		$cats_tree[$row[id]]=build_tree($cats,$row[id]);
		
	}
	if (!$_GET['mod']){
		
		$smarty->assign('cats_tree',$cats_tree);
		
		
	}
	
}	

$last=array_pop($categoryes);
$last[last]=1;

$categoryes[$last[id]]=$last;
// print_r($categoryes);

$smarty->assign('b_menu_cat',$categoryes);


function cat_menu($cur_id='') {
	
	$q="SELECT id,name,alias FROM a_shop_categ WHERE pid=0 AND isHidden='0'";
	$dd=mysql_query($q);
	while($row=mysql_fetch_assoc($dd)){
		$category_ids[]=$row[id];
		$category[$row[id]]=$row;
	}
	
	$q="SELECT id,name,alias,pid FROM a_shop_categ WHERE pid in (".implode(",",$category_ids).")  AND isHidden='0'";
	$dd=mysql_query($q);
	while($row=mysql_fetch_assoc($dd)){
		$category_ids[]=$row[id];
		$category[$row[pid]][child][]=$row;
	}
	
	return $category;

}

$smarty->assign('cat_menu',cat_menu());


if($_POST['mod']=='feedback'||$_GET['mod']=='feedback'){
	
	include_once(CMS_DIR.'modulespublic/feedback.php');
	echo $content;
	exit;
	
}

if ($_GET['mod']=='sitemap'){
	
	$sitemap="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
	<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
	";
	
	$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."</loc>
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		
	//разделы 
	$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/cat/</loc>
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
	$dd=mysql_query("SELECT id,alias FROM a_shop_categ WHERE main=1");
	
	while($row=mysql_fetch_assoc($dd)){
		
		$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/cat/".$row[alias]."/</loc> 
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		
	}
	
	//бренды
	$dd = mysql_query('SELECT p.*,i.name,i.path FROM '.PREFIX.'_shop_producers p,'.PREFIX.'_prod_images i WHERE i.isCover="1" AND i.modid=p.id AND p.footer=1 ORDER BY p.posi ASC');
	
	while($row=mysql_fetch_assoc($dd)){
		
		$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/brand/".$row[alias]."/</loc>
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		   
	}
	 
	//товары
	$dd=mysql_query("SELECT id,alias FROM a_shop_goods WHERE isHidden='0'");
	
	while($row=mysql_fetch_assoc($dd)){
		 
		$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/catalog/".$row[alias].".html</loc> 
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		
	}
	
	//Блог
	$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/blog/</loc>
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		
	
	
	$dd=mysql_query("SELECT id,alias FROM a_blog WHERE isHidden='0' AND pid IN(SELECT id FROM a_blog WHERE isHidden='0' AND pid=0)");
	
	while($row=mysql_fetch_assoc($dd)){
		 
		$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/blog/post/".$row[id].".html</loc> 
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		
	}
	
	$dd=mysql_query("SELECT id,alias FROM a_blog WHERE isHidden='0' AND pid=0");
	
	while($row=mysql_fetch_assoc($dd)){
		 
		$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/blog/category/".$row[id].".html</loc> 
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		
	}
	
	//статьи
	$dd=mysql_query("SELECT id,alias FROM a_articles WHERE isHidden='0' AND in_sitemap=1");
	
	while($row=mysql_fetch_assoc($dd)){
		 
		$sitemap.="
		<url>
			<loc>http://".$_SERVER['HTTP_HOST']."/articles/".$row[alias].".html</loc> 
			<lastmod>".date('Y-m-d')."</lastmod>
			<changefreq>weekly</changefreq>
			<priority>0.7</priority>
		</url>";
		
	}
	
	$sitemap.="</urlset>";
	header("Content-Type: text/xml");
	header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Cache-Control: post-check=0,pre-check=0");
	header("Cache-Control: max-age=0");
	header("Pragma: no-cache");
	echo  $sitemap;
	exit;
	
}




session_start();

if ($_POST['save_comment']){
//print_r($_POST);
	session_start();
	// $_SESSION['basket']['items'][$_POST['mod']][$_POST['id']][$_POST['key']]['comment']=iconv('utf-8','windows-1251',$_POST['comment']);
	$_SESSION['comment'][$_POST['mod']][$_POST['id']][$_POST['key']]=iconv('utf-8','windows-1251',$_POST['comment']);
	//print_r($_SESSION);
	exit;

}

if ($_GET[unsub]){
		
	$find=false;
	$f=file($_SERVER['DOCUMENT_ROOT']."/ve/email");
	$fw=fopen($_SERVER['DOCUMENT_ROOT']."/ve/email","w");
	foreach ($f as $k=>$v){
		
		if ($_GET[unsub]!=trim($v)){
			
			fwrite($fw,$v."\n");
			
		}
		else{
			
			$find=true;
			
		}
		
		
	}
	fclose($fw);
	if ($find)
		echo "<script>alert('Email, ".$_GET[unsub].", удален из рассылки')</script>";
	
	
	
	
}


if ($_POST['subscribe']) {
	
	preg_match("/.+@.+\..+/i",$_POST['email'],$res);
	
	header("Content-Type: application/x-suggestions+json; charset=windows-1251");
	
	if ($res[0]){
		
		//запишем в базу и зашлем оповещение
		include_once(CMS_DIR.'class/Mail.Class.php');
		
		$mail='info@ghp.su';
		$mailCC='kovaleva_mv@mail.ru';//'rdv@mail.ru';
		$f=fopen($_SERVER['DOCUMENT_ROOT']."/ve/email","a+");
		fwrite($f,$res[0]."\n");
		fclose($f);
		// $mailCC='matveev.leonid@gmail.com';//'rdv@mail.ru';
		$mailBCC='kovaleva@ghp.su';
		$subject="Подписка на сайте ".$_SERVER[HTTP_HOST];
		
		$thismail=new MIMEMail();
		
		
		
		$body="Новый Email в подписке на сайте ".$_SERVER[HTTP_HOST].": ".$res[0];
		
		//$email_end = "\n\n".'-- '."\n".'Сообщение отправлено с компьютера '.((isset($_SERVER['REMOTE_HOST']))? $_SERVER['REMOTE_HOST'].' ['.$_SERVER['REMOTE_ADDR'].']' : $_SERVER['REMOTE_ADDR'])."\n".'через форму http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].((isset($_SERVER['HTTP_USER_AGENT']))? "\n".'Браузер "'.$_SERVER['HTTP_USER_AGENT'].'"' : '')."\n\n";
				
		//$body=implode("\n",$body);
		// админу		
		$thismail->from_name='admin';
		$thismail->from_email="admin@".$_SERVER[HTTP_HOST];
		$thismail->subject=$subject;
		$thismail->to=$mail;
		$thismail->cc=$mailCC;
		$thismail->bcc=$mailBCC;
		$thismail->headers(); 
		$thismail->addtextpart(false,$body);
		$thismail->finish();
		$thismail->send();
		
		// подписанту
		
		$thismail=new MIMEMail();
		$body="Вы подписались на новости компании ООО «Гидравлические Комплексные Системы».\nТеперь вы будете в курсе всех изменений и событий.\nОтписаться от рассылки, вы сможете по ссылке http://hisco.ru/?unsub=".$res[0];
		$mail=$res[0];
		$thismail->from_name='admin';
		$thismail->from_email="admin@".$_SERVER[HTTP_HOST];
		$thismail->subject=$subject;
		$thismail->to=$mail;
		//$thismail->cc=$mailCC;
		//$thismail->bcc=$mailBCC;
		$thismail->headers(); 
		$thismail->addtextpart(false,$body);
		$thismail->finish();
		$thismail->send();
		
		
		
		echo json_encode(array('msg'=>'ok'));
	}
	else{
		echo json_encode(array('error'=>'ok'));
	}
	exit;

}

$q='SELECT name,id,producer_id,alias FROM `a_shop_goods` WHERE isHidden="0" ORDER BY posi';
$dd=mysql_query($q);
if (mysql_num_rows($dd)){
	$i=0;
	while($row=mysql_fetch_assoc($dd)){
		
		if (count($goods[$row[producer_id]])!=4){
		
			$goods[$row[producer_id]][]=$row;
			$producer_id[$row[producer_id]]=$row[producer_id];
			
			
		}
		
		$i++;
		
	}
	
	if ($producer_id){

		$q='SELECT name,id,alias  FROM `a_shop_producers` WHERE isHidden="0" AND id IN ('.implode(',',$producer_id).')  ORDER BY posi';
		// echo $q;
		$dd=mysql_query($q);
		if (mysql_num_rows($dd)){
			
			while($row=mysql_fetch_assoc($dd)){
			
				$producers[$row[id]]=$row;
				$producers[$row[id]][items]=$goods[$row[id]];
				
			}
			
		}

	}
	$smarty->assign('bm_producers',$producers);
	unset($goods);
	unset($producer_id);
	unset($producers);
}




if (!$_GET){

	$dd=mysql_query("SELECT  a.name name,a.id  id FROM `a_blog`  a join a_blog b ON a.pid=b.id WHERE b.pid=0 ORDER BY b.dateAdd DESC limit 1");
	if (mysql_num_rows($dd)){
		
		$smarty->assign('blog_index',mysql_fetch_assoc($dd));
	}

}



//Комментарии к блогу
if($_POST['action']=='add_comment'){
	
	include_once(CMS_DIR.'modulesPublic/blog.php');
	exit;
	if(!$errors){
		
		
		
		if($settings['feedback']['saveInDB']){
			$userinfo['fio']=$_POST['fio'];
			$userinfo['email']=$_POST['email'];
			$userinfo['phone']=$_POST['phone'];
			
			$q='INSERT INTO '.$mainTbl.' SET 
					name="",
					message="'.addslashes($_POST['message']).'",
					answer="",
					posi="1",
					isHidden="1",
					dateAdd=NOW(),
					userinfo="'.addslashes(serialize($userinfo)).'"';
			@mysql_query($q) or die(mysql_error());

			$lastId=@mysql_insert_id();
			unset($_SESSION['basket']);
		}
		
	
		$mail='design@privatecollection.ru';
		$mailCC='alla_taburet@mail.ru';
		$mailBCC='rdv@mail.ru';
		$subject=$_POST['subj']?$_POST['subj']:'Новое сообщение.';
		
		$thismail=new MIMEMail();
		
		
		if($_GET['shop'] && $_POST['obj'])
			$body[]='Оборудование >> '.$_POST['obj'];
		
		$body[]='Имя >> '.$_POST['fio'];
		$body[]='Email >> '.$_POST['email'];
		$body[]='Телефон >> '.$_POST['phone'];
		$body[]='Сообщение >> '.$_POST['message'];
		
		//$email_end = "\n\n".'-- '."\n".'Сообщение отправлено с компьютера '.((isset($_SERVER['REMOTE_HOST']))? $_SERVER['REMOTE_HOST'].' ['.$_SERVER['REMOTE_ADDR'].']' : $_SERVER['REMOTE_ADDR'])."\n".'через форму http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].((isset($_SERVER['HTTP_USER_AGENT']))? "\n".'Браузер "'.$_SERVER['HTTP_USER_AGENT'].'"' : '')."\n\n";
				
		$body=implode("\n",$body);
				
		$thismail->from_name=$_POST['fio'];
		$thismail->from_email=$_POST['email'];
		$thismail->subject=$subject;
		$thismail->to=$mail;
		$thismail->cc=$mailCC;
		$thismail->bcc=$mailBCC;
		$thismail->headers(); 
		$thismail->addtextpart(false,$body);
		$thismail->finish();
		$thismail->send();
	
	}
}


//print_r($_SESSION);
//include_once(CMS_DIR.'class/Class.Shop.php');

/*
if(!$_SESSION['course']){
	$_SESSION['course']=getCourseUSD();
}
if(!$_SESSION['courseEUR']){
	$_SESSION['courseEUR']=getCourseEUR();
}

*/

//echo phpinfo();
//echo'<pre>';print_r($_SESSION);echo'</pre>';
//unset($_SESSION['basket']);

$smarty->setTemplateDir(TEMPLATES_DIR.'public/');
$smarty->setCompileDir(SITE_ADM_PATH.'templates_c/public/');
$smarty->assign('orderItems',isset($_SESSION['basket']['orderItems']) ? $_SESSION['basket']['orderItems'] : 0);


if ($_GET['mod']=='news2'){
	
	$dd=mysql_query("SELECT name,id,alias FROM a_news WHERE `mod`='news' AND alias!='".$_GET['id']."' ORDER BY dateAdd DESC LIMIT 4");
				
	if (mysql_num_rows($dd)){
		while($row=mysql_fetch_assoc($dd)){
			$dd1=mysql_query('SELECT *,descr alt FROM a_shop_images WHERE `modid`='.$row[id].' AND `mod`="news" ORDER BY isCover DESC LIMIT 1');
			if (mysql_num_rows($dd1)){
				$img=mysql_fetch_assoc($dd1);
				$row[image]=$img;
			}
			$news_list_right[]=$row;
			
		}
		
		$smarty->assign('news_list_right1',$news_list_right);
		
	}

}

if ($_GET['mod']=='small_basket'){
	
	include_once(CMS_DIR.'/modulespublic/basket.php');
	exit;
}

if($_POST['mod']=='feedback'||$_GET['mod']=='feedback'){
	
	include_once(CMS_DIR.'modulespublic/feedback.php');
	echo $content;
	exit;
	
}

if (!$_GET['mod']){
	
	include(CMS_DIR.'/modules/settings.php');

	
}

if (!$_GET['mod']&&!$_GET['prod']){
	$_GET['mod']='nm';
	$smarty->assign('cats_tree',build_tree($cats,0,'ddd'));
}

if ($_GET[nm]){
	
	$_GET['mod']='nm';
	$smarty->assign('cats_tree',build_tree($cats,0,'ddd'));
}

if ($_GET['prod']){

	$_GET['mod']='prod';
}


/*метаинформация*/
$metaT=$settings['allsite']['metaT'];
$metaK=$settings['allsite']['metaK'];
$metaD=$settings['allsite']['metaD'];
//unset($settings['allsite']['metaT']);
unset($settings['allsite']['metaK']);
unset($settings['allsite']['metaD']);

$smarty->assign('settings',$settings);

$prepage='';

if($_GET['mod']&&@is_file(CMS_DIR.'/modulespublic/'.$_GET['mod'].'.php')){
	include_once(CMS_DIR.'/modulespublic/'.$_GET['mod'].'.php');
}else{

	//include_once(CMS_DIR.'modulespublic/inIndex.php');
}
if($errors){
	foreach($errors as $k=>$v){
		if(!is_array($v))
			$errorsBl.='<p style="color:red"><b>'.$v.'</b></p>';
		else{
			foreach($v as $k2=>$v2){
				$errorsBl.='<p style="color:red"><b>'.$v2.'</b></p>';
			}
		}
	}
}

if($_GET['mod']) {
	//$metaT.=$l->metaT?' - '.$l->metaT:'';

	if(in_array($_GET['mod'],array('shop','shop2','news')) && $_GET['id']){
		$metaT=($l->producern?$l->producern:'').($l->metaT?$l->metaT:$metaT);
		// print_r($l);
	}
	elseif($_GET['cat']){
		if($_GET['mod']=='shop2')
			$metaT=$categories2[$_GET['cat']]->metaT?$categories2[$_GET['cat']]->metaT:$categories2[$_GET['cat']]->name;
		else
			$metaT=$categories[$_GET['cat']]->metaT?$categories[$_GET['cat']]->metaT:$categories[$_GET['cat']]->name;

	}
	
	if($_GET['producer_id'])
		$metaT=$metaT.' '.$producersList[$_GET['producer_id']]->name;

	if(!$metaT) $metaT=$settings['allsite']['metaT'];

	$metaK=$l->metaK?$l->metaK.' '.$metaK:$metaK;
	$metaD=$l->metaD?$l->metaD:$metaD;
	$metaT=$l->metaT?$l->metaT:($l->name?$l->name:$metaT);
	
	
}else{
	$metaT.=$settings['indexpage']['metaT']?''.$settings['indexpage']['metaT']:'';
	$metaK=$settings['indexpage']['metaK']?$settings['indexpage']['metaK'].' '.$metaK:$metaK;
	$metaD=$settings['indexpage']['metaD']?$settings['indexpage']['metaD']:$metaD;
}
//echo'$metaT - '.$metaT.'<br>';
//echo'$metaK - '.$metaK.'<br>';
//echo'$metaD - '.$metaD.'<br>';
$smarty->assign('metaT',$metaT);
$smarty->assign('metaK',$metaK);
$smarty->assign('metaD',$metaD);


if(!$_GET['ajax']){
	if(!$_GET['print'])
		$prepage.=$smarty->fetch('_header.tpl');
		//$prepage.=$smarty->fetch('_headerTmp.tpl');
	else
		$prepage.=$smarty->fetch('_headerPrint.tpl');
}
else{
//	$content=iconv('cp1251','utf-8',$content);
}

$prepage.=$errorsBl;unset($errorsBl);
$prepage.=$content;unset($content);

if(!$_GET['ajax']){
	if(!$_GET['print'])
		$prepage.=$smarty->fetch('_footer.tpl');
	else
		$prepage.=$smarty->fetch('_footerPrint.tpl');
}


include_once(CMS_DIR.'modrewrite.php');

echo $prepage;

if($_SESSION['m']==4){
	unset($_SESSION['m']);
	echo '<script type="text/javascript">alert("Сообщение отправлено!");</script>';
}
elseif($_SESSION['m']==5){
	unset($_SESSION['m']);
	echo '<script type="text/javascript">alert("Не верный защитный код!");</script>';
}


	function format_price($price,$no_kop=1){
		
		
		//разбить по разрядам
		if ($no_kop)
			$price=preg_replace("/[\.]{1,}[0-9]{1,}/","",$price);
		
		$price=strrev($price);
		
		for($i=1,$j=strlen($price);$i<=$j;$i++){
			
			$new_price.=$price[$i-1];
			if ($i%3==0) $new_price.=" ";

		}
		
		return strrev($new_price);
	
	}
