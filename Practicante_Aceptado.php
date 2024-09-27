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
   // Recibir el ID del empleado desde la URL
    $primer_nombre = isset($_GET['primer_nombre']) ? $_GET['primer_nombre'] : null;
    $segundo_nombre = isset($_GET['segundo_nombre']) ? $_GET['segundo_nombre'] : null;
    $primer_apellido = isset($_GET['primer_apellido']) ? $_GET['primer_apellido'] : null;
    $segundo_apellido = isset($_GET['segundo_apellido']) ? $_GET['segundo_apellido'] : null;
    $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
    $fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : null;
   // Recibir el ID del empleado desde la URL
    $correo_electronico = isset($_GET['correo_electronico']) ? $_GET['correo_electronico'] : null;

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        .sidebar {
            padding: 30px;
            background-color: #163573;
            border-radius: 10px;
            color: #fff;
        }
        .sidebar h5 {
            text-align: center;
            font-weight: bold;
            color: white;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
        }
        .content h5 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 0.5rem;
        }
        .btn-aceptar {
            display: block;
            margin: 0 auto;
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
            display: block;
            margin: 0 auto;
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
        .form-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .dropdown-menu {
            width: 100%; /* Se ajusta al ancho del elemento padre */
            min-width: unset; /* Elimina el ancho mínimo predeterminado de Bootstrap */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .navprincipal{
            background-color: #071359;
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
    <div class="container mt-4">
        <form action="Crud_Practicante.php" method="POST">
            <div class="form-section">
                <div class="row">
                <div class="col-md-6">
                    <div class="sidebar">
                        <h5>Información del aspirante</h5>
                         <?php
                        // Obtener el ID del empleado
                        echo '<input type="hidden" name="id_aspirante" value="' . $id_aspirante . '">';
                        echo '<input type="hidden" name="primer_nombre" value="' . $primer_nombre . '">';
                        echo '<input type="hidden" name="segundo_nombre" value="' . $segundo_nombre . '">';
                        echo '<input type="hidden" name="primer_apellido" value="' . $primer_apellido . '">';
                        echo '<input type="hidden" name="segundo_apellido" value="' . $segundo_apellido . '">';
                        echo '<input type="hidden" name="fecha_inicio" value="' . $fecha_inicio . '">';
                        echo '<input type="hidden" name="fecha_final" value="' . $fecha_final . '">';
                        echo '<input type="hidden" name="correo_electronico" value="' . $correo_electronico . '">';
                        ?>
                        <?php
                        
                        // Definir la consulta SQL base para Dtos_Personales
                        $sql2 = "SELECT * FROM Informacion_Practicas WHERE ID_Practicante = ?";
        
                        // Preparar y ejecutar la consulta
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->bind_param("s", $id_aspirante);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();
        
                        // Mostrar los registros en los campos de texto
                        if ($result2->num_rows > 0) {
                            while($row = $result2->fetch_assoc()) {
                                if (!empty($row["Fecha_De_inicio"]) && $row["Fecha_De_inicio"] !== '0000-00-00 00:00:00') {
                                    $timestamp = strtotime($row["Fecha_De_inicio"]);
                                    if ($timestamp !== false) {
                                        $fecha_inicio = date('Y-m-d', $timestamp);
                                    } else {
                                        $fecha_inicio = '';
                                    }
                                } else {
                                    $fecha_inicio = '';
                                }
                                if (!empty($row["Fecha_De_Finalización"]) && $row["Fecha_De_Finalización"] !== '0000-00-00 00:00:00') {
                                    $timestamp = strtotime($row["Fecha_De_Finalización"]);
                                    if ($timestamp !== false) {
                                        $fecha_final = date('Y-m-d', $timestamp);
                                    } else {
                                        $fecha_final = '';
                                    }
                                } else {
                                    $fecha_final = '';
                                }
                                echo '<div class="mb-2">';
                                echo '<label for="colegio">Colegio:</label>';
                                echo '<input type="text" class="form-control" name="colegio" value="' . $row["Institucion_Colegio"] . '" placeholder="Colegio" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="grado">Grado:</label>';
                                echo '<input type="text" class="form-control" name="grado" value="' . $row["Grado"] . '" placeholder="Grado" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="carrera">Carrera:</label>';
                                echo '<input type="text" class="form-control" name="carrera" value="' . $row["Carrera"] . '" placeholder="Carrera" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="habilidades">Habilidades:</label>';
                                echo '<input type="text" class="form-control" name="habilidades" value="' . $row["Habilidades"] . '" placeholder="Habilidades" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="numero_De_Horas">Numer de horas:</label>';
                                echo '<input type="text" class="form-control" name="numero_De_Horas" value="' . $row["Numero_De_Horas"] . '" placeholder="Numero_De_Horas" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="Fecha_De_inicio">Fecha de inicio:</label>';
                                echo '<input type="text" class="form-control" name="Fecha_De_inicio" value="' . $fecha_inicio . '" placeholder="Fecha_De_inicio" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="Fecha_De_Finalización">Fecha de Finalización:</label>';
                                echo '<input type="text" class="form-control" name="Fecha_De_Finalización" value="' . $fecha_final . '" placeholder="Fecha_De_Finalización" readonly>';
                                echo '</div>';
                                echo '<div class="mb-2">';
                                echo ' <label for="Referido">Referido:</label>';
                                echo '<input type="text" class="form-control" name="Referido" value="' . $row["Referido"] . '" placeholder="Referido" readonly>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No se encontraron registros personales</p>';
                        }
                        ?>
                    </div>
                </div>
                <!-- información Personal -->
                <div class="col-md-6">
                    <div class="content">
                        <h5>Información Empresarial</h5>
                        
                        <div class="mb-3">
                            <label for="departamento">Departamento:<span style="color:red;">*</span></label>
                            <select id="departamento" name="departamento" class="form-control" required onchange="actualizarAreas()">
                                <option value="001.001">Dirección General</option>
                                <option value="002.001">Dirección de Tecnología</option>
                                <option value="003.001">Dirección de Producto</option>
                                <option value="004.001">Dirección de Ventas y Marketing</option>
                                <option value="005.001">Dirección de Soporte y Servicios</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="area">Área Específica:<span style="color:red;">*</span></label>
                            <select id="area" name="area" class="form-control" required>
                                <!-- Las opciones se actualizarán dinámicamente -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Fecha_contratacion">Fecha de contratación:<span style="color:red;">*</span></label>
                            <input type="date" name="Fecha_contratacion" id="Fecha_contratacion" class="form-control" required>
                             <input type="hidden" name="nuevo_estado" value="Aceptado">
                        </div>
                        <div class="mb-3">
                            <label for="Fecha_cargo">Fecha de inicio en el cargo:<span style="color:red;">*</span></label>
                            <input type="date" name="Fecha_cargo" id="Fecha_cargo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="Descripcion_responsabilidades">Responsabilidades:<span style="color:red;">*</span></label>
                            <textarea name="Descripcion_responsabilidades" id="Descripcion_responsabilidades" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="Jefe_inmediato">Jefe inmediato:<span style="color:red;">*</span></label>
                            <input type="text" name="Jefe_inmediato" id="Jefe_inmediato" class="form-control" required>
                        </div>
                         <button type="submit" name="action" value="Enviar Datos" class="btn-aceptar">Aceptar Practicante</button>
                    </div>
                </div>
            </div>
            </div>
        </form>
    </div>
    <script>
        // Definición de las áreas específicas por departamento
        const areas = {
            "001.001": [
                { value: "001", text: "Finanzas" },
                { value: "002", text: "Recursos Humanos" },
                { value: "003", text: "Compras" },
                { value: "004", text: "Legal" },
                { value: "005", text: "Servicios Generales" }
            ],
            "002.001": [
                { value: "001", text: "Backend" },
                { value: "002", text: "Frontend" },
                { value: "003", text: "Mobile" },
                { value: "004", text: "QA/Testing" },
                { value: "005", text: "DevOps" }
            ],
            "003.001": [
                { value: "001", text: "Product Manager" },
                { value: "002", text: "Diseño de Producto" },
                { value: "003", text: "Investigación y Desarrollo (I+D)" },
                { value: "004", text: "Gestión de Proyectos" },
                { value: "005", text: "Soporte al Producto" }
            ],
            "004.001": [
                { value: "001", text: "Ventas Corporativas" },
                { value: "002", text: "Ventas Digitales" },
                { value: "003", text: "Marketing" },
                { value: "004", text: "Customer Success" },
                { value: "005", text: "Relaciones Públicas" }
            ],
            "005.001": [
                { value: "001", text: "Soporte Técnico" },
                { value: "002", text: "Centro de Llamadas" },
                { value: "003", text: "Implementación de Servicios" },
                { value: "004", text: "Formación y Capacitación" },
                { value: "005", text: "Gestión de Incidencias" }
            ]
        };

        // Función para actualizar las áreas específicas según el departamento seleccionado
        function actualizarAreas() {
            const departamentoSelect = document.getElementById("departamento");
            const areaSelect = document.getElementById("area");
            const departamentoSeleccionado = departamentoSelect.value;

            // Limpiar opciones actuales
            areaSelect.innerHTML = "";

            // Obtener las áreas específicas del departamento seleccionado
            const opciones = areas[departamentoSeleccionado];

            // Añadir las nuevas opciones al select de área
            opciones.forEach(function(opcion) {
                const newOption = document.createElement("option");
                newOption.value = opcion.value;
                newOption.text = opcion.text;
                areaSelect.add(newOption);
            });
        }
        
         // Inicializar áreas específicas al cargar la página
        window.onload = function() {
            actualizarAreas();
        };
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


<?php
    // Cerrar la conexión a la base de datos
    $conn->close();
?>