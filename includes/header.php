<?php
// Cabecera igual en todas las paginas
// Inicia la sesion si no esta iniciada

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda de Zapatillas - Ivan Jimenez</title>
    <link rel="stylesheet" href="/tienda_zapatillas/assets/css/styles.css">
</head>
<body>
<header>
    <h1>Tienda Zapatillas - Ivan Jimenez</h1>
    <nav>
        <a href="/tienda_zapatillas/index.php">Inicio</a>
        <?php if (isset($_SESSION['usuario'])): ?>
            <span>Hola, <?= htmlspecialchars($_SESSION['usuario']) ?></span>
            <?php if ($_SESSION['rol'] === 'admin'): ?>
                <a href="/tienda_zapatillas/admin/index.php">Panel Admin</a>
            <?php else: ?>
                 <a href="/tienda_zapatillas/carrito.php">Carrito</a>
                 <a href="/tienda_zapatillas/mis_pedidos.php">Mis pedidos</a>
            <?php endif; ?>
            <a href="/tienda_zapatillas/logout.php">Cerrar sesion</a>
        <?php else: ?>
            <a href="/tienda_zapatillas/login.php">Login</a>
            <a href="/tienda_zapatillas/registro.php">Registro</a>
        <?php endif; ?>
    </nav>
</header>
<main>