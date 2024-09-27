<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Función para registrar una operación CRUD en la tabla Log_Json
function logCrudOperation($usuario, $operation, $tableName, $recordId, $detailsArray, $conn) {
    $detailsJson = json_encode($detailsArray);
    if ($detailsJson === false) {
        die("Error al convertir los detalles a JSON: " . json_last_error_msg());
    }

    date_default_timezone_set('America/Guatemala');
    $fechaActual = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("INSERT INTO Log_Json (Usuario, Operacion, Nombre_Tabla, Id_Asociado, Detalles, Fecha_de_Registro) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $usuario, $operation, $tableName, $recordId, $detailsJson, $fechaActual);

    
    if (!$stmt->execute()) {
        die("Error al insertar en Log_Json: " . $stmt->error . "<br>");
    }

    $stmt->close();
}

// Obtener los datos del primer paso del formulario
$primer_nombre = $_POST['primer_nombre'];
$segundo_nombre = $_POST['segundo_nombre'];
$tercer_nombre = $_POST['tercer_nombre'];
$primer_apellido = $_POST['primer_apellido'];
$segundo_apellido = $_POST['segundo_apellido'];
$apellido_de_casada = $_POST['apellido_de_casada'];
$fecha_de_nac = $_POST['fecha_de_nac'];
$lugar_de_nac = $_POST['lugar_de_nac'];
$direccion_domicilio = $_POST['direccion_domicilio'];
$numero_celular = $_POST['numero_celular'];
$numero_casa = $_POST['numero_casa'];
$correo_electronico = $_POST['correo_electronico'];

// Obtener los datos del segundo paso del formulario
$colegio = $_POST['colegio'];
$grado = $_POST['grado'];
$carrera = $_POST['carrera'];
$habilidades = $_POST['habilidades'];
$numero_de_horas = $_POST['numero_de_horas'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_finalizacion = $_POST['fecha_finalizacion'];
$referido = $_POST['referido'];
$estado = 'Pendiente';

$usuario = 'admin'; // Supongamos que este es el usuario que está realizando la operación

if ($_POST['action'] == 'Enviar Datos') {
    // Insertar los datos en la tabla Dtos_Personales
    $stmt = $conn->prepare("INSERT INTO Dtos_Personales (Primer_Nombre, Segundo_Nombre, Tercer_Nombre, Primer_Apellido, Segundo_Apellido, Apellido_de_Casada, Fecha_de_Nac, Lugar_de_Nac, Direccion_De_Domicilio, Numero_de_Celular, Numero_de_Casa, Correo_Electronico) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error en la preparación de la consulta SQL para Dtos_Personales: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssss", $primer_nombre, $segundo_nombre, $tercer_nombre, $primer_apellido, $segundo_apellido, $apellido_de_casada, $fecha_de_nac, $lugar_de_nac, $direccion_domicilio, $numero_celular, $numero_casa, $correo_electronico);
    
    if ($stmt->execute()) {
        echo "Registro en Dtos_Personales exitoso<br>";
        $recordId = $stmt->insert_id; // Obtener el ID del nuevo registro

        // Registrar la operación en Log_Json
        $detailsArray = [
            'Primer_Nombre' => $primer_nombre,
            'Segundo_Nombre' => $segundo_nombre,
            'Tercer_Nombre' => $tercer_nombre,
            'Primer_Apellido' => $primer_apellido,
            'Segundo_Apellido' => $segundo_apellido,
            'Apellido_de_Casada' => $apellido_de_casada,
            'Fecha_de_Nac' => $fecha_de_nac,
            'Lugar_de_Nac' => $lugar_de_nac,
            'Dirección_De_Domicilio' => $direccion_domicilio,
            'Número_de_Celular' => $numero_celular,
            'Número_de_Casa' => $numero_casa,
            'Correo_Electronico' => $correo_electronico
        ];
        logCrudOperation($usuario, 'INSERT', 'Dtos_Personales', $recordId, $detailsArray, $conn);
    } else {
        die("Error al crear el registro en Dtos_Personales: " . $stmt->error);
    }
    
    $stmt->close();

    date_default_timezone_set('America/Guatemala');
    $fechaActual2 = date('Y-m-d');

    // Insertar los datos en la tabla Informacion_Practicas
    $stmt = $conn->prepare("INSERT INTO Informacion_Practicas (ID_Practicante, Institucion_Colegio, Grado, Carrera, Habilidades, Numero_de_Horas, Fecha_De_inicio, Fecha_De_Finalización, Referido, Estado, Fecha_De_Solicitud) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error en la preparación de la consulta SQL para Informacion_Practicas: " . $conn->error);
    }

    $stmt->bind_param("issssssssss", $recordId, $colegio, $grado, $carrera, $habilidades, $numero_de_horas, $fecha_inicio, $fecha_finalizacion, $referido, $estado, $fechaActual2);

    if ($stmt->execute()) {
        echo "Registro en Informacion_Practicas exitoso<br>";
        $recordId2 = $stmt->insert_id; // Obtener el ID del nuevo registro

        // Registrar la operación en Log_Json
        $detailsArray = [
            'ID_Practicante' => $recordId,
            'Institucion_Colegio' => $colegio,
            'Grado' => $grado,
            'Carrera' => $carrera,
            'Habilidades' => $habilidades,
            'Numero_de_Horas' => $numero_de_horas,
            'Fecha_De_inicio' => $fecha_inicio,
            'Fecha_De_Finalización' => $fecha_finalizacion,
            'Referido' => $referido,
            'Estado' => $estado
        ];
        logCrudOperation($usuario, 'INSERT', 'Informacion_Practicas', $recordId2, $detailsArray, $conn);
    } else {
        die("Error al crear el registro en Informacion_Practicas: " . $stmt->error);
    }
    
    $stmt->close();
    
    // Redirigir a la página de agradecimiento
    header('Location: /gracias');
    exit();
}

$conn->close();
?>


