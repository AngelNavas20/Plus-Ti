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

    $stmt = $conn->prepare("INSERT INTO Log_Json (Usuario, Operacion, Nombre_Tabla, Id_Asociado, Detalles, Fecha_de_Registro) VALUES (?, ?, ?, ?, ?, NOW())");
    if ($stmt === false) {
        die("Error en la preparación de la consulta SQL para Log_Json: " . $conn->error);
    }

    $stmt->bind_param("sssss", $usuario, $operation, $tableName, $recordId, $detailsJson);
    
    if (!$stmt->execute()) {
        die("Error al insertar en Log_Json: " . $stmt->error . "<br>");
    }

    $stmt->close();
}


// Obtener los datos de la URL
$id_aspirante = isset($_GET['id_aspirante']) ? $_GET['id_aspirante'] : null;
$nuevo_estado = isset($_GET['nuevo_estado']) ? $_GET['nuevo_estado'] : null;
$usuario = 'admin';

// Verificar que se hayan recibido los parámetros
if ($id_aspirante && $nuevo_estado) {
    $stmt = $conn->prepare("UPDATE Informacion_Practicas SET Estado = ? WHERE ID_Practicante = ?");
    $stmt->bind_param("si", $nuevo_estado, $id_aspirante);

    if ($stmt->execute()) {
        echo "Actualización en InfoEmpresarial exitosa<br>";
        
        // Registrar la operación en Log_Json
        $detailsArray = ['Estado' => $nuevo_estado];
        logCrudOperation($usuario, 'UPDATE', 'InfoEmpresarial', $id_aspirante, $detailsArray, $conn);

        // Redirigir a la página de agradecimiento
        header('Location: /solicitudes-pendientes');
    } else {
        die("Error al actualizar el registro en InfoEmpresarial: " . $stmt->error);
    }

    $stmt->close();
} else {
    die("ID o estado no proporcionado.");
}

$conn->close();
?>