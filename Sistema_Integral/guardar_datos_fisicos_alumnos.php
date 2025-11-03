<?php
// Configuración de errores y output buffering
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
set_time_limit(60);
ini_set('memory_limit', '256M');

// Definición de Colores
define('COLOR_PRIMARY', [30, 70, 120]); // Azul Oscuro (Para títulos y énfasis)
define('COLOR_BACKGROUND', [248, 248, 248]); // Fondo muy claro
define('COLOR_LABEL_BG', [240, 240, 240]); // Fondo para etiquetas de datos
define('COLOR_SEPARATOR', [180, 180, 180]); // Gris suave para líneas


// Función para garantizar que SIEMPRE se envíe JSON
function enviarJSON($data, $statusCode = 200)
{
    if (!headers_sent()) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
    }
    echo json_encode($data);
    exit;
}

// Manejador de errores fatales
register_shutdown_function(function () {
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

function pdf_text($text)
{
    // Asegura la codificación para FPDF
    return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
}


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

// =================================================================
// FUNCIÓN DE LAYOUT: addGridRow Para las tablas.
// =================================================================

function addGridRow($pdf, $label1, $value1, $label2, $value2, $line_break = true)
{
    // Configuración de colores
    $COLOR_GRID_BG = [240, 240, 240]; // Gris muy claro para el fondo de la etiqueta

    // Configuración para Columna 1
    $pdf->SetFillColor($COLOR_GRID_BG[0], $COLOR_GRID_BG[1], $COLOR_GRID_BG[2]);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(45, 6, pdf_text($label1 . ':'), 'B', 0, 'L', true);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(45, 6, pdf_text($value1), 'B', 0, 'L');

    // Configuración para Columna 2
    if ($label2 || $value2) {
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetX(100); // Mueve a la segunda columna (asumiendo 100mm de inicio)
        $pdf->SetFillColor($COLOR_GRID_BG[0], $COLOR_GRID_BG[1], $COLOR_GRID_BG[2]);
        $pdf->Cell(45, 6, pdf_text($label2 . ':'), 'B', 0, 'L', true);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 6, pdf_text($value2), 'B', $line_break ? 1 : 0, 'L');
    } else {
        // Si no hay segunda columna, simplemente terminamos la línea actual
        $pdf->Ln(6);
    }
}

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
    class PDF extends FPDF
    {

        private $RUTA_IMAGEN_FONDO;
        private $RUTA_LOGO;

        public $CAT_FISICAS = [
            'Peso insuficiente',
            'Peso normal',
            'Sobrepeso',
            'Obesidad grado 1',
            'Obesidad grado 2',
            'Obesidad grado 3 (mórbida)'
        ];

        // REEMPLAZAR (Línea 223)
        public $CAT_DASS = [
            'Normal',
            'Leve',
            'Moderado',
            'Severo',
            'Extremadamente severo'
        ];
        public $ANCHO_TOTAL_BARRA = 150;

        public $COLOR_TITULO_BG = [34, 49, 63]; // Azul Oscuro 
        public $COLOR_TITULO_TEXT = [255, 255, 255];
        public $COLOR_SUBTITULO_BG = [220, 220, 220]; // Gris claro (modificado para ser menos oscuro que 200)
        public $COLOR_SEPARADOR = [150, 150, 150]; // Gris medio

        // Constructor para inicializar las rutas de imágenes
        public function __construct()
        {
            parent::__construct();

            // Construir rutas absolutas del sistema de archivos
            $this->RUTA_IMAGEN_FONDO = $_SERVER['DOCUMENT_ROOT'] . '/imagenes/despedida.png';
            $this->RUTA_LOGO = $_SERVER['DOCUMENT_ROOT'] . '/imagenes/logo_unacar_sf.png';

            error_log("=== RUTAS DE IMÁGENES ===");
            error_log("DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT']);
            error_log("Imagen fondo: " . $this->RUTA_IMAGEN_FONDO);
            error_log("Logo: " . $this->RUTA_LOGO);
        }

        // Método para convertir imágenes a formato válido
        private function convertirAImagenValida($rutaImagen)
        {
            if (!file_exists($rutaImagen)) {
                error_log("Imagen no existe: " . $rutaImagen);
                return null;
            }

            $imageInfo = @getimagesize($rutaImagen);
            if (!$imageInfo) {
                error_log("No se puede leer la imagen: " . $rutaImagen);
                return null;
            }

            $tipo = $imageInfo[2];
            error_log("Tipo de imagen detectado: " . $tipo . " para " . basename($rutaImagen));

            // Si ya es PNG válido, retornar la ruta
            if ($tipo === IMAGETYPE_PNG) {
                error_log("✓ Imagen PNG válida: " . basename($rutaImagen));
                return $rutaImagen;
            }

            // Convertir JPEG/JPG a PNG
            if ($tipo === IMAGETYPE_JPEG) {
                error_log("Convirtiendo JPEG a PNG: " . basename($rutaImagen));
                $img = imagecreatefromjpeg($rutaImagen);
                if (!$img) {
                    error_log("Error al crear imagen desde JPEG");
                    return null;
                }
                $rutaTemporal = sys_get_temp_dir() . '/' . uniqid() . '_' . basename($rutaImagen, '.png') . '.png';
                imagepng($img, $rutaTemporal);
                imagedestroy($img);
                error_log("✓ Imagen convertida a: " . $rutaTemporal);
                return $rutaTemporal;
            }

            // Convertir GIF a PNG
            if ($tipo === IMAGETYPE_GIF) {
                error_log("Convirtiendo GIF a PNG: " . basename($rutaImagen));
                $img = imagecreatefromgif($rutaImagen);
                if (!$img) {
                    error_log("Error al crear imagen desde GIF");
                    return null;
                }
                $rutaTemporal = sys_get_temp_dir() . '/' . uniqid() . '_' . basename($rutaImagen, '.png') . '.png';
                imagepng($img, $rutaTemporal);
                imagedestroy($img);
                error_log("✓ Imagen convertida a: " . $rutaTemporal);
                return $rutaTemporal;
            }

            error_log("Formato de imagen no soportado: " . $tipo);
            return null;
        }

        // Header del PDF (Con recuadro de color y texto blanco)
        function Header()
        {
            global $datos_pdf;

            $ancho_pagina = $this->GetPageWidth();
            $alto_pagina = $this->GetPageHeight();

            // DEFINICIÓN DE TAMAÑO Y POSICIÓN PARA CENTRADO
            $ANCHO_IMAGEN_CENTRO = 150; // 15 cm
            $ALTO_IMAGEN_CENTRO = 150;  // 15 cm

            // CÁLCULO PARA CENTRAR PARA IMAGEN FONDO
            $x_centro = ($ancho_pagina / 2) - ($ANCHO_IMAGEN_CENTRO / 2);
            $y_centro = ($alto_pagina / 2) - ($ALTO_IMAGEN_CENTRO / 2);

            // =======================================================
            // DIBUJAR LA IMAGEN DE FONDO (VA PRIMERO)
            // =======================================================
            $rutaImagenValida = $this->convertirAImagenValida($this->RUTA_IMAGEN_FONDO);
            if ($rutaImagenValida) {
                try {
                    $this->Image($rutaImagenValida, $x_centro, $y_centro, $ANCHO_IMAGEN_CENTRO, $ALTO_IMAGEN_CENTRO);
                    error_log("✓ Imagen de fondo cargada correctamente");
                } catch (Exception $e) {
                    error_log("ERROR al cargar imagen de fondo: " . $e->getMessage());
                }
            } else {
                error_log("ADVERTENCIA: No se pudo procesar la imagen de fondo");
            }

            // Marco de color para el título
            $this->SetFillColor($this->COLOR_TITULO_BG[0], $this->COLOR_TITULO_BG[1], $this->COLOR_TITULO_BG[2]);
            $this->Rect(0, 0, $this->GetPageWidth(), 20, 'F');

            $ANCHO_LOGO = 15; // Ancho del logo en mm
            $ALTO_LOGO = 15; // Alto del logo en mm

            // CÁLCULO DE POSICIÓN logo
            $x_pos = $ancho_pagina - 10 - $ANCHO_LOGO;
            $y_pos = 2; // Inicia 2mm desde el borde superior

            $rutaLogoValida = $this->convertirAImagenValida($this->RUTA_LOGO);
            if ($rutaLogoValida) {
                try {
                    $this->Image($rutaLogoValida, $x_pos, $y_pos, $ANCHO_LOGO, $ALTO_LOGO);
                    error_log("✓ Logo cargado correctamente");
                } catch (Exception $e) {
                    error_log("ERROR al cargar logo: " . $e->getMessage());
                }
            } else {
                error_log("ADVERTENCIA: No se pudo procesar el logo");
            }

            // Título Principal
            $this->SetY(5);
            $this->SetFont('Arial', 'B', 16);
            $this->SetTextColor($this->COLOR_TITULO_TEXT[0], $this->COLOR_TITULO_TEXT[1], $this->COLOR_TITULO_TEXT[2]);
            $this->Cell(0, 7, pdf_text('REPORTE DE SALUD INTEGRAL'), 0, 1, 'C');

            // Subtítulo/Fecha
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, pdf_text('UNACAR - Generado el: ' . $datos_pdf['fecha']), 0, 1, 'C');

            // Resetear color de texto a negro y posición
            $this->SetTextColor(0, 0, 0);
            $this->SetY(25);
        }

        // Footer (MODIFICADO: Leyenda a la izquierda, Página a la derecha)
        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);

            // Ancho total del contenido (210 - 2*10 = 190mm)
            $ancho_contenido = $this->GetPageWidth() - 20;

            // 1. Celda Izquierda (Leyenda) - 70% del ancho
            $this->Cell($ancho_contenido * 0.7, 10, pdf_text('Nota: Estos resultados son un acercamiento para un DX completo debes acudir a tu servicio medico a traves de tu seguro facultativo'), 0, 0, 'L');

            // 2. Celda Derecha (Número de página) - 30% del ancho
            $this->Cell($ancho_contenido * 0.3, 10, pdf_text('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
        }

        // Función de Mapeo de estados a colores (se mantiene)
        // REEMPLAZAR ESTA FUNCIÓN COMPLETA (Línea 417)
        function getEstadoColor($estado)
        {
            $estado = trim(strtolower($estado));
            $color = [200, 200, 200]; // Gris (Default)
            $texto_salida = ucwords($estado);

            switch ($estado) {
                // RANGOS BUENOS (Verde)
                case 'bajo':
                case 'normal':                  // <-- DASS
                case 'peso normal':             // <-- IMC
                case 'deseable':
                case 'leve':                    // <-- DASS
                case 'moderada':                // <-- DASS
                case 'saludable':
                    $color = [178, 223, 178]; // Verde Oscuro
                    break;

                // RANGOS DE RIESGO MEDIO/ALTO (Naranja)
                case 'peso insuficiente':       // <-- IMC (Lo ponemos naranja como advertencia)
                case 'sobrepeso':               // <-- IMC
                case 'riesgo moderado':
                case 'moderado':                // <-- DASS (Este es 'moderado' de DASS, no 'moderada')
                case 'límite':
                case 'severo':                  // <-- DASS
                case 'riesgo':
                    $color = [255, 204, 153]; // Naranja vivo
                    break;

                // RANGOS EXTREMOS (Rojo)
                case 'obesidad grado 1':
                case 'obesidad grado 2':
                case 'obesidad grado 3 (mórbida)':
                case 'riesgo alto':
                case 'extrema':
                case 'extremadamente severo':   // <-- DASS
                case 'no saludable':
                    $color = [255, 179, 179]; // Rojo Oscuro
                    // Acortar texto para que quepa en la barra
                    if ($estado == 'extremadamente severo') {
                        $texto_salida = 'EXTREMO';
                    } else {
                        $texto_salida = strtoupper($estado);
                    }
                    break;
            }

            return [pdf_text($texto_salida), $color];
        }

        // Función para calcular el ancho de la barra (se mantiene)
        // REEMPLAZAR ESTA FUNCIÓN COMPLETA (Línea 446)
        function calcularAnchoBarra($categorias, $estado_actual)
        {
            $estado_actual = trim(strtolower($estado_actual));
            $ancho_unidad = $this->ANCHO_TOTAL_BARRA / count($categorias);
            $ancho_barra = 0;

            foreach ($categorias as $i => $cat) {
                $cat_std = trim(strtolower($cat));

                // Acumula el ancho de esta categoría
                $ancho_barra += $ancho_unidad;

                // Si la categoría actual (ej. 'peso normal')
                // coincide con el estado del alumno (ej. 'peso normal'),
                // retorna el ancho acumulado HASTA este punto.
                if ($cat_std == $estado_actual) {
                    return $ancho_barra;
                }
            }

            // Fallback: Si por alguna razón no se encontró
            // (ej. un estado 'deseable' que no está en la lista),
            // mapeamos 'deseable' o 'saludable' a la segunda categoría ('Peso normal' o 'Normal')
            if ($estado_actual == 'deseable' || $estado_actual == 'saludable') {
                return $ancho_unidad * 2; // Asumir la segunda posición
            }

            // Si no se encontró, devuelve 0 para que la barra no se dibuje
            return 0;
        }

        // Dibujar encabezados de categoría (mejorados)
        function drawCategoryHeaders($categorias, $y)
        {
            $x_inicio = 50;
            $ancho_unidad = $this->ANCHO_TOTAL_BARRA / count($categorias);
            $this->SetY($y);
            $this->SetX($x_inicio);
            $this->SetFont('Arial', 'B', 5); // Negrita para headers de barra
            $this->SetFillColor(230, 230, 230); // Fondo gris claro
            foreach ($categorias as $cat) {
                $this->Cell($ancho_unidad, 4, pdf_text($cat), 1, 0, 'C', true); // Borde y fondo
            }
            $this->Ln(4);
        }

        // Indicador Progresivo (Ajustado para mejor presentación)
        function addIndicadorProgresivo($label, $valor, $estado, $x, &$y, $categorias)
        {
            list($estado_texto, $color) = $this->getEstadoColor($estado);
            $ancho_barra = $this->calcularAnchoBarra($categorias, $estado);
            $x_inicio_barra = $x + 40;
            $alto_barra_rect = 6;
            $y_barra_rect = $y + 1;

            // Etiqueta y Valor
            $this->SetFont('Arial', '', 10);
            $this->SetXY($x, $y);
            $this->Cell(25, $alto_barra_rect, pdf_text($label . ":"), 0, 0, 'L');
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(15, $alto_barra_rect, pdf_text($valor), 0, 0, 'L');

            // Fondo de la barra completo
            $this->SetDrawColor(150, 150, 150);
            $this->SetFillColor(240, 240, 240);
            $this->Rect($x_inicio_barra, $y_barra_rect, $this->ANCHO_TOTAL_BARRA, $alto_barra_rect, 'FD'); // Dibuja y rellena

            // Barra de progreso (color)
            $this->SetFillColor($color[0], $color[1], $color[2]);
            $this->Rect($x_inicio_barra, $y_barra_rect, $ancho_barra, $alto_barra_rect, 'F');

            // Texto de Estado (dentro de la barra)
            $this->SetFont('Arial', 'B', 7); // Negrita y pequeña
            $this->SetTextColor(255, 255, 255); // Texto blanco para visibilidad

            $this->SetXY($x_inicio_barra, $y_barra_rect);
            $this->Cell($ancho_barra - 1, $alto_barra_rect, $estado_texto, 0, 0, 'R');
            $this->SetTextColor(0, 0, 0); // Resetear color de texto

            $y += $alto_barra_rect + 2; // Incrementar Y de forma más controlada
        }

        // Tabla Estilo de Vida (mejorada con Total y Semáforo Vertical)
        function addTablaVida($datos)
        {

            $x_center = 45;

            $ancho_col1 = 60;
            $ancho_col2 = 40;
            $ancho_col3 = 30; // Para el Estado
            $ancho_col4 = 30; // Para el Nivel/Semáforo
            $alto_fila = 8;
            $alto_barra = 6;

            $this->SetFont('Arial', 'B', 10);
            $this->SetFillColor(220, 220, 220); // Fondo para encabezados de tabla

            $this->SetX($x_center);

            $this->Cell($ancho_col1, 8, pdf_text('Indicador'), 1, 0, 'C', true);
            $this->Cell($ancho_col3, 8, pdf_text('Estado'), 1, 0, 'C', true);
            $this->Cell($ancho_col4, 8, pdf_text('Nivel'), 1, 1, 'C', true);

            $this->SetFont('Arial', '', 9);
            $fill = false;
            foreach ($datos as $dato) {
                $total_puntos = isset($dato['total']) ? $dato['total'] : '';

                list($estado_texto, $color) = $this->getEstadoColor($dato['estado']);
                $y_celda = $this->GetY();
                $x_inicial = $this->GetX();

                // Alternar color de fondo de fila
                $this->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);

                $this->SetX($x_center);

                // Columna 1: Indicador
                $this->Cell($ancho_col1, $alto_fila, pdf_text($dato['nombre']), 'LRB', 0, 'L', true);

                // Columna 2: Estado (Texto)
                $this->Cell($ancho_col3, $alto_fila, $estado_texto, 'RB', 0, 'C', true);

                // Columna 3: Nivel (Semáforo)
                $this->Cell($ancho_col4, $alto_fila, '', 'RB', 1, 'C', true);

                // --- DIBUJAR LA BARRA VERTICAL (Semáforo) ---
                $x_barra = $x_center + $x_inicial + $ancho_col1 + $ancho_col3 + ($ancho_col4 / 2) - ($alto_barra / 2);
                $y_barra = $y_celda + ($alto_fila / 2) - ($alto_barra / 2);

                // Dibujar el rectángulo de color
                $this->SetFillColor($color[0], $color[1], $color[2]);
                $this->Rect($x_barra, $y_barra, $alto_barra, $alto_barra, 'F');

                $fill = !$fill;
            }
        }

        // Función para crear un separador de sección (Fondo gris y línea)
        function sectionSeparator($text, $ln_after = 2)
        {
            $this->Ln(3);
            $this->SetFont('Arial', 'B', 12);

            // Fondo y texto para subtítulo
            $this->SetFillColor($this->COLOR_SUBTITULO_BG[0], $this->COLOR_SUBTITULO_BG[1], $this->COLOR_SUBTITULO_BG[2]);
            $this->Cell(0, 7, pdf_text($text), 0, 1, 'L', true);

            // Línea de separación
            $this->SetDrawColor($this->COLOR_SEPARADOR[0], $this->COLOR_SEPARADOR[1], $this->COLOR_SEPARADOR[2]);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->Ln($ln_after);
        }
    }

    // === CREAR PDF ===
    $pdf = new PDF();
    $pdf->AliasNbPages(); // Para el footer
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    // -------------------------------------------------------------
// SECCIÓN: DATOS DEL ALUMNO (Revertida a diseño addGridRow)
// -------------------------------------------------------------
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(30, 70, 120);
    $pdf->Cell(0, 8, pdf_text('DATOS DEL ALUMNO'), 0, 1, 'L');

    $pdf->SetTextColor(0, 0, 0); // Texto de vuelta a negro

    // Uso de la función addGridRow (Etiqueta en fondo gris, Valor simple)
    addGridRow($pdf, 'Nombre Completo', $datos_pdf['nombres_alum'] . ' ' . $datos_pdf['ape_paterno_alum'] . ' ' . $datos_pdf['ape_materno_alum'], '', '');
    addGridRow($pdf, 'Matricula', $datos_pdf['matricula_alum'], '', '');
    addGridRow($pdf, 'Facultad', $datos_pdf['nombre_facultad'], '', '');
    addGridRow($pdf, 'Carrera', $datos_pdf['nombre_carrera'], '', '');
    addGridRow($pdf, 'Correo', $datos_pdf['correo_alum'], 'Edad', $datos_pdf['edad_alum']);
    addGridRow($pdf, 'Fecha de Reporte', $datos_pdf['fecha'], '', '');

    $pdf->Ln(5);


    // ----------------------------------------------------------------------
// === Indicadores de Salud Física (Con separador y mejoras en la barra) ===
    $pdf->sectionSeparator('Indicadores de Salud Física');

    $y = $pdf->GetY();
    $pdf->drawCategoryHeaders($pdf->CAT_FISICAS, $y);
    $y += 6;
    $pdf->addIndicadorProgresivo('IMC', $datos_pdf['imc'], $datos_pdf['clasificacion_imc'], 10, $y, $pdf->CAT_FISICAS);

    $pdf->Ln(5);

    $y = $pdf->GetY();
    $y += 6;
    $pdf->Ln(5);
    addGridRow($pdf, 'Peso', $datos_pdf['peso'], '', '');
    addGridRow($pdf, 'Talla', $datos_pdf['talla'], '', '');
    addGridRow($pdf, 'ICC', $datos_pdf['icc'], 'Resultado', $datos_pdf['clasificacion_de_icc']);
    addGridRow($pdf, 'ICE', $datos_pdf['ice'], '', '');
    addGridRow($pdf, 'Masa Muscular', $datos_pdf['masa_magra'], '', '');
    addGridRow($pdf, 'Masa grasa', $datos_pdf['porcentaje_masa_grasa'], 'Resultado', $datos_pdf['clasificacion_porcentaje_grasa']);
    addGridRow($pdf, 'Agua total(lt)', $datos_pdf['agua_total'], '', '');


    $pdf->Ln(5);
    // ----------------------------------------------------------------------
// === Perfil Sanguíneo YTA ===
    $pdf->sectionSeparator('Perfil Sanguíneo YTA');

    addGridRow($pdf, 'Glucosa (mg/dL)', $datos_pdf['glucosa'], 'Resultado', $datos_pdf['clasificacion_glucosa']);
    addGridRow($pdf, 'Colesterol (mg/dL)', $datos_pdf['colesterol'], 'Resultado', $datos_pdf['clasificacion_colesterol']);
    addGridRow($pdf, 'Triglicéridos (mg/dL)', $datos_pdf['trigliceridos'], 'Resultado', $datos_pdf['clasificacion_trigliceridos']);
    addGridRow($pdf, 'Tensión Arterial (mmHg)', $datos_pdf['tension_arterial'], 'Resultado', $datos_pdf['clasificacion_tension_arterial']);

    $pdf->Ln(5);

    // === Perfil Estilo de Vida (Tabla mejorada) ===
    $pdf->sectionSeparator('Perfil Estilo de Vida');

    $datosVida = [
        ['nombre' => 'Nutrición', 'estado' => $datos_pdf['saludable_nutricion']],
        ['nombre' => 'Ejercicio', 'estado' => $datos_pdf['saludable_ejercicio']],
        ['nombre' => 'Salud', 'estado' => $datos_pdf['saludable_salud']],
        ['nombre' => 'Soporte Interpersonal', 'estado' => $datos_pdf['saludable_soporte']],
        ['nombre' => 'Manejo de Estrés', 'estado' => $datos_pdf['saludable_manejo']],
        ['nombre' => 'Autoactualizacion', 'estado' => $datos_pdf['saludable_autoactualizacion']]
    ];
    $pdf->addTablaVida($datosVida);
    $pdf->Ln(8);

    // ----------------------------------------------------------------------
// === Perfil DASS (Con sección controlada para evitar páginas vacías) ===

    // Si el bloque no cabe en la página actual, crea una nueva
    if ($pdf->GetY() > 200) {
        $pdf->AddPage();
    }

    $pdf->sectionSeparator('Perfil DASS');

    $y = $pdf->GetY();
    $pdf->drawCategoryHeaders($pdf->CAT_DASS, $y);
    $y += 5;

    $pdf->addIndicadorProgresivo('Ansiedad', $datos_pdf['puntuacion_ansiedad'], $datos_pdf['severidad_ansiedad'], 10, $y, $pdf->CAT_DASS);
    $pdf->addIndicadorProgresivo('Estrés', $datos_pdf['puntuacion_estres'], $datos_pdf['severidad_estres'], 10, $y, $pdf->CAT_DASS);
    $pdf->addIndicadorProgresivo('Depresión', $datos_pdf['puntuacion_depresion'], $datos_pdf['severidad_depresion'], 10, $y, $pdf->CAT_DASS);


    // GUARDAR PDF EN CARPETA PERMANENTE CON ESTRUCTURA POR MATRÍCULA
    $matricula_sanitizada = preg_replace('/[^a-zA-Z0-9_-]/', '', $matricula);
    $timestamp = date('Y-m-d_H-i-s');

    // Crear carpeta específica para la matrícula dentro de reportes_salud
    $carpetaMatricula = $carpetaPDFs . '/' . $matricula_sanitizada;

    // Verificar si la carpeta existe, si no, crearla
    if (!file_exists($carpetaMatricula)) {
        if (!mkdir($carpetaMatricula, 0755, true)) {
            throw new Exception('No se pudo crear la carpeta para la matrícula: ' . $matricula_sanitizada);
        }
        error_log("Carpeta creada: " . $carpetaMatricula);
    }

    // Nombre del archivo con timestamp para permitir múltiples versiones
    $nombreArchivo = 'reporte_' . $matricula_sanitizada . '_' . $timestamp . '.pdf';
    $rutaPDF = $carpetaMatricula . '/' . $nombreArchivo;

    // Guardar el PDF
    $pdf->Output('F', $rutaPDF);

    // Verificar que el PDF se creó correctamente
    if (!file_exists($rutaPDF) || filesize($rutaPDF) == 0) {
        throw new Exception('Error al crear el PDF');
    }
    error_log("PDF creado: " . $rutaPDF . " (" . filesize($rutaPDF) . " bytes)");

    // PASO 5: Enviar correo - OPTIMIZADO PARA PUERTO 25
    $correoEnviado = false;
    $errorCorreo = '';

    try {
        ob_start();

        $mail = new PHPMailer(true);

        // Debug: 0 = producción, 2 = desarrollo
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = function ($str, $level) {
            error_log("PHPMailer [$level]: $str");
        };

        // Configuración básica
        $mail->isSMTP();
        $mail->Host = 'mail.sistema-integral-de-salud-unacar.com.mx';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@sistema-integral-de-salud-unacar.com.mx';
        $mail->Password = 'sklike5522';
        $mail->Port = 25; // PUERTO 25 (el único disponible)
        $mail->SMTPSecure = ''; // Sin encriptación
        $mail->SMTPAutoTLS = false; // CRÍTICO: Evita upgrade automático a TLS
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Timeout = 60;

        // Opciones SSL permisivas
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        error_log("Configurando correo para: $destinatario_email");

        // Configurar remitente y destinatario
        $mail->setFrom('noreply@sistema-integral-de-salud-unacar.com.mx', 'Sistema Integral de Salud UNACAR');
        $mail->addAddress($destinatario_email);

        // Verificar que el PDF existe antes de adjuntar
        if (!file_exists($rutaPDF)) {
            throw new Exception("El archivo PDF no existe: $rutaPDF");
        }

        $mail->addAttachment($rutaPDF, $nombreArchivo);

        // Configurar contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Resultados de tu Evaluación de Salud - UNACAR';

        // Cuerpo HTML
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
                .header { background-color: #1e4678; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background-color: white; padding: 30px; border-radius: 0 0 8px 8px; }
                .button { display: inline-block; padding: 12px 24px; background-color: #1e4678; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                .highlight { background-color: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>🏥 Sistema Integral de Salud UNACAR</h1>
                </div>
                <div class="content">
                    <h2>Estimado(a) estudiante,</h2>
                    <p>Te informamos que tu <strong>Reporte de Salud Integral</strong> ha sido generado exitosamente el día <strong>' . date('d/m/Y') . '</strong>.</p>
                    
                    <p>📎 Encontrarás adjunto a este correo tu reporte completo en formato PDF.</p>
                    
                    <div class="highlight">
                        <strong>⚠️ Nota importante:</strong> Estos resultados son un acercamiento orientativo. Para un diagnóstico completo y profesional, debes acudir a tu servicio médico a través de tu seguro facultativo.
                    </div>
                    
                    <p>Tu reporte incluye:</p>
                    <ul>
                        <li>✓ Indicadores de Salud Física (IMC, ICC, ICE)</li>
                        <li>✓ Perfil Sanguíneo y Tensión Arterial</li>
                        <li>✓ Perfil de Estilo de Vida</li>
                        <li>✓ Evaluación DASS (Ansiedad, Estrés, Depresión)</li>
                    </ul>
                    
                    <p>Si tienes alguna pregunta o inquietud sobre tus resultados, no dudes en contactar con el departamento de salud universitaria.</p>
                    
                    <p>Cuida tu salud,<br><strong>Equipo de Salud UNACAR</strong></p>
                </div>
                <div class="footer">
                    <p>📧 Este correo fue generado automáticamente, por favor no responder.</p>
                    <p>&copy; ' . date('Y') . ' UNACAR - Universidad Autónoma del Carmen</p>
                </div>
            </div>
        </body>
        </html>';

        // Texto alternativo
        $mail->AltBody = "Estimado(a) estudiante,\n\n"
            . "Tu Reporte de Salud Integral ha sido generado el " . date('d/m/Y') . ".\n\n"
            . "Encontrarás adjunto tu reporte completo en formato PDF.\n\n"
            . "NOTA IMPORTANTE: Estos resultados son un acercamiento. Para un diagnóstico completo, acude a tu servicio médico.\n\n"
            . "Tu reporte incluye:\n"
            . "- Indicadores de Salud Física\n"
            . "- Perfil Sanguíneo y TA\n"
            . "- Perfil de Estilo de Vida\n"
            . "- Evaluación DASS\n\n"
            . "Saludos,\nEquipo de Salud UNACAR";

        // Intentar enviar
        error_log("Intentando enviar correo...");

        if ($mail->send()) {
            $correoEnviado = true;
            error_log("✅ Correo enviado exitosamente a: $destinatario_email");
        } else {
            throw new Exception("El método send() retornó false");
        }

        ob_end_clean();

    } catch (Exception $e) {
        ob_end_clean();
        $errorCorreo = $e->getMessage();
        error_log("❌ ERROR al enviar correo: " . $errorCorreo);
        error_log("Detalles PHPMailer: " . ($mail->ErrorInfo ?? 'N/A'));
    }

    $conn->close();

    // URL para descargar el PDF
    $urlDescarga = $protocol . "://" . $host . "/reportes_salud/" . $matricula_sanitizada . "/" . $nombreArchivo;

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