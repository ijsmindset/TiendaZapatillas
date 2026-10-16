<?php
// Carrito de la compra
require 'config/conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Acciones de eliminar un articulo y vaciar carrito
if (isset($_GET['eliminar'])) {
    $id = (int) $_GET['eliminar'];
    unset($_SESSION['carrito'][$id]);
    header("Location: carrito.php");
    exit;
}

if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header("Location: carrito.php");
    exit;
}

// Cargamos los articulos del carrito desde la BD
$items = [];
$total = 0;

if (!empty($_SESSION['carrito'])) {
    // Creamos una lista de ids separados por comas
    $ids = implode(',', array_keys($_SESSION['carrito']));
    $stmt = $pdo->query("SELECT * FROM articulos WHERE id IN ($ids)");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculamos el total
    foreach ($items as $item) {
        $cantidad = $_SESSION['carrito'][$item['id']];
        $total += $item['precio'] * $cantidad;
    }
}
?>
<?php include 'includes/header.php'; ?>

<h2>Tu carrito</h2>

<?php if (empty($items)): ?>
    <p>Tu carrito esta vacio.</p>
    <p><a href="index.php" class="btn">Volver al catalogo</a></p>
<?php else: ?>

<table>
    <tr>
        <th>Articulo</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Subtotal</th>
        <th>Accion</th>
    </tr>
    <?php foreach ($items as $item): 
        $cantidad = $_SESSION['carrito'][$item['id']];
        $subtotal = $item['precio'] * $cantidad;
    ?>
    <tr>
        <td><?= htmlspecialchars($item['nombre']) ?></td>
        <td><?= number_format($item['precio'], 2) ?> EUR</td>
        <td><?= $cantidad ?></td>
        <td><?= number_format($subtotal, 2) ?> EUR</td>
        <td>
            <a href="carrito.php?eliminar=<?= $item['id'] ?>" class="btn btn-danger">Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td colspan="2"><strong><?= number_format($total, 2) ?> EUR</strong></td>
    </tr>
</table>

<p>
    <a href="confirmar_compra.php" class="btn">Confirmar compra</a>
    <a href="carrito.php?vaciar=1" class="btn btn-danger">Vaciar carrito</a>
    <a href="index.php" class="btn">Seguir comprando</a>
</p>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>