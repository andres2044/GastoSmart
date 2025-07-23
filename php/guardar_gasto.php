<?php
require 'conexion.php'; // asegúrate de tener tu conexión aquí

session_start();
$usuario_id = 1; // Sustituye por $_SESSION['usuario_id'] si tienes login implementado

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];

    $sql = "INSERT INTO gasto (categoria, descripcion, monto, fecha, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $categoria, $descripcion, $monto, $fecha, $usuario_id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Gasto registrado correctamente'); window.location.href = 'gasto.html';</script>";
    } else {
        echo "<script>alert('❌ Error al registrar el gasto'); window.location.href = 'gasto.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
