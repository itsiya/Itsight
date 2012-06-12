<?php
class ItsightHttpRequest {
    const METHOD_HEAD = 'HEAD';
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_OVERRIDE = '_METHOD';

	protected $env;

    public function __construct( $env ) {
        $this->env = $env;
    }

	public function getMethod() {
        return $this->env['REQUEST_METHOD'];
    }


    public function isGet() {
        return $this->getMethod() === self::METHOD_GET;
    }


    public function isPost() {
        return $this->getMethod() === self::METHOD_POST;
    }


    public function isPut() {
        return $this->getMethod() === self::METHOD_PUT;
    }


    public function isDelete() {
        return $this->getMethod() === self::METHOD_DELETE;
    }


    public function isHead() {
        return $this->getMethod() === self::METHOD_HEAD;
    }


    public function isOptions() {
        return $this->getMethod() === self::METHOD_OPTIONS;
    }


    public function isAjax() {
        if ( $this->params('isajax') ) {
            return true;
        } else if ( isset($this->env['HTTP_X_REQUESTED_WITH']) && $this->env['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' ) {
            return true;
        } else {
            return false;
        }
    }

    public function isXhr() {
        return $this->isAjax();
    }


	public function getCallClassName() {
		$className = "";
		foreach (explode("/",$this->env['PATH_INFO']) as $name )
		{
			if ($name != "") {
				$name[0] = strtoupper($name[0]);
				$className = $className . $name;
			}

		}
		return $className;
	}


	public function params( $key = null ) {
        $union = array_merge($this->get(), $this->post());
        if ( $key ) {
            if ( isset($union[$key]) ) {
                return $union[$key];
            } else {
                return null;
            }
        } else {
            return $union;
        }
    }

	public function get( $key = null ) {
        if ( !isset($this->env['query_hash']) ) {
            $output = array();
            if ( function_exists('mb_parse_str')) {
                mb_parse_str($this->env['QUERY_STRING'], $output);
            } else {
                parse_str($this->env['QUERY_STRING'], $output);
            }
            $this->env['query_hash'] = $output;
        }
        if ( $key ) {
            if ( isset($this->env['query_hash'][$key]) ) {
                return $this->env['query_hash'][$key];
            } else {
                return null;
            }
        } else {
            return $this->env['query_hash'];
        }
    }

	public function post( $key = null ) {
        if ( isset($_POST) ) {
            $this->env['form_hash'] = $_POST;
        }
        if ( $key ) {
            if ( isset($this->env['form_hash'][$key]) ) {
                return $this->env['form_hash'][$key];
            } else {
                return null;
            }
        } else {
            return $this->env['form_hash'];
        }
    }

}
?>