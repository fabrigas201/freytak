<?
error_reporting(0);


function _month($month){
	
	$ar=array(
	'01'=>'Января',
	'02'=>'Февраля',
	'03'=>'Марта',
	'04'=>'Апреля',
	'05'=>'Мая',
	'06'=>'Июня',
	'07'=>'Июля',
	'08'=>'Августа',
	'09'=>'Сентября',
	'10'=>'Октября',
	'11'=>'Ноября',
	'12'=>'Декабря',
	);
	
	return $ar[$month];

}
function comments($id,$num=0){
	
	global $blog;
	
	$blog->catList=array();
	$blog->getTree($id);
	$subartisles=$blog->catList;
	
	/*
	if(!$subartisles && $l->id){
		$blog->catList=array();
		$blog->getChilds($l->id);
		$subartisles=$blog->catList;
	}
	*/
	
	if ($num) {
		return count($subartisles);
	}
	
	if ($subartisles){
		
		foreach ($subartisles as $k=>$v){
			
			$subartisles[$k]->name=trim(str_replace("&#8212;","",str_replace("|","",$subartisles[$k]->name)));
			$comm_ids[]=$v->id;
			
		}
		
		$dd=mysql_query("SELECT *,DATE_FORMAT(dateAdd,'%d') d,DATE_FORMAT(dateAdd,'%m') m,DATE_FORMAT(dateAdd,'%Y') y FROM ".$blog->mainTbl." WHERE id IN(".implode(",",$comm_ids).")");
		
		
		if (mysql_num_rows($dd)){
			
			while($tmp=mysql_fetch_object($dd)){
				
				$tmp->name=str_replace("&#8212;","",str_replace("|","",$tmp->name));
				$tmp->dateAdd=$tmp->d." "._month($tmp->m)." ".$tmp->y;
				$comm_data[$tmp->id]=$tmp;
				
				
			}
			
			foreach ($subartisles as $k=>$v){
			
				$v->data=$comm_data[$v->id];
				
				$subartisles[$k]=$v;
			
			}
			
		}
		
		return $subartisles;

	}
	return false;
}
	
	if ($_GET['id']){
	
		include('config.php');
		include_once(CMS_DIR.'class/Class.Blog.php');
		mysql_connect(DBHOST,DBLOGIN,DBPASS);
		mysql_select_db(DBNAME);
		$mainTbl=PREFIX.'_blog';
		global $blog;
		
		$blog=new Blog($mainTbl);
		$blog->mainTbl=$mainTbl;
		$blog->setMode('user');
		
		$comment=comments($_GET['id']);
		
		
		
if ($comment){
		
		$dd=mysql_query("SELECT name FROM ".$mainTbl." WHERE id=".$_GET['id']."");
		$name=mysql_fetch_array($dd);
			
		header("Content-Type: text/xml;  charset=windows-1251");
		header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Cache-Control: post-check=0,pre-check=0");
		header("Cache-Control: max-age=0");
		header("Pragma: no-cache");
		
		
			
print '<?xml  version="1.0" encoding="windows-1251"?>
		<rss version="2.0">
		  <channel>
			<title>'.$_SERVER['HTTP_HOST'].'</title>
			<link>http://'.$_SERVER['HTTP_HOST'].'</link>
			<description>'.$name['name'].'</description>
			<language>ru</language>
			<pubDate>'.gmdate("D, d M Y H:i:s").'</pubDate>
			<lastBuildDate>'.gmdate("D, d M Y H:i:s").'</lastBuildDate>
			<docs>http://'.$_SERVER['HTTP_HOST'].'/blog/post/'.$_GET['id'].'.html</docs>
			<generator>ve</generator>
			<webMaster>webmaster@example.com</webMaster>';
		
		foreach ($comment as $k=>$v){
		
			echo "
			<item>
			  <title>".$v->name."</title>
			  <link>http://".$_SERVER["HTTP_HOST"]."/blog/post/".$_GET["id"].".html</link>
			  <description><![CDATA[".$v->data->descrfull."]]></description>
			</item>
			";
			
		
		}
	
		
echo "</channel>
</rss>";	
	}}


  