<?php
// Conectar a la base de datos (ajusta los datos de conexión)
$servername = "localhost";
$username = "root";
$password = "";
$database = "pisi";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pre_inicial = $_POST["preguntainicial"];
    $pre2 = $_POST["escala"];
    $matricula = isset($_POST["matricula"]) ? trim($_POST["matricula"]) : ""; // Capturar matrícula

    if (empty($matricula)) {
        die("Error: La matrícula es obligatoria.");
    }

    // Verificar si pre_inicial es "No"
    if ($pre_inicial == "No") {
        $respuestas = array_fill(0, 45, "N/A");
    } else {
        $respuestas = [];
        for ($i = 1; $i <= 45; $i++) {
            $respuestas[] = isset($_POST["p$i"]) ? $_POST["p$i"] : "N/A";
        }
    }

    // Crear la consulta SQL
    $sql = "INSERT INTO estres_cisco (pre_inicial, pre_2, " . implode(", ", array_map(fn($n) => "p$n", range(1, 45))) . ")
            VALUES (" . str_repeat("?, ", 46) . "?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat("s", 47);
        $stmt->bind_param($types, $pre_inicial, $pre2, ...$respuestas);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $ultimo_id = $stmt->insert_id; // Obtener el último ID insertado en estres_cisco
            echo "Respuesta guardada correctamente. ID: " . $ultimo_id . "<br>";

            // Verificar si la matrícula existe antes de actualizar
            $check_sql = "SELECT * FROM alumnos WHERE matricula_alum = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $matricula);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows > 0) {
                // Actualizar alumnos con el idestres_cisco
                $update_sql = "UPDATE alumnos SET idestres_cisco = ? WHERE matricula_alum = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("is", $ultimo_id, $matricula);

                if ($update_stmt->execute()) {
                    if ($update_stmt->affected_rows > 0) {
                        echo "Registro actualizado en alumnos.<br>";
                    } else {
                        echo "No se modificó ningún registro en alumnos.<br>";
                    }
                } else {
                    echo "Error al actualizar alumnos: " . $update_stmt->error;
                }
                $update_stmt->close();
            } else {
                echo "Error: La matrícula '$matricula' no existe en alumnos.";
            }
            $check_stmt->close();
        } else {
            echo "Error al guardar la respuesta: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
}

$conn->close();
?>


