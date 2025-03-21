<?php
require_once 'bd.php';

$database = new Database();

try {
    $conn = $database->connect();
    
    if ($conn instanceof mysqli) {
        echo "✅ Conexión exitosa a la base de datos<br>";
        echo "Información del servidor: " . $conn->server_info . "<br>";
        echo "Versión del protocolo: " . $conn->protocol_version;
    } else {
        throw new Exception("No se pudo establecer la conexión");
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
} finally {
    if (isset($database)) {
        $database->close();
    }
}
?>

