<?php
function getArticle($params) {
    $id=intval($params['id']);
    
    if(!$id) return false;
    
    if(!class_exists('Articles'))
    	include_once(CMS_DIR.'class/Class.Articles.php');
	$mainTbl=PREFIX.'_articles';
	$articles=new Articles($mainTbl);
	$articles->mainTbl=$mainTbl;
	$articles->setMode('user');
	$l=$articles->getById($id);
	//$smarty->assign('article',$l);
	
	return $l->descrfull;
}