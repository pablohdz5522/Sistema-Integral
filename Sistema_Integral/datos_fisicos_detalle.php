<?php
header("Content-Type: application/json");

// Obtener parámetros de la URL
$matricula = isset($_GET['matricula_alum']) ? $_GET['matricula_alum'] : "";
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : "";

if (!$matricula || !$fecha) {
    echo json_encode(["error" => "Matrícula o fecha no proporcionada"]);
    exit;
}

// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "pisi");

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión a la base de datos"]));
}

// Consulta para obtener los datos físicos en la fecha seleccionada
$sql = "SELECT peso, talla, imc, clasificacion_imc, glucosa, colesterol, trigliceridos, tension_arterial 
        FROM datos_fisicos_alumnos 
        WHERE matricula_alum = ? AND fecha = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $matricula, $fecha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $datos = $result->fetch_assoc();
    echo json_encode($datos);
} else {
    echo json_encode(["error" => "No se encontraron datos para la fecha seleccionada"]);
}

$conn->close();
?>