<?php
// Cerrar sesion
session_start();

// Borramos todas las variables de sesion
session_unset();
// Destruimos la sesion
session_destroy();

// Redirigimos al inicio
header("Location: index.php");
exit;
?>