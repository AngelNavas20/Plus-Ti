<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Formulario de Múltiples Pasos</title>
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

    </style>
</head>
<body>
    <nav class="navbar" style="background-color: #071359">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">
                <img src="./img/LogoStarTech.png" alt="Logo" width="139" height="35" class="d-inline-block align-text-top">
            </a>
            <a class="btn btn-pricipal" href="" role="button">Conócenos</a>
        </div>
    </nav>
    <br>
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
        <form id="multi-step-form" action="crud.php" method="post">
            <!-- Paso 1: Datos Personales -->
            <div id="step-1" class="form-section">
            <h5><strong>Información personal del solicitante</strong></h5>
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
                    <label for="direccion_domicilio">Dirección de Domicilio:</label>
                    <input type="text" name="direccion_domicilio" id="direccion_domicilio" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="numero_celular">Número de Celular:</label>
                    <input type="number" name="numero_celular" id="numero_celular" class="form-control" max="99999999">
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



            <!-- Paso 2: Datos Escolares -->
            <div id="step-2" class="form-section hidden">
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="colegio">Colegio o Institución: <span style="color:red;">*</span></label>
                            <input type="text" name="colegio" id="colegio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="grado">Grado: <span style="color:red;">*</span></label>
                            <input type="text" name="grado" id="grado" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="carrera">Carrera: <span style="color:red;">*</span></label>
                            <input type="text" name="carrera" id="carrera" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="habilidades">Habilidades: <span style="color:red;">*</span></label>
                            <textarea name="habilidades" id="habilidades" class="form-control" required></textarea>
                        </div>
                    </div>
                    
                    <!-- Columna Derecha -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="numero_de_horas">Número de Horas: <span style="color:red;">*</span></label>
                    <input type="number" name="numero_de_horas" id="numero_de_horas" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_inicio">Fecha de Inicio: <span style="color:red;">*</span></label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_finalizacion">Fecha de Finalización: <span style="color:red;">*</span></label>
                    <input type="date" name="fecha_finalizacion" id="fecha_finalizacion" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="referido">Referido: <span style="color:red;">*</span></label>
                    <input type="text" name="referido" id="referido" class="form-control" required>
                </div>
            </div>
        </div>
    </div>
            <!-- Botones de Navegación -->
            <div class="d-flex justify-content-between">
                <button type="button" id="prev-step" class="btn btn-primary hidden">Anterior</button>
                <button type="button" id="next-step" class="btn btn-next">Siguiente</button>
                <button type="submit" id="submit-form" class="btn btn-pricipal hidden">Enviar</button>
            </div>
            <input type="hidden" name="action" value="Enviar Datos">
        </form>
    </div>
    <br>
    <br>

     <script>
document.addEventListener('DOMContentLoaded', function () {
    const allowedPattern = /^[A-Za-z\s]*$/; // Solo permite letras y espacios
    const allowedNumericPattern = /^[0-9]*$/; // Solo permite números

    // Validar campos del paso 1 y 2 (Solo letras)
    const inputsStep1And2 = document.querySelectorAll('#step-1 input[type="text"], #step-2 input[type="text"]');
    inputsStep1And2.forEach(input => {
        input.addEventListener('input', function () {
            if (!allowedPattern.test(this.value)) {
                this.value = this.value.replace(/[^A-Za-z\s]/g, ''); // Remueve caracteres no permitidos
            }
        });
    });

    // Validar campos numéricos en ambos pasos
    const numericFields = ['numero_celular', 'numero_casa', 'numero_de_horas']; // IDs de campos que deberían ser numéricos
    numericFields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function () {
                if (!allowedNumericPattern.test(this.value)) {
                    this.value = this.value.replace(/[^0-9]/g, ''); // Remueve caracteres no numéricos
                }
            });
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
    const emailField = document.getElementById('correo_electronico');

    function showStep(step) {
        if (step === 1) {
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
            prevStepBtn.classList.add('hidden');
            nextStepBtn.classList.remove('hidden');
            submitBtn.classList.add('hidden');
            progress.style.width = '50%';
        } else if (step === 2) {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            prevStepBtn.classList.remove('hidden');
            nextStepBtn.classList.add('hidden');
            submitBtn.classList.remove('hidden');
            progress.style.width = '100%';
        }
    }

    // Validación del paso 1
    function validateStep1() {
        const requiredFields = step1.querySelectorAll('input[required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                alert('Por favor, completa todos los campos obligatorios.');
                return false;
            }
        }
        
        // Validar el formato de correo electrónico
        if (!validateEmailPattern(emailField)) {
            emailField.focus();
            alert('Por favor, ingresa un correo electrónico válido.');
            return false;
        }

        return true;
    }

    // Validación del paso 2
    function validateStep2() {
        const requiredFields = step2.querySelectorAll('input[required], textarea[required]');
        for (let field of requiredFields) {
            if (!field.value.trim()) {
                field.focus();
                alert('Por favor, completa todos los campos obligatorios en este paso.');
                return false;
            }
        }
        
        // Validación de campos numéricos
        const numericFieldsStep2 = ['numero_de_horas']; // Añadir más campos numéricos si los tienes
        for (let id of numericFieldsStep2) {
            const field = document.getElementById(id);
            if (!allowedNumericPattern.test(field.value)) {
                field.focus();
                alert('Por favor, ingresa solo números en los campos numéricos.');
                return false;
            }
        }

        return true;
    }

    // Validación de correo en tiempo real
    function validateEmailPattern(emailField) {
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailPattern.test(emailField.value);
    }

    emailField.addEventListener('input', function () {
        validateEmailPattern(emailField);
    });

    nextStepBtn.addEventListener('click', function () {
        if (currentStep === 1 && validateStep1()) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevStepBtn.addEventListener('click', function () {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Validar y enviar formulario en el paso 2
    submitBtn.addEventListener('click', function (e) {
        if (currentStep === 2 && validateStep2()) {
            alert('Formulario enviado exitosamente');
        } else {
            e.preventDefault(); // Evitar que el formulario se envíe si no pasa la validación
        }
    });

    showStep(currentStep);
});


    </script>
</body>
</html>