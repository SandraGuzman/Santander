<?php
require_once("Connection.php");

$uri      = explode("/", $_SERVER["REQUEST_URI"]);
$resource = end($uri);

if (strcmp($resource, 'uploadFile') == 0) {
    if ($_FILES["fileField"]) {
        $json = array();
        
        $file_name = $_FILES["fileField"]["name"];
        $file_size = $_FILES["fileField"]["size"];
        $file_tmp  = $_FILES["fileField"]["tmp_name"];
        $file_type = $_FILES["fileField"]["type"];
        $file_ext  = strtolower(end(explode('.', $file_name)));
        
        $title   = $_POST["titleFile"];
        $year    = $_POST["yearFile"];
        $quarter = $_POST["quarterFile"];
        
        $target_dir  = "../Uploads/";
        $target_file = $target_dir . $title . '.' . $file_ext;
        
        $expensions = array(
            "jpeg",
            "jpg",
            "png",
            "pdf"
        );
        
        if (in_array($file_ext, $expensions) === false) {
            $json = array(
                "error" => "extension not allowed, please choose a JPEG, PNG or PDF file."
            );
        }
        
        if (file_exists($target_file)) {
            $json = array(
                "error" => "Sorry, file already exists."
            );
        }
        
        if (empty($errors) == true) {
            if (move_uploaded_file($file_tmp, $target_file)) {
                $Conn = new Connection();
                $Conn->connect();
                $query = "INSERT INTO uploadFiles (filename, fileType,publicationDate,year,quarterly)
							VALUES ('" . $title . "','" . $file_ext . "','" . date("Y/m/d") . "','" . $year . "','" . $quarter . "' )";
                
                $result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
                $file_id = mysql_insert_id();
                if (!$result) {
                    $json = array(
                        "error" => "MySQL Query Error"
                    );
                } else {
					  $json = array( "file_id" => $file_id, "filename" => $title, "fileType" => $file_ext,
					                 "publicationDate" => date("Y/m/d"), "year" => $year, "quarterly" => $quarter);
                }
            } else {
                $json = array(
                    "error" => "Sorry, there was an error uploading your file."
                );
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($json);
        
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            "error" => "File not found"
        ));
    }
} else if (strcmp($resource, 'getFiles') == 0) {
    $json = array();
    $Conn = new Connection();
    $Conn->connect();
    
    $query = "SELECT * FROM uploadFiles";
    $result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
    
    if (!$result) {
        $json = array(
            "error" => "MySQL Query Error"
        );
    } else {
        while ($row = mysql_fetch_array($result)) {
            array_push($json, $row);
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($json);
} else if (strcmp($resource, 'deleteFile') == 0) {
    $json = array();
    $Conn = new Connection();
    $Conn->connect();
    $fileId   = $_POST["file_id"];
	$filename   = $_POST["filename"];
	$ext   = $_POST["fileType"];
	$target_dir  = "../Uploads/";
	
    $query = "DELETE FROM uploadFiles WHERE file_id =" . $fileId;
    $result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
    
    if (!$result) {
        $json = array(
            "error" => "MySQL Query Error"
        );
    } else {
	    unlink($target_dir . $filename . '.' .$ext);
        $json = array(
            "success" => "Ok"
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($json);
}

?>
