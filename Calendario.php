<?php
date_default_timezone_set('America/Guatemala');

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "u943374840_AngelyLucia";
$password = "W1IagP3[IQi6j=lflShñ";
$database = "u943374840_PlusTiES";

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit();
}

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Obtener el mes y año seleccionados
$mesActual = isset($_GET['mes']) ? (int)$_GET['mes'] : date('n');
$anioActual = isset($_GET['anio']) ? (int)$_GET['anio'] : date('Y');

// Consultar cumpleaños y aniversarios del mes actual
$sqlEventos = "
    SELECT dp.Primer_Nombre, dp.Segundo_Nombre, dp.Primer_Apellido, dp.Segundo_Apellido, dp.Fecha_de_Nac, 'Cumpleaños' AS tipo_evento
    FROM Dtos_Personales dp 
    WHERE MONTH(dp.Fecha_de_Nac) = $mesActual
    UNION
    SELECT dp.Primer_Nombre, dp.Segundo_Nombre, dp.Primer_Apellido, dp.Segundo_Apellido, ie.Fecha_contratacion, 'Aniversario' AS tipo_evento
    FROM Dtos_Personales dp 
    JOIN InfoEmpresarial ie ON dp.ID = ie.Id_Empleado 
    WHERE MONTH(ie.Fecha_contratacion) = $mesActual
";

// Ejecutar consulta de cumpleaños y aniversarios
$resultEventos = $conn->query($sqlEventos);

// Consultar eventos personalizados del mes actual
$sqlEventos2 = "
    SELECT titulo, fecha, 'Evento' AS tipo_evento
    FROM eventos e
    WHERE MONTH(e.fecha) = $mesActual
";
$resultEventos2 = $conn->query($sqlEventos2);

// Inicializar el array de eventos
$eventos = [];

// Procesar los resultados de cumpleaños y aniversarios
if ($resultEventos->num_rows > 0) {
    while ($row = $resultEventos->fetch_assoc()) {
        $fechaEvento = isset($row['Fecha_de_Nac']) ? $row['Fecha_de_Nac'] : $row['Fecha_contratacion'];
        $dia = date('d', strtotime($fechaEvento));
        $eventos[$dia][] = [
            'nombre' => $row['Primer_Nombre'] . " " . $row['Primer_Apellido'],
            'tipo' => $row['tipo_evento']
        ];
    }
}

// Procesar los resultados de eventos personalizados
if ($resultEventos2->num_rows > 0) {
    while ($row = $resultEventos2->fetch_assoc()) {
        $fechaEvento = $row['fecha'];
        $dia = date('d', strtotime($fechaEvento));
        $eventos[$dia][] = [
            'nombre' => $row['titulo'],
            'tipo' => $row['tipo_evento']
        ];
    }
}

// Nombres de meses
$nombresMeses = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
    7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];

?>


<script>
function mostrarNombreMes() {
    var select = document.getElementById('mes');
    var nombreMes = select.options[select.selectedIndex].text;
    document.getElementById('nombreMesSeleccionado').innerHTML = "<p>Mes seleccionado: " + nombreMes + "</p>";
}
</script>

<?php
function generarCalendario($mes, $anio, $eventos, $eventos2) {
    $primerDia = mktime(0, 0, 0, $mes, 1, $anio);
    $diasDelMes = date('t', $primerDia);
    $diaDeLaSemana = date('N', $primerDia);
    
    echo "<table class='calendario'>";
    echo "<tr><th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th><th>Sábado</th><th>Domingo</th></tr>";
    
    echo "<tr>";
    for ($j = 1; $j < $diaDeLaSemana; $j++) {
        echo "<td></td>";
    }

    for ($dia = 1; $dia <= $diasDelMes; $dia++) {
        if ($diaDeLaSemana > 7) {
            echo "</tr><tr>";
            $diaDeLaSemana = 1;
        }

        if (isset($eventos[$dia])) {
            echo "<td class='evento'>";
            echo "<div class='dia-circulo'>$dia</div>";
            foreach ($eventos[$dia] as $evento) {
                if ($evento['tipo'] == 'Cumpleaños') {
                    echo "<div class='evento-detalle'>";
                    echo "<span class='icono-cumple'>🎂</span> <strong>Cumpleaños:</strong> " . $evento['nombre'];
                    echo "</div>";
                } elseif ($evento['tipo'] == 'Aniversario') {
                    echo "<div class='evento-detalle'>";
                    echo "<span class='icono-aniversario'>🎉</span> <strong>Aniversario:</strong> " . $evento['nombre'];
                    echo "</div>";
                } elseif ($evento['tipo'] == 'Evento') {
                    echo "<div class='evento-detalle'>";
                    echo "<span class='icono-evento'>📅</span> <strong>Evento:</strong> " . $evento['nombre'];
                    echo "</div>";
                }
            }
            echo "</td>";
        } else {
            echo "<td>$dia</td>";
        }

        $diaDeLaSemana++;
    }
    
    if ($diaDeLaSemana <= 7) {
        for ($j = $diaDeLaSemana; $j <= 7; $j++) {
            echo "<td></td>";
        }
    }
    
    echo "</tr>";
    echo "</table>";
}

$conn->close();
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
            padding: 20px 40px 40px;
            border-radius:15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .calendario {
            width: 100%;
            border-collapse: collapse;
        }

        .calendario th {
            background-color: #071359;
            color: #fff;
            border: #fff;
            padding: 10px;
        }

        .calendario td {
            text-align: left;
            padding: 15px;
            border: 1px solid #ddd;
            height: 100px;
            vertical-align: top;
        }
        .numday{
            color: red;
        }

        /*Color de Evento*/


        .navprincipal {
            background-color: #071359;
        }

        .nav-tabs .nav-link.active {
            color: #fff;
            background-color: #071359;
            border-radius: 10px 10px 0px 0px;
            border: #071359;
            margin-right: 5px;
            transition: border-color 0.3s ease;
        }
        .dia-circulo {
            width: 40px;              /* Ancho del círculo */
            height: 40px;             /* Alto del círculo */
            background-color: #163573;    /* Color de fondo rojo */
            color: white;             /* Color del texto blanco */
            border-radius: 50%;       /* Hace el div circular */
            display: flex;            /* Alinear contenido */
            align-items: center;      /* Centrar verticalmente */
            justify-content: center;  /* Centrar horizontalmente */
            font-size: 16px;          /* Tamaño de la fuente */
            margin: 0 auto;           /* Centrar el círculo dentro del td */
            margin-bottom: 8px;
        }
        #mes {
            appearance: none; /* Quita el estilo predeterminado del navegador */
            -webkit-appearance: none; /* Para Safari */
            -moz-appearance: none; /* Para Firefox */
            background-color: #f0f0f0; /* Color de fondo */
            border: none; /* Bordes sutiles */
            border-radius: 18px; /* Bordes redondeados */
            padding: 10px; /* Espaciado interno */
            font-size: 16px; /* Tamaño de la fuente */
            color: #333; /* Color del texto */
            cursor: pointer; /* Cambia el cursor al pasar por encima */
            width: 150px; /* Ancho del select */
            transition: border-color 0.3s ease; /* Efecto de transición */
        }
        
        #mes:focus {
            border-color: #2a6aa8; /* Color del borde al enfocar */
            outline: none; /* Quitar el contorno predeterminado */
        }
        
        #mes option {
            padding: 10px; /* Espaciado interno en las opciones */
            background-color:#fff;
            border:none;
        }
        .form-inline {
            display: flex; /* Usa flexbox para alinear elementos en línea */
            align-items: center; /* Alinea verticalmente en el centro */
        }
        
        .add-event-button {
            background-color: #2a6aa8; /* Color de fondo del botón */
            border: none; /* Sin borde */
            border-radius: 50%; /* Hacer el botón circular */
            width: 45px; /* Ancho del botón */
            height: 45px; /* Alto del botón */
            display: flex; /* Para centrar el icono */
            align-items: center; /* Centrar verticalmente */
            justify-content: center; /* Centrar horizontalmente */
            cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
            margin-left: 10px; /* Espacio entre el select y el botón */
        }
        
        .add-event-button span {
            color: white; /* Color del icono */
            font-size: 28px; /* Tamaño del icono */
        }
        
        .add-event-button:hover {
            background-color: #1a4b88; /* Color al pasar el mouse */
        }
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; /* Mantener en su lugar */
            z-index: 1; /* Por encima de otros elementos */
            left: 0;
            top: 0;
            width: 100%; /* Ancho completo */
            height: 100%; /* Alto completo */
            overflow: auto; /* Habilitar desplazamiento si es necesario */
            background-color: rgba(0, 0, 0, 0.6); /* Fondo semi-transparente */
        }
        
        .modal-content {
            background-color: #ffffff;
            margin: 10% auto; /* 10% desde la parte superior y centrado */
            padding: 30px;
            border-radius: 8px; /* Bordes redondeados */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Sombra del modal */
            width: 80%; /* Ancho del modal */
            max-width: 500px; /* Ancho máximo */
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: #071359;
            text-decoration: none;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 15px; /* Espacio entre los grupos de formulario */
        }
        
        label {
            display: block; /* Hacer que las etiquetas ocupen todo el ancho */
            margin-bottom: 5px; /* Espacio debajo de la etiqueta */
        }
        
        input[type="text"],
        input[type="date"] {
            width: 100%; /* Ancho completo */
            padding: 10px; /* Espaciado interno */
            border: 1px solid #ccc; /* Borde gris claro */
            border-radius: 4px; /* Bordes redondeados */
        }
        
        input[type="submit"] {
            background-color: #2a6aa8; /* Color de fondo del botón */
            color: white; /* Color del texto */
            border: none; /* Sin borde */
            border-radius: 4px; /* Bordes redondeados */
            padding: 10px 15px; /* Espaciado interno */
            cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
            font-size: 16px; /* Tamaño del texto */
            transition: background-color 0.3s; /* Transición para el efecto hover */
        }
        
        input[type="submit"]:hover {
            background-color: #1a4b88; /* Color al pasar el mouse */
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
<p style="font-size: 20px; color: #071359; margin-top: 13px" class="text-center"><strong>Calendario de novedades</strong></p>
<div class="container mt-3">
    <div class="tab-content" id="calendar-tab">
    <form method="GET" action="" class="form-inline">
        <label for="mes">Mes:</label>
        <select name="mes" id="mes" onchange="this.form.submit()">
            <?php
            for ($i = 1; $i <= 12; $i++) {
                $selected = ($i == $mesActual) ? 'selected' : '';
                echo "<option id='Mes_design' value='$i' $selected>{$nombresMeses[$i]}</option>";
            }
            ?>
        </select>
        <!-- Botón circular a la derecha del select -->
        <button id="addEventBtn" class="add-event-button" title="Agregar Evento" onclick="openModal(event)">
            <span class="material-symbols-outlined">calendar_add_on</span>
        </button>
    </form>

    <?php 
    generarCalendario($mesActual, $anioActual, $eventos, $eventos2); 
    ?>

    <!-- Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close text-end" onclick="closeModal()">&times;</span>
            <form id="addEventForm" method="POST" action="guarda_evento.php">
                <div class="form-group">
                    <label for="eventTitle">Título del Evento:</label>
                    <input type="text" id="eventTitle" name="eventTitle" required>
                </div>
                <div class="form-group">
                    <label for="eventDate">Fecha:</label>
                    <input type="date" id="eventDate" name="eventDate" required>
                </div>
                <input type="submit" class="submit-button" value="Agregar Evento">
            </form>
        </div>
    </div>
</div>
</div>
<script>
function openModal(event) {
    event.preventDefault(); // Evita que el botón envíe el formulario
    var modal = document.getElementById("eventModal");
    modal.style.display = "block"; // Muestra el modal
}

function closeModal() {
    var modal = document.getElementById("eventModal");
    modal.style.display = "none"; // Oculta el modal
}

// Cerrar el modal si el usuario hace clic fuera del contenido del modal
window.onclick = function(event) {
    var modal = document.getElementById("eventModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>




