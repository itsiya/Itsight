<?php

class UserLogin extends AppAPI{
	public $uses = array('Auth');

	public function beforeFilter() {
		parent::beforeFilter();
		//$this->controller->halt(404,'notfound');
		//$this->controller->stop();
		//echo"no";;
	}

	public function get() { 
		$this->setViewData('b',array('a'=>1,'b'=>2));
		$this->setViewData('a',array('a'=>1,'b'=>2));
		$this->setXmlTemplet('xml.php');
		//$this->render('xml.php','xml');
		//$this->controller->halt(404);
		//$this->controller->redirect('http://josiah2.cafe24.com/test/');
		//$this->render("UserLogin::get() was called", "json"); 
	}

	public function post() { 
		$this->setViewData('user_login_id',$this->User['User']['username']);													
		$this->setViewData('a',array('a'=>1,'b'=>2));
		$this->setXmlTemplet('xml.php');
		//$this->controller->halt(404);
		//$this->controller->redirect('http://josiah2.cafe24.com/test/');
		//$this->render("UserLogin::get() was called", "json"); 
	}
}

?>