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
    } else {
    }

    $stmt->close();
}

$usuario = 'admin'; // Supongamos que este es el usuario que está realizando la operación

// Registrar la operación en Log_Json al Descargar
logCrudOperation($usuario, 'Descargar Json', 'Log_Json', null, null, $conn);


// Obtener la fecha del formulario
$fecha_deseada = $_POST['fecha'];  // Debe estar en formato YYYY-MM-DD

// Validar la fecha
if (empty($fecha_deseada)) {
    die("Debe seleccionar una fecha.");
}

$detailsArray = [
            'fecha_de_json_al_descargar' => $fecha_deseada
        ];
// Registrar la operación en Log_Json al Descargar
logCrudOperation($usuario, 'Descargar Json', 'Log_Json', null, $detailsArray, $conn);

// Consultar los datos de la tabla Log_Json según la fecha
$query = "SELECT * FROM Log_Json WHERE DATE(Fecha_de_Registro) = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $fecha_deseada);
$stmt->execute();
$result = $stmt->get_result();

// Crear un array principal para la estructura JSON
$json_output = array(
    "file" => "json_formato.json",
    "fecha_creacion" => date("d/m/Y H:i:s"),  // Formato de fecha con hora
    "data" => array()
);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Decodificar el campo Detalles como JSON
        $row['Detalles'] = json_decode($row['Detalles'], true);

        // Añadir cada registro a la estructura de "data"
        $json_output['data'][] = array(
            "ID" => $row['ID'],
            "Usuario" => $row['Usuario'],
            "Operacion" => $row['Operacion'],
            "Nombre_Tabla" => $row['Nombre_Tabla'],
            "Id_Asociado" => $row['Id_Asociado'],
            "Detalles" => $row['Detalles'],
            "Fecha_de_Registro" => $row['Fecha_de_Registro']
        );
    }

    // Convertir los datos a JSON
    $json_data = json_encode($json_output, JSON_PRETTY_PRINT);

    // Generar el checksum MD5 del JSON
    $md5_checksum = md5($json_data);

    // Establecer los encabezados para la descarga de archivos zip
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="log_json_data.zip"');

    // Crear un archivo temporal para el JSON
    $temp_file_data = tempnam(sys_get_temp_dir(), 'json_data');
    file_put_contents($temp_file_data, $json_data);

    // Crear un archivo temporal para el checksum
    $temp_file_checksum = tempnam(sys_get_temp_dir(), 'checksum');
    file_put_contents($temp_file_checksum, $md5_checksum);

    // Crear el archivo zip con ambos archivos
    $zip = new ZipArchive();
    $zip_filename = tempnam(sys_get_temp_dir(), 'zip');
    $zip->open($zip_filename, ZipArchive::CREATE);
    $zip->addFile($temp_file_data, 'log_json_data.json');
    $zip->addFile($temp_file_checksum, 'checksum.md5');
    $zip->close();

    // Leer el contenido del archivo zip y enviarlo al cliente
    readfile($zip_filename);

    // Eliminar los archivos temporales
    unlink($temp_file_data);
    unlink($temp_file_checksum);
    unlink($zip_filename);
} else {
    echo "No hay datos en la tabla Log_Json para la fecha seleccionada.";
}

$conn->close();
?>
