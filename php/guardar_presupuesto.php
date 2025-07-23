<?php
require 'conexion.php'; // asegúrate de tener configurado correctamente

session_start();
$usuario_id = 1; // Sustituir con $_SESSION['usuario_id'] si ya tienes login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoria = $_POST['categoria'];
    $monto = $_POST['monto'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $sql = "INSERT INTO presupuesto (categoria, monto, fecha_inicio, fecha_fin, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssi", $categoria, $monto, $fecha_inicio, $fecha_fin, $usuario_id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Presupuesto registrado correctamente'); window.location.href = 'presupuesto.html';</script>";
    } else {
        echo "<script>alert('❌ Error al registrar presupuesto: " . $stmt->error . "'); window.location.href = 'presupuesto.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
