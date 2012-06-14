<?php
class ItsightAPI {
	protected $controller;
	
	public $uses = array();

	public $data = null;

	public $User = null;

	public $Auth = null;

	public function __construct($itsight_controller){
		$this->controller = $itsight_controller;
	}

	public function beforeAuthCheck() {
		//echo "Siya::beforeFilter() was called";
	}

	public function authCheckSuccess() {
		//echo "Siya::beforeFilter() was called";
	}
	public function authCheckFail() {
		//echo "Siya::beforeFilter() was called";
	}

	public function beforeFilter() {
		//echo "Siya::beforeFilter() was called";
	}

	public function afterFilter() {
		//echo "Siya::beforeFilter() was called";
	}
	
	public function beforeRender() {
		//echo "Siya::beforeFilter() was called";
	}
	public function afterRender() {
		//echo "Siya::beforeFilter() was called";
	}



	public function setViewData() {
		$args = func_get_args();
		//print_r($args[0]);
		$this->controller->setViewData($args);
	}

	public function setXmlTemplet($templet=null) {
		$this->controller->setTemplet($templet);
	}

	public function render($data,$templet=null,$option='json') {
		$this->controller->render($data,$templet,$option);
	}

/*
	public function get() {
	}

	public function post() {
	}

	public function put() {
	}

	public function delete() {
	}
	
	public function head() {
	}

	public function option() {
	}

	public function call(){
	}
*/
}

?>