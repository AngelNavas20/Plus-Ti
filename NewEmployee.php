<?php
    session_start();

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['user_id'])) {
        // Redirigir al login si no está autenticado
        header('Location: /login');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Nuevo empleado</title>
    <style>
        body {
            background-color: #f0f4f7;
            font-family: Arial, sans-serif;
        }
        .container-stepbar {
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
            background-color: #2D6DA6;
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
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .hidden {
            display: none;
        }

        .section-form {
            margin-left: 30px;
            margin-right: 30px;
        }

        .btn-pricipal {
            background-color: #071359;
            color: white;
        }
        
        .btn-pricipal:hover {
            background-color: #163573;
            color: white;
        }
          
        .btn-next {
            background-color: #2D6DA6;
            color: white;
        }
        
        .btn-next:hover {
            background-color: #4AB0D9;
            color: white;
        }
        .border-separation {
            position: relative;
            padding-bottom: 1rem; /* Espacio para la línea */
            padding-top: 1rem; /* Espacio para la línea */
        }
        
        .border-separation::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: 50%; /* A mitad de la columna */
            width: 100%;
            border-bottom: 1px solid #ccc; /* Color y estilo de la línea */
        }
        .navprincipal{
            background-color: #071359;
        }
        .dropdown-menu {
            width: 100%; /* Se ajusta al ancho del elemento padre */
            min-width: unset; /* Elimina el ancho mínimo predeterminado de Bootstrap */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    <br>
    <p style="font-size: 20px; color: #071359; margin-bottom: 15px; margin-left:65px" class="text-left"><strong>Nuevo empleado</strong></p>
    <div class="container-stepbar">
        <div class="position-relative m-4">
            <div class="progress" style="height: 3px;" hidden>
                <div id="progress-top-bar2" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="progress" style="height: 3px;" >
                <div id="progress-top-bar" class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">1</button>
            <button type="button" class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">2</button>
            <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">3</button>
        </div>
    </div>
    <!-- Formularios combinados -->
    <div class="container mt-4">
        <form id="multi-step-form" action="crudEmployee.php" method="post">
            <!-- Paso 1: Datos Personales -->
            <div id="step-1" class="form-section">
            <h5><strong>Información personal del empleado</strong></h5>
            <div class="row form-row">
            <!-- Primera Fila: Nombres y Apellidos -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="primer_nombre">Primer Nombre: <span style="color:red;">*</span></label>
                    <input type="text" name="primer_nombre" id="primer_nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="segundo_nombre">Segundo Nombre:</label>
                    <input type="text" name="segundo_nombre" id="segundo_nombre" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="tercer_nombre">Tercer Nombre:</label>
                    <input type="text" name="tercer_nombre" id="tercer_nombre" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="primer_apellido">Primer Apellido: <span style="color:red;">*</span></label>
                    <input type="text" name="primer_apellido" id="primer_apellido" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="segundo_apellido">Segundo Apellido:</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="apellido_de_casada">Apellido de Casada:</label>
                    <input type="text" name="apellido_de_casada" id="apellido_de_casada" class="form-control">
                </div>
            </div>
        </div>
        <hr>
        <div class="row form-row mt-3">
            <!-- Segunda Fila: Nacimiento y Dirección -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="fecha_de_nac">Fecha de Nacimiento: <span style="color:red;">*</span></label>
                    <input type="date" name="fecha_de_nac" id="fecha_de_nac" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="lugar_de_nac">Lugar de Nacimiento:</label>
                    <input type="text" name="lugar_de_nac" id="lugar_de_nac" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="direccion_domicilio">Dirección de Domicilio: <span style="color:red;">*</span></label>
                    <input type="text" name="direccion_domicilio" id="direccion_domicilio" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="numero_celular">Número de Celular: <span style="color:red;">*</span></label>
                    <input type="text" name="numero_celular" id="numero_celular" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="numero_casa">Número de Casa:</label>
                    <input type="text" name="numero_casa" id="numero_casa" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="correo_electronico">Correo Electrónico: <span style="color:red;">*</span></label>
                    <input type="email" name="correo_electronico" id="correo_electronico" class="form-control" required>
                </div>
            </div>
        </div>
        </div>
            <!-- Paso 2: Datos Empresariales -->
            <div id="step-2" class="form-section hidden">
                <h5><strong>Información relativa a la empresa</strong></h5>
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="departamento">Departamento:</label>
                            <select id="departamento" name="departamento" class="form-control" required onchange="actualizarAreas()">
                                <option value="001.001">Dirección General</option>
                                <option value="002.001">Dirección de Tecnología</option>
                                <option value="003.001">Dirección de Producto</option>
                                <option value="004.001">Dirección de Ventas y Marketing</option>
                                <option value="005.001">Dirección de Soporte y Servicios</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="area">Área Específica: <span style="color:red;">*</span></label>
                            <select id="area" name="area" class="form-control" required>
                                <!-- Las opciones se actualizarán dinámicamente -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Fecha_contratacion">Fecha de contratación: <span style="color:red;">*</span></label>
                            <input type="date" name="Fecha_contratacion" id="Fecha_contratacion" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="Fecha_cargo">Fecha de inicio en el cargo: <span style="color:red;">*</span></label>
                            <input type="date" name="Fecha_cargo" id="Fecha_cargo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="Cargo">Cargo: <span style="color:red;">*</span></label>
                            <input type="text" name="Cargo" id="Cargo" class="form-control" required>
                        </div>
                    </div>
                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="Sueldo">Sueldo: <span style="color:red;">*</span></label>
                            <input type="text" name="Sueldo" id="Sueldo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="Descripcion_responsabilidades">Responsabilidades: <span style="color:red;">*</span></label>
                            <textarea name="Descripcion_responsabilidades" id="Descripcion_responsabilidades" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="Personal_cargo">Personal a cargo:</label>
                            <input type="number" name="Personal_cargo" id="Personal_cargo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="Jefe_inmediato">Jefe inmediato:</label>
                            <input type="text" name="Jefe_inmediato" id="Jefe_inmediato" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Botones de Navegación -->
            <div class="d-flex justify-content-between">
                <button type="button" id="prev-step" class="btn btn-primary hidden">Anterior</button>
                <button type="button" id="next-step" class="btn btn-next">Siguiente</button>
                <!-- Modificación en el botón de Enviar -->
                <button type="submit" id="submit-form" class="btn btn-pricipal hidden">Enviar</button>
            </div>
            <input type="hidden" name="action" value="Enviar Datos">
        </form>
    </div>
    <br>
    <br>

    <script>
    // Asignar función al formulario para confirmar envío
    document.getElementById('multi-step-form').onsubmit = function() {
        return confirmarEnvio();
    };

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

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('multi-step-form');

        // Añadir el evento 'submit' al formulario
        form.addEventListener('submit', function(event) {
            // Si el usuario no confirma, se cancela el envío del formulario
            if (!confirm('¿Está seguro de que desea enviar el formulario?')) {
                event.preventDefault(); // Cancela el envío del formulario
            }
        });

        let currentStep = 1;
        const totalSteps = 2;

        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        const prevStepBtn = document.getElementById('prev-step');
        const nextStepBtn = document.getElementById('next-step');
        const submitBtn = document.getElementById('submit-form');
        const progress = document.getElementById('progress-top-bar');
        const progress2 = document.getElementById('progress-top-bar2');
        const emailField = document.getElementById('correo_electronico'); // Campo de email

        function showStep(step) {
            if (step === 1) {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
                prevStepBtn.classList.add('hidden');
                nextStepBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
                progress2.classList.remove('hidden');
                progress.classList.add('hidden');
            } else if (step === 2) {
                progress.classList.remove('hidden');
                progress2.classList.add('hidden');
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
                prevStepBtn.classList.remove('hidden');
                nextStepBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            }
        }

        function validateStep1() {
            const requiredFields = step1.querySelectorAll('input[required]');
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    field.focus();
                    alert('Por favor, completa todos los campos obligatorios.');
                    return false;
                }
            }
            return true;
        }

        // Validar dominio de correo mediante AJAX
        function validateEmailDomain(email) {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'validate-email.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.valid) {
                                resolve(true);
                            } else {
                                reject('Dominio del correo no válido.');
                            }
                        } else {
                            reject('Error en la validación del correo.');
                        }
                    }
                };
                xhr.onerror = function() {
                    reject('Error de red. Verifica tu conexión.');
                };
                xhr.send(`email=${encodeURIComponent(email)}`);
            });
        }

        nextStepBtn.addEventListener('click', function () {
            if (currentStep === 1 && validateStep1()) {
                nextStepBtn.disabled = true; // Deshabilitar botón mientras valida

                // Validar dominio del correo antes de avanzar al siguiente paso
                validateEmailDomain(emailField.value)
                    .then(() => {
                        currentStep++;
                        showStep(currentStep);
                    })
                    .catch(error => {
                        alert(error);
                        emailField.focus();
                    })
                    .finally(() => {
                        nextStepBtn.disabled = false; // Habilitar botón nuevamente
                    });
            }
        });

        prevStepBtn.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        showStep(currentStep);
    });

    // Manejo de menú móvil
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

    // Manejo de dropdown
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