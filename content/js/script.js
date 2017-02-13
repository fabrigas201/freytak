
function highligthrow(obj,flag){
	if(flag==1){
		objBg=obj.style.background;
		obj.style.background='#eeeeee';
	}else{
		obj.style.background=(objBg?objBg:'white');
		//obj.style.background='white';
	}
	
	return false;
}

function UrlTranslit(str){
	var rusChars=new Array(' ','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','\я');
	var transChars=new Array('_','A','B','V','G','D','E','YO','ZH','Z','I','J','K','L','M','N','O','P','R','S','T','U','F','H','C','CH','SH','SHC','','Y','','E','YU','YA','a','b','v','g','d','e','yo','zh','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','shc','','y','','e','yu','ya');
	var allowChars=new Array('_','A','B','C','D','E','F','G','H','J','K','H','L','M','N','O','P','R','S','T','U','V','W','Q','X','Y','Z','I','1','2','3','4','5','6','7','8','9','0');
	
	var transStr=new String();
	var len=str.length;
	var character,isRus;
	for(i=0; i<len; i++){
		character=str.charAt(i,1);
		isRus=false;
		for(j=0; j<rusChars.length; j++){
			if(character==rusChars[j]){
				isRus=true;
				break;
			}
		}
		
		if(isRus){
			transStr+=transChars[j];
		}else{
			character=character.toLowerCase();
			for(j=0; j<allowChars.length; j++){
				if(character==allowChars[j]){
					transStr+=allowChars[j];
					break;
				}
			}
		}
		
		//transStr+=(isRus)?transChars[j]:character;
	}
	
	return transStr;
}

function setAlias(str){
	var obj=document.getElementById('alias');
	if(obj){
		if(!obj.value){
			obj.value=UrlTranslit(str);			
		}
	}
}
