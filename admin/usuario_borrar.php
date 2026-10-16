<?php
// RF01 - Borrar usuario
require '../config/conexion.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    // No permitir borrarse a si mismo
    if ($_GET['id'] == $_SESSION['id']) {
        header("Location: usuarios.php?msg=No puedes borrarte a ti mismo");
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    header("Location: usuarios.php?msg=Usuario eliminado");
    exit;
}

header("Location: usuarios.php");
?>