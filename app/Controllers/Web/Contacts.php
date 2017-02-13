<?php namespace App\Controllers\Web;

use Swift_Message;
use Swift_Mailer;
use Swift_MailTransport;
use Cms\Controller;
use Cms\Request;
use Cms\Api\ContactsModel;

use Cms\Exception\NotFoundException;

class Contacts extends BaseController{
   
   public $errors = [];
    
	public function send(){
	    header("Content-Type: application/x-suggestions+json; charset=utf-8");
		
		$request = new Request();
		if($request -> isPost()){
		   
			$html = '';
		   
			$email = $request -> post('email');
			$name = $request -> post('name');
			$text = $request -> post('body');
			$remaller_check = $request -> post('remaller_check_email');
			
			
			if(!empty($remaller_check) || filter_var($remaller_check, FILTER_VALIDATE_EMAIL) === true){
				
				$fileOpen = fopen(CMS_DIR.'settings/spam_ip.txt', 'a');
				if ($fileOpen) {
					fwrite($fileOpen, $_SERVER['REMOTE_ADDR']."\r\n");
					fclose($fileOpen);
				}
				
				
				$this -> errors[] = __('form_error');
			}
			
		   
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			   $this -> errors[] = __('email_filed');
			}else{
				$html .= '<p>Email: "'.$email.'"</p>';
			}
			if(empty($name)){
			   $this -> errors[] =  __('name_filed');
			}else{
				$html .= '<p>Имя: "'.$name.'"</p>';
			}
			if(empty($text)){
			   $this -> errors[] =  __('text_filed');
			}else{
				$html .= '<p>Сообщение:<br /> "'.htmlentities($text).'"</p>';
			}

			
			$alt = "Имя: ".$name."| Email: ".$email."| Текст: ".htmlentities($text)."";
		   
			$body = '<html>
					<head>
						<meta charset="utf-8"/>
						<title>Сообщение через форму обратной связи ["'.$_SERVER['HTTP_HOST'].'"]: </title>
					</head> 
					<body>
						'.$html.'
					</body>
					</html>';
		   
		   
			$config = config('settings.settings');
		   
			$emails = [];
			$emails_cc = [];
			$emails_bcc = [];
			
			if(isset($config['email'])){
				$emails[] = $config['email'];
			}
			
			if(isset($config['emails'])){
				foreach($config['emails'] as $email){
					$emails[] = $email;
				}
			}
			
			if(isset($config['emails_cc'])){
				foreach($config['emails_cc'] as $email){
					$emails_cc[] = $email;
				}
			}
			
			if(isset($config['emails_bcc'])){
				foreach($config['emails_bcc'] as $email){
					$emails_bcc[] = $email;
				}
			}
		   
		   
			$message = Swift_Message::newInstance();
			$message -> setSubject("Сообщение через форму обратной связи [".$_SERVER['HTTP_HOST']."]: ");
			$message -> setFrom(array('admin@'.$_SERVER['HTTP_HOST'] => 'Admin'));
			$message -> setTo($emails);
			
			if(count($emails_cc)){
				$message->setCc($emails_cc);
			}
			if(count($emails_bcc)){
				$message->setBcc($emails_bcc);
			}
			
			$message -> setBody($body, 'text/html');
		    $message -> addPart($alt, 'text/plain');

			// Create the Transport
			$transport = Swift_MailTransport::newInstance();

			// Create the Mailer using your created Transport
			$mailer = Swift_Mailer::newInstance($transport);
			
			
			
			
			//$mail->addAddress('eg@freytakandsons.com', 'Admin');     // Add a recipient
			
							
			if(count($this -> errors) > 0){
				echo json_encode(
					['errors' => $this -> errors]
				);
				exit;
			}

			if(!$mailer->send($message)) {
				echo json_encode(['errors' => __('error_contact')]); exit;
			} else {
				echo json_encode(['success' => __('success')]);
				exit;
			}
		}
	}
	
	public function subscribe(){
	    header("Content-Type: application/x-suggestions+json; charset=utf-8");
		
		$request = new Request();
	   
		if($request -> isPost()){
		   
			$html = '';
		   
			$email = $request -> post('email');
			$name = $request -> post('name');
			$phone = $request -> post('phone');
			$url = $request -> post('url');
			
			
			$remaller_check = $request -> post('remaller_check');

			if(!empty($remaller_check)){
				
				$fileOpen = fopen(CMS_DIR.'settings/spam_ip.txt', 'a');
				if ($fileOpen) {
					fwrite($fileOpen, $_SERVER['REMOTE_ADDR']."\r\n");
					fclose($fileOpen);
				}
				
				$this -> errors[] = __('form_error');
			}
			
		   
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			   $this -> errors[] =  __('email_filed');
			}else{
				$html .= '<p>Email: "'.$email.'"</p>';
			}
			if(empty($name)){
			   $this -> errors[] =  __('name_filed');
			}else{
				$html .= '<p>Имя: "'.$name.'"</p>';
			}
			if(empty($phone)){
			   $this -> errors[] =  __('phone_filed');
			}else{
				$html .= '<p>Телефон:<br /> "'.$phone.'"</p>';
			}
			
			if(empty($url)){
			   $this -> errors[] = __('url_error');
			}else{
				$html .= '<p>Страница подписки:<br /> "'.$url.'"</p>';
			}

			
			$alt = "Имя: ".$name."| Email: ".$email."| телефон: ".$phone."| Страница подписки: ".$url."";
		   
			$body = '<html>
					<head>
						<meta charset="utf-8"/>
						<title>Подписка на правовые новости с сайта '.$_SERVER['HTTP_HOST'].' </title>
					</head> 
					<body>
						'.$html.'
					</body>
					</html>';
		   
		   
		   
		   
		     
			$config = config('settings.settings');
		   
			$emails = [];
			$emails_cc = [];
			$emails_bcc = [];
			
			if(isset($config['email'])){
				$emails[] = $config['email'];
			}
			
			if(isset($config['emails'])){
				foreach($config['emails'] as $email){
					$emails[] = $email;
				}
			}
			
			if(isset($config['emails_cc'])){
				foreach($config['emails_cc'] as $email){
					$emails_cc[] = $email;
				}
			}
			
			if(isset($config['emails_bcc'])){
				foreach($config['emails_bcc'] as $email){
					$emails_bcc[] = $email;
				}
			}
		   
			$message = Swift_Message::newInstance();

			$message -> setSubject("Подписка на правовые новости с сайта ".$_SERVER['HTTP_HOST']."");
			$message -> setFrom(array('admin@'.$_SERVER['HTTP_HOST'] => 'Admin'));
			$message -> setTo($emails);
			
			if(count($emails_cc)){
				$message->setCc($emails_cc);
			}
			if(count($emails_bcc)){
				$message->setBcc($emails_bcc);
			}
			$message -> setBody($body, 'text/html');
		    $message -> addPart($alt, 'text/plain');
			
			
			// Create the Transport
			$transport = Swift_MailTransport::newInstance();

			// Create the Mailer using your created Transport
			$mailer = Swift_Mailer::newInstance($transport);
			
			
			
			
			//$mail->addAddress('eg@freytakandsons.com', 'Admin');     // Add a recipient
			
							
			if(count($this -> errors) > 0){
				echo json_encode(
					['errors' => $this -> errors]
				);
				exit;
			}

			if(!$mailer->send($message)) {
				echo json_encode(['errors' => __('error_contact')]); exit;
			} else {
				echo json_encode(['success' => __('success')]);
				exit;
			}
		}
	}
	
	// Этот метод дублирует susbscribe для того, если захотим что-то поменять"
	public function events(){
	    header("Content-Type: application/x-suggestions+json; charset=utf-8");
		
		$request = new Request();
	   
		if($request -> isPost()){
		   
			$html = '';
		   
			$email = $request -> post('email');
			$name = $request -> post('name');
			$phone = $request -> post('phone');
			$url = $request -> post('url');
			
			
			$remaller_check = $request -> post('remaller_check');
			
			
			if(!empty($remaller_check)){
				
				$fileOpen = fopen(CMS_DIR.'settings/spam_ip.txt', 'a');
				if ($fileOpen) {
					fwrite($fileOpen, $_SERVER['REMOTE_ADDR']."\r\n");
					fclose($fileOpen);
				}
				
				$this -> errors[] = __('form_error');
			}
			
		   
			if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
			   $this -> errors[] = __('email_error');
			}else{
				$html .= '<p>Email: "'.$email.'"</p>';
			}
			if(empty($name)){
			   $this -> errors[] = __('name_filed');
			}else{
				$html .= '<p>Имя: "'.$name.'"</p>';
			}
			if(empty($phone)){
			   $this -> errors[] = __('phone_filed');
			}else{
				$html .= '<p>Телефон:<br /> "'.$phone.'"</p>';
			}
			
			if(empty($url)){
			   $this -> errors[] = __('url_error');
			}else{
				$html .= '<p>Страница подписки:<br /> "'.$url.'"</p>';
			}

			
			$alt = "Имя: ".$name."| Email: ".$email."| телефон: ".$phone."| Страница подписки: ".$url."";
		   
			$body = '<html>
					<head>
						<meta charset="utf-8"/>
						<title>Подписка на события с сайта '.$_SERVER['HTTP_HOST'].' </title>
					</head> 
					<body>
						'.$html.'
					</body>
					</html>';
		   
			$config = config('settings.settings');
		   
			$emails = [];
			$emails_cc = [];
			$emails_bcc = [];
			
			if(isset($config['email'])){
				$emails[] = $config['email'];
			}
			
			if(isset($config['emails'])){
				foreach($config['emails'] as $email){
					$emails[] = $email;
				}
			}
			
			if(isset($config['emails_cc'])){
				foreach($config['emails_cc'] as $email){
					$emails_cc[] = $email;
				}
			}
			
			if(isset($config['emails_bcc'])){
				foreach($config['emails_bcc'] as $email){
					$emails_bcc[] = $email;
				}
			}
		   
			$message = Swift_Message::newInstance();

			$message -> setSubject("Подписка события с сайта ".$_SERVER['HTTP_HOST']."");
			$message -> setFrom(array('admin@'.$_SERVER['HTTP_HOST'] => 'Admin'));
			$message -> setTo($emails);
			
			if(count($emails_cc)){
				$message->setCc($emails_cc);
			}
			if(count($emails_bcc)){
				$message->setBcc($emails_bcc);
			}
			$message -> setBody($body, 'text/html');
		    $message -> addPart($alt, 'text/plain');
			
			
			// Create the Transport
			$transport = Swift_MailTransport::newInstance();

			// Create the Mailer using your created Transport
			$mailer = Swift_Mailer::newInstance($transport);

			//$mail->addAddress('eg@freytakandsons.com', 'Admin');     // Add a recipient
			
							
			if(count($this -> errors) > 0){
				echo json_encode(
					['errors' => $this -> errors]
				);
				exit;
			}

			if(!$mailer->send($message)) {
				echo json_encode(['errors' => __('error_contact')]); exit;
			} else {
				echo json_encode(['success' => __('success')]);
				exit;
			}
		}
	}
	
	public function index(){
		$request = new Request();
		$id = $request -> segment(3);

		$sqlParams = [
			'menu_id' => $id
		];
		

		$contacts  = new ContactsModel();
		$result = $contacts -> getContactWeb($sqlParams);

		// Хлебные крошки
		$breadcrumbs = [];
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang')).'">'.__('home').'</a>';
		$breadcrumbs[] = '<a class="breadcrumbs__link" href="'.get_url(config('lang.weblang'),'contact', $result -> alias) .'">'.$result -> title.'</a>';

		$vars = [
			'title' 		=> $result -> title,
			'metaK' 		=> $result -> metaK,
			'metaD' 		=> $result -> metaD,
			'result'	 	=> $result,
			'breadcrumbs' 	=> $breadcrumbs,
			'segment' 		=> $request -> segment(1),
			
		];
		
		return $this -> view -> show('public/other/kontact_list', $vars);
	}
}