<?php
class ItsightAuthModel {

	public $dbcon;

	public $config;

	public function __construct($db_config) {
		$this->config = $db_config;
		$this->dbcon = mysql_connect($db_config['host'],$db_config['login'],$db_config['password']);
		mysql_select_db($db_config['database'],$this->dbcon);

	}


	public function cheackUserLogin($user_id,$user_pw) {
		$query = 
			"select * ".
			" from ".$this->config['authtable'].
			" where ".$this->config['authtable'].".".$this->config['authid']." = "."'$user_id'".
			" and ".$this->config['authtable'].".".$this->config['authpassword']." = "."'$user_pw'".
			""; 

		$result = mysql_query($query); 

		while($row = mysql_fetch_assoc($result))
		{
			return $row;
		}

		return false ;


	}
	public function setKey($user_id,$key) {
		$query = 
			"UPDATE ".$this->config['authtable'].
			" SET ".$this->config['authtable'].".".$this->config['authkey']." = " ."'$key'".
			" WHERE ".$this->config['authtable'].".".$this->config['authid']." = "."'$user_id'"."LIMIT 1 ".
			""; 
		//echo $query;
		$result = mysql_query($query); 
		
		if (!$result) {
			echo "Could not successfully run query ($query) from DB: " . mysql_error();
			return false;
		}else {
			return true ;
		}
	}
	public function checkUserKey($key) {
		$query = 
			"SELECT *".
			" FROM ".$this->config['authtable'].
			" WHERE ".$this->config['authtable'].".".$this->config['authkey']." = '$key'".
			" LIMIT 0,30"; 
		$result = mysql_query($query); 

		while($row = mysql_fetch_assoc($result))
		{
			$data['User'] = $row;
			return $data;
		}

		return null ;


	}

	public function fieldslist($table) {
		$query = "select * from ".$table;
		$result = mysql_query($query); 
	}

}

?>