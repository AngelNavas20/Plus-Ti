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
// Registrar la operación en el log
    $detallesArray = [
        'query' => $sql,
        'campos' => [
            'Campo1' => 'Primer_Nombre',
            'Campo2' => 'Segundo_Nombre',
            'Campo3' => 'Primer_Apellido',
            'Campo4' => 'Segundo_Apellido',
            'Campo5' => 'Institucion_Colegio',
            'Campo6' => 'Grado',
            'Campo7' => 'Carrera',
            'Campo8' => 'Habilidades'
        ]
    ];
    
    logCrudOperation('admin', 'SELECT', 'Dtos_Personales JOIN InfoEmpresarial', null, $detallesArray, $conn);

$usuario = 'admin';
// Código para el formulario de búsqueda y el registro de la consulta
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['searchQuery']) && !empty($_GET['searchQuery'])) {
    $searchQuery = sanitizeInput($_GET['searchQuery'], $conn);
    $searchTerms = explode(' ', $searchQuery);

    $whereClauses = [];
    foreach ($searchTerms as $term) {
        $term = sanitizeInput($term, $conn);
        $whereClauses[] = "(dp.Primer_Nombre LIKE '%$term%' OR dp.Segundo_Nombre LIKE '%$term%' OR dp.Primer_Apellido LIKE '%$term%' OR dp.Segundo_Apellido LIKE '%$term%' OR ip.Institucion_Colegio LIKE '%$term%' OR ip.Grado LIKE '%$term%' OR ip.Carrera LIKE '%$term%' OR ip.Habilidades LIKE '%$term%')";
    }

    $sql = "SELECT Primer_Nombre, Segundo_Nombre, Primer_Apellido, Segundo_Apellido, Institucion_Colegio, Grado, Carrera, Habilidades
            FROM Dtos_Personales dp JOIN Informacion_Practicas ip ON dp.ID = ip.ID_Practicante WHERE ip.Estado='Pendiente'";

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
            'Campo4' => 'Segundo_Apellido',
            'Campo5' => 'Institucion_Colegio',
            'Campo6' => 'Grado',
            'Campo7' => 'Carrera',
            'Campo8' => 'Habilidades'
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
    <title>Solicitudes de prácticas</title>
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
        .btn-revisar {
            background-color: #FFA000;
            color: #261800;
            padding-left:20px;
            padding-right:20px;
            padding-top: 10px;
            padding-bottom: 10px;
            margin: auto;
            border-radius: 15px;
            cursor: pointer;
        }

        .btn-revisar:hover {
            background-color: #FFA000;
            
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
        /* Columnas con anchos específicos */
        .col-22 {
            width: 19%;
        }

        .col-12 {
            width: 12%;
        }
        .col-24 {
            width: 23%;
        }

        .col-10 {
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
        .navprincipal{
            background-color: #071359;
        }
        .dropdown-menu {
            width: 100%; /* Se ajusta al ancho del elemento padre */
            min-width: unset; /* Elimina el ancho mínimo predeterminado de Bootstrap */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .status {
            font-size: 35px;
            margin: 2px 2px 0 0;
            display: inline-block;
            vertical-align: middle;
            line-height: 10px;
        }
        .filter-button {
            background-color: #2D6DA6; /* Color gris */
            border: none; /* Sin borde */
            color: #fff; /* Color del texto (si lo hay) */
            width: 48px; /* Ajusta el tamaño */
            height: 47px; /* Igual a width para que sea circular */
            border-radius: 50%; /* Hace que el botón sea circular */
            cursor: pointer; /* Cambia el cursor al pasar por encima */
        }
        
        .filter-button:hover {
            background-color: #163573;
            color: #fff;
        }
        
        .filter-button:focus,
        .filter-button:active {
            outline: none; /* Evita el outline al hacer clic o enfocar */
            box-shadow: none; /* Sin sombra al hacer clic */
        }
        .modal-content{
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .form-control {
            border-radius: 0.5rem;
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
    <p style="font-size: 20px; color: #071359; margin-bottom: 13px" class="text-center"><strong>Listado de solicitudes de prácticas</strong></p>
    <form id="searchForm" action="/solicitudes-practicas" method="GET" class="mb-3 text-right">
        <div class="SearchAndShow block">
            <div class="mx-auto">
                <button type="button" class="btn filter-button inline-flex items-center" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <span class="material-symbols-outlined">tune</span>
                </button>
                <input type="text" class="formControl max-w-lg bg-slate-300 text-black w-8/12 px-5 rounded-3xl h-12" id="MostrarTodo" placeholder="Buscar por nombre o apellido..." name="searchQuery" oninput="removeSpecialChars(this)" maxlength="40" value="<?php echo isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '' ?>">
                <button type="submit" class="btn-search">Buscar</button>
                <button type="button" class="btn-search" onclick="location.href='/solicitudes-practicas'">Ver todos</button>
            </div>
        </div>
    </form>
</div>
<!-- Modal para filtros -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3xl">
            <div class="modal-body" style="margin: 20px; max-width: 100%;">
                <form id="filterForm" action="/solicitudes-practicas" method="GET">
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control" id="startDate" name="startDate" value="<?php echo isset($_GET['startDate']) ? $_GET['startDate'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">Fecha de fin</label>
                        <input type="date" class="form-control" id="endDate" name="endDate" value="<?php echo isset($_GET['endDate']) ? $_GET['endDate'] : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Todos</option>
                            <option value="Aceptado" <?php echo isset($_GET['status']) && $_GET['status'] == 'Aceptado' ? 'selected' : '' ?>>Aceptado</option>
                            <option value="Denegado" <?php echo isset($_GET['status']) && $_GET['status'] == 'Denegado' ? 'selected' : '' ?>>Denegado</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Aplicar filtro</button>
                    </div>                   
                </form>
            </div>
        </div>
    </div>
</div>
<div class="container3 text-center">
    <table class="table">
        <thead>
            <tr>
                <th class="col-24">Nombre completo del solicitante</th>
                <th class="col-22">Colegio</th>
                <th class="col-22">Carrera</th>
                <th class="col-22">Habilidades</th>
                <th class="col-10">Estado</th>
                <th class="col-12">Acciones</th>
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
            $sql = "SELECT ID, Primer_Nombre, Segundo_Nombre, Primer_Apellido, Segundo_Apellido, Institucion_Colegio, Grado, Carrera, Habilidades, Estado
            FROM Dtos_Personales dp JOIN Informacion_Practicas ip ON dp.ID = ip.ID_Practicante WHERE 1=1 AND ip.Estado IN ('Aceptado', 'Denegado')";
            
            
           
            // Agregar cláusulas de búsqueda si se proporciona una consulta
            if (isset($_GET['searchQuery']) && !empty($_GET['searchQuery'])) {
                $searchQuery = $conn->real_escape_string($_GET['searchQuery']);
                $searchTerms = explode(' ', $searchQuery);
            
                $whereClauses = [];
                foreach ($searchTerms as $term) {
                    $whereClauses[] = "(dp.Primer_Nombre LIKE '%$term%' OR dp.Segundo_Nombre LIKE '%$term%' OR dp.Primer_Apellido LIKE '%$term%' OR dp.Segundo_Apellido LIKE '%$term%' OR ip.Institucion_Colegio LIKE '%$term%' OR ip.Grado LIKE '%$term%' OR ip.Carrera LIKE '%$term%' OR ip.Habilidades LIKE '%$term%' OR ip.Estado LIKE '%$term%')";
                }
            
                $sql .= " AND (" . implode(' AND ', $whereClauses) . ")";
                $detailsArray = ['searchQuery' => $searchQuery];
                logCrudOperation('admin', 'SELECT', 'Dtos_Personales JOIN Informacion_Practicas', null, ['searchQuery' => $searchQuery], $conn);
            }
            
            // Filtrar por fecha si se proporcionan fechas
            if (isset($_GET['startDate']) && !empty($_GET['startDate'])) {
                $startDate = $conn->real_escape_string($_GET['startDate']);
                $sql .= " AND ip.Fecha_De_Solicitud >= '$startDate'";
                logCrudOperation('admin', 'SELECT', 'Dtos_Personales JOIN Informacion_Practicas', null, ['Fecha_De_Solicitud_Inicial' => $startDate], $conn);
            }
            if (isset($_GET['endDate']) && !empty($_GET['endDate'])) {
                $endDate = $conn->real_escape_string($_GET['endDate']);
                $sql .= " AND ip.Fecha_De_Solicitud <= '$endDate'";
                logCrudOperation('admin', 'SELECT', 'Dtos_Personales JOIN Informacion_Practicas', null, ['Fecha_De_Solicitud_Final' => $endDate], $conn);
            }
            
            // Filtrar por estado si se proporciona un estado
            if (isset($_GET['status']) && !empty($_GET['status'])) {
                $status = $conn->real_escape_string($_GET['status']);
                $sql .= " AND ip.Estado = '$status'";
                logCrudOperation('admin', 'SELECT', 'Dtos_Personales JOIN Informacion_Practicas', null, ['Estado' => $status], $conn);
            }

            
            // Agregar el orden por nombre
            $sql .= " ORDER BY ip.Fecha_De_Solicitud ASC";
            
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
                    echo "<tr>";
                    echo "<td class='bg-slate-200 p-5 py-3 text-black'>" . $row["Primer_Nombre"] . " " . $row["Segundo_Nombre"] . " " . $row["Primer_Apellido"] . " " . $row["Segundo_Apellido"] . "</td>";
                    echo "<td class='bg-slate-200 p-5 py-3 text-black'>" . $row["Institucion_Colegio"] . "</td>";
                    echo "<td class='bg-slate-200 p-5 py-3 text-black'>" . $row["Grado"] . " " . $row["Carrera"] . "</td>";
                    echo "<td class='bg-slate-200 p-5 py-3 text-black'>" . $row["Habilidades"] . "</td>";
            
                    $estado = $row["Estado"];
                    if ($estado == 'Aceptado') {
                        echo "<td><span class='status text-green-500'>&bull;</span> Aceptado</td>";
                    } elseif ($estado == 'Denegado') {
                        echo "<td><span class='status text-red-500'>&bull;</span> Denegado</td>";
                    } else {
                        echo "<td><span class='status text-gray-500'>&bull;</span> Desconocido</td>";
                    }
                    echo "<td class='text-center'><button class='btn-revisar' onclick=\"window.location.href='/consulta-informacion-aspirante?id_aspirante=" . $row['ID'] . "';\" title='Revisar solicitud'><span class='material-symbols-outlined'>quick_reference_all</span></button></td>";
                    echo "</tr>";
                    echo "<tr class='espacio bg-white h-1'></tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center text-red-500'>No se encontraron registros</td></tr>";
            }
            
            // Calcular el número total de registros
            $countSql = "SELECT COUNT(*) as total FROM Dtos_Personales dp JOIN Informacion_Practicas ip ON dp.ID = ip.ID_Practicante WHERE 1=1 AND Estado='Aceptado' OR Estado='Denegado'";
            if (isset($_GET['searchQuery']) && !empty($_GET['searchQuery'])) {
                $countSql .= " AND (" . implode(' AND ', $whereClauses) . ")";
            }
            if (isset($_GET['startDate']) && !empty($_GET['startDate'])) {
                $countSql .= " AND ip.Fecha_De_Solicitud >= '$startDate'";
            }
            if (isset($_GET['endDate']) && !empty($_GET['endDate'])) {
                $countSql .= " AND ip.Fecha_De_Solicitud <= '$endDate'";
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
