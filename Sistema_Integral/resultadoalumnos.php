<?php
// Conexión a la base de datos
$conn = new mysqli("pdb1042.awardspace.net", "4528622_pisi", "sklike5522", "4528622_pisi");

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener filtros desde JavaScript
$filtroFacultad = isset($_GET['facultad']) ? (int)$_GET['facultad'] : "";
$filtroSexo = isset($_GET['sexo']) ? $_GET['sexo'] : "";
$filtroTexto = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 20; // Número de alumnos por página
$offset = ($pagina - 1) * $limite;

// Construir la consulta con JOINs para obtener la facultad y validar formularios
$sql = "SELECT a.matricula_alum, a.nombres_alum, a.ape_paterno_alum, a.ape_materno_alum, 
        a.sexo, f.nombre_facultad,
        (SELECT COUNT(*) FROM dass WHERE dass.matricula_alum = a.matricula_alum) as tiene_dass,
        (SELECT COUNT(*) FROM estilo_de_vida WHERE estilo_de_vida.matricula_alum = a.matricula_alum) as tiene_estilo_vida,
        (SELECT COUNT(*) FROM datos_fisicos_alumnos WHERE datos_fisicos_alumnos.matricula_alum = a.matricula_alum) as tiene_datos_fisicos
        FROM alumnos a 
        JOIN facultad f ON a.id_facultad = f.id_facultad 
        WHERE 1=1";

// Aplicar filtros dinámicamente
if (!empty($filtroFacultad)) {
    $sql .= " AND a.id_facultad = $filtroFacultad";
}

if (!empty($filtroSexo)) {
    $sql .= " AND a.sexo = '$filtroSexo'";
}

if (!empty($filtroTexto)) {
    $sql .= " AND (a.matricula_alum LIKE '%$filtroTexto%' OR a.nombres_alum LIKE '%$filtroTexto%')";
}

// Paginación
$sql .= " LIMIT $limite OFFSET $offset";

// Ejecutar la consulta
$result = $conn->query($sql);
$contador = $offset + 1;

// Generar filas de la tabla
if ($result->num_rows > 0) { 
    while ($row = $result->fetch_assoc()) {
        $nombreCompleto = $row['nombres_alum'] . " " . $row['ape_paterno_alum'] . " " . $row['ape_materno_alum'];
        
        // Verificar si tiene formularios respondidos
        $iconoDass = $row['tiene_dass'] > 0 ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger fw-bold">✗</span>';
        $iconoEstiloVida = $row['tiene_estilo_vida'] > 0 ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger fw-bold">✗</span>';
        $iconoDatosFisicos = $row['tiene_datos_fisicos'] > 0 ? '<span class="text-success fw-bold">✓</span>' : '<span class="text-danger fw-bold">✗</span>';
        
        // Verificar si existe el PDF de resultado (buscar el más reciente)
        $carpetaMatricula = "reportes_salud/" . $row['matricula_alum'];
        $rutaPdfUltimo = $carpetaMatricula . "/reporte_" . $row['matricula_alum'] . "_ultimo.pdf";
        
        if (file_exists($rutaPdfUltimo)) {
            // Si existe el PDF "último"
            $botonResultado = "<a href='$rutaPdfUltimo' class='btn btn-sm btn-primary' target='_blank'><i class='bi bi-file-pdf'></i></a>";
        } elseif (is_dir($carpetaMatricula)) {
            // Si no existe "último", buscar el más reciente por fecha
            $archivos = glob($carpetaMatricula . "/reporte_*.pdf");
            if (!empty($archivos)) {
                // Ordenar por fecha de modificación (más reciente primero)
                usort($archivos, function($a, $b) {
                    return filemtime($b) - filemtime($a);
                });
                $rutaPdfReciente = $archivos[0];
                $botonResultado = "<a href='$rutaPdfReciente' class='btn btn-sm btn-primary' target='_blank'><i class='bi bi-file-pdf'></i></a>";
            } else {
                $botonResultado = '<span class="text-muted">Sin resultado</span>';
            }
        } else {
            $botonResultado = '<span class="text-muted">Sin resultado</span>';
        }
        
        echo "<tr>";
        echo "<td>{$row['matricula_alum']}</td>";
        echo "<td>{$nombreCompleto}</td>";
        echo "<td>{$row['nombre_facultad']}</td>";
        echo "<td>{$row['sexo']}</td>";
        echo "<td>$iconoDass</td>";
        echo "<td>$iconoEstiloVida</td>";
        echo "<td>$iconoDatosFisicos</td>";
        echo "<td>$botonResultado</td>";
        echo "</tr>";
        $contador++;
    }
} else {
    echo "<tr><td colspan='8'>NO HAY ALUMNOS REGISTRADOS</td></tr>";
}

// Cerrar conexión
$conn->close();
?>