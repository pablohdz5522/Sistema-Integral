<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "pisi");

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

// Construir la consulta con JOIN para obtener la facultad
$sql = "SELECT a.matricula_alum, a.nombres_alum, a.ape_paterno_alum, a.ape_materno_alum, 
        a.sexo, f.nombre_facultad 
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
        echo "<tr>";
        echo "<td>{$contador}</td>";
        echo "<td>{$row['matricula_alum']}</td>";
        echo "<td>{$nombreCompleto}</td>";
        echo "<td>{$row['nombre_facultad']}</td>";
        echo "<td>{$row['sexo']}</td>";
        echo "<td><a href='expediente.html?matricula_alum={$row['matricula_alum']}' class='btn btn-info'>Ver Perfil</a></td>";
        echo "</tr>";
        $contador++;
    }
} else {
    echo "<tr><td colspan='6'>NO HAY ALUMNOS REGISTRADOS</td></tr>";
}

// Cerrar conexión
$conn->close();
?>
