<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "pisi";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['error' => "Error de conexión: " . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = strtoupper(trim($_POST["matricula"] ?? ''));

    if (empty($matricula)) {
        echo json_encode(['error' => 'La matrícula es obligatoria']);
        exit;
    }

    // Verificar si la matrícula existe
    $sql = "SELECT matricula_alum, nombres_alum, ape_paterno_alum, ape_materno_alum, edad_alum, sexo, correo_alum, fe_nacimiento_alum, id_carrera, id_facultad 
            FROM alumnos 
            WHERE matricula_alum = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Matrícula encontrada, guardar datos en la sesión
        $alumno = $result->fetch_assoc();
        $_SESSION['alumno'] = [
            'matricula' => $alumno['matricula_alum'],
            'nombre' => $alumno['nombres_alum'],
            'apepa' => $alumno['ape_paterno_alum'],
            'apema' => $alumno['ape_materno_alum'],
            'edad' => $alumno['edad_alum'],
            'sexo' => $alumno['sexo'],
            'correo' => $alumno['correo_alum'],
            'fecha' => $alumno['fe_nacimiento_alum'],
            'id_carrera' => $alumno['id_carrera'],
            'id_facultad' => $alumno['id_facultad']
        ];
        $_SESSION['bienvenida'] = true;
        echo json_encode(['success' => true, 'redirect' => 'menuAlumno.php']);
    } else {
        // Matrícula no encontrada
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}

$conn->close();
?>