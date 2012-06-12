<?php
class AppAPI extends ItsightAPI {

	public function beforeFilter() {
		parent::beforeFilter();
		//echo "Siya::beforeFilter() was called";
	}

	public function afterFilter() {
		parent::afterFilter();
		//echo "Siya::beforeFilter() was called";
	}
	
	public function beforeRender() {
		parent::beforeRender();
		//echo "Siya::beforeFilter() was called";
	}
	public function afterRender() {
		parent::afterRender();
		//echo "Siya::beforeFilter() was called";
	}

}

?>