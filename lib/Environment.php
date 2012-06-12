<?php
class ItsightEnvironment implements ArrayAccess, IteratorAggregate {

    protected $properties;

    protected static $environment;

    public static function getInstance( $refresh = false ) {
        if ( is_null(self::$environment) || $refresh ) {
            self::$environment = new self();
        }
        return self::$environment;
    }

    private function __construct() {
		$env = array();

		//The HTTP request method
		$env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];

		//The IP
		$env['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];

		// Application paths

		if ( strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) === 0 ) {
			$env['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME']; //Without URL rewrite
		} else {
			$env['SCRIPT_NAME'] = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']) ); //With URL rewrite
		}
		$env['PATH_INFO'] = substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($env['SCRIPT_NAME']));
		if ( strpos($env['PATH_INFO'], '?') !== false ) {
			$env['PATH_INFO'] = substr_replace($env['PATH_INFO'], '', strpos($env['PATH_INFO'], '?')); //query string is not removed automatically
		}
		$env['SCRIPT_NAME'] = rtrim($env['SCRIPT_NAME'], '/');
		$env['PATH_INFO'] = '/' . ltrim($env['PATH_INFO'], '/');
		$tmp = explode('.',$env['PATH_INFO']);
		$env['PATH_INFO'] =  $tmp[0];
		//TDDO error ditect
		$env['PATH_INFO_TYPE'] = $tmp[1] != '' ? $tmp[1] : 'json';
 
		$env['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';

		$env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];

		$env['SERVER_PORT'] = $_SERVER['SERVER_PORT'];
		
		$specialHeaders = array('CONTENT_TYPE', 'CONTENT_LENGTH', 'PHP_AUTH_USER', 'PHP_AUTH_PW', 'PHP_AUTH_DIGEST', 'AUTH_TYPE');
		foreach ( $_SERVER as $key => $value ) {
			$value = is_string($value) ? trim($value) : $value;
			$env[$key] = $value;
		}
		
		$env['url_scheme'] = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';
		$env['errors'] = fopen('php://stderr', 'w');

		$this->properties = $env;
	}

    public function offsetExists( $offset ) {
        return isset($this->properties[$offset]);
    }


    public function offsetGet( $offset ) {
        if ( isset($this->properties[$offset]) ) {
            return $this->properties[$offset];
        } else {
            return null;
        }
    }


    public function offsetSet( $offset, $value ) {
        $this->properties[$offset] = $value;
    }


    public function offsetUnset( $offset ) {
        unset($this->properties[$offset]);
    }


    public function getIterator() {
        return new ArrayIterator($this->properties);
    }

}
?>