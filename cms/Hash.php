<?php namespace Cms;

use Cms\Exception\BaseException;

class Hash {
    
    
    
    public function make($value)
    {

        $hash = password_hash($value, PASSWORD_BCRYPT);

        if ($hash === false) {
            throw new BaseException('Bcrypt hashing not supported.');
        }

        return $hash;
    }
	
	
	public function check($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }
	

}