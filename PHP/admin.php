<?php
session_start();

if (isset($_SESSION['user'])) {
    $json = array(
        "user" => $_SESSION['user']
    );
    header('Content-Type: application/json');
    echo json_encode($json);
} else {
    $json = array(
        "session" => "Sesión no iniciada"
    );
    header('Content-Type: application/json');
    echo json_encode($json);
}

?>
