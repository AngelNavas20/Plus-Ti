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

    // Recibir el ID del empleado desde la URL
    $id_aspirante = isset($_POST['id_aspirante']) ? $_POST['id_aspirante'] : null;
    $nuevo_estado = "Denegado"; // Asignar el estado de denegado

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar los datos del formulario
    $motivo = trim($_POST['motivo']);
    $correo_electronico = filter_var(trim($_POST['correo_electronico']), FILTER_SANITIZE_EMAIL);
    $primer_nombre = filter_var(trim($_POST['primer_nombre']), FILTER_SANITIZE_EMAIL);
    $segundo_nombre = filter_var(trim($_POST['segundo_nombre']), FILTER_SANITIZE_EMAIL);
    $primer_apellido = filter_var(trim($_POST['primer_apellido']), FILTER_SANITIZE_EMAIL);
    $segundo_apellido = filter_var(trim($_POST['segundo_apellido']), FILTER_SANITIZE_EMAIL);

    // Validar el correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        echo "Correo electrónico no válido: " . htmlspecialchars($correo_electronico);
        exit;
    }

    // Validar que el motivo no esté vacío
    if (empty($motivo)) {
        echo "El motivo de la denegación no puede estar vacío.";
        exit;
    }

    // Preparar el correo
    $to = $correo_electronico;
    $subject = "Motivo de la Denegación";
    $message = '
    <html>
    <head>
      <title>Solicitud de Prácticas</title>
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
            <p>Lamentablemente, después de una cuidadosa evaluación de su perfil y habilidades, debemos informarle que su solicitud para realizar prácticas en <strong>StarTech</strong> no ha sido aceptada en esta ocasión.</p>
            <p>El motivo de la denegación es el siguiente:</p>
            <p><strong>' . htmlspecialchars($motivo) . '</strong></p>
            <p>Agradecemos el tiempo y el interés que ha demostrado en nuestra empresa. Aunque no hemos podido avanzar con su solicitud, le animamos a que siga aplicando a futuras oportunidades que se ajusten a su perfil.</p>
            <p>Si tiene alguna pregunta o desea recibir retroalimentación sobre su solicitud, no dude en ponerse en contacto con nosotros.</p>
            
            <p>Le deseamos mucho éxito en sus futuros proyectos y agradecemos nuevamente su interés en <strong>StarTech</strong>.</p>
            
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
        header('Location: Estado_Estudiante.php?id_aspirante=' . $id_aspirante . '&nuevo_estado=' . $nuevo_estado);
        exit;
    }
 else {
        echo "Error al enviar el correo.";
    }
} else {
    echo "Método de solicitud no permitido.";
}

// Cerrar la conexión
$conn->close();
?>
