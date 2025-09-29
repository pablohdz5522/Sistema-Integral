<?php
// Configuración de la conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "pisil");

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener la matrícula desde el formulario (método GET para AJAX)
$matricula = $_GET['matricula'] ?? null;

if ($matricula) {
    // Verificar si existe la matrícula en la tabla 'estres_maestros'
    $sql = "SELECT COUNT(*) FROM estres_maestros WHERE matricula_mae = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $stmt->bind_result($respondido);
    $stmt->fetch();
    $stmt->close();
    
    // Enviar respuesta JSON al frontend
    echo json_encode(["respondido" => $respondido > 0]);
} else {
    echo json_encode(["error" => "No se recibió la matrícula"]);
}

$conn->close();
?>
