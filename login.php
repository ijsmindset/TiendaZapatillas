<?php
// Login de usuarios
require 'config/conexion.php';
session_start();

// Si ya esta logueado va al inicio
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$error = "";

// Si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Se recogen los datos con POST
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if ($email === "" || $pass === "") {
        $error = "Debes rellenar email y contrasena.";
    } else {
        // Buscamos el usuario en la base de datos
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Comprobar la contrasena encriptada
        if ($usuario && password_verify($pass, $usuario['password'])) {
            // Guardar datos en la sesion
            $_SESSION['id']      = $usuario['id'];
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['rol']     = $usuario['rol'];

            // Redirigir segun el rol
            if ($usuario['rol'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Email o contrasena incorrectos.";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<h2>Iniciar sesion</h2>

<?php if ($error): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Contrasena:</label>
    <input type="password" name="password" required>

    <br><br>
    <button type="submit" class="btn">Entrar</button>
</form>

<p>No tienes cuenta? <a href="registro.php">Registrate aqui</a></p>

<?php include 'includes/footer.php'; ?>