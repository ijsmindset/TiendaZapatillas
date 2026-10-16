<?php
// Anade un articulo al carrito y queda guardado en sesion
require 'config/conexion.php';
session_start();

// Si no esta logueado redirigimos al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Solo los clientes pueden comprar no el admin
if ($_SESSION['rol'] !== 'cliente') {
    header("Location: index.php?msg=El admin no puede comprar");
    exit;
}

// Recogemos el id del articulo por la URL
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Comprobamos que el articulo existe
    $stmt = $pdo->prepare("SELECT id FROM articulos WHERE id = :id");
    $stmt->execute([':id' => $id]);

    if ($stmt->fetch()) {
        // Si no existe el carrito en sesion lo creamos como array vacio
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Si ya esta el articulo sumamos 1 a la cantidad
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]++;
        } else {
            // Si no lo anyadimos con cantidad 1
            $_SESSION['carrito'][$id] = 1;
        }
    }
}

header("Location: index.php?msg=Articulo anadido al carrito");
exit;
?>