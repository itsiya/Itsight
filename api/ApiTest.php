<?php
class ApiTest extends AppAPI {
	
	public $uses = array('Auth');
	public function get(){
		$this->setViewData('root_name','root');
		$this->setViewData('key1','test_data');
		$this->setViewData('key2',array('data1'=>1,'data2'=>2));
	}
	public function post(){
	}
	public function put(){
	}
	public function delete(){
	}

	public function authCheckFail() {
		$this->setViewData('result','fail');

	}
}
?>