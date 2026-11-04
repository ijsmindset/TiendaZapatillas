<?php
// RF02 - Listado de articulos - solo admin
require '../config/conexion.php';
session_start();

// Proteccion - solo admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Leemos todos los articulos
$stmt = $pdo->query("SELECT * FROM articulos ORDER BY id DESC");
$articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>

<h2>Gestion de articulos</h2>

<p><a href="articulo_form.php" class="btn">Nuevo articulo</a></p>

<?php if (isset($_GET['msg'])): ?>
    <p class="ok"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Marca</th>
        <th>Talla</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($articulos as $a): ?>
    <tr>
        <td><?= $a['id'] ?></td>
        <td><?= htmlspecialchars($a['nombre']) ?></td>
        <td><?= htmlspecialchars($a['marca']) ?></td>
        <td><?= $a['talla'] ?></td>
        <td><?= number_format($a['precio'], 2) ?> EUR</td>
        <td><?= $a['stock'] ?></td>
        <td>
            <a href="articulo_form.php?id=<?= $a['id'] ?>" class="btn">Editar</a>
            <a href="articulo_borrar.php?id=<?= $a['id'] ?>"
               class="btn btn-danger"
               onclick="return confirmarBorrado('Seguro que quieres borrar este articulo?');">
               Borrar
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>