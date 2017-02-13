<?php namespace Cms\Modules;

use Cms\Modules;
use Cms\Request;

class Settings extends Modules{
	
	public $errors = [];
	
	
	public function index(){
		
		$result = [];
		$settings = [];
		
		$request = new Request();

		if($request -> isPost() && $request -> post('save') == '1'){
			$title = $request -> post('title');
			$description = $request -> post('description');
			$keywords = $request -> post('keywords');
			$email = $request -> post('email');
			$emails = $request -> post('emails');
			$emails_cc = $request -> post('emails_cc');
			$emails_bcc = $request -> post('emails_bcc');
			
			if(!empty($email)){
				$settings['email'] =  $email;
			}else{
				$this -> errors[] = 'Поле Email обязательно для заполнения';
			}
			
			if(!empty($emails)){
				$settings['emails'] =  text_to_array($emails);
			}else{
				$this -> errors[] = 'Поле Постовые ящики не должно быть пустым';
			}

			if(!empty($emails_cc)){
				$settings['emails_cc'] =  text_to_array($emails_cc);
			}
			if(!empty($emails_bcc)){
				$settings['emails_bcc'] =  text_to_array($emails_bcc);
			}
			if(!empty($title)){
				$settings['title'] =  $title;
			}else{
				$this -> errors['title'] = 'Поле Название сайта не должно быть пустым';
			}
			if(!empty($description)){
				$settings['description'] =  $description;
			}
			if(!empty($keywords)){
				$settings['keywords'] = $keywords;
			}
			
			if(empty($this -> errors)){
				$result['settings'] = $settings;
			
			
				$arr = "<?php\n".'return '.var_export($result, true)."\n;";

				$fileOpen = fopen(CMS_DIR.'settings/settings.php', 'w');
				if ($fileOpen) {
					fwrite($fileOpen, $arr);
					fclose($fileOpen);
				}
				redirect('admin/?mod=settings&m=2');
			}
		}
		
		$config = config('settings.settings');
		
		$vars = [];
		
		if(isset($config['title'])){
			$vars['title'] = $config['title'];
		}else{
			$vars['title'] = null;
		}
		
		if(isset($config['description'])){
			$vars['description'] = $config['description'];
		}else{
			$vars['description'] = null;
		}
		
		if(isset($config['keywords'])){
			$vars['keywords'] = $config['keywords'];
		}else{
			$vars['keywords'] = null;
		}
		
		
		if(isset($config['email'])){
			$vars['email'] = $config['email'];
		}else{
			$vars['email'] = null;
		}

		if(isset($config['emails'])){
			$vars['emails'] = array_to_text($config['emails']);
		}else{
			$vars['emails'] = null;
		}
		if(isset($config['emails_cc'])){
			$vars['emails_cc'] = array_to_text($config['emails_cc']);
		}else{
			$vars['emails_cc'] = null;
		}
		if(isset($config['emails_bcc'])){
			$vars['emails_bcc'] = array_to_text($config['emails_bcc']);
		}else{
			$vars['emails_bcc'] = null;
		}

		
		$data = [
			'title' => 'Настройки',
			'errors' => $this -> errors,
			'vars' => $vars
		];
		
		return $this -> view -> show('admin/settings/addEdit', $data);
	}
	
}