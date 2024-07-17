<?php 
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "login";

    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
