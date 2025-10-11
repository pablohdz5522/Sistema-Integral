<?php
session_start();

// Deshabilitar caché para evitar reenvíos accidentales
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Seguridad: validar sesión
if (!isset($_SESSION['alumno']) || !isset($_SESSION['alumno']['matricula'])) {
    header("Location: registro.php");
    exit;
}

$alumno = $_SESSION['alumno'];
$matricula = $alumno['matricula'];

// Conexión a la base de datos
$servername = "pdb1042.awardspace.net";
$username = "4528622_pisi";
$password = "sklike5522";
$database = "4528622_pisi";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si el alumno ya respondió el formulario
$sql_verificar = "SELECT 1 FROM estilo_de_vida WHERE matricula_alum = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("s", $matricula);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows > 0) {
    $stmt_verificar->close();
    $conn->close();
    header("Location: menuAlumno.php");
    exit;
}
$stmt_verificar->close();

// Inicializar mensaje de error
$error_message = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que todas las preguntas estén respondidas y sean válidas (1-4)
    $required_fields = ['p1', 'p2', 'p3', 'p4', 'p5', 'p6', 'p7', 'p8', 'p9', 'p10', 'p11', 'p12', 'p13', 'p14', 'p15', 'p16', 'p17', 'p18', 'p19', 'p20', 'p21', 'p22', 'p23', 'p24', 'p25', 'p26', 'p27', 'p28', 'p29', 'p30', 'p31', 'p32', 'p33', 'p34', 'p35', 'p36', 'p37', 'p38', 'p39', 'p40', 'p41', 'p42', 'p43', 'p44', 'p45', 'p46', 'p47', 'p48'];
    $all_valid = true;
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || !is_numeric($_POST[$field]) || $_POST[$field] < 1 || $_POST[$field] > 4) {
            $all_valid = false;
            break;
        }
    }

    if (!$all_valid) {
        $error_message = "Error: Todas las preguntas deben responderse con un valor entre 1 y 4.";
    } else {
        // Calcular puntajes por categoría
        $p1 = (int)$_POST['p1'];
        $p5 = (int)$_POST['p5'];
        $p14 = (int)$_POST['p14'];
        $p19 = (int)$_POST['p19'];
        $p26 = (int)$_POST['p26'];
        $p35 = (int)$_POST['p35'];
        $total_nutricion = $p1 + $p5 + $p14 + $p19 + $p26 + $p35;
        $saludable_nutricion = ($total_nutricion > 16) ? 'Saludable' : 'No Saludable';

        $p4 = (int)$_POST['p4'];
        $p13 = (int)$_POST['p13'];
        $p22 = (int)$_POST['p22'];
        $p30 = (int)$_POST['p30'];
        $p38 = (int)$_POST['p38'];
        $total_ejercicio = $p4 + $p13 + $p22 + $p30 + $p38;
        $saludable_ejercicio = ($total_ejercicio > 14) ? 'Saludable' : 'No Saludable';

        $p2 = (int)$_POST['p2'];
        $p7 = (int)$_POST['p7'];
        $p15 = (int)$_POST['p15'];
        $p20 = (int)$_POST['p20'];
        $p28 = (int)$_POST['p28'];
        $p32 = (int)$_POST['p32'];
        $p33 = (int)$_POST['p33'];
        $p42 = (int)$_POST['p42'];
        $p43 = (int)$_POST['p43'];
        $p46 = (int)$_POST['p46'];
        $total_salud = $p2 + $p7 + $p15 + $p20 + $p28 + $p32 + $p33 + $p42 + $p43 + $p46;
        $saludable_salud = ($total_salud > 26) ? 'Saludable' : 'No Saludable';

        $p10 = (int)$_POST['p10'];
        $p18 = (int)$_POST['p18'];
        $p24 = (int)$_POST['p24'];
        $p25 = (int)$_POST['p25'];
        $p31 = (int)$_POST['p31'];
        $p39 = (int)$_POST['p39'];
        $p47 = (int)$_POST['p47'];
        $total_soporte = $p10 + $p18 + $p24 + $p25 + $p31 + $p39 + $p47;
        $saludable_soporte = ($total_soporte > 18) ? 'Saludable' : 'No Saludable';

        $p6 = (int)$_POST['p6'];
        $p11 = (int)$_POST['p11'];
        $p27 = (int)$_POST['p27'];
        $p36 = (int)$_POST['p36'];
        $p40 = (int)$_POST['p40'];
        $p41 = (int)$_POST['p41'];
        $p45 = (int)$_POST['p45'];
        $total_estres = $p6 + $p11 + $p27 + $p36 + $p40 + $p41 + $p45;
        $saludable_estres = ($total_estres > 18) ? 'Saludable' : 'No Saludable';

        $p3 = (int)$_POST['p3'];
        $p8 = (int)$_POST['p8'];
        $p9 = (int)$_POST['p9'];
        $p12 = (int)$_POST['p12'];
        $p16 = (int)$_POST['p16'];
        $p17 = (int)$_POST['p17'];
        $p21 = (int)$_POST['p21'];
        $p23 = (int)$_POST['p23'];
        $p29 = (int)$_POST['p29'];
        $p34 = (int)$_POST['p34'];
        $p37 = (int)$_POST['p37'];
        $p44 = (int)$_POST['p44'];
        $p48 = (int)$_POST['p48'];
        $total_auto = $p3 + $p8 + $p9 + $p12 + $p16 + $p17 + $p21 + $p23 + $p29 + $p34 + $p37 + $p44 + $p48;
        $saludable_auto = ($total_auto > 33) ? 'Saludable' : 'No Saludable';

        $total_general = $total_nutricion + $total_ejercicio + $total_salud + $total_soporte + $total_estres + $total_auto;
        $estado_saludable = ($total_general > 121) ? 'Saludable' : 'No Saludable';

        // Insertar en la tabla estilo_de_vida
        $fecha_actual = date('Y-m-d H:i:s'); 
        $sql_cuestionario = "INSERT INTO estilo_de_vida (matricula_alum, total, fecha, estado_saludable) VALUES (?, ?, ?, ?)";
        $stmt_cuestionario = $conn->prepare($sql_cuestionario);
        $stmt_cuestionario->bind_param("siss", $matricula, $total_general, $fecha_actual, $estado_saludable);
        if ($stmt_cuestionario->execute()) {
            $id_cuestionario = $conn->insert_id;

            // Insertar en la tabla nutricion
            $sql_nutricion = "INSERT INTO nutricion (id_cuestionario, p1, p5, p14, p19, p26, p35, total_nutricion, saludable) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_nutricion = $conn->prepare($sql_nutricion);
            $stmt_nutricion->bind_param("iiiiiiiss", $id_cuestionario, $p1, $p5, $p14, $p19, $p26, $p35, $total_nutricion, $saludable_nutricion);
            $stmt_nutricion->execute();
            $stmt_nutricion->close();

            // Insertar en la tabla ejercicio
            $sql_ejercicio = "INSERT INTO ejercicio (id_cuestionario, p4, p13, p22, p30, p38, total_ejercicio, saludable_ejercicio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_ejercicio = $conn->prepare($sql_ejercicio);
            $stmt_ejercicio->bind_param("iiiiiiis", $id_cuestionario, $p4, $p13, $p22, $p30, $p38, $total_ejercicio, $saludable_ejercicio);
            $stmt_ejercicio->execute();
            $stmt_ejercicio->close();

            // Insertar en la tabla salud
            $sql_salud = "INSERT INTO salud (id_cuestionario, p2, p7, p15, p20, p28, p32, p33, p42, p43, p46, total_salud, saludable_salud) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_salud = $conn->prepare($sql_salud);
            $stmt_salud->bind_param("iiiiiiiiiiiis", $id_cuestionario, $p2, $p7, $p15, $p20, $p28, $p32, $p33, $p42, $p43, $p46, $total_salud, $saludable_salud);
            $stmt_salud->execute();
            $stmt_salud->close();

            // Insertar en la tabla soporte_interpersonal
            $sql_soporte = "INSERT INTO soporte_interpersonal (id_cuestionario, p10, p18, p24, p25, p31, p39, p47, total_soporte, saludable_soporte) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_soporte = $conn->prepare($sql_soporte);
            $stmt_soporte->bind_param("iiiiiiiiis", $id_cuestionario, $p10, $p18, $p24, $p25, $p31, $p39, $p47, $total_soporte, $saludable_soporte);
            $stmt_soporte->execute();
            $stmt_soporte->close();

            // Insertar en la tabla manejo_de_estres
            $sql_estres = "INSERT INTO manejo_de_estres (id_cuestionario, p6, p11, p27, p36, p40, p41, p45, total_manejoestres, saludable_manejo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_estres = $conn->prepare($sql_estres);
            $stmt_estres->bind_param("iiiiiiiiis", $id_cuestionario, $p6, $p11, $p27, $p36, $p40, $p41, $p45, $total_estres, $saludable_estres);
            $stmt_estres->execute();
            $stmt_estres->close();

            // Insertar en la tabla autoactualizacion
            $sql_auto = "INSERT INTO autoactualizacion (id_cuestionario, p3, p8, p9, p12, p16, p17, p21, p23, p29, p34, p37, p44, p48, total_autoactualizacion, saludable_autoactualizacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_auto = $conn->prepare($sql_auto);
            $stmt_auto->bind_param("iiiiiiiiiiiiiiis", $id_cuestionario, $p3, $p8, $p9, $p12, $p16, $p17, $p21, $p23, $p29, $p34, $p37, $p44, $p48, $total_auto, $saludable_auto);
            $stmt_auto->execute();
            $stmt_auto->close();

            $conn->close();
            header('Location: menuAlumno.php');
            exit;
        } else {
            $error_message = "Error al guardar los datos. Por favor, intenta de nuevo.";
        }
        $stmt_cuestionario->close();
    }
}
$conn->close();
?>

<!doctype html>
<html lang="es">
    <head>
        <title>Estilo de Vida</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link href="PEPS-1.css" rel="stylesheet"/> 
    </head> 

    <body style="background: linear-gradient(278deg, rgba(23, 19, 235, 0.50) 13.7%, rgba(255, 255, 255, 0.25) 13.7%), linear-gradient(263deg, rgba(71, 15, 255, 0.00) 87.02%, rgba(71, 15, 255, 0.50) 87.03%), linear-gradient(277deg, rgba(255, 242, 0, 0.00) 89.31%, #FFF600 89.63%), linear-gradient(87deg, #FFF 88.1%, #FDEE18 88.48%); margin: 0;height: 100%; min-height: 100vh;">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-8 col-sm-10 col-12">
                    
                    <div class="text-center" style="margin-top: 35px;">
                        <h3>Cuestionario de Perfil de Estilo de Vida (PEPS-1)</h3>
                    </div>
                    <div class="mt-4" style="display: block;" id="instrucciones">
                        <p style="font-weight: bolder;">Instrucciones:</p>
                        <p style="margin-top: -15px; font-weight: bolder;">a) En este cuestionario se pregunta sobre el modo en que vives en relación a tus hábitos personales actuales.</p>
                        <p style="margin-top: -15px; font-weight: bolder;">b) No hay respuesta correcta o incorrecta, solo es tu forma de vivir. Favor de no dejar preguntas sin responder. </p>
                        <p style="margin-top: -15px; font-weight: bolder;">c) Escoge una respuesta que refleje mejor tu forma de vivir.</p>
                        <p class="text-center mt-5" style="font-weight: bolder;">1=Nunca, 2=A veces, 3=Frecuentemente, 4=Rutinariamente</p>
                    </div>

                    <form action="PEPS-1.php" method="post" style="display: block;" id="formulario">
                        <input type="hidden" name="matricula" id="matriculaOculta">

                        <div class="mt-5 cuadro">
                            <label for="p1" class="form-label">1. Tomas algún alimento al levantarte por las mañanas</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p1" id="p1_nunca" value="1" required>
                                    <label class="form-check-label" for="p1_nunca">nunca</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p1" id="p1_aveces" value="2" required>
                                    <label class="form-check-label" for="p1_aveces">A veces</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p1" id="p1_frecuente" value="3" required>
                                    <label class="form-check-label" for="p1_frecuente">Frecuentemente</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p1" id="p1_rutina" value="4" required>
                                    <label class="form-check-label" for="p1_rutina">Rutinariamente</label>
                                </div>
                            </div>
                        </div>
                        

                        <div class="mt-3 cuadro">
                            <label for="p2" class="form-label">2. Relatas al médico cualquier síntoma extraño relacionado con tu salud</label>
                            <div>
                               <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p2" id="p2_nunca" value="1" required>
                                    <label class="form-check-label" for="p2_nunca">Nunca</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p2" id="p2_aveces" value="2" required>
                                    <label class="form-check-label" for="p2_aveces">A veces</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p2" id="p2_frecuente" value="3" required>
                                    <label class="form-check-label" for="p2_frecuente">Frecuentemente</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input colorradio" type="radio" name="p2" id="p2_rutina" value="4" required>
                                    <label class="form-check-label" for="p2_rutina">Rutinariamente</label>
                                </div>
                            </div> 
                        </div>
                        
                        <div class="mt-3 cuadro">
                            <label for="p3" class="form-label">3. Te quieres a ti misma(o) </label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p3" id="p3_nunca" value="1" required>
                                     <label class="form-check-label" for="p3_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p3" id="p3_aveces" value="2" required>
                                     <label class="form-check-label" for="p3_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p3" id="p3_frecuente" value="3" required>
                                     <label class="form-check-label" for="p3_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p3" id="p3_rutina" value="4" required>
                                     <label class="form-check-label" for="p3_rutina">Rutinariamente</label>
                                 </div>
                            </div> 
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p4" class="form-label">4. Realizas ejercicios para relajar tus músculos al menos 3 veces por día o por semana </label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p4" id="p4_nunca" value="1" required>
                                     <label class="form-check-label" for="p4_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p4" id="p4_aveces" value="2" required>
                                     <label class="form-check-label" for="p4_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p4" id="p4_frecuente" value="3" required>
                                     <label class="form-check-label" for="p4_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p4" id="p4_rutina" value="4" required>
                                     <label class="form-check-label" for="p4_rutina">Rutinariamente</label>
                                 </div>
                            </div> 
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p5" class="form-label">5. seleccionas comidas que no contienen ingredientes artificiales o químicos para conservarlos (sustancias que te eleven tu presión arterial) </label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p5" id="p5_nunca" value="1" required>
                                     <label class="form-check-label" for="p5_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p5" id="p5_aveces" value="2" required>
                                     <label class="form-check-label" for="p5_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p5" id="p5_frecuente" value="3" required>
                                     <label class="form-check-label" for="p5_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p5" id="p5_rutina" value="4" required>
                                     <label class="form-check-label" for="p5_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">6. Tomas tiempo cada dia para el relajamiento</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p6" id="p6_nunca" value="1" required>
                                     <label class="form-check-label" for="p6_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p6" id="p6_aveces" value="2" required>
                                     <label class="form-check-label" for="p6_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p6" id="p6_frecuente" value="3" required>
                                     <label class="form-check-label" for="p6_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p6" id="p6_rutina" value="4" required>
                                     <label class="form-check-label" for="p6_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">7. Conoces el nivel de colesterol en tu sangre</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p7" id="p7_nunca" value="1" required>
                                     <label class="form-check-label" for="p7_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p7" id="p7_aveces" value="2" required>
                                     <label class="form-check-label" for="p7_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p7" id="p7_frecuente" value="3" required>
                                     <label class="form-check-label" for="p7_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p7" id="p7_rutina" value="4" required>
                                     <label class="form-check-label" for="p7_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">8. Eres entusiasta y optimista con referencia a tu vida</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p8" id="p8_nunca" value="1" required>
                                     <label class="form-check-label" for="p8_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p8" id="p8_aveces" value="2" required>
                                     <label class="form-check-label" for="p8_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p8" id="p8_frecuente" value="3" required>
                                     <label class="form-check-label" for="p8_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p8" id="p8_rutina" value="4" required>
                                     <label class="form-check-label" for="p8_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">9. Crees que estás creciendo y cambiando personalmente en direcciones positivas</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p9" id="p9_nunca" value="1" required>
                                     <label class="form-check-label" for="p9_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p9" id="p9_aveces" value="2" required>
                                     <label class="form-check-label" for="p9_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p9" id="p9_frecuente" value="3" required>
                                     <label class="form-check-label" for="p9_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p9" id="p9_rutina" value="4" required>
                                     <label class="form-check-label" for="p9_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">10. Discutes con personas cercanas tus preocupaciones y problemas personasles</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p10" id="p10_nunca" value="1" required>
                                     <label class="form-check-label" for="p10_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p10" id="p10_aveces" value="2" required>
                                     <label class="form-check-label" for="p10_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p10" id="p10_frecuente" value="3" required>
                                     <label class="form-check-label" for="p10_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p10" id="p10_rutina" value="4" required>
                                     <label class="form-check-label" for="p10_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">11. Eres consciente de las fuentes que producen tensión (Comúnmente nervios) en tu vida</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p11" id="p11_nunca" value="1" required>
                                     <label class="form-check-label" for="p11_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p11" id="p11_aveces" value="2" required>
                                     <label class="form-check-label" for="p11_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p11" id="p11_frecuente" value="3" required>
                                     <label class="form-check-label" for="p11_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p11" id="p11_rutina" value="4" required>
                                     <label class="form-check-label" for="p11_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">12. Te sientes feliz y contento(a)</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p12" id="p12_nunca" value="1" required>
                                     <label class="form-check-label" for="p12_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p12" id="p12_aveces" value="2" required>
                                     <label class="form-check-label" for="p12_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p12" id="p12_frecuente" value="3" required>
                                     <label class="form-check-label" for="p12_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p12" id="p12_rutina" value="4" required>
                                     <label class="form-check-label" for="p12_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">13. Realizas ejercicio vigoroso por 20 o 30 minutos al menos tres veces a la semana</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p13" id="p13_nunca" value="1" required>
                                     <label class="form-check-label" for="p13_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p13" id="p13_aveces" value="2" required>
                                     <label class="form-check-label" for="p13_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p13" id="p13_frecuente" value="3" required>
                                     <label class="form-check-label" for="p13_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p13" id="p13_rutina" value="4" required>
                                     <label class="form-check-label" for="p13_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">14. Comes tres comidas al día</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p14" id="p14_nunca" value="1" required>
                                     <label class="form-check-label" for="p14_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p14" id="p14_aveces" value="2" required>
                                     <label class="form-check-label" for="p14_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p14" id="p14_frecuente" value="3" required>
                                     <label class="form-check-label" for="p14_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p14" id="p14_rutina" value="4" required>
                                     <label class="form-check-label" for="p14_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">15. Lees revistas o folletos sobre como cuidar tu salud</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p15" id="p15_nunca" value="1" required>
                                     <label class="form-check-label" for="p15_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p15" id="p15_aveces" value="2" required>
                                     <label class="form-check-label" for="p15_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p15" id="p15_frecuente" value="3" required>
                                     <label class="form-check-label" for="p15_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p15" id="p15_rutina" value="4" required>
                                     <label class="form-check-label" for="p15_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">16. Eres consciente de tus capacidades y debilidades personales</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p16" id="p16_nunca" value="1" required>
                                     <label class="form-check-label" for="p16_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p16" id="p16_aveces" value="2" required>
                                     <label class="form-check-label" for="p16_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p16" id="p16_frecuente" value="3" required>
                                     <label class="form-check-label" for="p16_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p16" id="p16_rutina" value="4" required>
                                     <label class="form-check-label" for="p16_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">17. Trabajas en apoyo de metas a largo plazo en tu vida</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p17" id="p17_nunca" value="1" required>
                                     <label class="form-check-label" for="p17_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p17" id="p17_aveces" value="2" required>
                                     <label class="form-check-label" for="p17_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p17" id="p17_frecuente" value="3" required>
                                     <label class="form-check-label" for="p17_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p17" id="p17_rutina" value="4" required>
                                     <label class="form-check-label" for="p17_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">18. Elogias fácilmente a otras personas por sus éxitos</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p18" id="p18_nunca" value="1" required>
                                     <label class="form-check-label" for="p18_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p18" id="p18_aveces" value="2" required>
                                     <label class="form-check-label" for="p18_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p18" id="p18_frecuente" value="3" required>
                                     <label class="form-check-label" for="p18_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p18" id="p18_rutina" value="4" required>
                                     <label class="form-check-label" for="p18_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">19. Lees las etiquetas de las comidas empaquetadas para identificar nutrientes (artificiales y/o naturales, colesterol, 
                                sodio o sal, conservadores)</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p19" id="p19_nunca" value="1" required>
                                     <label class="form-check-label" for="p19_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p19" id="p19_aveces" value="2" required>
                                     <label class="form-check-label" for="p19_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p19" id="p19_frecuente" value="3" required>
                                     <label class="form-check-label" for="p19_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p19" id="p19_rutina" value="4" required>
                                     <label class="form-check-label" for="p19_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">20. Le preguntas a otro médico o buscas otra opción cuando no estas de acuerdo con lo que el tuyo te recomienda para cuidar tu salud</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p20" id="p20_nunca" value="1" required>
                                     <label class="form-check-label" for="p20_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p20" id="p20_aveces" value="2" required>
                                     <label class="form-check-label" for="p20_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p20" id="p20_frecuente" value="3" required>
                                     <label class="form-check-label" for="p20_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p20" id="p20_rutina" value="4" required>
                                     <label class="form-check-label" for="p20_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">21. Miras hacia el futuro</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p21" id="p21_nunca" value="1" required>
                                     <label class="form-check-label" for="p21_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p21" id="p21_aveces" value="2" required>
                                     <label class="form-check-label" for="p21_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p21" id="p21_frecuente" value="3" required>
                                     <label class="form-check-label" for="p21_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p21" id="p21_rutina" value="4" required>
                                     <label class="form-check-label" for="p21_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">22. Participas en programas o actividades de ejercicio físico bajo supervisión</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p22" id="p22_nunca" value="1" required>
                                     <label class="form-check-label" for="p22_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p22" id="p22_aveces" value="2" required>
                                     <label class="form-check-label" for="p22_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p22" id="p22_frecuente" value="3" required>
                                     <label class="form-check-label" for="p22_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p22" id="p22_rutina" value="4" required>
                                     <label class="form-check-label" for="p22_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">23. Eres consciente de lo que te importa en la vida</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p23" id="p23_nunca" value="1" required>
                                     <label class="form-check-label" for="p23_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p23" id="p23_aveces" value="2" required>
                                     <label class="form-check-label" for="p23_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p23" id="p23_frecuente" value="3" required>
                                     <label class="form-check-label" for="p23_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p23" id="p23_rutina" value="4" required>
                                     <label class="form-check-label" for="p23_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">24. Te gusta expresar y que te expresen cariño personas cercanas a ti</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p24" id="p24_nunca" value="1" required>
                                     <label class="form-check-label" for="p24_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p24" id="p24_aveces" value="2" required>
                                     <label class="form-check-label" for="p24_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p24" id="p24_frecuente" value="3" required>
                                     <label class="form-check-label" for="p24_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p24" id="p24_rutina" value="4" required>
                                     <label class="form-check-label" for="p24_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">25. Mantienes relaciones interpersonales que te dan satisfacción</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p25" id="p25_nunca" value="1" required>
                                     <label class="form-check-label" for="p25_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p25" id="p25_aveces" value="2" required>
                                     <label class="form-check-label" for="p25_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p25" id="p25_frecuente" value="3" required>
                                     <label class="form-check-label" for="p25_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p25" id="p25_rutina" value="4" required>
                                     <label class="form-check-label" for="p25_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">26. Incluyes en tu dieta alimentos que contienen fibra (ejemplo: granos enteros, frutas crudas, verduras crudas)</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p26" id="p26_nunca" value="1" required>
                                     <label class="form-check-label" for="p26_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p26" id="p26_aveces" value="2" required>
                                     <label class="form-check-label" for="p26_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p26" id="p26_frecuente" value="3" required>
                                     <label class="form-check-label" for="p26_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p26" id="p26_rutina" value="4" required>
                                     <label class="form-check-label" for="p26_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>
                    
                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">27. Pasas de 15 a 20 minutos diariamente en relajamiento o meditación</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p27" id="p27_nunca" value="1" required>
                                     <label class="form-check-label" for="p27_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p27" id="p27_aveces" value="2" required>
                                     <label class="form-check-label" for="p27_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p27" id="p27_frecuente" value="3" required>
                                     <label class="form-check-label" for="p27_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p27" id="p27_rutina" value="4" required>
                                     <label class="form-check-label" for="p27_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">28. Discutes con profesionales calificados tus inquietudes respecto al cuidado de tu salud</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p28" id="p28_nunca" value="1" required>
                                     <label class="form-check-label" for="p28_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p28" id="p28_aveces" value="2" required>
                                     <label class="form-check-label" for="p28_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p28" id="p28_frecuente" value="3" required>
                                     <label class="form-check-label" for="p28_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p28" id="p28_rutina" value="4" required>
                                     <label class="form-check-label" for="p28_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">29. Respetas tus propios éxitos</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p29" id="p29_nunca" value="1" required>
                                     <label class="form-check-label" for="p29_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p29" id="p29_aveces" value="2" required>
                                     <label class="form-check-label" for="p29_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p29" id="p29_frecuente" value="3" required>
                                     <label class="form-check-label" for="p29_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p29" id="p29_rutina" value="4" required>
                                     <label class="form-check-label" for="p29_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>
                       
                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">30. Checas tu pulso durante el ejercicio físico</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p30" id="p30_nunca" value="1" required>
                                     <label class="form-check-label" for="p30_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p30" id="p30_aveces" value="2" required>
                                     <label class="form-check-label" for="p30_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p30" id="p30_frecuente" value="3" required>
                                     <label class="form-check-label" for="p30_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p30" id="p30_rutina" value="4" required>
                                     <label class="form-check-label" for="p30_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">31. Pasas tiempo con amigos cercanos</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p31" id="p31_nunca" value="1" required>
                                     <label class="form-check-label" for="p31_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p31" id="p31_aveces" value="2" required>
                                     <label class="form-check-label" for="p31_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p31" id="p31_frecuente" value="3" required>
                                     <label class="form-check-label" for="p31_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p31" id="p31_rutina" value="4" required>
                                     <label class="form-check-label" for="p31_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">32. Haces medir tu presión arterial y sabes el resultado</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p32" id="p32_nunca" value="1" required>
                                     <label class="form-check-label" for="p32_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p32" id="p32_aveces" value="2" required>
                                     <label class="form-check-label" for="p32_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p32" id="p32_frecuente" value="3" required>
                                     <label class="form-check-label" for="p32_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p32" id="p32_rutina" value="4" required>
                                     <label class="form-check-label" for="p32_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">33. Asistes a programas educativos sobre el mejoramiento del medio ambiente en que vives</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p33" id="p33_nunca" value="1" required>
                                     <label class="form-check-label" for="p33_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p33" id="p33_aveces" value="2" required>
                                     <label class="form-check-label" for="p33_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p33" id="p33_frecuente" value="3" required>
                                     <label class="form-check-label" for="p33_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p33" id="p33_rutina" value="4" required>
                                     <label class="form-check-label" for="p33_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">34. Ves cada día como interesante y desafiante</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p34" id="p34_nunca" value="1" required>
                                     <label class="form-check-label" for="p34_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p34" id="p34_aveces" value="2" required>
                                     <label class="form-check-label" for="p34_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p34" id="p34_frecuente" value="3" required>
                                     <label class="form-check-label" for="p34_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p34" id="p34_rutina" value="4" required>
                                     <label class="form-check-label" for="p34_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">35. Planeas o escoges comidas que incluyan los cuatro grupos básicos de nutrientes cada día (Proteínas, carbohidratos, grasas y vitaminas)</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p35" id="p35_nunca" value="1" required>
                                     <label class="form-check-label" for="p35_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p35" id="p35_aveces" value="2" required>
                                     <label class="form-check-label" for="p35_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p35" id="p35_frecuente" value="3" required>
                                     <label class="form-check-label" for="p35_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p35" id="p35_rutina" value="4" required>
                                     <label class="form-check-label" for="p35_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">36. Relajas conscientemente tus musculos antes de dormir</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p36" id="p36_nunca" value="1" required>
                                     <label class="form-check-label" for="p36_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p36" id="p36_aveces" value="2" required>
                                     <label class="form-check-label" for="p36_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p36" id="p36_frecuente" value="3" required>
                                     <label class="form-check-label" for="p36_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p36" id="p36_rutina" value="4" required>
                                     <label class="form-check-label" for="p36_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">37. Encuentras agradable y satisfecho el ambiente de tu vida</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p37" id="p37_nunca" value="1" required>
                                     <label class="form-check-label" for="p37_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p37" id="p37_aveces" value="2" required>
                                     <label class="form-check-label" for="p37_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p37" id="p37_frecuente" value="3" required>
                                     <label class="form-check-label" for="p37_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p37" id="p37_rutina" value="4" required>
                                     <label class="form-check-label" for="p37_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">38. Realizas actividades físicas de recreo como caminar, nadar, jugar futbol, ciclisco</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p38" id="p38_nunca" value="1" required>
                                     <label class="form-check-label" for="p38_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p38" id="p38_aveces" value="2" required>
                                     <label class="form-check-label" for="p38_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p38" id="p38_frecuente" value="3" required>
                                     <label class="form-check-label" for="p38_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p38" id="p38_rutina" value="4" required>
                                     <label class="form-check-label" for="p38_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">39. Expresas fácilmente interés, amor y calor humano hacia otros</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p39" id="p39_nunca" value="1" required>
                                     <label class="form-check-label" for="p39_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p39" id="p39_aveces" value="2" required>
                                     <label class="form-check-label" for="p39_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p39" id="p39_frecuente" value="3" required>
                                     <label class="form-check-label" for="p39_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p39" id="p39_rutina" value="4" required>
                                     <label class="form-check-label" for="p39_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">40. Te concentras en pensamientos agradables a la hora de dormir</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p40" id="p40_nunca" value="1" required>
                                     <label class="form-check-label" for="p40_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p40" id="p40_aveces" value="2" required>
                                     <label class="form-check-label" for="p40_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p40" id="p40_frecuente" value="3" required>
                                     <label class="form-check-label" for="p40_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p40" id="p40_rutina" value="4" required>
                                     <label class="form-check-label" for="p40_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">41. Pides información a los profesionales para cuidar de tu salud</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p41" id="p41_nunca" value="1" required>
                                     <label class="form-check-label" for="p41_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p41" id="p41_aveces" value="2" required>
                                     <label class="form-check-label" for="p41_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p41" id="p41_frecuente" value="3" required>
                                     <label class="form-check-label" for="p41_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p41" id="p41_rutina" value="4" required>
                                     <label class="form-check-label" for="p41_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">42. Encuentras maneras positivas para expresar tus sentimientos</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p42" id="p42_nunca" value="1" required>
                                     <label class="form-check-label" for="p42_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p42" id="p42_aveces" value="2" required>
                                     <label class="form-check-label" for="p42_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p42" id="p42_frecuente" value="3" required>
                                     <label class="form-check-label" for="p42_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p42" id="p42_rutina" value="4" required>
                                     <label class="form-check-label" for="p42_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>


                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">43. Observas al menos cada mes tu cuerpo para ver cambios físicos o señas de peligro</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p43" id="p43_nunca" value="1" required>
                                     <label class="form-check-label" for="p43_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p43" id="p43_aveces" value="2" required>
                                     <label class="form-check-label" for="p43_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p43" id="p43_frecuente" value="3" required>
                                     <label class="form-check-label" for="p43_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p43" id="p43_rutina" value="4" required>
                                     <label class="form-check-label" for="p43_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">44. Eres realista en las metas que te propones</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p44" id="p44_nunca" value="1" required>
                                     <label class="form-check-label" for="p44_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p44" id="p44_aveces" value="2" required>
                                     <label class="form-check-label" for="p44_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p44" id="p44_frecuente" value="3" required>
                                     <label class="form-check-label" for="p44_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p44" id="p44_rutina" value="4" required>
                                     <label class="form-check-label" for="p44_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">45. Usas métodos específicos para controlar la tensión (nervios)</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p45" id="p45_nunca" value="1" required>
                                     <label class="form-check-label" for="p45_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p45" id="p45_aveces" value="2" required>
                                     <label class="form-check-label" for="p45_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p45" id="p45_frecuente" value="3" required>
                                     <label class="form-check-label" for="p45_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p45" id="p45_rutina" value="4" required>
                                     <label class="form-check-label" for="p45_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">46. Asistes a programas educativos sobre el cuidado de la salud personal</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p46" id="p46_nunca" value="1" required>
                                     <label class="form-check-label" for="p46_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p46" id="p46_aveces" value="2" required>
                                     <label class="form-check-label" for="p46_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p46" id="p46_frecuente" value="3" required>
                                     <label class="form-check-label" for="p46_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p46" id="p46_rutina" value="4" required>
                                     <label class="form-check-label" for="p46_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro">
                            <label for="p6" class="form-label">47. Te gusta mostrar y que te muestren afecto con palmadas, abrazos y caricias, por personas que te importan 
                                (papás, familiares, profesores y amigos)</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p47" id="p47_nunca" value="1" required>
                                     <label class="form-check-label" for="p47_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p47" id="p47_aveces" value="2" required>
                                     <label class="form-check-label" for="p47_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p47" id="p47_frecuente" value="3" required>
                                     <label class="form-check-label" for="p47_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p47" id="p47_rutina" value="4" required>
                                     <label class="form-check-label" for="p47_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="mt-3 cuadro" style="margin-bottom: 20px;">
                            <label for="p6" class="form-label">48. Crees que tu vida tiene un propósito</label>
                            <div>
                                <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p48" id="p48_nunca" value="1" required>
                                     <label class="form-check-label" for="p48_nunca">Nunca</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p48" id="p48_aveces" value="2" required>
                                     <label class="form-check-label" for="p48_aveces">A veces</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p48" id="p48_frecuente" value="3" required>
                                     <label class="form-check-label" for="p48_frecuente">Frecuentemente</label>
                                 </div>
                                 <div class="form-check form-check-inline">
                                     <input class="form-check-input colorradio" type="radio" name="p48" id="p48_rutina" value="4" required>
                                     <label class="form-check-label" for="p48_rutina">Rutinariamente</label>
                                 </div>
                            </div>
                        </div>

                        <div class="text-center" style="margin-bottom: 40px;" id="enviarResultados">
                            <button class="btn btn-outline-primary colorboton">Enviar Resultados</button>
                        </div>
                        

                        <div class="text-center" id="gracias" style="display: none;">
                            <h2>Muchas gracias por presentar el cuestionario, por favor dirígete con el encargado del proyecto para saber que más hacer</h2>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>        
    </body>
</html>