<?php

// Definir los tiempos de bloqueo en segundos (12, 14, 16 segundos, etc.)
$bloqueos = [120, 300, 3600, 86400, 86450];  // Ejemplo con un nuevo tiempo añadido (45 segundos)

$usuarioError = '';
$contraError = '';

// Asegúrate de que los tiempos de bloqueo están definidos antes de usarlos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "pisi");

    if ($conn->connect_errno) {
        die("<p class='error-msg'>Error de conexión: " . $conn->connect_error . "</p>");
    }

    // Recibir y filtrar datos de entrada
    $usuario = htmlspecialchars($_POST['usuario'], ENT_QUOTES, 'UTF-8');
    $contra = htmlspecialchars($_POST['contra'], ENT_QUOTES, 'UTF-8');

    // Obtener datos de la base de datos para el usuario
    $stmt = $conn->prepare("SELECT * FROM administradores WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $queryusu = $stmt->get_result();

    if ($queryusu->num_rows === 1) {
        $resultado = $queryusu->fetch_assoc();

        // Obtener intentos fallidos y último intento desde la base de datos
        $intentos = $resultado['intentos_fallidos'];
        $ultimoIntento = strtotime($resultado['ultimo_intento']);
        $tiempoActual = time();

        // Verificar si la cuenta ya está bloqueada permanentemente
        if ($intentos >= (count($bloqueos) * 3)) {
            $usuarioError = "⚠️ Su cuenta ha sido bloqueada permanentemente. Contacte al administrador.";
        } else {
            // Si hay un bloqueo activo, verificar cuánto tiempo falta
            if ($intentos >= 3) {
                // Calcular el índice de bloqueo correctamente
                $indiceBloqueo = min(floor(($intentos - 3) / 3), count($bloqueos) - 1);
                $tiempoEspera = $bloqueos[$indiceBloqueo];

                if (($tiempoActual - $ultimoIntento) < $tiempoEspera) {
                    $tiempoRestante = $tiempoEspera - ($tiempoActual - $ultimoIntento);
                    $usuarioError = "⏳ Cuenta bloqueada. Intente nuevamente en " . gmdate("H:i:s", $tiempoRestante);
                } else {
                    // Si el bloqueo ha pasado, actualizar los intentos en la base de datos
                    // Incrementar intentos en función del bloque de 3 intentos
                    $intentos = min(3 * (floor($intentos / 3) + 1), count($bloqueos) * 3);  // Mantener dentro del rango de bloqueos
                    $fechaActual = date("Y-m-d H:i:s"); // Definir la fecha actual en una variable
                    $stmt_update = $conn->prepare("UPDATE administradores SET intentos_fallidos = ?, ultimo_intento = ? WHERE usuario = ?");
                    $stmt_update->bind_param("iss", $intentos, $fechaActual, $usuario);
                    $stmt_update->execute();
                }
            }

            if (empty($usuarioError)) {
                // Verificar contraseña
                if (password_verify($contra, $resultado['contraseña'])) {
                    // Iniciar sesión correctamente
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['rol'] = $resultado['rol'];

                    // Reiniciar intentos y eliminar bloqueo
                    $fechaActual = date("Y-m-d H:i:s"); // Definir la fecha actual
                    $stmt_update = $conn->prepare("UPDATE administradores SET intentos_fallidos = 0, ultimo_intento = ? WHERE usuario = ?");
                    $stmt_update->bind_param("ss", $fechaActual, $usuario);
                    $stmt_update->execute();

                    header('Location: carga.html');
                    exit();
                } else {
                    // Incrementar intentos fallidos
                    $intentos++;
                    $fechaActual = date("Y-m-d H:i:s"); // Definir la fecha actual
                    $stmt_update = $conn->prepare("UPDATE administradores SET intentos_fallidos = ?, ultimo_intento = ? WHERE usuario = ?");
                    $stmt_update->bind_param("iss", $intentos, $fechaActual, $usuario);
                    $stmt_update->execute();

                    // Mostrar mensaje de error
                    $intentosRestantes = max(0, 3 - ($intentos % 3));  // Mostrar el número correcto de intentos restantes
                    if ($intentosRestantes > 0) {
                        $contraError = " Contraseña incorrecta. Intentos restantes: $intentosRestantes.";
                    } else {
                        // Aquí actualizamos el mensaje de error para el caso de bloqueo temporal
                        $contraError = " Has agotado los intentos. Tu cuenta está bloqueada temporalmente.";
                    }
                }
            }
        }
    } else {
        $usuarioError = " Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inicio de Sesion</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
          crossorigin="anonymous"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <div class="header-bar">
            <h4> Sistema Integral De Salud</h4>
        </div>
            <img src="images/delfines.png" alt="Logo" class="delfin-logo">
            <form method="post" action="login.php">
                <div class="input-container">
                    <label>Ingrese Usuario:</label>
                    <input type="text" placeholder="Usuario" name="usuario" required>
                        <?php if ($usuarioError): ?>
                            <p class="error-msg" id="usuarioError"><?php echo htmlspecialchars($usuarioError); ?></p>
                        <?php endif; ?>
                </div>

                <div class="password-container">
                    <label>Contraseña:</label>
                    <input type="password" name="contra" placeholder="Ingrese contraseña" id="password" required>
                    <span class="toggle-password" onclick="togglePassword()">👁️</span>
                        <?php if ($contraError): ?>
                            <p class="error-msg" id="contraError"><?php echo htmlspecialchars($contraError); ?></p>
                        <?php endif; ?>
                </div>
                <button type="submit">Ingresar al Sistema</button>
            </form>
        <!--
        <p class="footer-text">
            ¿Olvidaste tu <a href="nueva_pagina.php" class="forgot-password-link">Contraseña?</a> 
        </p>-->
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" 
            crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" 
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" 
            crossorigin="anonymous"></script>

            
    
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        function hideErrorMessages() {
            var usuarioError = document.getElementById('usuarioError');
            var contraError = document.getElementById('contraError');
            var inputContainer = document.querySelector('.input-container');
            var passwordContainer = document.querySelector('.password-container');

            if (usuarioError) {
                inputContainer.classList.add('error-active');
                usuarioError.style.display = 'block';
                setTimeout(function() {
                    usuarioError.style.display = 'none';
                    inputContainer.classList.remove('error-active');
                }, 7000); 
            }

            if (contraError) {
                passwordContainer.classList.add('error-active');
                contraError.style.display = 'block';
                setTimeout(function() {
                    contraError.style.display = 'none';
                    passwordContainer.classList.remove('error-active');
                }, 7000); 
            }
        }

        window.onload = hideErrorMessages;
    </script>
</body>
</html>