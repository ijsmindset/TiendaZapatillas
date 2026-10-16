<?php
// RF02 - Formulario crear - editar articulo
require '../config/conexion.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = "";
$articulo = [
    'id' => 0,
    'nombre' => '',
    'marca' => '',
    'talla' => '',
    'precio' => '',
    'stock' => 0,
    'descripcion' => ''
];

// Si llega un id por la URL cargamos ese articulo como modo edicion
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM articulos WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $articulo = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$articulo) {
        header("Location: articulos.php");
        exit;
    }
}

// Si se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre']);
    $marca       = trim($_POST['marca']);
    $talla       = (int) $_POST['talla'];
    $precio      = (float) $_POST['precio'];
    $stock       = (int) $_POST['stock'];
    $descripcion = trim($_POST['descripcion']);

    if ($nombre === "" || $precio <= 0) {
        $error = "El nombre y un precio valido son obligatorios.";
    } else {
        if ($articulo['id'] > 0) {
            // EDITAR
            $stmt = $pdo->prepare("UPDATE articulos SET
                nombre = :nombre, marca = :marca, talla = :talla,
                precio = :precio, stock = :stock, descripcion = :descripcion
                WHERE id = :id");
            $stmt->execute([
                ':nombre' => $nombre, ':marca' => $marca, ':talla' => $talla,
                ':precio' => $precio, ':stock' => $stock,
                ':descripcion' => $descripcion, ':id' => $articulo['id']
            ]);
            header("Location: articulos.php?msg=Articulo actualizado");
            exit;
        } else {
            // CREAR
            $stmt = $pdo->prepare("INSERT INTO articulos
                (nombre, marca, talla, precio, stock, descripcion)
                VALUES (:nombre, :marca, :talla, :precio, :stock, :descripcion)");
            $stmt->execute([
                ':nombre' => $nombre, ':marca' => $marca, ':talla' => $talla,
                ':precio' => $precio, ':stock' => $stock,
                ':descripcion' => $descripcion
            ]);
            header("Location: articulos.php?msg=Articulo creado");
            exit;
        }
    }
}
?>
<?php include '../includes/header.php'; ?>

<h2><?= $articulo['id'] > 0 ? 'Editar' : 'Nuevo' ?> articulo</h2>

<?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

<form method="POST">
    <label>Nombre *</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($articulo['nombre']) ?>" required>

    <label>Marca</label>
    <input type="text" name="marca" value="<?= htmlspecialchars($articulo['marca']) ?>">

    <label>Talla</label>
    <input type="number" name="talla" value="<?= htmlspecialchars($articulo['talla']) ?>">

    <label>Precio (EUR) *</label>
    <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($articulo['precio']) ?>" required>

    <label>Stock</label>
    <input type="number" name="stock" value="<?= htmlspecialchars($articulo['stock']) ?>">

    <label>Descripcion</label>
    <textarea name="descripcion" rows="3"><?= htmlspecialchars($articulo['descripcion']) ?></textarea>

    <br><br>
    <button type="submit" class="btn">Guardar</button>
    <a href="articulos.php" class="btn">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>