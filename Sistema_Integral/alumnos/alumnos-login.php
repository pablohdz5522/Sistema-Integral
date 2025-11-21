<?php
session_start();
header('Content-Type: application/json');

$servername = "pdb1042.awardspace.net";
$username = "4528622_pisi";
$password = "sklike5522";
$database = "4528622_pisi";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    echo json_encode(['error' => "Error de conexión: " . $conn->connect_error]);
    exit;
}

$conn->set_charset("utf8mb4");
date_default_timezone_set('America/Mexico_City');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $matricula = strtoupper(trim($_POST["matricula"] ?? ''));
    $passwordIngresada = trim($_POST["password"] ?? '');

    // Validación básica
    if (empty($matricula) || empty($passwordIngresada)) {
        echo json_encode(['error' => 'Matrícula y contraseña son obligatorios']);
        exit;
    }

    // Buscar al alumno en la base de datos
    $sql = "SELECT a.*, c.nom_carrera, f.nom_facultad 
            FROM alumnos a
            LEFT JOIN carrera c ON a.id_carrera = c.id_carrera
            LEFT JOIN facultad f ON a.id_facultad = f.id_facultad
            WHERE a.matricula_alum = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matricula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['error' => 'Matrícula no encontrada']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $alumno = $result->fetch_assoc();
    $stmt->close();

    // Verificar si tiene contraseña registrada
    if (empty($alumno['password'])) {
        echo json_encode(['error' => 'No tienes contraseña registrada. Contacta al administrador.']);
        $conn->close();
        exit;
    }

    // Verificar la contraseña con password_verify
    if (password_verify($passwordIngresada, $alumno['password'])) {
        // Contraseña correcta - Iniciar sesión
        $_SESSION['alumno'] = [
            'matricula' => $alumno['matricula_alum'],
            'nombre'    => $alumno['nombres_alum'],
            'apepa'     => $alumno['ape_paterno_alum'],
            'apema'     => $alumno['ape_materno_alum'],
            'edad'      => $alumno['edad_alum'],
            'sexo'      => $alumno['sexo'],
            'correo'    => $alumno['correo_alum'],
            'fecha'     => $alumno['fe_nacimiento_alum'],
            'id_carrera' => $alumno['id_carrera'],
            'id_facultad' => $alumno['id_facultad'],
            'generacion' => $alumno['generacion'],
            'nom_carrera' => $alumno['nom_carrera'],
            'nom_facultad' => $alumno['nom_facultad']
        ];

        $_SESSION['loggedin'] = true;

        echo json_encode([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'redirect' => 'menuAlum.php',
            'nombre_completo' => $alumno['nombres_alum'] . ' ' . $alumno['ape_paterno_alum']
        ]);
    } else {
        // Contraseña incorrecta
        echo json_encode(['error' => 'Contraseña incorrecta']);
    }
}

$conn->close();
?>