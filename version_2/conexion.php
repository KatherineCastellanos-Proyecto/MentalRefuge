<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$db   = mental_refuge_2";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8mb4");

// Esto es para saber que funcionó, luego lo borro
echo "¡Conexión lista con Mental Refuge!"; 
?>