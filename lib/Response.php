<?php
class ItsightHttpResponse implements ArrayAccess, Countable, IteratorAggregate {
    protected $status;


    protected $header;


    protected $body;


    protected $length;


    protected static $messages = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );


    public function __construct( $body = '', $status = 200, $header = array() ) {
        $this->status = (int)$status;
        $headers = array();
        foreach ( $header as $key => $value ) {
            $headers[$key] = $value;
        }
        $this->header = new ItsightHttpHeaders(array_merge(array('Content-Type' => 'text/html'), $headers));
        $this->body = '';
        $this->write($body);
    }


    public function status( $status = null ) {
        if ( !is_null($status) ) {
            $this->status = (int)$status;
        }
        return $this->status;
    }


    public function header( $name, $value = null ) {
        if ( !is_null($value) ) {
            $this[$name] = $value;
        }
        return $this[$name];
    }


    public function headers() {
        return $this->header;
    }


    public function body( $body = null ) {
        if ( !is_null($body) ) {
            $this->write($body, true);
        }
        return $this->body;
    }


    public function length( $length = null ) {
        if ( !is_null($length) ) {
            $this->length = (int)$length;
        }
        return $this->length;
    }


    public function write( $body, $replace = false ) {
        if ( $replace ) {
            $this->body = $body;
        } else {
            $this->body .= (string)$body;
        }
        $this->length = strlen($this->body);
        return $this->body;
    }


    public function finalize() {
		//echo $this->status;
        if ( in_array($this->status, array(204,304)) ) {
            unset($this->header['Content-Type'], $this->header['Content-Length']);
			//echo $this['Content-Type'];
			//print_r($this->header);
            return array($this->status, $this->header, '');
        } else {
            return array($this->status, $this->header, $this->body);
        }
    }


    public function redirect ( $url, $status = 302 ) {
        $this->status = $status;
        $this['Location'] = $url;
    }


    public function isEmpty() {
        return in_array($this->status, array(201, 204, 304));
    }


    public function isInformational() {
        return $this->status >= 100 && $this->status < 200;
    }


    public function isOk() {
        return $this->status === 200;
    }


    public function isSuccessful() {
        return $this->status >= 200 && $this->status < 300;
    }


    public function isRedirect() {
        return in_array($this->status, array(301, 302, 303, 307));
    }


    public function isRedirection() {
        return $this->status >= 300 && $this->status < 400;
    }


    public function isForbidden() {
        return $this->status === 403;
    }


    public function isNotFound() {
        return $this->status === 404;
    }


    public function isClientError() {
        return $this->status >= 400 && $this->status < 500;
    }


    public function isServerError() {
        return $this->status >= 500 && $this->status < 600;
    }

    public function offsetExists( $offset ) {
        return isset($this->header[$offset]);
    }


    public function offsetGet( $offset ) {
        if ( isset($this->header[$offset]) ) {
            return $this->header[$offset];
        } else {
            return null;
        }
    }

    public function offsetSet( $offset, $value ) {
        $this->header[$offset] = $value;
    }


    public function offsetUnset( $offset ) {
        unset($this->header[$offset]);
    }


    public function count() {
        return count($this->header);
    }


    public function getIterator() {
        return $this->header;
    }


    public static function getMessageForCode( $status ) {
        if ( isset(self::$messages[$status]) ) {
            return self::$messages[$status];
        } else {
            return null;
        }
    }
}
?>