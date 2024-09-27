<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "u943374840_AngelyLucia";
$password = "W1IagP3[IQi6j=lflShñ";
$database = "u943374840_PlusTiES";

session_start();

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['user_id'])) {
        // Redirigir al login si no está autenticado
        header('Location: /login');
        exit();
    }

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}


function logCrudOperation($usuario, $operation, $tableName, $recordId, $detailsArray, $conn) {
    $detailsJson = json_encode($detailsArray);
    if ($detailsJson === false) {
        die("Error al convertir los detalles a JSON: " . json_last_error_msg());
    }

   date_default_timezone_set('America/Guatemala');
    $fechaActual = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("INSERT INTO Log_Json (Usuario, Operacion, Nombre_Tabla, Id_Asociado, Detalles, Fecha_de_Registro) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $usuario, $operation, $tableName, $recordId, $detailsJson, $fechaActual);

    
    if (!$stmt->execute()) {
        die("Error al insertar en Log_Json: " . $stmt->error . "<br>");
    } else {
    }

    $stmt->close();
}

$usuario = 'admin'; // Supongamos que este es el usuario que está realizando la operación

// Registrar la operación en Log_Json en pantalla Descargar
logCrudOperation($usuario, 'Entrar a pantalla Descargar Json', 'Log_Json', null, null, $conn);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #071359;
            padding: 1rem;
        }
        .navbar .btn {
            background-color: #5A9BD5;
            color: white;
            border-radius: 8px;
        }
        .form-control {
            border-radius: 0.5rem;
        }
        .btn-aceptar {
            background-color: #071359;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-aceptar:hover {
            background-color: #163573;
        }
        .btn-denegar {
            background-color: #2D6DA6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-denegar:hover {
            background-color: #48CAE4;
        }
        .tab-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 0px 10px 10px 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .navprincipal{
            background-color: #071359;
        }
        /* Pestañas minimalistas */
        .nav-tabs .nav-link.active{
            color: #fff; /* Color del texto */
            background-color: #071359; /* Fondo transparente */
            border-radius: 10px 10px 0px 0px; 
            border: #071359; 
            border-bottom: 2px solid transparent; /* Borde inferior transparente */
            margin-right: 5px;
            transition: border-color 0.3s ease;
        }
        .nav-tabs .nav-link.active:hover {
            background-color: #163573;
            border-bottom: 2px solid #071359; /* Añadir borde inferior al pasar el mouse */
            color: #fff; /* Cambiar el color del texto */
        }
        .nav-tabs .nav-link {
            background-color: #fff;
            color: #071359; 
            border-bottom: 2px solid #f0f0f0;
            border-radius: 10px 10px 0px 0px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-right: 5px;
            transition: border-color 0.3s ease;
        }
        .nav-tabs .nav-link:hover {
            background-color: #2D6DA6;
            border-bottom: 2px solid #2D6DA6; /* Añadir borde inferior al pasar el mouse */
            color: #fff; /* Cambiar el color del texto */
        }
        .d-flex {
            display: flex;
            align-items: center; /* Alinea verticalmente el ícono y el texto */
        }
        
        .me-2 {
            margin-right: 0.5rem; /* Ajusta el espacio entre el ícono y el texto */
        }
        .material-symbols-outlined {
            font-size: 1.25rem; /* Ajusta el tamaño según sea necesario */
        }
    </style>
    <title>Información del aspirante</title>
</head>
<body>
<nav class="navprincipal">
    <div class="mx-auto max-w-screen-2xl px-2 lg:px-6 lg:px-8">
        <div class="relative flex h-20 items-center justify-between p-8 py-8"
            <!-- Logo -->
            <div class="items-center">
              <a href='/login'><img class="h-8" src="../img/LogoStarTech.png"  alt="Tu Empresa"> </a>
            </div>
    
            <div class="hidden 2xl:flex 2xl:items-center 2xl:space-x-4">
                <div class="dropdown">
                  <a href="#" class="dropdown-toggle rounded-3xl p-16 py-3 text-lg font-medium text-gray-300 hover:bg-[#163573] hover:text-white hover:p-16 hover:py-4 transition-all items-center" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <button type="button">Empleados</button>
                  </a>
                  <ul class="dropdown-menu rounded-2xl py-3 text-lg font-medium bg-[#163573] text-white hover:text-white hover:py-4 transition-all items-center" aria-labelledby="dropdownMenuButton">
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/empleados">
                        <span class="material-symbols-outlined mr-2">inventory</span> <!-- Icono -->
                        Empleados de alta
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/empleados-de-baja">
                        <span class="material-symbols-outlined mr-2">pending_actions</span> <!-- Icono -->
                        Empleados de baja
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/organigrama-empleados">
                        <span class="material-symbols-outlined mr-2">hub</span>
                        Organigrama
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="dropdown">
                  <a href="#" class="dropdown-toggle rounded-3xl p-5 py-3 text-lg font-medium text-gray-300 hover:bg-[#163573] hover:text-white hover:p-9 hover:py-4 transition-all items-center" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <button type="button">Solicitudes de prácticas</button>
                  </a>
                  <ul class="dropdown-menu rounded-2xl py-3 text-lg font-medium bg-[#163573] text-white hover:text-white hover:py-4 transition-all items-center" aria-labelledby="dropdownMenuButton">
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/solicitudes-pendientes">
                        <span class="material-symbols-outlined mr-2">pending_actions</span> <!-- Icono -->
                        Solicitudes pendientes
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/solicitudes-practicas">
                        <span class="material-symbols-outlined mr-2">inventory</span> <!-- Icono -->
                        Todas las solicitudes
                      </a>
                    </li>
                  </ul>
                </div>
                
                <a href='/nuevo-empleado' class="rounded-3xl p-5 py-3 text-lg font-medium text-gray-300 hover:bg-[#163573] hover:text-white hover:p-9 hover:py-4 transition-all items-center">
                    <button id="button" type="submit">Nuevo empleado</button>
                </a>
                <a href='/download-json' class="rounded-3xl p-5 py-3 text-lg font-medium text-gray-300 hover:bg-[#163573] hover:text-white hover:p-9 hover:py-4 transition-all items-center">
                    <button id="button" type="submit">Descargar JSON</button>
                </a>
                <a href='logout.php' class="rounded-3xl bg-[#163573] p-5 py-2 text-lg font-medium text-white hover:p-9 hover:py-4 transition-all flex items-center text-center">
                    <button id="button" type="submit" class="inline-flex items-center">
                        <span class="material-icons">logout</span>
                        <span class="ms-1">Cerrar sesión</span>
                    </button>
                </a>
            </div>
        
            <!-- Botón de Menú Móvil -->
            <div class="absolute inset-y-0 right-0 flex items-center 2xl:hidden mr-8">
                <button id="mobile-menu-button" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#163573] hover:text-white" aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Abrir menú principal</span>
                    <svg class="block h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg class="hidden h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="2xl:hidden hidden bg-[#163573]" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a href='/empleados' class="block rounded-3xl p-3 py-2 font-medium text-gray-300 hover:bg-[#071359] hover:text-white hover:p-6 hover:py-3 text-base transition-all">
                <button id="button" type="submit">Empleados de alta</button>
            </a>
            <a href='/empleados-de-baja' class="block rounded-3xl p-3 py-2 font-medium text-gray-300 hover:bg-[#071359] hover:text-white hover:p-6 hover:py-3 text-base transition-all">
                <button id="button" type="submit">Empleados de baja</button>
            </a>
            <a href='/organigrama-empleados' class="block rounded-3xl p-3 py-2 font-medium text-gray-300 hover:bg-[#071359] hover:text-white hover:p-6 hover:py-3 text-base transition-all">
                <button id="button" type="submit">Organigrama de empleados</button>
            </a>
            <a href='/solicitudes-pendientes' class="block rounded-3xl p-3 py-2 font-medium text-gray-300 hover:bg-[#071359] hover:text-white hover:p-6 hover:py-3 text-base transition-all">
                     <button id="button" type="submit">Solicitudes de prácticas pendientes</button>
            </a>
            <a href='/solicitudes-practicas' class="block rounded-3xl p-3 py-2 font-medium text-gray-300 hover:bg-[#071359] hover:text-white hover:p-6 hover:py-3 text-base transition-all">
                     <button id="button" type="submit">Todas las solicitudes de prácticas</button>
            </a>
            <a href='/nuevo-empleado' class="block rounded-3xl p-3 py-2 font-medium text-gray-300 hover:bg-[#071359] hover:text-white hover:p-6 hover:py-3 transition-all items-center">
                <button id="button" type="submit">Nuevo empleado</button>
            </a>
            <a href='/download-json' class="block rounded-3xl p-3 py-2 font-medium text-gray-300 hover:bg-[#071359] hover:text-white hover:p-6 hover:py-3 transition-all items-center">
                <button id="button" type="submit">Descargar JSON</button>
            </a>
            <a href='logout.php' class="flex rounded-3xl p-3 py-2 font-medium text-gray-300 bg-[#071359] hover:text-white hover:p-6 hover:py-3  text-base transition-all items-center">
                <button id="button" type="submit" class="inline-flex">
                    <span class="material-icons">logout</span>
                    <span class="ms-1">Cerrar sesión</span>
                </button>
            </a>
        </div>
    </div>
</nav>
    <div class="container mt-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-aspirante-tab" data-bs-toggle="tab" data-bs-target="#info-aspirante" type="button" role="tab" aria-controls="info-aspirante" aria-selected="true">Descargar Json</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <form action="export_log_json.php" method="Post" class="mt-3">
                    <div class="mb-3">
                        <label for="fecha">Fecha Deseada:</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-secondary d-flex align-items-center">
                            <span class="material-symbols-outlined me-2">download</span>
                            Descargar
                        </button>
                    </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        menuButton.addEventListener('click', () => {
        const isExpanded = menuButton.getAttribute('aria-expanded') === 'true';

        menuButton.setAttribute('aria-expanded', !isExpanded);
        mobileMenu.classList.toggle('hidden');

        menuButton.querySelectorAll('svg').forEach(svg => svg.classList.toggle('hidden'));
      });
    });
    
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownButton.addEventListener('click', function (e) {
    e.preventDefault();
    dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!dropdownButton.contains(e.target)) {
        dropdownMenu.classList.add('hidden');
        }
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
