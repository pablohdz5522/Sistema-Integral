<?php
$servername = "pdb1042.awardspace.net";
$username = "4528622_pisi";
$password = "sklike5522";
$database = "4528622_pisi";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

$tipo = $_GET["tipo"] ?? '';

if ($tipo == "facultades") {
    $sql = "SELECT id_facultad, nombre_facultad FROM facultad";
} elseif ($tipo == "carreras" && isset($_GET["idfacultad"])) {
    $idfacultad = intval($_GET["idfacultad"]);
    $sql = "SELECT id_carrera, nombre_carrera FROM carrera WHERE id_facultad = $idfacultad";
} else {
    echo json_encode([]);
    exit;
}

$result = $conn->query($sql) or die(json_encode(["error" => $conn->error]));
$datos = [];

while ($fila = $result->fetch_assoc()) {
    $datos[] = $fila;
}

echo json_encode($datos);
?>