<?php
    // Datos de conexión a la base de datos
    $servername = "localhost";
    $username = "u943374840_AngelyLucia";
    $password = "W1IagP3[IQi6j=lflShñ";
    $database = "u943374840_PlusTiES";
    
    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $database);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error en la conexión a la base de datos: " . $conn->connect_error);
    }
    session_start();

    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        // Preparar la consulta
        $query = "SELECT id, username, password FROM Login WHERE username=?";
        $stmt = $conn->prepare($query);

        // Vincular el parámetro
        $stmt->bind_param("s", $_POST['username']);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->get_result();
        $results = $result->fetch_assoc();
        $message = '';

        // Verificar la contraseña
        if ($results && password_verify($_POST['password'], $results['password'])) {
            // Guardar el usuario en la sesión
            $_SESSION['user_id'] = $results['id'];
            $_SESSION['username'] = $results['username'];

            // Redirigir si la autenticación es exitosa
            header('Location: /empleados');
            exit();
        } else {
            $message = 'Las credenciales no coinciden';
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/delimitadores.js"></script>
    <title>Página de Inicio de Sesión</title>
    <style>
        .login-container {
            display: flex;
            flex-direction: row;
            min-height: 80vh;
            background-color: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
            margin: auto;
            margin-top: 70px;
            width: 80%;
            max-width: 1200px;
        }

        .image-section {
            flex: 1;
            background-color: #071359;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .image-section img {
            width:70%;
            height: auto;
            border-radius: 15px;
        }

        .form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background-color: #ffffff;
        }

        .form-section img {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
        }

        .form-section form {
            width: 100%;
            max-width: 400px;
        }

        .form-section form .form-control {
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #071359;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #092b8a;
        }

        /* Estilos Responsivos */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                width: 95%;
                margin-top: 20px;
            }

            .form-section {
                padding: 20px;
            }

            .image-section {
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            .form-section img {
                width: 120px;
            }

            .btn-primary {
                font-size: 1rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="image-section">
        <img src="./img/LogoStarTech.png" alt="Logo o Imagen">
    </div>
    <div class="form-section">
        <!-- Logo encima del formulario -->
        <img src="./img/user.png" alt="Logo Formulario">
        <h6>¡Bienvenido! Ingresa tus credenciales</h6>
        <br>
        <form action="/login" method="post">
            <input autocomplete="off" type="text" class="form-control" id="floatingInput" placeholder="Ingrese su usuario" name="username" oninput="removeSpecialChars(this)" maxlength="40" required>
            <div style="position: relative;">
                <input class="form-control" type="password" autocomplete="off" id="floatingPassword" placeholder="Ingrese su contraseña" name="password" oninput="removeSpecialChars(this)" maxlength="16" required>
                <span id="togglePassword" class="material-symbols-outlined" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #2a6aa8;">
                    visibility
                </span>
            </div>
            <?php if(!empty($message)): ?>
                <p class="mb-6 font-semibold text-center" style="color: #163573;"><strong><?= $message ?></strong></p>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
        <br>
        <h7>¿Olvidaste tu contraseña?</h7>
    </div>
</div>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#floatingPassword');

    togglePassword.addEventListener('click', function () {
        // Alternar el tipo de input entre password y text
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Cambiar el ícono dependiendo del estado
        this.textContent = type === 'password' ? 'visibility' : 'visibility_off';
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
