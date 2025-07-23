<?php
header('Content-Type: application/javascript');
require 'conexion.php';

$usuario_id = 1; // Sustituir con $_SESSION['usuario_id'] si ya tienes login

// Consulta para obtener gastos por categoría
$sql_gasto = "SELECT categoria, SUM(monto) as total_gasto FROM gasto WHERE usuario_id = ? GROUP BY categoria";
$stmt_gasto = $conn->prepare($sql_gasto);
$stmt_gasto->bind_param("i", $usuario_id);
$stmt_gasto->execute();
$result_gasto = $stmt_gasto->get_result();

$categorias = [];
$gastos = [];

while ($row = $result_gasto->fetch_assoc()) {
    $categorias[] = $row['categoria'];
    $gastos[] = $row['total_gasto'];
}

// Consulta para obtener presupuestos por categoría
$sql_presupuesto = "SELECT categoria, SUM(monto) as total_presupuesto FROM presupuesto WHERE usuario_id = ? GROUP BY categoria";
$stmt_presupuesto = $conn->prepare($sql_presupuesto);
$stmt_presupuesto->bind_param("i", $usuario_id);
$stmt_presupuesto->execute();
$result_presupuesto = $stmt_presupuesto->get_result();

$presupuestos = [];
while ($row = $result_presupuesto->fetch_assoc()) {
    $presupuestos[$row['categoria']] = $row['total_presupuesto'];
}

// Arreglo de presupuestos alineado con categorías de gastos
$presupuestos_data = [];
foreach ($categorias as $cat) {
    $presupuestos_data[] = isset($presupuestos[$cat]) ? $presupuestos[$cat] : 0;
}
?>

const ctx = document.getElementById('reporteChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($categorias); ?>,
        datasets: [
            {
                label: 'Gasto',
                data: <?php echo json_encode($gastos); ?>,
                backgroundColor: 'rgba(244, 67, 54, 0.6)'
            },
            {
                label: 'Presupuesto',
                data: <?php echo json_encode($presupuestos_data); ?>,
                backgroundColor: 'rgba(76, 175, 80, 0.6)'
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Gasto vs Presupuesto por Categoría'
            },
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
