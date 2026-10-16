<?php
// RF01 - Listado de usuarios (solo admin)
require '../config/conexion.php';
session_start();

// Proteccion: solo admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Se leen todos los usuarios
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>

<h2>Gestion de usuarios</h2>

<p><a href="usuario_form.php" class="btn">Nuevo usuario</a></p>

<?php if (isset($_GET['msg'])): ?>
    <p class="ok"><?= htmlspecialchars($_GET['msg']) ?></p>
<?php endif; ?>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Telefono</th>
        <th>Acciones</th>
    </tr>
    <?php foreach ($usuarios as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= $u['rol'] ?></td>
        <td><?= htmlspecialchars($u['telefono']) ?></td>
        <td>
            <a href="usuario_form.php?id=<?= $u['id'] ?>" class="btn">Editar</a>
            <a href="usuario_borrar.php?id=<?= $u['id'] ?>"
               class="btn btn-danger"
               onclick="return confirm('Seguro que quieres borrar este usuario?');">
               Borrar
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>