<?php
// config.php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sistema_academico';

// Crear la conexión
$conn = new mysqli($host, $user, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
