<?php namespace Cms\Exception;

use Exception;


class BaseException extends Exception
{
    public function __construct($ex) {
       set_exception_handler(array($this, 'exception_handler'));
       throw new Exception($ex);
	}

	public function exception_handler($e) {

		echo '<style>body{background:#333333; color:#ffffff;} h1,h2,h3,p,td {font-family:Verdana; font-weight:lighter;}</style>';
		echo '<h1>REMALLER CMS - Base '.get_class($e).'</h1>';
		echo '<h2>Description - '.$e->getMessage().'</h2>';
		echo '<h2>Path</h2>';
		echo '<p>Exception thrown on line <code>'
		. $e->getLine() . '</code> in <code>'
		. $e->getFile() . '</code></p>';

		echo '<h2>Stack trace</h2>';
		$traces = $e->getTrace();
		if (count($traces) > 1) {
			echo '<pre style="font-family:Verdana; line-height: 20px; border-bottom:1px solid #ccc;">';

			$level = 0;
			foreach (array_reverse($traces) as $trace) {
				++$level;

				if (isset($trace['class'])) echo $trace['class'].'&rarr;';

				$args = array();
				if ( ! empty($trace['args'])) {
					foreach ($trace['args'] as $arg) {
						if (is_null($arg)) $args[] = 'null';
						else if (is_array($arg)) $args[] = 'array['.sizeof($arg).']';
							else if (is_object($arg)) $args[] = get_class($arg).' Object';
								else if (is_bool($arg)) $args[] = $arg ? 'true' : 'false';
									else if (is_int($arg)) $args[] = $arg;
										else {
											$arg = htmlspecialchars(substr($arg, 0, 64));
											if (strlen($arg) >= 64) $arg .= '...';
											$args[] = "'". $arg ."'";
										}
					}
				}
				echo '<strong>'.$trace['function'].'</strong>('.implode(', ',$args).')  ';
				echo 'on line <code>'.(isset($trace['line']) ? $trace['line'] : 'unknown').'</code> ';
				echo 'in <code>'.(isset($trace['file']) ? $trace['file'] : 'unknown')."</code>\n";
				echo str_repeat("   ", $level);
			}
			echo '</pre>';
		}
	}
}
