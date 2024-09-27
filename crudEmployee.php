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
$Id_empleado = $_POST['Id_empleado'];
$departamento = $_POST['departamento'];
$area = $_POST['area'];
$Fecha_contratacion = $_POST['Fecha_contratacion'];
$Fecha_cargo = $_POST['Fecha_cargo'];
$Cargo = $_POST['Cargo'];
$Sueldo = $_POST['Sueldo'];
$Descripcion_responsabilidades = $_POST['Descripcion_responsabilidades'];
$Personal_cargo = $_POST['Personal_cargo'];
$Jefe_inmediato = $_POST['Jefe_inmediato'];
$Estado = 'Activo';

$usuario = 'admin'; // Supongamos que este es el usuario que está realizando la operación

// Construir la clave base del código personal
$clave = $departamento . "." . $area;

// Consulta para obtener el último código personal generado para el área específica
$sql = "SELECT Codigo_personal FROM InfoEmpresarial WHERE Codigo_personal LIKE '$clave%' ORDER BY Codigo_personal DESC LIMIT 1";
$result = $conn->query($sql);

// Determinar el próximo número incremental
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ultimo_codigo = $row['Codigo_personal'];
    // Extraer el último número incremental del código personal
    $numero_incremental = intval(substr($ultimo_codigo, -3)) + 1;
} else {
    // Si no hay registros previos, empezar desde 1
    $numero_incremental = 1;
}

// Generar el nuevo código personal
$Codigo_personal = $clave . "." . str_pad($numero_incremental, 3, "0", STR_PAD_LEFT);

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

    // Insertar los datos en la tabla InfoEmpresarial
    $stmt = $conn->prepare("INSERT INTO InfoEmpresarial (Id_empleado, Codigo_personal, Fecha_contratacion, Fecha_cargo, Cargo, Sueldo, Descripcion_responsabilidades, Personal_cargo, Jefe_inmediato, Estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error en la preparación de la consulta SQL para InfoEmpresarial: " . $conn->error);
    }

    $stmt->bind_param("isssssssss", $recordId, $Codigo_personal, $Fecha_contratacion, $Fecha_cargo, $Cargo, $Sueldo, $Descripcion_responsabilidades, $Personal_cargo, $Jefe_inmediato, $Estado);

    if ($stmt->execute()) {
        echo "Registro en InfoEmpresarial exitoso<br>";
        $recordId2 = $stmt->insert_id; // Obtener el ID del nuevo registro

        // Registrar la operación en Log_Json
        $detailsArray = [
            'Id_empleado' => $recordId,
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

    header('Location: /nuevo-empleado');
    exit();
}

$conn->close();
?>