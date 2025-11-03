<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conn = new mysqli("pdb1042.awardspace.net", "4528622_pisi", "sklike5522", "4528622_pisi");

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['error' => 'Error de conexión']));
}

// Obtener filtros
$filtroFacultad = isset($_GET['facultad']) ? (int)$_GET['facultad'] : "";
$filtroSexo = isset($_GET['sexo']) ? $_GET['sexo'] : "";
$filtroTexto = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";

// Construir consulta para contar
$sql = "SELECT COUNT(*) as total FROM alumnos a 
        JOIN facultad f ON a.id_facultad = f.id_facultad 
        WHERE 1=1";

// Aplicar filtros
if (!empty($filtroFacultad)) {
    $sql .= " AND a.id_facultad = $filtroFacultad";
}

if (!empty($filtroSexo)) {
    $sql .= " AND a.sexo = '$filtroSexo'";
}

if (!empty($filtroTexto)) {
    $sql .= " AND (a.matricula_alum LIKE '%$filtroTexto%' OR a.nombres_alum LIKE '%$filtroTexto%')";
}

$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode(['total' => $row['total']]);

$conn->close();
?>