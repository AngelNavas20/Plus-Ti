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

// Función para registrar las operaciones CRUD en Log_Json
function logCrudOperation($usuario, $operacion, $nombreTabla, $idAsociado, $detallesArray, $conn) {
    $detallesJson = json_encode($detallesArray, JSON_UNESCAPED_UNICODE);
    if ($detallesJson === false) {
        die("Error al convertir los detalles a JSON: " . json_last_error_msg());
    }

    date_default_timezone_set('America/Guatemala');
    $fechaActual = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("INSERT INTO Log_Json (Usuario, Operacion, Nombre_Tabla, Id_Asociado, Detalles, Fecha_de_Registro) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $usuario, $operacion, $nombreTabla, $idAsociado, $detallesJson, $fechaActual);
    
    if (!$stmt->execute()) {
        error_log("Error al insertar en Log_Json: " . $stmt->error);
        return false;
    }

    $stmt->close();
    return true;
}

// Sanitización de entrada
function sanitizeInput($input, $conn) {
    return $conn->real_escape_string(trim($input));
}

// Código para el formulario de búsqueda y el registro de la consulta
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['searchQuery']) && !empty($_GET['searchQuery'])) {
    $searchQuery = sanitizeInput($_GET['searchQuery'], $conn);
    $searchTerms = explode(' ', $searchQuery);

    $whereClauses = [];
    foreach ($searchTerms as $term) {
        $term = sanitizeInput($term, $conn);
        $whereClauses[] = "(dp.Primer_Nombre LIKE '%$term%' OR dp.Segundo_Nombre LIKE '%$term%' OR dp.Primer_Apellido LIKE '%$term%' OR dp.Segundo_Apellido LIKE '%$term%')";
    }

    $sql = "SELECT dp.Primer_Nombre, dp.Segundo_Nombre, dp.Primer_Apellido, dp.Segundo_Apellido 
            FROM Dtos_Personales dp 
            JOIN InfoEmpresarial ie ON dp.ID = ie.Id_Empleado 
            WHERE " . implode(' AND ', $whereClauses) . " 
            ORDER BY dp.Primer_Nombre ASC 
            LIMIT 20 OFFSET 0";

    // Ejecutar la consulta
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error en la consulta SQL: " . $conn->error);
    }

    // Registrar la operación en el log
    $detallesArray = [
        'query' => $sql,
        'campos' => [
            'Campo1' => 'Primer_Nombre',
            'Campo2' => 'Segundo_Nombre',
            'Campo3' => 'Primer_Apellido',
            'Campo4' => 'Segundo_Apellido'
        ]
    ];
    
    logCrudOperation('admin', 'SELECT', 'Dtos_Personales JOIN InfoEmpresarial', null, $detallesArray, $conn);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f0f4f7;
            font-family: Arial, sans-serif;
        }
        .container3 {
            margin: 20px auto;
            max-width: 90%;
        }
        .containersearch{
            margin: 20px auto;
            max-width: 90%;
        }

        .table {
            background-color: #f8fafc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            overflow: hidden;
        }

        thead {
            background-color: #071359;
            color: white;
        }

        th, td {
            text-align: center;
        }
        
        td {
            text-align: left;
            font-size: 17px;
        }


        th {
            font-size: 17px;
        }

        .formControl {
            border: 1px solid #ccc;
            border-radius: 25px;
            padding: 10px 20px;
            width: 100%;
        }
        .btn-pagination {
            background-color: #071359;
            color: white;
            padding-left:20px;
            padding-right:20px;
            padding-top: 10px;
            padding-bottom: 10px;
            margin-left: 10px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-pagination:hover {
            background-color: #071359;
            color: white;
        }
        .btn-search {
            background-color: #071359;
            color: white;
            padding-left:20px;
            padding-right:20px;
            padding-top: 10px;
            padding-bottom: 10px;
            margin-left: 10px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-search:hover {
            background-color: #163573;
            color: white;
        }
        .btn-ver {
            background-color: #071359;
            color: white;
            padding-left:20px;
            padding-right:20px;
            padding-top: 13px;
            padding-bottom: 13px;
            margin-left: 10px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .btn-ver:hover {
            background-color: #163573;
            color: white;
        }
        

        .btn-update {
            background-color: #FFA000;
            color: #261800;
            margin-left: 10px;
            border-radius: 20px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }
        
        .btn-update:hover {
            background-color: #FFA000;
            
        }
        
        .btn-update .material-symbols-outlined {
            font-size: 30px;
        }
        
        /* Columnas con anchos específicos */
        .col-26 {
            width: 30%;
        }
        .col-20 {
            width: 20%;
        }
        .col-10 {
            width: 11%;
        }

        .col-8 {
            width: 8%;
        }
        
        .page-link {
            color: #2a6aa8; /* Color del texto de los enlaces */
            border: none; /* Eliminar borde */
        }
        
        .page-item.active .page-link {
            background-color: #2a6aa8; /* Fondo de la página activa */
            color: #fff; /* Color del texto de la página activa */
        }
        
        .page-item.disabled .page-link {
            color: #2a6aa8; /* Color del texto de los enlaces deshabilitados */
            opacity: 0.5; /* Hacer los enlaces deshabilitados un poco más transparentes */
        }
        
        .page-item .page-link:hover {
            background-color: #071359; /* Color del fondo al pasar el ratón */
            color: white;
        }
        
        .justify-content-end {
            display: flex;
            justify-content: flex-end;
        }

        .btn-on {
            background-color: #071359;
            color: white;
            margin-left: 10px;
            border-radius: 20px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .btn-on:hover {
            background-color: #4AB0D9;
        }
        .btn-on .material-symbols-outlined {
            font-size: 30px;
        }

        .btn-off {
            background-color: #2d6da6;
            color: white;
            margin-left: 10px;
            border-radius: 20px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .btn-off:hover {
            background-color: #4AB0D9;
        }
        .btn-off .material-symbols-outlined {
            font-size: 30px;
        }
        .nav-link{
            font-size: 18px;
        }
        .nav-link:hover {
            color: #CAF0F8 !important;
        }
        .btn-primary {
            background-color: #2D6DA6;
            color: #fff;
            padding-left:20px;
            padding-right:20px;
            border-radius: 15px;
            cursor: pointer;
            border-color: #2D6DA6;
        }

        .btn-primary:hover {
            background-color: #4AB0D9;
            border-color: #4AB0D9;
            
        }
        .navprincipal{
            background-color: #071359;
        }
        .dropdown-menu {
            width: 100%; /* Se ajusta al ancho del elemento padre */
            min-width: unset; /* Elimina el ancho mínimo predeterminado de Bootstrap */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
            /* Estilos para el icono de notificaciones redondo */
        .notification-icon {
            position: fixed;
            bottom: 20px; /* Posicionarlo en la parte inferior */
            right: 20px;  /* Posicionarlo en la parte derecha */
            width: 65px;  /* Tamaño más grande */
            height: 65px; /* Tamaño más grande */
            background-color: #163573; /* Color de fondo del icono */
            color: white;
            font-size: 32px; /* Tamaño de fuente más grande */
            border-radius: 50%; /* Hace el icono redondo */
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .notification-icon .material-symbols-outlined {
            font-size: 30px; /* Tamaño del icono 'cake' */
        }
        
        /* Estilos básicos del pop-up flotante */
        .notification-popup {
            position: fixed;
            bottom: -400px; /* Fuera de pantalla para crear el efecto de deslizamiento */
            right: 20px;
            width: 400px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            border-radius: 10px;
            z-index: 1000;
            opacity: 0;
            transition: all 0.3s ease-in-out; /* Transición suave */
        }
        
        /* Mostrar el popup con la animación */
        .notification-popup.active {
            bottom: 100px; /* Posición final cuando se abre */
            opacity: 1; /* Hacer visible */
        }
        
        .notification-popup h3 {
            margin-top: 0;
        }
        
        .notification {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f0f4f7;
            border-radius: 5px;
            position: relative;
        }
        
        .notification button {
            position: absolute;
            top: 5px;
            right: 5px;
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        
        /* Mostrar el popup cuando esté activo */
        .notification-popup.active {
            display: block;
        }
        /* Puntito rojo de notificación */
        .notification-icon.with-notifications::after {
            content: '';
            position: absolute;
            top: 0px;
            right: 0px;
            width: 15px;
            height: 15px;
            background-color: red;
            border-radius: 50%;
        }
    </style>
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
<div class="containersearch">
    <p style="font-size: 20px; color: #071359; margin-bottom: 13px" class="text-center"><strong>Listado de empleados de baja</strong></p>
    <form id="searchForm" action="/empleados-de-baja" method="GET" class="mb-3 text-right">
        <div class="SearchAndShow block">
            <div class="mx-auto">
                <input type="text" class="formControl max-w-lg bg-slate-300 text-black w-8/12 px-5 rounded-3xl h-12" id="MostrarTodo" placeholder="Buscar por nombre o apellido..." name="searchQuery" oninput="removeSpecialChars(this)" maxlength="40" value="<?php echo isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '' ?>">
                <button type="submit" class="btn-search">Buscar</button>
                <a href="/empleados-de-baja" class="btn-ver">Ver todos</a>
            </div>
        </div>
    </form>
</div>
    <?php
    date_default_timezone_set('America/Guatemala');
    
    $notificacionesDisponibles = false; // Bandera para saber si hay notificaciones
    
    // Cumpleaños
    $sqlCumpleanios = "SELECT dp.Primer_Nombre, dp.Segundo_Nombre, dp.Primer_Apellido, dp.Segundo_Apellido, dp.Fecha_de_Nac 
                       FROM Dtos_Personales dp 
                       WHERE DATE_FORMAT(dp.Fecha_de_Nac, '%m-%d') = DATE_FORMAT(NOW(), '%m-%d')";
    $resultCumpleanios = $conn->query($sqlCumpleanios);
    
    if (!$resultCumpleanios) {
        die("Error en la consulta de cumpleaños: " . $conn->error);
    }
    
    if ($resultCumpleanios->num_rows > 0) {
        $notificacionesDisponibles = true; // Hay cumpleaños
    }
    
    // Aniversarios
    $sqlAniversarios = "SELECT dp.Primer_Nombre, dp.Segundo_Nombre, dp.Primer_Apellido, dp.Segundo_Apellido, ie.Fecha_contratacion 
                        FROM Dtos_Personales dp 
                        JOIN InfoEmpresarial ie ON dp.ID = ie.Id_Empleado 
                        WHERE DATE_FORMAT(ie.Fecha_contratacion, '%m-%d') = DATE_FORMAT(NOW(), '%m-%d')";
    $resultAniversarios = $conn->query($sqlAniversarios);
    
    if (!$resultAniversarios) {
        die("Error en la consulta de aniversarios: " . $conn->error);
    }
    
    if ($resultAniversarios->num_rows > 0) {
        $notificacionesDisponibles = true; // Hay aniversarios
    }
    ?>
    <!-- Icono de notificación -->
<div class="notification-icon <?php echo $notificacionesDisponibles ? 'with-notifications' : ''; ?>" onclick="toggleNotifications()">
    <span class="material-symbols-outlined">
        cake
    </span>
</div>

<!-- Centro de notificaciones flotante -->
<div class="notification-popup" id="notificationPopup">
    <h2>Centro de Notificaciones</h2>
    <br>
    <div class="floating-container">
        <?php
        if ($resultCumpleanios->num_rows > 0) {
            while ($row = $resultCumpleanios->fetch_assoc()) {
                echo "<div class='notification'>";
                echo "🎂 ¡Hoy es el cumpleaños de: " . $row['Primer_Nombre'] . " " . $row['Primer_Apellido'] . "!";
                echo "</div>";
            }
        } else {
            echo "<div class='notification'>No hay cumpleaños hoy.</div>";
        }

        if ($resultAniversarios->num_rows > 0) {
            while ($row = $resultAniversarios->fetch_assoc()) {
                echo "<div class='notification'>";
                echo "🎉 ¡Hoy es el aniversario de: " . $row['Primer_Nombre'] . " " . $row['Primer_Apellido'] . "!";
                echo "</div>";
            }
        } else {
            echo "<div class='notification'>No hay aniversarios hoy.</div>";
        }
        ?>
    </div>
</div>

<div class="container3 text-center">
    <table class="table">
        <thead>
            <tr>
                <th class="col-8">Código personal</th>
                <th class="col-26">Nombre completo del empleado</th>
                <th class="col-20">Cargo</th>
                <th class="col-20">Fecha de contratación</th>
                <th class="col-10">Acciones</th>
                <th class="col-10">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Número de registros por página
            $recordsPerPage = 20;

            // Determinar la página actual
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            if ($currentPage < 1) {
                $currentPage = 1;
            }

            // Calcular el desplazamiento
            $offset = ($currentPage - 1) * $recordsPerPage;

            // Definir la consulta SQL base
            $sql = "SELECT dp.ID, dp.Primer_Nombre, dp.Segundo_Nombre, dp.Tercer_Nombre, dp.Primer_Apellido, dp.Segundo_Apellido, dp.Apellido_de_Casada, ie.Id_empleado, ie.Estado, ie.Codigo_personal, ie.Cargo, DATE(ie.Fecha_Contratacion) AS Fecha_Contratacion FROM Dtos_Personales dp JOIN InfoEmpresarial ie ON dp.ID = ie.Id_empleado WHERE ie.Estado='De Baja'";

            
            // Verificar si se ha enviado una consulta de búsqueda
            if (isset($_GET['searchQuery']) && !empty($_GET['searchQuery'])) {
                $searchQuery = $conn->real_escape_string($_GET['searchQuery']);
                // Dividir la cadena en palabras individuales
                $searchTerms = explode(' ', $searchQuery);
            
                // Crear la cláusula WHERE dinámicamente para buscar en varios campos
                $whereClauses = [];
                foreach ($searchTerms as $term) {
                    $whereClauses[] = "(dp.Primer_Nombre LIKE '%$term%' OR dp.Segundo_Nombre LIKE '%$term%' OR dp.Primer_Apellido LIKE '%$term%' OR dp.Segundo_Apellido LIKE '%$term%')";
                }
                
               // Unir todas las cláusulas con AND para que se cumplan todas las condiciones
                $sql .= " AND " . implode(' AND ', $whereClauses);
                
                // Registrar la operación de búsqueda en el log
                logCrudOperation('admin', 'SELECT', 'Dtos_Personales, InfoEmpresarial', null, ['searchQuery' => $searchQuery], $conn);
            }
            
            // Agregar el orden por nombre
            $sql .= " ORDER BY ie.Id ASC";
            
            // Limitar los resultados y agregar el desplazamiento
            $sql .= " LIMIT $recordsPerPage OFFSET $offset";
            
            // Ejecutar la consulta SQL
            $result = $conn->query($sql);
            
            // Manejo de errores en la consulta
            if (!$result) {
                die("Error en la consulta SQL: " . $conn->error);
            }
            
            
            // Mostrar los registros en la tabla
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                $estado = $row['Estado'];
            
                // Estilo condicional
                $btnOnClass = 'btn-on';
                $btnOffClass = 'btn-off';
                
                echo "<tr>";
                echo "<td class='bg-slate-200 p-5 py-3 text-black'><strong>" . $row["Codigo_personal"] . "<strong></td>";
                echo "<td class='bg-slate-200 p-5 py-3 text-black'>" . $row["Primer_Nombre"] . " " . $row["Segundo_Nombre"] . " " . $row["Primer_Apellido"] . " " . $row["Segundo_Apellido"] . "</td>";
                echo "<td class='bg-slate-200 p-5 py-3 text-black'>" . $row["Cargo"] . "</td>";
                echo "<td class='bg-slate-200 p-5 py-3 text-black'>" . $row["Fecha_Contratacion"] . "</td>";
                echo "<td class='text-center'><button class='btn-update' onclick=\"window.location.href='/modificar-empleado?id_empleado=" . $row['Id_empleado'] . "&from=empleados-de-baja';\" title='Modificar datos'><span class='material-symbols-outlined'>edit</span></button></td>";

                // Mostrar botón según el estado
                if ($estado === 'Activo') {
                    echo "<td id='Azul' class='text-center'>
                            <button class='$btnOffClass' onclick='updateEmployeeStatus(" . $row['ID'] . ", \"De Baja\")' title='Dar de baja'>
                                <span class='material-symbols-outlined'>delete</span>
                            </button>
                          </td>";
                } elseif ($estado === 'De Baja') {
                    echo "<td id='Azul' class='text-center'>
                            <button class='$btnOnClass' onclick='updateEmployeeStatus(" . $row['ID'] . ", \"Activo\")' title='Dar de alta'>
                                <span class='material-symbols-outlined'>restore_from_trash</span>
                            </button>
                          </td>";
                }
            
                echo "</tr>";
                echo "<tr class='espacio bg-white h-1'></tr>";
            }
            } else {
                echo "<tr><td colspan='4' class='text-center text-red-500'>No se encontraron registros</td></tr>";
            }

            // Calcular el número total de registros
            $countSql = "SELECT COUNT(*) as total FROM Dtos_Personales dp JOIN InfoEmpresarial ie ON dp.ID = ie.Id_Empleado WHERE Estado='De Baja'";
            if (isset($_GET['searchQuery']) && !empty($_GET['searchQuery'])) {
                $countSql .= " AND " . implode(' AND ', $whereClauses);
            }
            $countResult = $conn->query($countSql);
            if ($countResult) {
                $totalRows = $countResult->fetch_assoc()['total'];
                $totalPages = ceil($totalRows / $recordsPerPage);
            } else {
                $totalPages = 1;
            }

            $conn->close();
            ?>
        </tbody>
    </table>

    <!-- Navegación de paginación -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <!-- Enlace para la página anterior -->
            <li class="page-item <?php if ($currentPage <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo ($currentPage - 1); ?><?php echo isset($_GET['searchQuery']) ? '&searchQuery=' . urlencode($_GET['searchQuery']) : ''; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            
            <?php
            // Mostrar enlaces de página
            for ($page = 1; $page <= $totalPages; $page++) {
                echo "<li class='page-item " . ($page == $currentPage ? "active" : "") . "'>";
                echo "<a class='page-link' href='?page=$page" . (isset($_GET['searchQuery']) ? '&searchQuery=' . urlencode($_GET['searchQuery']) : '') . "'>$page</a>";
                echo "</li>";
            }
            ?>

            <!-- Enlace para la página siguiente -->
            <li class="page-item <?php if ($currentPage >= $totalPages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo ($currentPage + 1); ?><?php echo isset($_GET['searchQuery']) ? '&searchQuery=' . urlencode($_GET['searchQuery']) : ''; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<br>

<script>

function confirmarAccion(accion) {
    return confirm(`¿Estás seguro de que deseas ${accion} este empleado?`);
}

function updateEmployeeStatus(idEmpleado, nuevoEstado) {
    // Preguntar al usuario si está seguro de la acción
    var accion = nuevoEstado === 'Activo' ? 'activar' : 'dar de baja';
    if (!confirmarAccion(accion)) {
        return; // Si el usuario cancela, salir de la función
    }

    // Crear objeto XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "actualizar_estado.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Manejar la respuesta del servidor
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Recargar la página para mostrar el cambio
            location.reload();
        }
    };

    // Enviar la solicitud con los datos
    xhr.send("id_empleado=" + idEmpleado + "&nuevo_estado=" + nuevoEstado);
    }

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
    
    function toggleNotifications() {
    const popup = document.getElementById('notificationPopup');
    popup.classList.toggle('active');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
