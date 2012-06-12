<?php
class ItsightAuth {

	public $AuthModel ;
	public $AuthPassword ;

	public $test_user ;

	public function __construct($config) {
		$this->AuthModel = new ItsightAuthModel($config);
		$this->test_user = array(
			'test'=>array('User'=>array('id'=>'test_id')),
			'test2'=>array('User'=>array('id'=>'test_id2'))
			);
	}
	
	public function generateKey($user_id,$user_agent) {
		$this->test_user[''.sha1($user_id.$user_agent.'siyatest')] = array('User'=>array('id'=>$user_id));
		return sha1($user_id.$user_agent.'siyatest');
	}

	public function checkUserKey($key) {
		return $this->AuthModel->checkUserKey($key);
		//TODO :cheack client agent info

		//echo $test_user[$key];
		//return $this->test_user[$key];
	}
}
?>