<?php
// Configuración de errores y output buffering
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
set_time_limit(60);
ini_set('memory_limit', '256M');

// Función para garantizar que SIEMPRE se envíe JSON
function enviarJSON($data, $statusCode = 200) {
    if (!headers_sent()) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
    }
    echo json_encode($data);
    exit;
}

// Manejador de errores fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log("Error fatal: " . print_r($error, true));
        enviarJSON(['error' => 'Error fatal del servidor: ' . $error['message']], 500);
    }
});

ob_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require('fpdf/fpdf.php');

ob_end_clean();
header('Content-Type: application/json; charset=UTF-8');

// Validar método
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    enviarJSON(['error' => 'Método no permitido'], 405);
}

// Validar datos requeridos
if (empty($_POST['matricula'])) {
    enviarJSON(['error' => 'Falta la matrícula'], 400);
}

$servername = "pdb1042.awardspace.net";
$username = "4528622_pisi";
$password = "sklike5522";
$database = "4528622_pisi";

try {
    error_log("=== INICIO GUARDADO - Matrícula: " . $_POST['matricula'] . " ===");
    
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        throw new Exception('Conexión fallida: ' . $conn->connect_error);
    }

    // PASO 1: Guardar datos
    $sql_guardar = "INSERT INTO datos_fisicos_alumnos 
            (matricula_alum, fecha, cintura, cadera, clasificacion_cintura_cadera, icc, clasificacion_de_icc, peso, talla, imc, clasificacion_imc, ice, mb, actividad1, get1, porcentaje_masa_grasa, valor_ideal_porcentaje_grasa,
            clasificacion_porcentaje_grasa, masa_magra, agua_total, porcentaje_agua_total, glucosa, clasificacion_glucosa, trigliceridos, clasificacion_trigliceridos, colesterol, clasificacion_colesterol, tension_arterial, clasificacion_tension_arterial) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_guardar);
    if (!$stmt) {
        throw new Exception('Error al preparar consulta: ' . $conn->error);
    }

    $fecha_actual = date("Y-m-d");
    $stmt->bind_param(
        "isddsdsdddsddsdssssdddsdsdsss",
        $_POST["matricula"],
        $fecha_actual,
        $_POST["cintura1"],
        $_POST["cadera1"],
        $_POST["clasificacioncadcin1"],
        $_POST["icc1"],
        $_POST["clasificacionicc1"],
        $_POST["peso1"],
        $_POST["talla1"],
        $_POST["imc1"],
        $_POST["clasificacionimc1"],
        $_POST["ice"],
        $_POST["mb1"],
        $_POST["actividad1"],
        $_POST["get1"],
        $_POST["pormasagrasa1"],
        $_POST["valoridealgrasa1"],
        $_POST["clasificaciongrasa1"],
        $_POST["masamagra1"],
        $_POST["aguatotal1"],
        $_POST["porcentajeaguatotal1"],
        $_POST["glucosa1"],
        $_POST["clasificacionglucosa1"],
        $_POST["trigliceridos1"],
        $_POST["clasificaciontrigliceridos1"],
        $_POST["colesterol1"],
        $_POST["clasificacioncolesterol1"],
        $_POST["tensionarterial1"],
        $_POST["clasificacionta1"]
    );

    if (!$stmt->execute()) {
        throw new Exception('Error al guardar: ' . $stmt->error);
    }
    $stmt->close();
    error_log("Datos guardados en BD exitosamente");

    $matricula = $_POST['matricula'];
    $destinatario_email = $_POST['correo1'];

    // PASO 2: Crear carpeta de PDFs si no existe
    $carpetaPDFs = __DIR__ . '/reportes_salud';
    if (!file_exists($carpetaPDFs)) {
        if (!mkdir($carpetaPDFs, 0755, true)) {
            error_log("No se pudo crear carpeta: " . $carpetaPDFs);
            throw new Exception('Error al crear carpeta de reportes');
        }
    }

    // PASO 3: Obtener datos consolidados
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $url_api = $protocol . "://" . $host . "/resultadospdf.php?matricula_alum=" . urlencode($matricula);
    
    error_log("Consultando API: " . $url_api);
    
    // Usar cURL en lugar de file_get_contents para mejor control
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $json_data = curl_exec($ch);
    $curl_error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($json_data === false) {
        throw new Exception('Error al contactar servicio de reportes: ' . $curl_error);
    }

    if ($http_code !== 200) {
        throw new Exception('Servicio de reportes respondió con código: ' . $http_code);
    }

    $datos_pdf = json_decode($json_data, true);
    if (!$datos_pdf || isset($datos_pdf['error'])) {
        throw new Exception('No se encontraron datos consolidados: ' . ($datos_pdf['error'] ?? 'Respuesta vacía'));
    }
    error_log("Datos consolidados obtenidos");

    // PASO 4: Generar PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Reporte de Salud Integral - UNACAR'), 0, 1, 'C');
    $pdf->Ln(10);

    function addRow($pdf, $label, $value, $classification = '') {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 7, utf8_decode($label . ':'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 7, utf8_decode($value . ($classification ? " ($classification)" : "")), 0, 1);
    }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Datos del Alumno', 0, 1);
    addRow($pdf, 'Nombre', $datos_pdf['nombres_alum'] . ' ' . $datos_pdf['ape_paterno_alum'] . ' ' . $datos_pdf['ape_materno_alum']);
    addRow($pdf, 'Matricula', $datos_pdf['matricula_alum']);
    addRow($pdf, 'Correo', $datos_pdf['correo_alum']);
    addRow($pdf, 'Edad', $datos_pdf['edad_alum']);
    addRow($pdf, 'Facultad', $datos_pdf['nombre_facultad']);
    addRow($pdf, 'Carrera', $datos_pdf['nombre_carrera']);
    addRow($pdf, 'Fecha', $datos_pdf['fecha']);

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Indicadores de Salud Fisica'), 0, 1);
    addRow($pdf, 'IMC', $datos_pdf['imc'], $datos_pdf['clasificacion_imc']);
    addRow($pdf, 'ICC', $datos_pdf['icc'], $datos_pdf['clasificacion_de_icc']);
    addRow($pdf, 'ICE', $datos_pdf['ice']);
    addRow($pdf, 'Glucosa', $datos_pdf['glucosa'], $datos_pdf['clasificacion_glucosa']);
    addRow($pdf, 'Colesterol', $datos_pdf['colesterol'], $datos_pdf['clasificacion_colesterol']);
    addRow($pdf, 'Trigliceridos', $datos_pdf['trigliceridos'], $datos_pdf['clasificacion_trigliceridos']);
    addRow($pdf, 'Tension Arterial', $datos_pdf['tension_arterial'], $datos_pdf['clasificacion_tension_arterial']);

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Perfil Estilo de Vida'), 0, 1);
    addRow($pdf, 'Nutricion', $datos_pdf['total_nutricion'], $datos_pdf['saludable_nutricion']);
    addRow($pdf, 'Ejercicio', $datos_pdf['total_ejercicio'], $datos_pdf['saludable_ejercicio']);
    addRow($pdf, 'Salud', $datos_pdf['total_salud'], $datos_pdf['saludable_salud']);
    addRow($pdf, 'Soporte Interpersonal', $datos_pdf['total_soporte'], $datos_pdf['saludable_soporte']);
    addRow($pdf, 'Manejo de Estres', $datos_pdf['total_manejoestres'], $datos_pdf['saludable_manejo']);
    addRow($pdf, 'Autoactualizacion', $datos_pdf['total_autoactualizacion'], $datos_pdf['saludable_autoactualizacion']);

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, utf8_decode('Perfil DASS'), 0, 1);
    addRow($pdf, 'Ansiedad', $datos_pdf['total_ansiedad'], $datos_pdf['severidad_ansiedad']);
    addRow($pdf, 'Estres', $datos_pdf['total_estres'], $datos_pdf['severidad_estres']);
    addRow($pdf, 'Depresion', $datos_pdf['total_depresion'], $datos_pdf['severidad_depresion']);

    // GUARDAR PDF EN CARPETA PERMANENTE
    $matricula_sanitizada = preg_replace('/[^a-zA-Z0-9_-]/', '', $matricula);
    $timestamp = date('Y-m-d_H-i-s');
    $nombreArchivo = 'reporte_' . $matricula_sanitizada . '.pdf';
    $rutaPDF = $carpetaPDFs . '/' . $nombreArchivo;

    $pdf->Output('F', $rutaPDF);

    if (!file_exists($rutaPDF) || filesize($rutaPDF) == 0) {
        throw new Exception('Error al crear el PDF');
    }
    error_log("PDF creado: " . $rutaPDF . " (" . filesize($rutaPDF) . " bytes)");

    // PASO 5: Enviar correo con mejores configuraciones
    $correoEnviado = false;
    $errorCorreo = '';
    
    try {
        // Capturar cualquier salida de debug en buffer
        ob_start();
        
        $mail = new PHPMailer(true);
        
        // IMPORTANTE: SMTPDebug = 0 para producción (sin mensajes en pantalla)
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer [$level]: $str");
        };
        
        $mail->isSMTP();
        $mail->Host = 'mail.sistema-integral-de-salud-unacar.com.mx';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@sistema-integral-de-salud-unacar.com.mx';
        $mail->Password = 'sklike5522';
        
        // Intentar múltiples configuraciones
        $configuraciones = [
            ['port' => 587, 'secure' => PHPMailer::ENCRYPTION_STARTTLS],
            ['port' => 465, 'secure' => PHPMailer::ENCRYPTION_SMTPS],
            ['port' => 25, 'secure' => PHPMailer::ENCRYPTION_STARTTLS],
            ['port' => 25, 'secure' => '']
        ];
        
        $conectado = false;
        foreach ($configuraciones as $config) {
            try {
                $mail->Port = $config['port'];
                $mail->SMTPSecure = $config['secure'];
                
                error_log("Intentando puerto {$config['port']} con " . ($config['secure'] ?: 'sin encriptación'));
                
                // Configuraciones adicionales
                $mail->CharSet = 'UTF-8';
                $mail->Timeout = 20;
                $mail->SMTPKeepAlive = false;
                
                // Opciones SSL/TLS flexibles
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->setFrom('noreply@sistema-integral-de-salud-unacar.com.mx', 'Sistema Integral de Salud UNACAR');
                $mail->addAddress($destinatario_email);
                $mail->addAttachment($rutaPDF, $nombreArchivo);
                
                $mail->isHTML(true);
                $mail->Subject = 'Resultados de tu Evaluacion de Salud - UNACAR';
                $mail->Body = 'Hola,<br><br>Adjunto encontraras tu reporte de salud integral.<br><br>Saludos,<br><b>Equipo de Salud UNACAR</b>';
                $mail->AltBody = 'Hola, Adjunto encontraras tu reporte de salud integral. Saludos, Equipo de Salud UNACAR';

                if ($mail->send()) {
                    $conectado = true;
                    $correoEnviado = true;
                    error_log("✅ Correo enviado exitosamente con puerto {$config['port']}");
                    break;
                }
            } catch (Exception $e) {
                error_log("Fallo puerto {$config['port']}: " . $e->getMessage());
                // Continuar con siguiente configuración
                $mail->clearAddresses();
                $mail->clearAttachments();
                continue;
            }
        }
        
        if (!$conectado) {
            throw new Exception("No se pudo conectar con ninguna configuración SMTP");
        }
        
        // Limpiar buffer de debug
        ob_end_clean();
        
    } catch (Exception $e) {
        ob_end_clean(); // Limpiar buffer también en caso de error
        $errorCorreo = $e->getMessage();
        error_log("❌ Error al enviar correo: " . $errorCorreo);
    }

    $conn->close();
    
    // URL para descargar el PDF
    $urlDescarga = $protocol . "://" . $host . "/reportes_salud/" . $nombreArchivo;
    
    // Respuesta final
    if ($correoEnviado) {
        $mensaje = 'Datos guardados y reporte enviado por correo exitosamente.';
    } else {
        $mensaje = 'Datos guardados. PDF disponible para descarga.';
    }
    
    error_log("=== FIN GUARDADO EXITOSO ===");
    enviarJSON([
        'success' => $mensaje,
        'pdf_url' => $urlDescarga,
        'pdf_nombre' => $nombreArchivo,
        'correo_enviado' => $correoEnviado,
        'error_correo' => $errorCorreo
    ]);

} catch (Exception $e) {
    error_log("ERROR: " . $e->getMessage());
    error_log("Stack: " . $e->getTraceAsString());
    if (isset($conn)) {
        $conn->close();
    }
    enviarJSON(['error' => $e->getMessage()], 500);
}
?>