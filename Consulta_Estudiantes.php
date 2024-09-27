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

    // Recibir el ID del empleado desde la URL
    $id_aspirante = isset($_GET['id_aspirante']) ? $_GET['id_aspirante'] : null;
    $primer_nombre = isset($_GET['primer_nombre']) ? $_GET['primer_nombre'] : null;
    $segundo_nombre = null;
    $primer_apellido = null;
    $segundo_apellido = null;
    $fecha_inicio = null;
    $fecha_final = null;
    $correo_electronico = null;  // Inicializamos la variable de correo electrónico

    // Si no se recibió un ID, mostrar un mensaje de error
    if (empty($id_aspirante)) {
        die("ID del aspirante no proporcionado");
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
            background-color: #163573;
            border-bottom: 2px solid #163573; /* Añadir borde inferior al pasar el mouse */
            color: #fff; /* Cambiar el color del texto */
        }
        .dropdown-menu {
            width: 100%; /* Se ajusta al ancho del elemento padre */
            min-width: unset; /* Elimina el ancho mínimo predeterminado de Bootstrap */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-salir {
            background-color: #2a6aa8; /* Color de fondo */
            border: none; /* Sin borde */
            color: white; /* Color del ícono */
            width: 40px; /* Ancho del botón */
            height: 40px; /* Alto del botón, igual al ancho para hacerlo circular */
            border-radius: 50%; /* Borde redondeado para hacerlo circular */
            cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
            display: inline-flex; /* Alineación del contenido dentro del botón */
            align-items: center; /* Centra el ícono verticalmente */
            justify-content: center; /* Centra el ícono horizontalmente */
            transition: background-color 0.3s ease; /* Transición suave del color */
        }
        
        .btn-salir:hover {
            background-color: #163573; /* Color de fondo en hover */
        }
        
        .material-symbols-outlined {
            font-size: 20px; /* Tamaño del ícono */
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
                        <span class="material-symbols-outlined mr-2">account_circle</span> <!-- Icono -->
                        Empleados de alta
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/empleados-de-baja">
                        <span class="material-symbols-outlined mr-2">no_accounts</span> <!-- Icono -->
                        Empleados de baja
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/nuevo-empleado">
                        <span class="material-symbols-outlined mr-2">person_add</span> <!-- Icono -->
                        Nuevo empleado
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item text-gray-300 hover:bg-[#071359] hover:text-white rounded-1xl p-4 py-2 flex items-center" href="/organigrama-empleados">
                        <span class="material-symbols-outlined mr-2">reduce_capacity</span>
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
                <a href='/novedades-calendario' class="rounded-3xl p-5 py-3 text-lg font-medium text-gray-300 hover:bg-[#163573] hover:text-white hover:p-9 hover:py-4 transition-all items-center">
                    <button id="button" type="submit">Novedades</button>
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
    <div class="container">
        <div class="d-flex justify-content-end">
            <a href="/solicitudes-practicas">
                <button class="btn-salir mt-4" title="Cancelar"><span class="material-symbols-outlined">close</span></button>
            </a>
        </div>
        <form action="" method="POST">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-aspirante-tab" data-bs-toggle="tab" data-bs-target="#info-aspirante" type="button" role="tab" aria-controls="info-aspirante" aria-selected="true">Información del Aspirante</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="info-personal-tab" data-bs-toggle="tab" data-bs-target="#info-personal" type="button" role="tab" aria-controls="info-personal" aria-selected="false">Información Personal</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="info-aspirante" role="tabpanel" aria-labelledby="info-aspirante-tab">
                            <?php
                            // Obtener el ID del empleado
                            echo '<input type="hidden" name="id_aspirante" value="' . $id_aspirante . '">';
                            
                            // Consulta para Información del Aspirante
                            $sql2 = "SELECT * FROM Informacion_Practicas WHERE ID_Practicante = ?";
                            $stmt2 = $conn->prepare($sql2);
                            $stmt2->bind_param("s", $id_aspirante);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
        
                            if ($result2->num_rows > 0) {
                                while($row = $result2->fetch_assoc()) {
                                    echo '<div class="row form-row">';
                                    echo '<div class="col-md-6">';
                                    echo '<div class="mb-2">';
                                    echo '<label for="colegio">Colegio:</label>';
                                    echo '<input type="text" class="form-control" name="colegio" value="' . $row["Institucion_Colegio"] . '" placeholder="Colegio" readonly>';
                                    echo '</div>';
                                    echo '<div class="mb-2">';
                                    echo '<label for="grado">Grado:</label>';
                                    echo '<input type="text" class="form-control" name="grado" value="' . $row["Grado"] . '" placeholder="Grado" readonly>';
                                    echo '</div>';
                                    echo '<div class="mb-2">';
                                    echo '<label for="carrera">Carrera:</label>';
                                    echo '<input type="text" class="form-control" name="carrera" value="' . $row["Carrera"] . '" placeholder="Carrera" readonly>';
                                    echo '</div>';
                                    echo '<div class="mb-2">';
                                    echo '<label for="habilidades">Habilidades:</label>';
                                    echo '<input type="text" class="form-control" name="habilidades" value="' . $row["Habilidades"] . '" placeholder="Habilidades" readonly>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="col-md-6">';
                                    echo '<div class="mb-2">';
                                    echo '<label for="numero_De_Horas">Número de horas:</label>';
                                    echo '<input type="text" class="form-control" name="numero_De_Horas" value="' . $row["Numero_De_Horas"] . '" placeholder="Número de horas" readonly>';
                                    echo '</div>';
                                    echo '<div class="mb-2">';
                                    echo '<label for="Fecha_De_inicio">Fecha de inicio:</label>';
                                    echo '<input type="text" class="form-control" name="Fecha_De_inicio" value="' . $row["Fecha_De_inicio"] . '" placeholder="Fecha de inicio" readonly>';
                                    echo '</div>';
                                    echo '<div class="mb-2">';
                                    echo '<label for="Fecha_De_Finalización">Fecha de Finalización:</label>';
                                    echo '<input type="text" class="form-control" name="Fecha_De_Finalización" value="' . $row["Fecha_De_Finalización"] . '" placeholder="Fecha de Finalización" readonly>';
                                    echo '</div>';
                                    echo '<div class="mb-2">';
                                    echo '<label for="Referido">Referido:</label>';
                                    echo '<input type="text" class="form-control" name="Referido" value="' . $row["Referido"] . '" placeholder="Referido" readonly>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p>No se encontraron registros personales</p>';
                            }
                            ?>
                    </div>
                    <div class="tab-pane fade" id="info-personal" role="tabpanel" aria-labelledby="info-personal-tab">
                        <div class="mt-3">
                            <?php
                            // Consulta para Información Personal
                            $sql = "SELECT * FROM Dtos_Personales WHERE ID = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $id_aspirante);
                            $stmt->execute();
                            $result = $stmt->get_result();
        
                            // Mostrar los registros en los campos de texto
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<div class="row form-row">';
                                echo '<div class="col-md-6">';
                                echo '<div class="mb-2">';
                                echo ' <label for="primer_nombre">Primer Nombre:</label>';
                                echo '<input type="text" class="form-control" name="primer_nombre" value="' . $row["Primer_Nombre"] . '" placeholder="Primer Nombre" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="segundo_nombre">Segundo Nombre:</label>';
                                echo '<input type="text" class="form-control" name="segundo_nombre" value="' . $row["Segundo_Nombre"] . '" placeholder="Segundo Nombre" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="tercer_nombre">Tercer Nombre:</label>';
                                echo '<input type="text" class="form-control" name="tercer_nombre" value="' . $row["Tercer_Nombre"] . '" placeholder="Tercer_Nombre" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="primer_apellido">Primer Apellido:</label>';
                                echo '<input type="text" class="form-control" name="primer_apellido" value="' . $row["Primer_Apellido"] . '" placeholder="Primer Apellido" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="segundo_apellido">Segundo Apellido:</label>';
                                echo '<input type="text" class="form-control" name="segundo_apellido" value="' . $row["Segundo_Apellido"] . '" placeholder="Segundo_Apellido" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="apellido_de_casada">Apellido de casada:</label>';
                                echo '<input type="text" class="form-control" name="apellido_de_casada" value="' . $row["Apellido_de_Casada"] . '" placeholder="Apellido_de_Casada" readonly>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="col-md-6">';
                                echo '<div class="mb-2">';
                                echo ' <label for="fecha_de_nac">Fecha de nacimiento:</label>';
                                echo '<input type="date" class="form-control" name="fecha_de_nac" value="' . $row["Fecha_de_Nac"] . '" placeholder="Fecha_de_Nac" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="lugar_de_nac">Lugar de nacimiento:</label>';
                                echo '<input type="text" class="form-control" name="lugar_de_nac" value="' . $row["Lugar_de_Nac"] . '" placeholder="Lugar_de_Nac" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="direccion_domicilio">Dirección de domicilio:</label>';
                                echo '<input type="text" class="form-control" name="direccion_domicilio" value="' . $row["Direccion_De_Domicilio"] . '" placeholder="Direccion_De_Domicilio" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="numero_celular">Télefono:</label>';
                                echo '<input type="text" class="form-control" name="numero_celular" value="' . $row["Numero_de_Celular"] . '" placeholder="Numero_de_Celular" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="numero_casa">Número de casa:</label>';
                                echo '<input type="text" class="form-control" name="numero_casa" value="' . $row["Numero_de_Casa"] . '" placeholder="Numero_de_Casa" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="correo_electronico">Correo electrónico:</label>';
                                echo '<input type="text" class="form-control" name="correo_electronico" value="' . $row["Correo_Electronico"] . '" placeholder="Correo_Electronico" readonly>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No se encontraron registros personales</p>';
                        }
                            ?>
                        </div>
                    </div>
                </div>
        </form>
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
