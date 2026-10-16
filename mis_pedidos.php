<?php
// Historial de pedidos del cliente
require 'config/conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Sacar los pedidos del usuario logueado
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = :id ORDER BY fecha DESC");
$stmt->execute([':id' => $_SESSION['id']]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'includes/header.php'; ?>

<h2>Mis pedidos</h2>

<?php if (isset($_GET['msg'])): ?>
    <p class="ok"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php endif; ?>

<?php if (empty($pedidos)): ?>
    <p>Todavia no has hecho ningun pedido.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Pedido</th>
            <th>Fecha</th>
            <th>Total</th>
        </tr>
        <?php foreach ($pedidos as $p): ?>
        <tr>
            <td>#<?= $p['id'] ?></td>
            <td><?= $p['fecha'] ?></td>
            <td><?= number_format($p['total'], 2) ?> EUR</td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>