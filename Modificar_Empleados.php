<?php
    date_default_timezone_set('America/Guatemala');
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
    $id_empleado = isset($_GET['id_empleado']) ? $_GET['id_empleado'] : null;


    // Si no se recibió un ID, mostrar un mensaje de error
    if (empty($id_empleado)) {
        die("ID del empleado no proporcionado");
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
        .container2 {
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }
        .tab-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 0px 10px 10px 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-control {
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .btn-modify {
            display: block;
            margin: 0 auto;
            background-color: #5A9BD5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-modify:hover {
            background-color: #4682B4;
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
        .navprincipal{
            background-color: #071359;
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
    <title>Información del Empleado</title>
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
            <a id="botonRegreso" href="#">
                <button class="btn-salir mt-4" title="Cancelar">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </a>
        </div>
        <form action="modificar.php" method="POST">
            <input type="hidden" name="id_empleado" value="<?php echo $id_empleado; ?>">
            
            <!-- Pestañas de navegación -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#infoEmpresa">Información de Empresa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#infoPersonal">Información Personal</a>
                </li>
            </ul>
            
            <!-- Contenido de las pestañas -->
            <div class="tab-content">
                <!-- Información de Empresa -->
                <div class="tab-pane container active" id="infoEmpresa">
                    <?php
                    $sql = "SELECT * FROM InfoEmpresarial WHERE Id_empleado = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $id_empleado);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    // Mostrar los registros en la tabla
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                if (!empty($row["Fecha_contratacion"]) && $row["Fecha_contratacion"] !== '0000-00-00 00:00:00') {
                                    $timestamp = strtotime($row["Fecha_contratacion"]);
                                    if ($timestamp !== false) {
                                        $fecha_contratacion = date('Y-m-d', $timestamp);
                                    } else {
                                        $fecha_contratacion = '';
                                    }
                                } else {
                                    $fecha_contratacion = '';
                                }
                                
                                if (!empty($row["Fecha_cargo"]) && $row["Fecha_cargo"] !== '0000-00-00 00:00:00') {
                                    $timestamp = strtotime($row["Fecha_cargo"]);
                                    if ($timestamp !== false) {
                                        $fecha_cargo = date('Y-m-d', $timestamp);
                                    } else {
                                        $fecha_cargo = '';
                                    }
                                } else {
                                    $fecha_cargo = '';
                                }
                                echo '<div class="row form-row">';
                                echo '<div class="col-md-6">';
                                echo '<div class="mb-2">';
                                echo '<label for="Codigo_personal">Código personal:</label>';
                                echo '<input type="text" class="form-control" name="Codigo_personal" value="' . $row["Codigo_personal"] . '" placeholder="Codigo_personal" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="Fecha_contratacion">Fecha de contratación:<span style="color:red;">*</span></label>';
                                echo '<input type="date" class="form-control" name="Fecha_contratacion" value="' . $fecha_contratacion . '" placeholder="Fecha_contratacion" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="Fecha_cargo">Fecha inicio en cargo:<span style="color:red;">*</span></label>';
                                echo '<input type="date" class="form-control" name="Fecha_cargo" value="' . $fecha_cargo . '" placeholder="Fecha_cargo" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="Cargo">Cargo:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="Cargo" value="' . $row["Cargo"] . '" placeholder="Cargo" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="Sueldo">Sueldo:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="Sueldo" value="' . $row["Sueldo"] . '" placeholder="Sueldo" required>';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="col-md-6">';
                                echo '<div class="mb-2">';
                                echo '<label for="Descripcion_responsabilidades">Descripcion de responsabilidades:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="Descripcion_responsabilidades" value="' . $row["Descripcion_responsabilidades"] . '" placeholder="Descripcion_responsabilidades" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="Personal_cargo">Personal a cargo:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="Personal_cargo" value="' . $row["Personal_cargo"] . '" placeholder="Personal_cargo" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="Jefe_inmediato">Jefe inmediato:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="Jefe_inmediato" value="' . $row["Jefe_inmediato"] . '" placeholder="Jefe_inmediato" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="Estado">Estado:</label>';
                                echo '<input type="text" class="form-control" name="Estado" value="' . $row["Estado"] . '" placeholder="Estado" readonly>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo "<p>No se encontraron registros</p>";
                        }
                    ?>
                </div>

                <!-- Información Personal -->
                <div class="tab-pane container fade" id="infoPersonal">
                    <?php
                    $sql = "SELECT * FROM Dtos_Personales WHERE ID = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $id_empleado);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                if (!empty($row["Fecha_de_Nac"]) && $row["Fecha_de_Nac"] !== '0000-00-00 00:00:00') {
                                    $timestamp = strtotime($row["Fecha_de_Nac"]);
                                    if ($timestamp !== false) {
                                        $fecha_nacimiento = date('Y-m-d', $timestamp);
                                    } else {
                                        $fecha_nacimiento = '';
                                    }
                                } else {
                                    $fecha_nacimiento = '';
                                }
                                echo '<div class="row form-row">';
                                echo '<div class="col-md-6">';
                                echo '<div class="mb-2">';
                                echo '<label for="primer_nombre">Primer Nombre:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="primer_nombre" value="' . $row["Primer_Nombre"] . '" placeholder="Primer Nombre" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="segundo_nombre">Segundo Nombre:</label>';
                                echo '<input type="text" class="form-control" name="segundo_nombre" value="' . $row["Segundo_Nombre"] . '" placeholder="Segundo Nombre">';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="tercer_nombre">Tercer Nombre:</label>';
                                echo '<input type="text" class="form-control" name="tercer_nombre" value="' . $row["Tercer_Nombre"] . '" placeholder="Tercer_Nombre">';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="primer_apellido">Primer Apellido:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="primer_apellido" value="' . $row["Primer_Apellido"] . '" placeholder="Primer Apellido" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="segundo_apellido">Segundo Apellido:</label>';
                                echo '<input type="text" class="form-control" name="segundo_apellido" value="' . $row["Segundo_Apellido"] . '" placeholder="Segundo_Apellido">';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="apellido_de_casada">Apellido de casada:</label>';
                                echo '<input type="text" class="form-control" name="apellido_de_casada" value="' . $row["Apellido_de_Casada"] . '" placeholder="Apellido_de_Casada">';
                                echo '</div>';
                                echo '</div>';
                                echo '<div class="col-md-6">';
                                echo '<div class="mb-2">';
                                echo '<label for="fecha_de_nac">Fecha de nacimiento:<span style="color:red;">*</span></label>';
                                echo '<input type="date" class="form-control" name="fecha_de_nac" value="' . $fecha_nacimiento . '" placeholder="Fecha_de_Nac" required>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="lugar_de_nac">Lugar de nacimiento:</label>';
                                echo '<input type="text" class="form-control" name="lugar_de_nac" value="' . $row["Lugar_de_Nac"] . '" placeholder="Lugar_de_Nac">';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="direccion_domicilio">Dirección de domicilio:</label>';
                                echo '<input type="text" class="form-control" name="direccion_domicilio" value="' . $row["Direccion_De_Domicilio"] . '" placeholder="Direccion_De_Domicilio">';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="numero_celular">Télefono:</label>';
                                echo '<input type="text" class="form-control" name="numero_celular" value="' . $row["Numero_de_Celular"] . '" placeholder="Numero_de_Celular">';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="numero_casa">Télefono de casa:</label>';
                                echo '<input type="text" class="form-control" name="numero_casa" value="' . $row["Numero_de_Casa"] . '" placeholder="Numero_de_Casa">';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo '<label for="correo_electronico">Correo electrónico:<span style="color:red;">*</span></label>';
                                echo '<input type="text" class="form-control" name="correo_electronico" value="' . $row["Correo_Electronico"] . '" placeholder="Correo_Electronico" required>';
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

            <!-- Botón de enviar -->
            <button type="submit" class="btn-modify mt-4">Guardar</button>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtén el elemento del botón de regreso
        var botonRegreso = document.getElementById("botonRegreso");

        // Obtén los parámetros de la URL
        const params = new URLSearchParams(window.location.search);
        const fromPage = params.get('from');

        // Verifica el valor del parámetro 'from' y establece el href del botón
        if (fromPage === "empleados") {
            botonRegreso.href = "/empleados";
        } else if (fromPage === "empleados-de-baja") {
            botonRegreso.href = "/empleados-de-baja";
        } else {
            // Enlace por defecto si no se corresponde
            botonRegreso.href = "/empleados"; // Cambia esto a la URL que desees
        }
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
<?php
    // Cerrar la conexión a la base de datos
    $conn->close();
?>