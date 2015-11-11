<?php
require_once("Connection.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") 
{
		$username = $_POST["username"];
		$psw = $_POST["psw"];
		$json;
		
		$Conn = new Connection();
		$Conn->connect();
		
		$query = "SELECT * FROM user WHERE username='".$username."' AND password='".$psw ."'";
		$result = mysql_query($query,$Conn->ConnectionID) or die(mysql_error());
		
		if (!$result) {
			$json = array("error" => "MySQL Query Error");
		} else {
			if (mysql_num_rows($result) == 0) {
				$json = array("error" => "Usuario no encontrado");
			} else {
			    $row = mysql_fetch_array($result);
				$user_id = $row["user_id"];
			    $username = $row["username"];
			    $password = $row["password"];
				
               session_start();
				$_SESSION['user'] = $username;
				
				$json = array( "id" => $user_id, "username" => $username, "password" => $password);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($json);
}
?>