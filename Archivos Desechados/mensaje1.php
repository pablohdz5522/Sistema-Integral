<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias por responder</title>
    <!-- Agregar Bootstrap desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJx3t8g4cATg2nPejVx2q7pQ0RAVXZKx9poa7jlgmDXfQtZyF+exVZ5x8NhV" crossorigin="anonymous">
    <style>
        /* Estilo general del cuerpo */
        body {
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Contenedor principal */
        .contenedor {
            background-color: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            width: 100%;
            max-width: 600px;
        }

        /* Estilo del mensaje */
        .mensaje {
            font-size: 22px;
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 30px;
        }

        /* Estilo del subtítulo */
        .subtitulo {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        /* Estilo del botón */
        .boton {
            padding: 12px 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            text-decoration: none;
            display: inline-block;
        }

        /* Efecto al pasar el ratón */
        .boton:hover {
            background-color: #0056b3;
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        /* Efecto al hacer clic en el botón */
        .boton:active {
            transform: translateY(1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Respuesta en pantallas pequeñas */
        @media (max-width: 480px) {
            .contenedor {
                padding: 20px;
            }

            .mensaje {
                font-size: 18px;
            }

            .boton {
                font-size: 16px;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="mensaje">
            ¡Gracias por responder el cuestionario!
        </div>
        <div class="subtitulo">
            Tus datos han sido enviados correctamente. Si tienes alguna otra consulta, no dudes en contactar con nosotros.
        </div>
        <a href="estres.html" class="boton btn btn-primary">Volver al inicio</a>
    </div>

    <!-- Agregar Bootstrap JS y Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBf5un2c89D8xxd0eI1h9xro6b8Qw5Gi5+jb2i0/1a4jqsVY" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0ka3HpzyFj5jZT9c7P4YdYf9tW31ZJh9h6V9jwGZlLg9TuK9t" crossorigin="anonymous"></script>
</body>
</html>
