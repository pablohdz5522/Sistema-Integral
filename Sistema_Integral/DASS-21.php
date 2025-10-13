<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['alumno']) || !isset($_SESSION['alumno']['matricula'])) {
    header("Location: registro.php");
    exit();
}

$alumno = $_SESSION['alumno'];
$matricula = $alumno['matricula'];

$servername = "pdb1042.awardspace.net";
$username = "4528622_pisi";
$password = "sklike5522";
$database = "4528622_pisi";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql_verificar = "SELECT id_cuestionario FROM dass WHERE matricula_alum = ?";
$stmt_verificar = $conn->prepare($sql_verificar);
$stmt_verificar->bind_param("i", $matricula);
$stmt_verificar->execute();
$resultado = $stmt_verificar->get_result();

if ($resultado->num_rows > 0) {
    $stmt_verificar->close();
    $conn->close();
    header("Location: menuAlumno.php");
    exit();
}
$stmt_verificar->close();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = range(1, 21);
    $all_valid = true;
    foreach ($required_fields as $num) {
        $key = 'p' . $num;
        if (!isset($_POST[$key]) || !is_numeric($_POST[$key]) || $_POST[$key] < 0 || $_POST[$key] > 3) {
            $all_valid = false;
            break;
        }
    }

    if (!$all_valid) {
        $error_message = "Error: Todas las preguntas deben ser respondidas con un valor entre 0 y 3.";
    } else {
        // Respuestas
        for ($i = 1; $i <= 21; $i++) {
            ${"p$i"} = (int) $_POST["p$i"];
        }

        // Depresión
        $total_depresion = $p3 + $p5 + $p10 + $p13 + $p16 + $p17 + $p21;
        $severidad_dep = ($total_depresion <= 4) ? 'Normal' :
            (($total_depresion <= 6) ? 'Leve' :
                (($total_depresion <= 10) ? 'Moderado' :
                    (($total_depresion <= 13) ? 'Severo' : 'Extremadamente Severo')));

        // Ansiedad
        $total_ansiedad = $p2 + $p4 + $p7 + $p9 + $p15 + $p19 + $p20;
        $severidad_ans = ($total_ansiedad <= 3) ? 'Normal' :
            (($total_ansiedad <= 4) ? 'Leve' :
                (($total_ansiedad <= 7) ? 'Moderado' :
                    (($total_ansiedad <= 9) ? 'Severo' : 'Extremadamente Severo')));

        // Estrés
        $total_estres = $p1 + $p6 + $p8 + $p11 + $p12 + $p14 + $p18;
        $severidad_estres = ($total_estres <= 7) ? 'Normal' :
            (($total_estres <= 9) ? 'Leve' :
                (($total_estres <= 12) ? 'Moderado' :
                    (($total_estres <= 16) ? 'Severo' : 'Extremadamente Severo')));

        $total_general = $total_depresion + $total_ansiedad + $total_estres;

        // Insertar en tabla principal
        $sql_cuestionario = "INSERT INTO dass (matricula_alum, total_depresion, total_ansiedad, total_estres, total_general) VALUES (?, ?, ?, ?, ?)";
        $stmt_cuestionario = $conn->prepare($sql_cuestionario);
        $stmt_cuestionario->bind_param("iiiii", $matricula, $total_depresion, $total_ansiedad, $total_estres, $total_general);

        if ($stmt_cuestionario->execute()) {
            $id_cuestionario = $conn->insert_id;
            $stmt_cuestionario->close();

            // Insertar en dass_depresion
            $sql_dep = "INSERT INTO dass_depresion (id_cuestionario, p3, p5, p10, p13, p16, p17, p21, total_depresion, severidad)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_dep = $conn->prepare($sql_dep);
            $stmt_dep->bind_param("iiiiiiiiss", $id_cuestionario, $p3, $p5, $p10, $p13, $p16, $p17, $p21, $total_depresion, $severidad_dep);
            $stmt_dep->execute();
            $stmt_dep->close();

            // Insertar en dass_ansiedad
            $sql_ans = "INSERT INTO dass_ansiedad (id_cuestionario, p2, p4, p7, p9, p15, p19, p20, total_ansiedad, severidad)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_ans = $conn->prepare($sql_ans);
            $stmt_ans->bind_param("iiiiiiiiss", $id_cuestionario, $p2, $p4, $p7, $p9, $p15, $p19, $p20, $total_ansiedad, $severidad_ans);
            $stmt_ans->execute();
            $stmt_ans->close();

            // Insertar en dass_estres
            $sql_est = "INSERT INTO dass_estres (id_cuestionario, p1, p6, p8, p11, p12, p14, p18, total_estres, severidad)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_est = $conn->prepare($sql_est);
            $stmt_est->bind_param("iiiiiiiiss", $id_cuestionario, $p1, $p6, $p8, $p11, $p12, $p14, $p18, $total_estres, $severidad_estres);
            $stmt_est->execute();
            $stmt_est->close();

            $conn->close();
            header("Location: menuAlumno.php");
            exit();
        } else {
            $error_message = "Error al guardar los datos del formulario. Por favor, intenta de nuevo.";
        }
    }
}
$conn->close();
?>



<!doctype html>
<html lang="es">

<head>
    <title>DASS</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="PEPS-1.css" rel="stylesheet" />
</head>

<body
    style="background: linear-gradient(278deg, rgba(23, 19, 235, 0.50) 13.7%, rgba(255, 255, 255, 0.25) 13.7%), linear-gradient(263deg, rgba(71, 15, 255, 0.00) 87.02%, rgba(71, 15, 255, 0.50) 87.03%), linear-gradient(277deg, rgba(255, 242, 0, 0.00) 89.31%, #FFF600 89.63%), linear-gradient(87deg, #FFF 88.1%, #FDEE18 88.48%); margin: 0;height: 100%; min-height: 100vh;">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-8 col-sm-10 col-12">

                <div class="text-center" style="margin-top: 35px;">
                    <h3>Cuestionario DASS</h3>
                </div>
                <div class="mt-4" style="display: block;" id="instrucciones">
                    <p style="font-weight: bolder;">Instrucciones:</p>
                    <p style="margin-top: -15px; font-weight: bolder;">Por favor lea las siguientes afirmaciones y
                        indica lo que le ha ocurrido a usted esta afirmacion *durante la semana pasada*.</p>
                    <p style="margin-top: -15px; font-weight: bolder;">La escala de calificación se encuentra en el
                        boton.</p>
                </div>

                <br>
                <form action="DASS-21.php" method="post" style="display: block;" id="formulario">

                    <div class="mt-2 cuadro">
                        <label for="p1" class="form-label">1. Me ha costado mucho descargar la tensión...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p1" id="p1_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p1_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p1" id="p1_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p1_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p1" id="p1_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p1_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p1" id="p1_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p1_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>


                    <div class="mt-3 cuadro">
                        <label for="p2" class="form-label">2. Me di cuenta que tenía la boca seca...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p2" id="p2_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p2_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p2" id="p2_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p2_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p2" id="p2_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p2_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p2" id="p2_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p2_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p3" class="form-label">3. No podía sentir ningún sentimiento positivo...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p3" id="p3_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p3_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p3" id="p3_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p3_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p3" id="p3_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p3_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p3" id="p3_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p3_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p4" class="form-label">4. Se me hizo difícil respirar...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p4" id="p4_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p4_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p4" id="p4_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p4_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p4" id="p4_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p4_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p4" id="p4_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p4_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p5" class="form-label">5. Se me hizo difícil tomar la iniciativa para hacer
                            cosas...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p5" id="p5_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p5_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p5" id="p5_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p5_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p5" id="p5_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p5_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p5" id="p5_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p5_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">6. Reaccioné exageradamente en ciertas situaciones...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p6" id="p6_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p6_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p6" id="p6_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p6_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p6" id="p6_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p6_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p6" id="p6_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p6_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">7. Sentí que mis manos temblaban...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p7" id="p7_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p7_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p7" id="p7_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p7_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p7" id="p7_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p7_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p7" id="p7_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p7_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">8. He sentido que estaba gastando una gran cantidad de
                            energía...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p8" id="p8_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p8_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p8" id="p8_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p8_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p8" id="p8_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p8_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p8" id="p8_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p8_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">9. Estaba preocupado por situaciones en las cuales podía
                            tener pánico o en las que podría
                            hacer el ridículo...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p9" id="p9_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p9_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p9" id="p9_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p9_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p9" id="p9_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p9_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p9" id="p9_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p9_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">10. He sentido que no había nada que me ilusionara...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p10" id="p10_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p10_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p10" id="p10_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p10_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p10" id="p10_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p10_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p10" id="p10_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p10_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">11. Me he sentido inquieto...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p11" id="p11_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p11_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p11" id="p11_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p11_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p11" id="p11_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p11_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p11" id="p11_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p11_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">12. Se me hizo difícil relajarme...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p12" id="p12_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p12_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p12" id="p12_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p12_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p12" id="p12_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p12_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p12" id="p12_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p12_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">13. Me sentí triste y deprimido...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p13" id="p13_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p13_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p13" id="p13_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p13_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p13" id="p13_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p13_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p13" id="p13_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p13_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">14. No toleré nada que no me permitiera continuar con lo que
                            estaba haciendo...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p14" id="p14_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p14_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p14" id="p14_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p14_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p14" id="p14_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p14_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p14" id="p14_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p14_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">15. Sentí que estaba al punto de pánico...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p15" id="p15_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p15_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p15" id="p15_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p15_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p15" id="p15_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p15_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p15" id="p15_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p15_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">16. No me pude entusiasmar por nada...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p16" id="p16_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p16_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p16" id="p16_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p16_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p16" id="p16_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p16_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p16" id="p16_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p16_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">17. Sentí que valía muy poco como persona...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p17" id="p17_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p17_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p17" id="p17_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p17_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p17" id="p17_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p17_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p17" id="p17_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p17_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">18. He tendido a sentirme enfadado con facilidad...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p18" id="p18_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p18_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p18" id="p18_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p18_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p18" id="p18_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p18_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p18" id="p18_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p18_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">19. Sentí los latidos de mi corazón a pesar de no haber hecho
                            ningún esfuerzo físico...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p19" id="p19_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p19_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p19" id="p19_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p19_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p19" id="p19_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p19_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p19" id="p19_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p19_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">20. Tuve miedo sin razón...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p20" id="p20_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p20_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p20" id="p20_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p20_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p20" id="p20_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p20_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p20" id="p20_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p20_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 cuadro">
                        <label for="p6" class="form-label">21. Sentí que la vida no tenía ningún sentido...</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p21" id="p21_nunca"
                                    value="0" required>
                                <label class="form-check-label" for="p21_nunca">No me ha ocurrido</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p21" id="p21_aveces"
                                    value="1" required>
                                <label class="form-check-label" for="p21_aveces">Me ha ocurrido un poco</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p21" id="p21_frecuente"
                                    value="2" required>
                                <label class="form-check-label" for="p21_frecuente">Me ha ocurrido bastante</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input colorradio" type="radio" name="p21" id="p21_rutina"
                                    value="3" required>
                                <label class="form-check-label" for="p21_rutina">Me ha ocurrido mucho</label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center" style="margin-bottom: 40px;" id="enviarResultados">
                        <button class="btn btn-outline-primary colorboton" type="submit">Enviar Resultados</button>
                    </div>


                    <div class="text-center" id="gracias" style="display: none;">
                        <h2>Muchas gracias por presentar el cuestionario, por favor dirígete con el encargado del
                            proyecto para saber que más hacer</h2>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
</body>

</html>