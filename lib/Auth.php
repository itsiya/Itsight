<?php
class ItsightAuth {

	public $AuthModel ;
	public $AuthPassword ;

	public function __construct($config) {
		$this->AuthModel = new ItsightAuthModel($config);

	}
	
	public function  cheackUserLogin($user_id,$user_pw) {
		return $this->AuthModel->cheackUserLogin($user_id,$user_pw);
	}

	public function generateKey($user_id,$user_agent) {

		$key = sha1($user_id.$user_agent.'siyatest');
		if (! $this->AuthModel->setKey($user_id,$key))
			return false;

		return $key;
	}

	public function checkUserKey($key) {
		return $this->AuthModel->checkUserKey($key);
		//TODO :cheack client agent info

		//echo $test_user[$key];
		//return $this->test_user[$key];
	}
}
?>