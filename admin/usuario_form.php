<?php
// RF01 - Formulario crear / editar usuario
require '../config/conexion.php';
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$error = "";
$usuario = [
    'id' => 0,
    'nombre' => '',
    'email' => '',
    'rol' => 'cliente',
    'telefono' => '',
    'direccion' => ''
];

// Si llega un id por la URL cargamos ese usuario como modo edicion
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        header("Location: usuarios.php");
        exit;
    }
}

// Si se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre']);
    $email     = trim($_POST['email']);
    $rol       = $_POST['rol'];
    $telefono  = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $password  = $_POST['password'];

    if ($nombre === "" || $email === "") {
        $error = "Nombre y email son obligatorios.";
    } else {
        if ($usuario['id'] > 0) {
            // EDITAR
            $sql = "UPDATE usuarios SET nombre=:nombre, email=:email, rol=:rol,
                    telefono=:telefono, direccion=:direccion";
            $params = [
                ':nombre' => $nombre, ':email' => $email, ':rol' => $rol,
                ':telefono' => $telefono, ':direccion' => $direccion,
                ':id' => $usuario['id']
            ];
            // Si han escrito contrasena la actualizamos
            if ($password !== "") {
                $sql .= ", password=:password";
                $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $sql .= " WHERE id=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            header("Location: usuarios.php?msg=Usuario actualizado");
            exit;
        } else {
            // CREAR
            if ($password === "") {
                $error = "La contrasena es obligatoria al crear un usuario.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO usuarios
                    (nombre, email, password, rol, telefono, direccion)
                    VALUES (:nombre, :email, :password, :rol, :telefono, :direccion)");
                $stmt->execute([
                    ':nombre' => $nombre, ':email' => $email,
                    ':password' => password_hash($password, PASSWORD_DEFAULT),
                    ':rol' => $rol, ':telefono' => $telefono, ':direccion' => $direccion
                ]);
                header("Location: usuarios.php?msg=Usuario creado");
                exit;
            }
        }
    }
}
?>
<?php include '../includes/header.php'; ?>

<h2><?= $usuario['id'] > 0 ? 'Editar' : 'Nuevo' ?> usuario</h2>

<?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

<form method="POST">
    <label>Nombre *</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

    <label>Email *</label>
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>

    <label>Contrasena <?= $usuario['id'] > 0 ? '(dejar vacio para no cambiar)' : '*' ?></label>
    <input type="password" name="password" <?= $usuario['id'] === 0 ? 'required' : '' ?>>

    <label>Rol *</label>
    <select name="rol">
        <option value="cliente" <?= $usuario['rol'] === 'cliente' ? 'selected' : '' ?>>Cliente</option>
        <option value="admin"   <?= $usuario['rol'] === 'admin'   ? 'selected' : '' ?>>Administrador</option>
    </select>

    <label>Telefono</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">

    <label>Direccion</label>
    <input type="text" name="direccion" value="<?= htmlspecialchars($usuario['direccion']) ?>">

    <br><br>
    <button type="submit" class="btn">Guardar</button>
    <a href="usuarios.php" class="btn">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>