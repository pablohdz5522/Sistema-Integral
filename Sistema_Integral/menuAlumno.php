<?php
session_start();
if (!isset($_SESSION['alumno'])) {
    header('Location: registro.html');
    exit;
}

$alumno = $_SESSION['alumno'];

$nombreCompleto = htmlspecialchars($alumno['nombre']. ' ' . $alumno['apepa'].' '. $alumno['apema']);

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "pisi";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar si ya respondió el formulario Estilo de Vida
$yaRespondioEstilo = false;
$sqlCheck = "SELECT 1 FROM estilo_de_vida WHERE matricula_alum = ?";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("i", $alumno['matricula']);
$stmt->execute();
$stmt->store_result();
$yaRespondioEstilo = $stmt->num_rows > 0;
$stmt->close();

// Verificar si ya respondió DASS
$yaRespondioDASS = false;
$sqlCheckDASS = "SELECT 1 FROM dass WHERE matricula_alum = ?";
$stmtDASS = $conn->prepare($sqlCheckDASS);
$stmtDASS->bind_param("i", $alumno['matricula']);
$stmtDASS->execute();
$stmtDASS->store_result();
$yaRespondioDASS = ($stmtDASS->num_rows() >0);
$stmtDASS->close();

// Obtener nombres de carrera y facultad
$nombreCarrera = "Desconocida";
$nombreFacultad = "Desconocida";

$sql = "SELECT c.nombre_carrera, f.nombre_facultad 
        FROM carrera c 
        JOIN facultad f ON c.id_facultad = f.id_facultad
        WHERE c.id_carrera = ? AND c.id_facultad = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $alumno['id_carrera'], $alumno['id_facultad']);
$stmt->execute();
$stmt->bind_result($nombreCarrera, $nombreFacultad);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú del Alumno</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .formulario-rect {
            border: 4px solid #333;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #f9f9f9;
        }
        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>

<?php if (isset($_SESSION['bienvenida']) && $_SESSION['bienvenida']) : ?>
    <!-- Modal de bienvenida -->
    <div class="modal fade" id="modalBienvenida" tabindex="-1" aria-labelledby="modalBienvenidaLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-primary">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="modalBienvenidaLabel">¡Bienvenido!</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body text-center">
            <p>Hola, <strong><?php echo $nombreCompleto; ?></strong>.</p>
            <p>Gracias por acceder al sistema. Te deseamos una excelente experiencia.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const bienvenida = new bootstrap.Modal(document.getElementById('modalBienvenida'));
            bienvenida.show();
        });
    </script>

    <?php unset($_SESSION['bienvenida']); ?>
<?php endif; ?>

<body class="container mt-4">
    <button class="btn btn-danger logout" data-bs-toggle="modal" data-bs-target="#modalLogout">
    Cerrar sesión
</button>


<div class="card mb-4 shadow-sm">
  <div class="card-header bg-primary text-white">
    <h4 class="mb-0">Datos del Alumno</h4>
  </div>
  <div class="card-body">
    <p><strong>Nombre completo:</strong> <?php echo $nombreCompleto; ?></p>
    <p><strong>Matrícula:</strong> <?php echo htmlspecialchars($alumno['matricula']); ?></p>
    <p><strong>Correo institucional:</strong> <?php echo htmlspecialchars($alumno['correo']); ?></p>
    <p><strong>Carrera:</strong> <?php echo htmlspecialchars($nombreCarrera); ?></p>
    <p><strong>Facultad:</strong> <?php echo htmlspecialchars($nombreFacultad); ?></p>
  </div>
</div>


<div class="formulario-rect <?php echo $yaRespondioEstilo ? 'bg-success text-white' : ''; ?>">
    <h4>Formulario: Estilo de Vida</h4>
    <?php if ($yaRespondioEstilo): ?>
        <p>Ya has contestado este formulario. Gracias.</p>
    <?php else: ?>
        <form action="PEPS-1.php">
            <button type="submit" class="btn btn-primary">Contestar</button>
        </form>
    <?php endif; ?>
</div>


<div class="formulario-rect <?php echo $yaRespondioDASS ? 'bg-success text-white' : '' ?>">
    <h3>Formulario: DASS-2</h3>
    <?php if ($yaRespondioDASS): ?>
        <p>Ya has contestado este formulario. Gracias.</p>
    <?php else: ?>
        <a href="DASS-21.php" class="btn btn-primary">Contestar</a>
    <?php endif; ?>
</div>

<?php if ($yaRespondioEstilo && $yaRespondioDASS): ?>
<!-- Modal de Felicitación -->
<div class="modal fade" id="modalCompletado" tabindex="-1" aria-labelledby="modalCompletadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalCompletadoLabel">¡Felicidades!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p>Has completado exitosamente los formularios <strong>Estilo de Vida</strong> y <strong>DASS-21</strong>.</p>
        <p>Gracias por tu participación. Esta información es valiosa para mejorar tu bienestar.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmación para Cerrar Sesión -->
<div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-danger">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalLogoutLabel">¿Cerrar sesión?</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p>¿Estás seguro de que deseas salir del sistema?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a href="logoutAlumno.php" class="btn btn-danger">Cerrar sesión</a>
      </div>
    </div>
  </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const modal = new bootstrap.Modal(document.getElementById('modalCompletado'));
        modal.show();
    });
</script>
<?php endif; ?>

</body>
</html>