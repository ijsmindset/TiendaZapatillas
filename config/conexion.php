<?php
// Conexion a la base de datos con PDO
// Esta en todas las paginas que necesiten la base de datos

$host = "localhost";
$db   = "tienda_zapatillas";
$user = "root";
$pass = "";

try {
    // Creando la conexion
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    // Modo errores: lanza excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}
?>