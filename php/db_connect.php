<?php

use MongoDB\Client;

require("config.php");


// Conexión a MongoDB
try {
    $mongoClient = new Client("mongodb://" . MONGO_HOST . ":" . MONGO_PORT);
    $mongoDB = $mongoClient->selectDatabase(MONGO_DB);

} catch (Exception $e) {
    die("Error al conectar con MongoDB: " . $e->getMessage());
}


?>