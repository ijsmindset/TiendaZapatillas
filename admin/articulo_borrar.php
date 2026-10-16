<?php
// RF02 - Borrar articulo
require '../config/conexion.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM articulos WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    header("Location: articulos.php?msg=Articulo eliminado");
    exit;
}

header("Location: articulos.php");
?>