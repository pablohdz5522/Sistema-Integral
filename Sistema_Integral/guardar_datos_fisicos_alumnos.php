<?php
$servername = "pdb1042.awardspace.net";
$username = "4528622_pisi";
$password = "sklike5522";
$database = "4528622_pisi";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);


// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = trim($_POST["matricula"] ?? '');
    $fecha = date("Y-m-d");

    $cintura = $_POST["cintura1"];
    $cadera=$_POST["cadera1"];
    $clasificacioncincad=$_POST["clasificacioncadcin1"];
    $icc=$_POST["icc1"];
    $clasificacionicc=$_POST["clasificacionicc1"];

    $peso=$_POST["peso1"];
    $talla=$_POST["talla1"];
    $imc=$_POST["imc1"];
    $clasificacionimc=$_POST["clasificacionimc1"];
    $ice=$_POST["ice"];

    $mb=$_POST["mb1"];
    $actividad=$_POST["actividad1"];
    $get1=$_POST["get1"];
    $porcentajemasagrasa=$_POST["pormasagrasa1"];
    $valorideal=$_POST["valoridealgrasa1"];
    $clasificacionporgrasa=$_POST["clasificaciongrasa1"];

    $masamagra=$_POST["masamagra1"];
    $aguatotal=$_POST["aguatotal1"];
    $porcentajeagua=$_POST["porcentajeaguatotal1"];

    $glucosa=$_POST["glucosa1"];
    $clasificacionglucosa=$_POST["clasificacionglucosa1"];

    $trigliceridos=$_POST["trigliceridos1"];
    $clasificaciontrigliceridos=$_POST["clasificaciontrigliceridos1"];

    $colesterol=$_POST["colesterol1"];
    $clasificacioncolesterol=$_POST["clasificacioncolesterol1"];


    $tension_arterial = $_POST["tensionarterial1"];
    $clasificacion_tension_arterial = $_POST["clasificacionta1"];

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO datos_fisicos_alumnos 
            (matricula_alum, fecha, cintura, cadera, clasificacion_cintura_cadera, icc, clasificacion_de_icc, peso, talla, imc, clasificacion_imc, ice, mb, actividad1, get1, porcentaje_masa_grasa, valor_ideal_porcentaje_grasa,
            clasificacion_porcentaje_grasa, masa_magra, agua_total, porcentaje_agua_total, glucosa, clasificacion_glucosa, trigliceridos, clasificacion_trigliceridos, colesterol, clasificacion_colesterol, tension_arterial, clasificacion_tension_arterial) 
            VALUES (?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    // Usar una consulta preparada para mayor seguridad
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isddsdsdddsddsdssssdddsdsdsss", 
        $matricula, $fecha, $cintura, $cadera, $clasificacioncincad, $icc, $clasificacionicc, 
        $peso, $talla, $imc, $clasificacionimc, $ice, $mb, $actividad, $get1, $porcentajemasagrasa,
        $valorideal, $clasificacionporgrasa, $masamagra, $aguatotal, $porcentajeagua, 
        $glucosa, $clasificacionglucosa, $trigliceridos, $clasificaciontrigliceridos, 
        $colesterol, $clasificacioncolesterol, $tension_arterial, $clasificacion_tension_arterial
    );

    // Ejecutar la consulta y verificar si se insertó correctamente
    if ($stmt->execute()) {
        echo "<script>alert('Datos guardados exitosamente.'); window.location.href='datos_fisicos.html';</script>";
    } else {
        echo "<script>alert('Error al guardar los datos: " . $stmt->error . "'); window.location.href='datos_fisicos.html';</script>";
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
}

$conn->close();
?>