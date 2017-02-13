<?php namespace Cms;

use Cms\Exception\ResponseException;

class Response{
    
	public $output;
	public $statusCode = 200;
	public $statusMessage;
	public $headers = [];
    
	
	// http://stackoverflow.com/questions/3913960/predefined-array-of-http-errors-for-php-use
	public $httpCodes = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended'
	);
	
	
    public function __construct($output, $statusCode = 200, $headers = []) {
		
		$this -> setOutput($output);
		$this -> setStatusCode($statusCode);

		foreach($headers as $name => $value) {
			$this->headers[strtolower($name)] = $value;
		}
	}
	
	public static function create($content = '', $statusCode = 200, $headers = [])
    {
        return new static($content, $statusCode, $headers);
    }
	
    
	public function setOutput($output)
    {
        if (!is_string($output)) {
            throw new ResponseException('Response output content must be a string.');
        }
        $this -> output = $output;
        return $this;
    }
	
	public function setStatusCode($statusCode, $message = '')
    {
        if (empty($message)) {
            if (!isset($this -> httpStatuses[$statusCode])) {
                throw new ResponseException('Invalid status code provided: "' . $statusCode . '".');
            } else {
                $this -> statusMessage = $this -> httpStatuses[$statusCode];
            }
        } else {
            $this -> statusMessage = $message;
        }
        $this -> statusCode = $statusCode;
        return $this;
    }
	
	public function sendContent()
    {
        echo $this->output;
        return $this;
    }

	
	

}