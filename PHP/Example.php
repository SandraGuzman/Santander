<?php
require_once("Connection.php");
        header('Content-Type: text/html; charset=utf-8');
        
        $title   = $_GET["titleFile"];
        $file_name = $_GET["filename"];
        
        echo "TITULO:  ".$title." NOMBRE: ".$file_name;
        
        $Conn = new Connection();
        $Conn->connect();
        $query = "INSERT INTO uploadFiles (title,filename)
				VALUES ('" . $title . "','" . $file_name . "')";
                
        $result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
        if (!$result) {
            echo "MySQL Query Error";
        } else {
        	echo "Insert exitoso";
        }
?>
