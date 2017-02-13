<?php namespace Cms\Libs;


class Wysiwyg{
	
	public $textAreaName;
	public $textAreaValue;
	public $wysiwygUse;
	public $mod;

	public function __construct(){
		$this -> wysiwygUse = 'tinymce';
	}
	
	
	public function setField($fieldName,$fieldValue = false,$mod = 'full'){
		$this -> textAreaName  = $fieldName;
		$this -> textAreaValue = $fieldValue;
		$this -> mod           = $mod;
	}
	

	public function CreateHtml(){

		if($this -> wysiwygUse=='tinymce')
			$tmp = new TinyEditor($this -> textAreaName,$this -> textAreaValue);
		elseif($this -> wysiwygUse=='ckeditor')
			$tmp = new FCKeditor($this -> textAreaName,$this -> textAreaValue);
		else
			$tmp=new ElrteEditor($this -> textAreaName,$this -> textAreaValue);

		$tmp->mod=$this->mod;
		$tmp->Height=($this->mod=='full'?'400':'100');

		return $tmp->CreateHtml();
	}
	public function getScript(){
		if($this -> wysiwygUse=='tinymce')
			$tmp = new TinyEditor(false,false);

		return $tmp->getScript();
	}
}

class TinyEditor{
	public $textAreaName;
	public $textAreaValue;
	public $BasePath = '';
	public $finderPath = '';
	private static $initComplete;

	function __construct($textAreaName,$textAreaValue=false){
		$this->BasePath = WYSIWYG_PATH.'tiny_mce/';
		//$this->finderPath = '/admin/'.WYSIWYG_PATH.'elfinder-1.2/';
		$this->textAreaName=$textAreaName;
		$this->textAreaValue=$textAreaValue;
		//$this->initialized=false;
	}
	
	function getScript(){
		self::$initComplete=true;
		$content='<script type="text/javascript" src="'.$this->BasePath.'tiny_mce.js"></script>';
		return $content;
	}
	
	function CreateHtml(){
		$textAreaHTML='';
		if(empty(self::$initComplete)){
			self::$initComplete=true;
			$textAreaHTML.='<script type="text/javascript" src="'.$this->BasePath.'tiny_mce.js"></script>';
		}
		$textAreaHTML.='
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		plugins : "imagemanager,filemanager,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
		language : "ru",
		elements : "'.$this->textAreaName.'",

		//Cleanup/Output
		apply_source_formatting : true,
		convert_urls : false,
		//convert_newlines_to_brs : true,
		force_br_newlines : true,
		force_p_newlines : false,
		forced_root_block : \'p\',
		element_format : "html",

		// Skin options
		theme : "advanced",
		skin : "o2k7",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",'.
		($this->mod=='full'?
			'theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",':
			'theme_advanced_buttons2 : "bullist,numlist,|,link,unlink,anchor,image,cleanup,code,|,preview,|,forecolor,backcolor,'.
			'|,sub,sup,|,charmap,iespell,",'
		).
		'theme_advanced_buttons3 : '.($this->mod=='full'?'"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",':'"",').
		'theme_advanced_buttons4 : '.($this->mod=='full'?'"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",':'"",').
		'theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		//content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		//template_external_list_url : "lists/template_list.js",
		//external_link_list_url : "lists/link_list.js",
		//external_image_list_url : "lists/image_list.js",
		//media_external_list_url : "lists/media_list.js",


		// Style formats
		style_formats : [
			{title : \'Bold text\', inline : \'b\'},
			{title : \'Red text\', inline : \'span\', styles : {color : \'#ff0000\'}},
			{title : \'Red header\', block : \'h1\', styles : {color : \'#ff0000\'}},
			{title : \'Example 1\', inline : \'span\', classes : \'example1\'},
			{title : \'Example 2\', inline : \'span\', classes : \'example2\'},
			{title : \'Table styles\'},
			{title : \'Table row 1\', selector : \'tr\', classes : \'tablerow1\'}
		],

		// Replace values for the template plugin
		/*template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}*/
	});
</script>'.
//'<textarea id="'.$this->textAreaName.'" name="'.$this->textAreaName.'" style="width:100%;height:'.$this->Height.'px">'.$this->textAreaValue.'</textarea>'
'<div id="'.$this->textAreaName.'" style="width:100%;height:'.$this->Height.'px">'.$this->textAreaValue.'</div>';

		return $textAreaHTML;
	}
}

class ElrteEditor{
	var $textAreaName;
	var $textAreaValue;
	var $BasePath = '';
	var $finderPath = '';

	function __construct($textAreaName,$textAreaValue=false){
		$this->BasePath = '/admin/'.WYSIWYG_PATH.'elrte-1.3/';
		$this->finderPath = '/admin/'.WYSIWYG_PATH.'elfinder-1.2/';
		$this->textAreaName=$textAreaName;
		$this->textAreaValue=$textAreaValue;
		//$this->initialized=false;
	}
	function CreateHtml(){
		static $initComplete;

		$textAreaHTML='';
		//if($this->initialized==false){
		if(empty($initComplete)){
			$initComplete=true;
			$textAreaHTML.='
			<link rel="stylesheet" href="'.$this->BasePath.'css/smoothness/jquery-ui-1.8.13.custom.css" type="text/css" media="screen" charset="utf-8">
			<script src="'.$this->BasePath.'js/jquery-1.6.1.min.js" type="text/javascript" charset="utf-8"></script>
			<script src="'.$this->BasePath.'js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" charset="utf-8"></script>

			<link rel="stylesheet" href="'.$this->BasePath.'css/elrte.min.css" type="text/css" media="screen" charset="utf-8">
			<script src="'.$this->BasePath.'js/elrte.min.js" type="text/javascript" charset="utf-8"></script>
			<script src="'.$this->BasePath.'js/i18n/elrte.ru.js" type="text/javascript" charset="utf-8"></script>

			<link rel="stylesheet" href="'.$this->finderPath.'css/elfinder.css" type="text/css" media="screen" title="no title" charset="utf-8">
			<script src="'.$this->finderPath.'js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
			<script src="'.$this->finderPath.'js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>';
		}

		$textAreaHTML.='
<script type="text/javascript" charset="utf-8">
	$().ready(function() {
		var opts = {
			lang         : \'ru\',
			styleWithCSS : false,
			height       : '.$this->Height.',
			absoluteURLs : false,
			toolbar      : \'maxi\',

			fmOpen : function(callback) {
				$(\'<div id="elfinder" />\').elfinder({
					url : \''.$this->finderPath.'connectors/php/connector.php\',
					lang : \'ru\',
					dialog : { width : 900, modal : true, title : \'Files\' }, // открываем в диалоговом окне
					closeOnEditorCallback : true, // закрываем после выбора файла
					editorCallback : callback // передаем callback файловому менеджеру
				})
			}
		};

		//create editor
		$(\'#'.$this->textAreaName.'\').elrte(opts);
	});
</script>
<div id="'.$this->textAreaName.'">'.$this->textAreaValue.'</div>';

		return $textAreaHTML;
	}
}

if(class_exists('CKEditor')){
	class FCKeditor extends CKEditor{
		var $textAreaName;
		var $textAreaValue;
		var $BasePath = '';
		var $returnOutput=true;
		var $finderPath = '';

		function __construct($textAreaName,$textAreaValue=false){
			$this->BasePath = '/admin/'.WYSIWYG_PATH.'ckeditor/';
			$this->finderPath = '/admin/'.WYSIWYG_PATH.'ckfinder/';
			$this->textAreaName=$textAreaName;
			$this->textAreaValue=$textAreaValue;
			$this->initialized=false;

			$this->config['dialog_backgroundCoverColor'] =  'gray';
			//$this->config['removePlugins'] =  'forms';
			//$this->config['plugins'] =  'image';
			//$this->config['extraPlugins'] =  'toolbar';
			//$this->config['skin'] =  'office2003';
			//$this->config['startupMode'] = 'source';
			$this->config['tabSpaces'] = 4;


			$this->config['filebrowserBrowseUrl'] = $this->finderPath.'ckfinder.html';
			$this->config['filebrowserImageBrowseUrl'] = $this->finderPath.'ckfinder.html?type=Images';
			$this->config['filebrowserFlashBrowseUrl'] = $this->finderPath.'ckfinder.html?type=Flash';
			$this->config['filebrowserUploadUrl'] = $this->finderPath.'core/connector/php/connector.php?command=QuickUpload&type=Files';
			$this->config['filebrowserImageUploadUrl'] = $this->finderPath.'core/connector/php/connector.php?command=QuickUpload&type=Images';
			$this->config['filebrowserFlashUploadUrl'] = $this->finderPath.'core/connector/php/connector.php?command=QuickUpload&type=Flash';
		}
		function CreateHtml(){
			$this->initialized=false;
			return $this->editor($this->textAreaName,$this->textAreaValue);
		}
	}
}