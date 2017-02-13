<?php namespace Cms;

//use \Smarty\SmartyBC;
use Cms\Exception\BaseException;
use Cms\Hash;
class Auth extends Controller{

	private $user_id;


	public function login($login, $password){
		
		$hash = new Hash;
		
		//echo $hash -> make('12345671234567');
		
		$result = $this->db->query("SELECT `uid`, `uname`, `pass`, `status` FROM " . PREFIX . "_user WHERE uname = '" . $login . "'  AND status = '1'");

		if($result -> numRows > 0){
			
			if($hash -> check($password, $result -> row -> pass) == true){
				$_SESSION['user_id'] = $result -> row -> uid;
				$_SESSION['uname'] = $result -> row -> uname;
				$this -> user_id = $result -> row -> uid;
				return true;
			}
		}
		return false;
		
	}
	
	public function isLogged(){
		if(isset($_SESSION['user_id'])){
			return true;
		}
		return false;
	}

	public function logout(){
		unset($_SESSION['user_id']);
		$this -> user_id = '';
	}

}