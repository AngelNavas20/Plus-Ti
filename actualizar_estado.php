<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "u943374840_AngelyLucia";
$password = "W1IagP3[IQi6j=lflShñ";
$database = "u943374840_PlusTiES";

// Crear conexión.php
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


// Obtener los datos enviados por AJAX
$id_empleado = $_POST['id_empleado'];
$nuevo_estado = $_POST['nuevo_estado'];
$usuario = 'admin';

// Actualizar el estado en la tabla InfoEmpresarial
$stmt = $conn->prepare("UPDATE InfoEmpresarial SET Estado = ? WHERE Id_empleado = ?");
$stmt->bind_param("si", $nuevo_estado, $id_empleado);

if ($stmt->execute()) {
    echo "Actualización en InfoEmpresarial exitosa<br>";

    // Registrar la operación en Log_Json
    $detailsArray = [
        'Estado' => $nuevo_estado
    ];
    logCrudOperation($usuario, 'UPDATE', 'InfoEmpresarial', $id_empleado, $detailsArray, $conn);
    
    // Redirigir a la página de agradecimiento
    header('Location: /empleados');
} else {
    die("Error al actualizar el registro en InfoEmpresarial: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>