<?php
require("config.php");

// Conexión a MySQL
try {
    $mysqli = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    if ($mysqli->connect_error) {
        throw new Exception("Error al conectar con MySQL: " . $mysqli->connect_error);
    }
} catch (Exception $e) {
    die($e->getMessage());
}

?>