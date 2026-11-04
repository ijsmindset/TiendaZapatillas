<?php
// Conexion a la base de datos con PDO
// Esta en todas las paginas que necesiten la base de datos
/* datos para usar localhost con xampp
$host = "localhost";
$db   = "tienda_zapatillas";
$user = "root";
$pass = "";
*/

//  DATOS PARA EL SERVIDOR ONLINE InfinityFree
$host = "sql200.infinityfree.com"; // Hostname real
$db   = "if0_43357365_tienda";      //  nombre BBDD
$user = "if0_43357365";             //  nombr3e usuario
$pass = "kQ6mZq0xKWR";              // Password

try {
    // Creando la conexion
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    // Modo errores: lanza excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
}
?>