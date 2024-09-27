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

// Obtener y sanitizar los datos del formulario
$id_aspirante = isset($_POST['id_aspirante']) ? intval($_POST['id_aspirante']) : null;
$primer_nombre = isset($_POST['primer_nombre']) ? $_POST['primer_nombre'] : null;
$segundo_nombre = isset($_POST['segundo_nombre']) ? $_POST['segundo_nombre'] : null;
$primer_apellido = isset($_POST['primer_apellido']) ? $_POST['primer_apellido'] : null;
$segundo_apellido = isset($_POST['segundo_apellido']) ? $_POST['segundo_apellido'] : null;
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$fecha_final= isset($_POST['fecha_final']) ? $_POST['fecha_final'] : null;
$correo_electronico = isset($_POST['correo_electronico']) ? $_POST['correo_electronico'] : null;



$departamento = isset($_POST['departamento']) ? $_POST['departamento'] : '';
$area = isset($_POST['area']) ? $_POST['area'] : '';
$Fecha_contratacion = isset($_POST['Fecha_contratacion']) ? $_POST['Fecha_contratacion'] : '';
$Fecha_cargo = isset($_POST['Fecha_cargo']) ? $_POST['Fecha_cargo'] : '';
$Descripcion_responsabilidades = isset($_POST['Descripcion_responsabilidades']) ? $_POST['Descripcion_responsabilidades'] : '';
$Jefe_inmediato = isset($_POST['Jefe_inmediato']) ? $_POST['Jefe_inmediato'] : '';
$nuevo_estado = 'Aceptado'; // El nuevo estado a actualizar
$usuario = 'admin'; // Usuario que realiza la operación
$Estado = 'Activo';

// Verificar que el ID del empleado no sea nulo
if ($id_aspirante === null) {
    die("ID del aspirante no proporcionado.");
}

// Generar código personal basado en el departamento y área
$clave = $departamento . "." . $area;
$sql = "SELECT Codigo_personal FROM InfoEmpresarial WHERE Codigo_personal LIKE '$clave%' ORDER BY Codigo_personal DESC LIMIT 1";
$result = $conn->query($sql);

$numero_incremental = ($result->num_rows > 0) ? intval(substr($result->fetch_assoc()['Codigo_personal'], -3)) + 1 : 1;
$Codigo_personal = $clave . "." . str_pad($numero_incremental, 3, "0", STR_PAD_LEFT);

// Insertar en InfoEmpresarial
if ($_POST['action'] == 'Enviar Datos') {
    $stmt = $conn->prepare("INSERT INTO InfoEmpresarial (Id_empleado, Codigo_personal, Fecha_contratacion, Fecha_cargo, Cargo, Sueldo, Descripcion_responsabilidades, Personal_cargo, Jefe_inmediato, Estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error en la preparación de la consulta SQL para InfoEmpresarial: " . $conn->error);
    }

    $Cargo = 'Practicante';
    $Sueldo = '0';
    $Personal_cargo = '0';
    
    $stmt->bind_param("isssssssss", $id_aspirante, $Codigo_personal, $Fecha_contratacion, $Fecha_cargo, $Cargo, $Sueldo, $Descripcion_responsabilidades, $Personal_cargo, $Jefe_inmediato, $Estado);

    if ($stmt->execute()) {
        echo "Registro en InfoEmpresarial exitoso<br>";
        $recordId2 = $stmt->insert_id;

        // Registrar la operación en Log_Json
        $detailsArray = [
            'Id_empleado' => $id_aspirante,
            'Codigo_personal' => $Codigo_personal,
            'Fecha_contratacion' => $Fecha_contratacion,
            'Fecha_cargo' => $Fecha_cargo,
            'Cargo' => $Cargo,
            'Sueldo' => $Sueldo,
            'Descripcion_responsabilidades' => $Descripcion_responsabilidades,
            'Personal_cargo' => $Personal_cargo,
            'Jefe_inmediato' => $Jefe_inmediato,
            'Estado' => $Estado
        ];
        logCrudOperation($usuario, 'INSERT', 'InfoEmpresarial', $recordId2, $detailsArray, $conn);
    } else {
        die("Error al crear el registro en InfoEmpresarial: " . $stmt->error);
    }

    $stmt->close();
}

// Actualizar el estado en Informacion_Practicas
$stmt = $conn->prepare("UPDATE Informacion_Practicas SET Estado = ? WHERE ID_Practicante = ?");
if ($stmt === false) {
    die("Error en la preparación de la consulta SQL para Informacion_Practicas: " . $conn->error);
}

$stmt->bind_param("si", $nuevo_estado, $id_aspirante);

if ($stmt->execute()) {
    echo "Actualización en Informacion_Practicas exitosa<br>";

    // Registrar la operación en Log_Json
    $detailsArray = [
        'Estado' => $nuevo_estado
    ];
    logCrudOperation($usuario, 'UPDATE', 'Informacion_Practicas', $id_aspirante, $detailsArray, $conn);
    
} else {
    die("Error al actualizar el registro en Informacion_Practicas: " . $stmt->error);
}

$stmt->close();
$conn->close();

header('Location: Correo_Aceptado.php?id_aspirante=' . $id_aspirante . '&primer_nombre=' . $primer_nombre . '&segundo_nombre=' . $segundo_nombre . '&primer_apellido=' . $primer_apellido . '&segundo_apellido=' . $segundo_apellido . '&fecha_inicio=' . $fecha_inicio . '&fecha_final=' . $fecha_final . '&departamento=' . $departamento . '&Jefe_inmediato=' . $Jefe_inmediato . '&correo_electronico=' . $correo_electronico );
exit();
?>
