<?php
class Connection
{
 	public $Host = "www.onikom.com.mx";
	public $Port = "3306";
	public $Database = "webSiteSantander";
	public $UserName = "adminSantander";
 	public $Password = "onimovil8";
	public $ConnectionID;
	public $Cdb;
	public $Error = "No error";

	public function connect()
	{
		$this->Error = "Sin error";
		$this->ConnectionID = mysql_connect($this->Host,$this->UserName,$this->Password);
		$this->Cdb = mysql_select_db($this->Database,$this->ConnectionID);
	}
}
?>