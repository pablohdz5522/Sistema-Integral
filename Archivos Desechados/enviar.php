<?php
// Configuración de la conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "pisi");

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibe los datos del formulario
$matricula = $_POST['matricula_mae'] ?? null;
$p1 = $_POST['pregunta1'] ?? null;
$p2 = $_POST['pregunta2'] ?? null;
$p3 = $_POST['pregunta3'] ?? null;
$p4 = $_POST['pregunta4'] ?? null;
$p5 = $_POST['pregunta5'] ?? null;
$p6 = $_POST['pregunta6'] ?? null;
$p7 = $_POST['pregunta7'] ?? null;
$p8 = $_POST['pregunta8'] ?? null;
$p9 = $_POST['pregunta9'] ?? null;
$p10 = $_POST['pregunta10'] ?? null;
$p11 = $_POST['pregunta11'] ?? null;
$p12 = $_POST['pregunta12'] ?? null;
$p13 = $_POST['pregunta13'] ?? null;
$p14 = $_POST['pregunta14'] ?? null;

// Verificar que la matrícula no esté vacía
if (empty($matricula)) {
    die("Error: La matrícula del maestro es obligatoria.");
}

// Verificar que la matrícula exista en la tabla 'maestros'
$sql_verificar = "SELECT COUNT(*) FROM maestros WHERE matricula_mae = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("s", $matricula);
$stmt_verificar->execute();
$stmt_verificar->bind_result($existe);
$stmt_verificar->fetch();
$stmt_verificar->close();

if ($existe == 0) {
    die("Error: La matrícula ingresada no existe en la base de datos.");
}

// Verificar si el maestro ya ha respondido el cuestionario en la tabla 'estres_maestros'
$sql_respuesta = "SELECT COUNT(*) FROM estres_maestros WHERE matricula_mae = ?";
$stmt_respuesta = $conn->prepare($sql_respuesta);
$stmt_respuesta->bind_param("s", $matricula);
$stmt_respuesta->execute();
$stmt_respuesta->bind_result($respondido);
$stmt_respuesta->fetch();
$stmt_respuesta->close();

// Si el maestro ha respondido, redirigir a la página 'ya_respondido.php'
if ($respondido > 0) {
    header("Location: mensaje2.php");
    exit();
} else {
    // Insertar los datos en la tabla 'estres_maestros'
    $sql = "INSERT INTO estres_maestros (matricula_mae, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, p11, p12, p13, p14) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la declaración
    $stmt = $conn->prepare($sql);

    // Verificar si la declaración fue preparada correctamente
    if ($stmt === false) {
        die("Error en la preparación de la declaración: " . $conn->error);
    }

    // Vincular los parámetros
    $stmt->bind_param("sssssssssssssss", $matricula, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14);

    // Ejecutar la declaración
    if ($stmt->execute()) {
        // Redirigir a la página de agradecimiento por responder
        header("Location: mensaje1.php");
        exit();
    } else {
        echo "Error al enviar los datos: " . $stmt->error;
    }

    // Cerrar la declaración de inserción
    $stmt->close();
}

// Ahora, debemos agregar un evento para la eliminación de registros en 'estres_maestros'.
// Esto puede implementarse con un script separado para verificar si un formulario se ha borrado.

$sql_check_deleted = "SELECT COUNT(*) FROM estres_maestros WHERE matricula_mae = ?";
$stmt_check_deleted = $conn->prepare($sql_check_deleted);
$stmt_check_deleted->bind_param("s", $matricula);
$stmt_check_deleted->execute();
$stmt_check_deleted->bind_result($count);
$stmt_check_deleted->fetch();
$stmt_check_deleted->close();

// Si no existe el registro en 'estres_maestros', significa que el formulario fue eliminado
if ($count == 0) {
    // Actualizar el campo 'idestres_maestro' a 0 en la tabla 'maestros'
    $sql_update_deleted = "UPDATE maestros SET idestres_maestro = 0 WHERE matricula_mae = ?";
    $stmt_update_deleted = $conn->prepare($sql_update_deleted);

    if ($stmt_update_deleted === false) {
        die("Error al preparar la actualización del formulario borrado: " . $conn->error);
    }

    // Ejecutar la actualización
    $stmt_update_deleted->bind_param("s", $matricula);
    if ($stmt_update_deleted->execute()) {
        echo "Formulario eliminado, idestres_maestro actualizado a 0";
    } else {
        echo "Error al actualizar el campo idestres_maestro cuando se elimina el formulario: " . $stmt_update_deleted->error;
    }

    // Cerrar la declaración de actualización
    $stmt_update_deleted->close();
}

// Cerrar la conexión
$conn->close();
?>
