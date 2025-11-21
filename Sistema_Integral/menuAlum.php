<?php
session_start();
if (!isset($_SESSION['alumno'])) {
    header('Location: registro.html');
    exit;
}

$alumno = $_SESSION['alumno'];
$nombreCompleto = htmlspecialchars($alumno['nombre']. ' ' . $alumno['apepa'].' '. $alumno['apema']);

// Conexión a la base de datos
$servername = "pdb1042.awardspace.net";
$username = "4528622_pisi";
$password = "sklike5522";
$database = "4528622_pisi";
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

<!doctype html>
<html lang="es">

<head>
    <title>Menú Alumno</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/menualum.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/ico/logo_pequeno.ico">
</head>

<body>
    <!-- Modal de Bienvenida -->
    <?php if (isset($_SESSION['bienvenida']) && $_SESSION['bienvenida']) : ?>
    <div class="modal fade" id="modalBienvenida" tabindex="-1" aria-labelledby="modalBienvenidaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-primary">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalBienvenidaLabel">¡Bienvenido!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
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

    <!-- Contenedor Principal -->
    <div class="container-fluid">
        <!-- Tarjeta de Datos del Alumno -->
        <div class="col-sm-6 centrado mt-4">
            <div class="card">
                <div class="card-header titulo">
                    <h5>👤 Datos del Alumno</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre completo:</strong> <?php echo $nombreCompleto; ?></p>
                    <p><strong>Matrícula:</strong> <?php echo htmlspecialchars($alumno['matricula']); ?></p>
                    <p><strong>Correo institucional:</strong> <?php echo htmlspecialchars($alumno['correo']); ?></p>
                    <p><strong>Carrera:</strong> <?php echo htmlspecialchars($nombreCarrera); ?></p>
                    <p><strong>Facultad:</strong> <?php echo htmlspecialchars($nombreFacultad); ?></p>
                </div>
            </div>
        </div>

        <!-- Formulario: Estilo de Vida -->
        <div class="formulario-rect mt-4 container col-sm-5 <?php echo $yaRespondioEstilo ? 'bg-success text-white' : ''; ?>">
            <h4>Formulario: Estilo de Vida</h4>
            <?php if ($yaRespondioEstilo): ?>
                <p>Ya has contestado este formulario. Gracias por tu participación.</p>
            <?php else: ?>
                <p>Por favor, completa este formulario para ayudarnos a conocer tu estilo de vida.</p>
                <form action="PEPS-1.php">
                    <button type="submit" class="btn btn-primary">Contestar Ahora</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Formulario: DASS-21 -->
        <div class="formulario-rect container col-sm-5 <?php echo $yaRespondioDASS ? 'bg-success text-white' : '' ?>">
            <h3>Formulario: DASS-21</h3>
            <?php if ($yaRespondioDASS): ?>
                <p>Ya has contestado este formulario. Gracias por tu participación.</p>
            <?php else: ?>
                <p>Completa este cuestionario sobre tu estado emocional actual.</p>
                <a href="DASS-21.php" class="btn btn-primary">Contestar Ahora</a>
            <?php endif; ?>
        </div>

        <!-- Botón Cerrar Sesión -->
        <div class="cerrar">
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalLogout">
                🚪 Cerrar Sesión
            </button>
            <div class="mt-2">
            <a href="/alumnos/login-alumno.html" class="btn btn-info">¡Logueate para ver tus resultados!</a>
            </div>
        </div>
        
    </div>

    <!-- Modal: Formularios Completados -->
    <?php if ($yaRespondioEstilo && $yaRespondioDASS): ?>
    <div class="modal fade" id="modalCompletado" tabindex="-1" aria-labelledby="modalCompletadoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-success">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalCompletadoLabel">🎉 ¡Felicidades!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Has completado exitosamente los formularios <strong>Estilo de Vida</strong> y
                        <strong>DASS-21</strong>.</p>
                    <p>Gracias por tu participación. Esta información es valiosa para mejorar tu bienestar.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
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

    <!-- Modal: Confirmar Cerrar Sesión -->
    <div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalLogoutLabel">⚠️ ¿Cerrar sesión?</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <p>¿Estás seguro de que deseas salir del sistema?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="logoutAlumno.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>

</html>