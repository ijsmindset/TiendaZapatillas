<?php
// Panel de administracion
require '../config/conexion.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<?php include '../includes/header.php'; ?>

<h2>Panel de Administracion</h2>
<p>Bienvenido, <strong><?= $_SESSION['usuario'] ?></strong>.</p>

<ul>
    <li><a href="usuarios.php">Gestionar usuarios (RF01)</a></li>
    <li><a href="articulos.php">Gestionar articulos (RF02)</a></li>
    <li><a href="../index.php">Ir a la tienda</a></li>
</ul>

<?php include '../includes/footer.php'; ?>