<?php
// Pagina principal catalogo de articulos
// Visible para todos pero para comprar hay que estar logueado
require 'config/conexion.php';
session_start();

// Leemos todos los articulos
$stmt = $pdo->query("SELECT * FROM articulos ORDER BY id DESC");
$articulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'includes/header.php'; ?>

<h2>Catalogo de zapatillas - Ivan Jimenez</h2>

<?php if (isset($_GET['msg'])): ?>
    <p class="ok"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php endif; ?>

<table>
    <tr>
        <th>Imagen</th> <!-- imagen ultima hora -->
        <th>Nombre</th>
        <th>Marca</th>
        <th>Talla</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Accion</th>
    </tr>
    <?php foreach ($articulos as $a): ?>
    <tr>
        <!-- poniendo imagen-->
        <td style="width: 90px; text-align: center;">
            <?php if (!empty($a['imagen'])): ?>
                <img src="<?= htmlspecialchars($a['imagen']) ?>" alt="<?= htmlspecialchars($a['nombre']) ?>" style="width: 70px; height: 70px; object-fit: contain; border-radius: 5px;">
            <?php else: ?>
                <span style="color: #999; font-size: 12px;">Sin imagen</span>
            <?php endif; ?>
        </td>
        
        <td>
            <strong><?= htmlspecialchars($a['nombre']) ?></strong><br>
            <small><?= htmlspecialchars($a['descripcion']) ?></small>
        </td>
        <td><?= htmlspecialchars($a['marca']) ?></td>
        <td><?= $a['talla'] ?></td>
        <td><?= number_format($a['precio'], 2) ?> EUR</td>
        <td><?= $a['stock'] ?></td>
        <td>
            <?php if ($a['stock'] > 0): ?>
                <a href="anadir_carrito.php?id=<?= $a['id'] ?>" class="btn">Anadir al carrito</a>
            <?php else: ?>
                <span class="error">Sin stock</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php if (empty($articulos)): ?>
    <p>No hay articulos disponibles.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>