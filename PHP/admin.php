<?php
if (isset($_SESSION) || isset($_SESSION['user'])) {
	$json = array(
                "session" => "Sesión no iniciada"
            );
	header('Content-Type: application/json');
	echo json_encode($json);
} else {
   session_start();
	$json = array(
                "user" => $_SESSION['user']
            );
	header('Content-Type: application/json');
	echo json_encode($json);
}

?>
