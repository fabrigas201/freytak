<?php namespace Cms;

use FilesystemIterator;

class Init{
	
	public static function func(){

		$fi = new FilesystemIterator(CMS_DIR . 'func', FilesystemIterator::SKIP_DOTS);

		foreach($fi as $file) {
			$ext = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

			if($file->isFile() and $file->isReadable() and '.' . $ext == '.php') {
				require_once $file->getPathname();
			}
		}
		
	}
}