<?php
// Incluir la clase de conexión
require_once '/var/www/database/connection.php';

// Intentar conectar y mostrar el resultado
try {
    $db = Database::connect();
    echo "¡Conexión exitosa a la base de datos!";
} catch (Exception $e) {
    echo "Error de conexión: " . $e->getMessage();
}