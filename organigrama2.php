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

// Consulta SQL combinada para obtener los nombres de los empleados y los códigos
$sql = "SELECT InfoEmpresarial.Codigo_personal, Dtos_Personales.Primer_Nombre, Dtos_Personales.Segundo_Nombre, Dtos_Personales.Primer_Apellido, Dtos_Personales.Segundo_Apellido, InfoEmpresarial.Personal_cargo 
        FROM InfoEmpresarial 
        JOIN Dtos_Personales ON InfoEmpresarial.Id_empleado = Dtos_Personales.ID";

$result = $conn->query($sql);

$empleados = array();

if ($result->num_rows > 0) {
    // Guardar los datos en un array
    while($row = $result->fetch_assoc()) {
        $empleados[] = array(
            'codigo_personal' => $row['Codigo_personal'],
            'nombre_completo' => $row['Primer_Nombre'] . ' ' . $row['Segundo_Nombre'] . ' ' . $row['Primer_Apellido'] . ' ' . $row['Segundo_Apellido'],
            'personal_cargo' => $row['Personal_cargo'] // Agregar el campo Personal_cargo
        );
    }
} else {
    echo "No se encontraron resultados.";
}
$conn->close();

// Pasar los datos a JavaScript
echo "<script>var empleadosDB = " . json_encode($empleados) . ";</script>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organigrama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
            width: 22%;
        }

        .col-12 {
            width: 12%;
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
        /* Estilos para un organigrama más limpio y estructurado */
        .department {
            margin-top: 20px;
            padding: 10px;
            border-radius: 8px;
            background-color: #f8fafc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            overflow: hidden;
        }

        .department h3 {
            color: #071359;
            margin-left: 25px;
        }

        .area {
            margin-left: 25px;
            padding: 5px;
            border-left: 2px solid #163573;
        }

        .employee {
            padding-left: 25px;
            border-left: 1px dashed #2D6DA6;
            margin-bottom: 5px;
        }
        .employee {
            padding-left: 100px; /* Ajusta este valor para alinearlo con el texto del coordinador */
        }
        
        .coordinador {
            padding-left: 0;
            font-weight: bold;
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
    <div class="container">
        <div id="organigrama" class="organigrama"></div>
    </div>

<script>
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

    // Mapa de códigos de departamentos con sus nombres
    const departamentos = {
        "001.001": "Dirección General",
        "002.001": "Dirección de Tecnología",
        "003.001": "Dirección de Producto",
        "004.001": "Dirección de Ventas y Marketing",
        "005.001": "Dirección de Soporte y Servicios"
    };

    
// Comprobar los datos de empleados obtenidos de la base de datos
console.log(empleadosDB);

// Función para crear el organigrama, agrupando por departamento y áreas
function generarOrganigrama() {
    const organigrama = document.getElementById('organigrama');

    // Agrupar empleados por departamento y área
    const departamentosMap = {};

   empleadosDB.forEach(empleado => {
    const codigo = empleado.codigo_personal;
    const nombreEmpleado = empleado.nombre_completo;
    const esCoordinador = empleado.personal_cargo > 0; // Comprobar si es coordinador

    // Separar el código en partes (departamento y área)
    const partes = codigo.split('.');
    const codigoDepartamento = `${partes[0]}.${partes[1]}`;
    const codigoArea = partes[2];

    // Si el departamento no existe en el mapa, agregarlo
    if (!departamentosMap[codigoDepartamento]) {
        departamentosMap[codigoDepartamento] = {};
    }

    // Si el área no existe en el departamento, agregarla
    if (!departamentosMap[codigoDepartamento][codigoArea]) {
        departamentosMap[codigoDepartamento][codigoArea] = [];
    }

    // Agregar el empleado a la lista del área correspondiente
    departamentosMap[codigoDepartamento][codigoArea].push({
        nombre: nombreEmpleado,
        coordinador: esCoordinador
    });
});

// Crear el organigrama a partir del mapa de departamentos y áreas
for (const [codigoDepartamento, areasMap] of Object.entries(departamentosMap)) {
    if (departamentos[codigoDepartamento]) {
        const departamentoDiv = document.createElement('div');
        departamentoDiv.className = 'department';
        departamentoDiv.innerHTML = `<h3><strong>${departamentos[codigoDepartamento]}</strong></h3>`;

        // Agregar las áreas y los empleados
        for (const [codigoArea, empleados] of Object.entries(areasMap)) {
            const areaEncontrada = areas[codigoDepartamento]?.find(area => area.value === codigoArea);

            if (areaEncontrada) {
                const areaDiv = document.createElement('div');
                areaDiv.className = 'area';
                areaDiv.innerHTML = `<strong>Área: ${areaEncontrada.text} (Código: ${codigoDepartamento}.${codigoArea})</strong>`;

                // Ordenar los empleados: los coordinadores primero
                empleados.sort((a, b) => b.coordinador - a.coordinador);

                // Agregar los empleados del área
                empleados.forEach(empleado => {
                    const empleadoDiv = document.createElement('div');
                    empleadoDiv.className = `employee ${empleado.coordinador ? 'coordinador' : ''}`;
                    empleadoDiv.innerText = `${empleado.coordinador ? 'Coordinador:' : ''} ${empleado.nombre}`;
                    areaDiv.appendChild(empleadoDiv);
                });

                departamentoDiv.appendChild(areaDiv);
            }
        }

        organigrama.appendChild(departamentoDiv);
    }
}

}

// Generar el organigrama al cargar la página
generarOrganigrama();
</script>

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