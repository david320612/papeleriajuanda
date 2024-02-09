<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

//crear conexion

$conn = new mysqli($servername, $username, $password, $dbname);

// verificar la conexion
if ($conn->connect_error){
    die("Conexión fallida" . $conn->connect_error);
}

?>

