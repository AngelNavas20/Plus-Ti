<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "u943374840_AngelyLucia";
$password = "W1IagP3[IQi6j=lflShñ";
$database = "u943374840_PlusTiES";

// Crear conexión con mysqli
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si los datos han sido enviados desde el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $titulo = isset($_POST['eventTitle']) ? trim($_POST['eventTitle']) : null;
    $fecha = isset($_POST['eventDate']) ? trim($_POST['eventDate']) : null;

    // Validar que los campos no estén vacíos
    if (!empty($titulo) && !empty($fecha)) {
        // Preparar la consulta SQL usando mysqli
        $fechaCompleta = $fecha . ' 00:00:00';
        $sql = "INSERT INTO eventos (titulo, fecha) VALUES (?, ?)";

        // Preparar la sentencia
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Enlazar parámetros (s = string, se usa para cada variable)
            $stmt->bind_param("ss", $titulo, $fecha);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Redirigir o mostrar un mensaje de éxito
                echo "El evento ha sido agregado exitosamente.";
                header('Location: /novedades-calendario');
                exit;
            } else {
                echo "Error al ejecutar la consulta: " . $stmt->error;
            }

            // Cerrar la sentencia
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}

// Cerrar la conexión
$conn->close();
?>
