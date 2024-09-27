<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "u943374840_AngelyLucia";
$password = "W1IagP3[IQi6j=lflShñ";
$database = "u943374840_PlusTiES";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}


// Verificar si la solicitud es GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Recibir el ID del empleado y otros datos desde la URL (GET)
$id_aspirante = isset($_GET['id_aspirante']) ? $_GET['id_aspirante'] : null;
$primer_nombre = isset($_GET['primer_nombre']) ? $_GET['primer_nombre'] : null;
$segundo_nombre = isset($_GET['segundo_nombre']) ? $_GET['segundo_nombre'] : null;
$primer_apellido = isset($_GET['primer_apellido']) ? $_GET['primer_apellido'] : null;
$segundo_apellido = isset($_GET['segundo_apellido']) ? $_GET['segundo_apellido'] : null;
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_final = isset($_GET['fecha_final']) ? $_GET['fecha_final'] : null;
$departamento = isset($_GET['departamento']) ? $_GET['departamento'] : null;
$Jefe_inmediato = isset($_GET['Jefe_inmediato']) ? $_GET['Jefe_inmediato'] : null;
$correo_electronico = isset($_GET['correo_electronico']) ? filter_var(trim($_GET['correo_electronico']), FILTER_SANITIZE_EMAIL) : null;

// Convertir a formato date
if ($fecha_inicio) {
    $fecha_inicio = (new DateTime($fecha_inicio))->format('Y-m-d');
}

if ($fecha_final) {
    $fecha_final = (new DateTime($fecha_final))->format('Y-m-d');
}

$departamentos = [
    '001.001' => 'Dirección general',
    '002.001' => 'Dirección de tecnología',
    '003.001' => 'Dirección de Producto',
    '004.001' => 'Dirección de Ventas y Marketing',
    '005.001' => 'Dirección de Sporte y Servicios',
];

$nombre_departamento = isset($departamentos[$departamento]) ? $departamentos[$departamento] : 'Departamento no encontrado';

    // Validar el correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        echo "Correo electrónico no válido: " . htmlspecialchars($correo_electronico);
        exit;
    }

    // Preparar el correo de aceptación
    $to = $correo_electronico;
    $subject = "Prácticas Aceptadas";
    $message = '
    <html>
    <head>
      <title>Prácticas Aceptadas</title>
      <style>
        .container {
          font-family: Arial, sans-serif;
          background-color: #ffffff;
          border: 1px solid #dddddd;
          border-radius: 10px;
          max-width: 80%;
          margin: 0 auto;
        }
        .header {
          background-color: #071359;
          color: white;
          padding: 15px;
          text-align: center;
          border-radius: 10px 10px 0 0;
        }
        .body {
          padding: 20px 40px;
          background-color: #ffffff;
          color: black;
        }
        .button {
          background-color: #007bff;
          color: white;
          padding: 10px 20px;
          text-decoration: none;
          border-radius: 5px;
          display: inline-block;
        }
        .footer {
          text-align: center;
          margin-top: 20px;
          font-size: 12px;
          color: #777777;
        }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header" style="display: flex; align-items: center; justify-content: center; position: relative;">
            <img src="https://luciaortizzz.website/img/LogoStarTech.png" alt="Logo de la Empresa" style="height: 35px; width: auto; position: absolute; left: 10px;">
        </div>
        <div class="body">
            <p><strong>Estimado/a, ' . htmlspecialchars($primer_nombre) . ' ' . htmlspecialchars($segundo_nombre) . ' ' . htmlspecialchars($primer_apellido) . ' ' . htmlspecialchars($segundo_apellido) . '</strong></p>
            <p>Nos complace informarle que su solicitud para realizar prácticas en <strong>StarTech</strong> ha sido aceptada. Tras una cuidadosa evaluación de su perfil y habilidades, estamos convencidos de que su incorporación aportará valor a nuestra empresa, al mismo tiempo que le brindará una experiencia de desarrollo profesional significativa.</p>
            <p>A continuación, detallamos información relevante para el inicio de sus prácticas:</p>
            
            <ul>
                <li><strong>Fecha de inicio:</strong> ' . htmlspecialchars($fecha_inicio) . '</li>
                <li><strong>Fecha de finalización:</strong> ' . htmlspecialchars($fecha_final) . '</li>
                <li><strong>Departamento asignado:</strong> ' . htmlspecialchars($nombre_departamento) . '</li>
                <li><strong>Horario de trabajo:</strong> 7:00 AM - 4:00 PM</li>
                <li><strong>Supervisor asignado:</strong> ' . htmlspecialchars($Jefe_inmediato) . '</li>
            </ul>
            
            <p>Le agradeceríamos que nos confirmara la recepción de este mensaje y su disponibilidad para una reunión inicial, en la cual le proporcionaremos más detalles sobre el programa de prácticas y su rol en el equipo.</p>
            
            <p>Por favor, no dude en ponerse en contacto con nosotros si requiere información adicional o tiene alguna pregunta.</p>
            
            <p>Le damos una cordial bienvenida a StarTech y esperamos que esta colaboración sea mutuamente beneficiosa.</p>
            
            <p>Atentamente,</p>
            <p><strong>StarTech</strong></p>
            
          <a href="#" class="button">Contactar Soporte</a>
        </div>
        <div class="footer">
          <p>Este es un correo electrónico automático, por favor no respondas a este mensaje.</p>
        </div>
      </div>
    </body>
    </html>
    ';

    // Encabezados del correo para enviar en formato HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: luciaizabelortizpaz556@gmail.com' . "\r\n";

    // Intentar enviar el correo
    if (mail($to, $subject, $message, $headers)) {
        // Redirigir a Estado_Estudiante.php con el ID y el nuevo estado en la URL
        header('Location: /empleados');
        exit;
    } else {
        echo "Error al enviar el correo.";
    }
} else {
    echo "Método de solicitud no permitido.";
}

// Cerrar la conexión
$conn->close();
?>
