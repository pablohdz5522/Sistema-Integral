<?php
session_start();

// Validar si el usuario está logueado y si tiene permiso (por ejemplo solo administradores)
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'Administrador') {
    header('Location: login.php');
    exit();
}

// Conexión a base de datos
 $conn = new mysqli("pdb1042.awardspace.net", "4528622_pisi", "sklike5522", "4528622_pisi");

if ($conn->connect_errno) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar registros
$sql = "SELECT id, usuario, nombre_completo, rol, fecha_ingreso, fecha_salida FROM registro_ingresos ORDER BY fecha_ingreso DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Accesos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/ico/logo_pequeno.ico">
</head>
<body style="background: linear-gradient(135deg, #003366,#ADB0B6,#003366);  margin: 0;height: 100%; min-height: 100vh;">
<div class="container mt-5">
    <h1 class="mb-4 text-white">📊 Panel de Control - Ingresos al Sistema</h1>
        <a href="menu.php" class="btn btn-primary mt-3">
        <i class="bi bi-arrow-left-circle"></i> Volver al Menú Principal
    </a>
    

    <table class="table table-hover table-bordered align-middle">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Rol</th>
                <th>Hora de Ingreso</th>
                <th>Hora de Salida</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($row['rol']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['fecha_ingreso'])); ?></td>
                    <td>
                        <?php 
                        if ($row['fecha_salida']) {
                            echo date('d/m/Y H:i', strtotime($row['fecha_salida']));
                        } else {
                            echo "<span class='text-danger fw-bold'>Conectado</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if ($row['fecha_salida']) {
                            echo "<span class='badge bg-secondary'>Finalizado</span>";
                        } else {
                            echo "<span class='badge bg-success'>Activo</span>";
                        }
                        ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>