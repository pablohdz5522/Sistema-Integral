<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
date_default_timezone_set('America/Mexico_City');
$conn = new mysqli("pdb1042.awardspace.net", "4528622_pisi", "sklike5522", "4528622_pisi");

// Obtener cantidad de alumnos
$sqlAlumnos = "SELECT COUNT(*) FROM alumnos";
$resultAlumnos = $conn->query($sqlAlumnos);
$totalAlumnos = 0;
if ($resultAlumnos) {
    $fila = $resultAlumnos->fetch_row();
    $totalAlumnos = $fila[0];
}

//cantidad de datos fisicos calculados
$sqlDatos = "SELECT COUNT(*) FROM datos_fisicos_alumnos";
$resultDatos = $conn->query($sqlDatos);
$totalDatos = 0;
if($resultDatos){
  $dts = $resultDatos->fetch_row();
  $totalDatos = $dts[0];
}

if ($conn->connect_errno) {
    die("Error de conexión: " . $conn->connect_error);
}

$usuario = $_SESSION['usuario'];

// Consulta para obtener nombre, foto y rol
$sql = "SELECT nombre_admi, foto, rol FROM administradores WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->bind_result($nombre_admi, $foto, $rol);
$stmt->fetch();
$stmt->close();

// Guardar en sesión
$_SESSION['nombre_admi'] = $nombre_admi;
$_SESSION['rol'] = $rol;

// Si no existe foto, usar default
$foto_base64 = $foto ? base64_encode($foto) : base64_encode(file_get_contents("images/escudo_UNACAR.png"));

// 🔥 Solo registrar ingreso si aún no lo hemos registrado 
if (!isset($_SESSION['registro_ingreso'])) {
    $fechaIngreso = date('Y-m-d H:i:s');

    $sqlInsert = "INSERT INTO registro_ingresos (usuario, nombre_completo, rol, fecha_ingreso) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssss", $usuario, $nombre_admi, $rol, $fechaIngreso);
    $stmtInsert->execute();

    // Guardar ID del registro para actualizarlo luego al salir
    $_SESSION['registro_ingreso'] = $stmtInsert->insert_id;

    $stmtInsert->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú Principal - UNACAR</title>
  <link rel="stylesheet" href="css/menu.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <style>

  </style>
</head>
<body>

    <header class="p-3  shadow-sm">
        <nav class="navbar navbar-expand-lg">
          <div class="container">
            <a class="navbar-brand" href="#">
              <img src="images/logo_unacar (1).png" alt="Logo UNACAR" style="height:60px;">
            </a>
      
            <div class="d-flex align-items-center ms-auto">
              <img src="data:image/jpeg;base64,<?php echo $foto_base64; ?>" class="rounded-circle me-3" style="width: 50px; height: 50px;" alt="Perfil">
              <div class="text-end me-3">
                <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['nombre_admi']); ?></div>
                <small class="text-muted"><?php echo htmlspecialchars($_SESSION['rol']); ?></small><br>
                <small class="text-muted">Ingreso: <?php echo date('H:i') . ' - ' . date('d/m/Y'); ?></small>
              </div>
              <form action="cerrar_sesion.php" method="POST">
                <button type="submit" class="btn btn-danger btn-sm">
                  <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
              </form>
            </div>
          </div>
        </nav>
      </header>
      

<div class="container mt-4" data-aos="fade-down">
  <div class="row align-items-center justify-content-between text-center">

    <!-- Cuadro izquierdo -->
    <div class="col-md-3 mb-3 mb-md-0">
      <div class="p-3 bg-light border rounded shadow-sm">
        <h6 class="mb-1 text-primary">Total de alumnos Registrados</h6>
        <div class="fw-bold fs-4 text-dark"><?php echo $totalAlumnos; ?></div>
      </div>
    </div>

    <!-- Texto central -->
    <div class="welcome col-md-5">
      <div class="text-center">
        <h1>Bienvenido al Sistema Integral de Salud</h1>
        <h4>Selecciona una opción para continuar</h4>
      </div>
    </div>

    <!-- Cuadro derecho -->
    <div class="col-md-3 mt-3 mt-md-0">
      <div class="p-3 bg-light border rounded shadow-sm">
        <h6 class="mb-1 text-primary">Total de Datos Físicos de alumnos Registrados</h6>
        <div class="fw-bold fs-4 text-dark"><?php echo $totalDatos; ?></div></div> <!-- Puedes cambiarlo -->
      </div>
    </div>

  </div>
</div>



<main class="container menu-grid">
  <div class="menu-card" onclick="location.href='historial_clinico.html'" data-aos="fade-up" data-aos-delay="100">
    <i class="bi bi-file-earmark-medical"></i>
    <h5 class="mt-3">Registro de Historial Clínico</h5>
  </div>

  <div class="menu-card" onclick="location.href='datos_fisicos.html'" data-aos="fade-up" data-aos-delay="200">
    <i class="bi bi-activity"></i>
    <h5 class="mt-3">Registro de Datos Físicos</h5>
  </div>

  <?php if ($_SESSION['rol'] === 'Administrador') { ?>
    <div class="menu-card" onclick="location.href='estadisticas.html'" data-aos="fade-up" data-aos-delay="300">
      <i class="bi bi-graph-up"></i>
      <h5 class="mt-3">Estadísticas</h5>
    </div>

    <div class="menu-card dropdown" data-aos="fade-up" data-aos-delay="400">
      <i class="bi bi-search"></i>
      <h5 class="mt-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">Búsqueda</h5>
      <ul class="dropdown-menu text-center">
        <li><a class="dropdown-item" href="ListadoAlumnos.html"><i class="bi bi-mortarboard"></i> Alumnos</a></li>
        <li hidden><a class="dropdown-item" href="ListadoDocentes.html"><i class="bi bi-person-badge"></i> Docentes</a></li>
        <li hidden><a class="dropdown-item" href="ListadoEmpleados.html"><i class="bi bi-briefcase"></i> Empleados</a></li>
      </ul>
    </div>

    <div class="menu-card" onclick="location.href='panel_control.php'" data-aos="fade-up" data-aos-delay="600">
      <i class="bi bi-people"></i>
      <h5 class="mt-3">Control Del Sistema</h5>
    </div>

    <div class="menu-card" onclick="location.href='#'" data-aos="fade-up" data-aos-delay="600">
      <i class="bi bi-cloud-download"></i>
      <h5 class="mt-3">Descargar datos</h5>
    </div>
  <?php } ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Si no hay datos de sesión, simula una sesión
        if (!sessionStorage.getItem('nombre_usuario')) {
            // ⚡ Aquí normalmente colocarías los datos al iniciar sesión real
            sessionStorage.setItem('nombre_usuario', 'Juan Pérez');
            sessionStorage.setItem('rol_usuario', 'Administrativo');
            const ahora = new Date();
            sessionStorage.setItem('hora_fecha_ingreso', `${ahora.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})} - ${ahora.toLocaleDateString()}`);
        }
    
        // Mostrar datos almacenados
        document.getElementById("nombreUsuario").textContent = sessionStorage.getItem('nombre_usuario');
        document.getElementById("rolUsuario").textContent = sessionStorage.getItem('rol_usuario');
        document.getElementById("horaFechaIngreso").textContent = `Ingreso: ${sessionStorage.getItem('hora_fecha_ingreso')}`;
    
        // Cerrar Sesión
        document.getElementById("cerrarSesion").addEventListener("click", function() {
            sessionStorage.clear(); // Borra datos de sesión
            window.location.href = "login.html"; // Redirige al login
        });
    });
    </script>
</body>
</html>