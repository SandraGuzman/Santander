<?php
require_once("Connection.php");
//session_start();

//if (isset($_SESSION['user'])) {
$uri      = explode("/", $_SERVER["REQUEST_URI"]);
$resource = end($uri);

if (strcmp($resource, 'uploadFile') == 0) {
    if ($_FILES["fileField"]) {
        //header('Content-Type: text/html; charset=utf-8');
        $json      = array();
        $file_name = $_FILES["fileField"]["name"];
        $file_size = $_FILES["fileField"]["size"];
        $file_tmp  = $_FILES["fileField"]["tmp_name"];
        $file_type = $_FILES["fileField"]["type"];
        $file_ext  = strtolower(end(explode('.', $file_name)));
        
        $title   = $_POST["titleFile"];
        $year    = $_POST["yearFile"];
        $quarter = $_POST["quarterFile"];
        $section   = $_POST["sectionFile"];
        
        $target_dir  = "../Uploads/";
        $target_file = $target_dir . $file_name;
        
        $expensions = array(
            "jpeg",
            "jpg",
            "png",
            "pdf"
        );
        
        if (in_array($file_ext, $expensions) === false) {
            $json = array(
                "error" => "Extensión no permitida, por favor escoger un JPEG, PNG o PDF."
            );
        }
        
        if (file_exists($target_file)) {
            $json = array(
                "error" => "El archivo ya existe."
            );
        }
        
        if (empty($json) == true) {
            if (move_uploaded_file($file_tmp, $target_file)) {
                $Conn = new Connection();
                $Conn->connect();
                $query = "INSERT INTO uploadFiles (title,filename, fileType,publicationDate,year,quarterly,section_id)
							VALUES ('" . $title . "','" . $file_name . "','" . $file_ext . "','" . date("Y/m/d") . "','" . $year . "','" . $quarter . "','". $section ."' )";
                
                $result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
                $file_id = mysql_insert_id();
                if (!$result) {
                    $json = array(
                        "error" => "MySQL Query Error"
                    );
                } else {
                    $json = array(
                        "file_id" => $file_id,
                        "filename" => $title,
                        "fileType" => $file_ext,
                        "publicationDate" => date("Y/m/d"),
                        "year" => $year,
                        "quarterly" => $quarter
                    );
                }
            } else {
                $json = array(
                    "error" => "Hubo un error durante la carga"
                );
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($json);
        
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            "error" => "Archivo no encontrado"
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
    $fileId     = $_POST["file_id"];
    $filename   = $_POST["filename"];
    $ext        = $_POST["fileType"];
    $target_dir = "../Uploads/";
    
    $query = "DELETE FROM uploadFiles WHERE file_id =" . $fileId;
    $result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
    
    if (!$result) {
        $json = array(
            "error" => "MySQL Query Error"
        );
    } else {
        unlink($target_dir . $filename . '.' . $ext);
        $json = array(
            "success" => "Ok"
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($json);
}
/*} else {
$json = array(
"session" => "Sesión no iniciada"
);
header('Content-Type: application/json');
echo json_encode($json);
}*/



?>
