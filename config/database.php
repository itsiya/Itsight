<?php

class DATABASE_CONFIG {

	public function default_db(){ return array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'josiah2',
		'password' => 'siya8641',
		'database' => 'josiah2',
		'authtable' => 'users',
		'authid' => 'username',
		'authpassword' => 'password',
		'authkey' => 'key',
		'prefix' => '',
		//'encoding' => 'utf8',
	);
	}


}
