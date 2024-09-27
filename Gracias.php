<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Formulario de Múltiples Pasos</title>
    <style>
        body {
            background-color: #f0f4f7;
            font-family: Arial, sans-serif;

        }
        .container-stepbar{
            margin-left: 80px;
            margin-right: 80px;
        }

        .navbar, .nav-tabs {
            background-color: #071359;
        }

        .nav-link {
            color: #fff;
        }

        .nav-link.active {
            background-color: #071359;
            color: #fff;
        }

        .progress-bar {
            background-color: #071359;
        }

        .btn-primary {
            background-color: #071359;
            border: none;
        }
        .btn-primary:onclick {
            background-color: #071359;
            border: none;
        }
        .btn-primary:active,
        .btn-primary:focus {
        background-color: #2D6DA6; /* Color azul claro al hacer clic o mantener el foco */
        border: none;
        outline: 2px solid #4AB0D9; 
        }
    
        .form-control {
            border-radius: 0.5rem;
        }

        label {
            margin-top: 10px;
        }

        .form-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .hidden {
            display: none;
        }
        .section-form{
            margin-left:30px;
            margin-right:30px;
            margin-left:30px;
        }
        .btn-pricipal {
            background-color: #071359;
            color: white;
          }
        
          .btn-pricipal:hover {
            background-color: #163573; /* Color más oscuro al pasar el mouse */
            color: white;
          }
          
        .btn-next {
            background-color: #2D6DA6;
            color: white;
          }
        
        .btn-next:hover {
            background-color: #4AB0D9; /* Color más oscuro al pasar el mouse */
            color: white;
        }
    </style>
</head>
<body>
    <!-- Contenido HTML aquí -->
    <nav class="navbar" style="background-color: #071359">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">
                <img src="../img/LogoStarTech.png" alt="Logo" width="139" height="35" class="d-inline-block align-text-top">
            </a>
            <a class="btn btn-pricipal" href="" role="button">Conócenos</a>
        </div>
    </nav>
    <br>
    <div class="container-stepbar">
        <div class="position-relative m-4">
        <div class="progress" style="height: 3px;">
            <div id="progress-top-bar" class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">1</button>
            <button type="button" class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">2</button>
            <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">3</button>
        </div>
    </div>
    <!-- Formularios combinados -->
    <div class="container mt-4">
            <!-- Paso 3: Resumen -->
            <div id="step-3" class="form-section">
            <div style="text-align: left; margin-top: 20px; margin-left:30px;">
                <h2>¡Gracias por tu solicitud!</h2>
                <p>Apreciamos tu interés en realizar tus prácticas con nosotros. Hemos recibido tu solicitud y nuestro equipo de reclutamiento la revisará cuidadosamente.</p>
                <p>Te notificaremos el resultado de tu solicitud a través del correo electrónico proporcionado. Mantente atento a tu bandeja de entrada en las próximas semanas.</p>
                <p>¡Te deseamos mucho éxito en este proceso!</p>
                <br>
                <p>Atentamente,</p>
                <p><strong>StarTech</strong></p>
            </div>
        </div>
    </div>
    <br>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>