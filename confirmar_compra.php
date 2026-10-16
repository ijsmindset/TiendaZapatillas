<?php
// Confirma la compra y guarda el pedido en la BD
require 'config/conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['rol'] !== 'cliente') {
    header("Location: index.php");
    exit;
}

// Si el carrito esta vacio volvemos
if (empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

// Calculamos el total y cargamos los articulos
$ids = implode(',', array_keys($_SESSION['carrito']));
$stmt = $pdo->query("SELECT * FROM articulos WHERE id IN ($ids)");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($items as $item) {
    $total += $item['precio'] * $_SESSION['carrito'][$item['id']];
}

// 1) Insertamos el pedido
$stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, total) VALUES (:id_usuario, :total)");
$stmt->execute([
    ':id_usuario' => $_SESSION['id'],
    ':total'      => $total
]);

// Recuperamos el id del pedido recien creado
$id_pedido = $pdo->lastInsertId();

// 2) Insertamos las lineas del pedido
// 2) Insertamos las lineas del pedido y actualizamos el stock
$stmt = $pdo->prepare("INSERT INTO lineas_pedido
    (id_pedido, id_articulo, cantidad, precio_unitario)
    VALUES (:id_pedido, :id_articulo, :cantidad, :precio)");

// Preparamos la consulta para restar stock
$stmtStock = $pdo->prepare("UPDATE articulos SET stock = stock - :cantidad
                            WHERE id = :id");

foreach ($items as $item) {
    $cantidad = $_SESSION['carrito'][$item['id']];

    // Insertamos la linea del pedido
    $stmt->execute([
        ':id_pedido'    => $id_pedido,
        ':id_articulo'  => $item['id'],
        ':cantidad'     => $cantidad,
        ':precio'       => $item['precio']
    ]);

    // Restamos el stock del articulo
    $stmtStock->execute([
        ':cantidad' => $cantidad,
        ':id'       => $item['id']
    ]);
}

// 3) Vaciamos el carrito
$_SESSION['carrito'] = [];

header("Location: mis_pedidos.php?msg=Compra realizada correctamente");
exit;
?>