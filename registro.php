<?php
// Registro nuevos clientes
require 'config/conexion.php';
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$error = "";
$ok = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos con POST
    $nombre    = trim($_POST['nombre']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $password2 = $_POST['password2'];
    $telefono  = trim($_POST['telefono']);   // opcional
    $direccion = trim($_POST['direccion']);  // opcional

    // Validaciones
    if ($nombre === "" || $email === "" || $password === "") {
        $error = "Nombre, email y contrasena son obligatorios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El email no tiene un formato valido.";
    } elseif ($password !== $password2) {
        $error = "Las contrasenas no coinciden.";
    } else {
        // Comprobar si el email ya existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            $error = "Ese email ya esta registrado.";
        } else {
            // Encriptar la contrasena
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertamos el nuevo usuario (rol cliente por defecto)
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol, telefono, direccion)
                                   VALUES (:nombre, :email, :password, 'cliente', :telefono, :direccion)");
            $stmt->execute([
                ':nombre'    => $nombre,
                ':email'     => $email,
                ':password'  => $hash,
                ':telefono'  => $telefono,
                ':direccion' => $direccion
            ]);

            $ok = "Registro correcto. Ya puedes iniciar sesion.";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<h2>Registro de cliente</h2>

<?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
<?php if ($ok): ?><p class="ok"><?= $ok ?></p><?php endif; ?>

<form method="POST" action="registro.php">
    <label>Nombre *</label>
    <input type="text" name="nombre" required>

    <label>Email *</label>
    <input type="email" name="email" required>

    <label>Contrasena *</label>
    <input type="password" name="password" required>

    <label>Repetir contrasena *</label>
    <input type="password" name="password2" required>

    <label>Telefono (opcional)</label>
    <input type="text" name="telefono">

    <label>Direccion (opcional)</label>
    <input type="text" name="direccion">

    <br><br>
    <button type="submit" class="btn">Registrarme</button>
</form>

<?php include 'includes/footer.php'; ?>