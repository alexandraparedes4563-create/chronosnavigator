<?php
// ¡ATENCIÓN! CREDENCIALES ACTUALIZADAS PARA INFINITYFREE (sql111.infinityfree.com)

// Parámetros de conexión reales
define('DB_SERVER', 'sql111.infinityfree.com'); 
define('DB_USERNAME', 'if0_40692499');        
define('DB_PASSWORD', 'hX1NufqSkgI749'); // <--- PEGA TU CONTRASEÑA REAL AQUÍ
define('DB_NAME', 'if0_40692499_navegador_de_cronos'); 

// Intentar la conexión a MySQL usando la extensión MySQLi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a UTF8
$conn->set_charset("utf8");

// La variable $conn contiene el objeto de conexión que usaremos para todas las consultas.
?>