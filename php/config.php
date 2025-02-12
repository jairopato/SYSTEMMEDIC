<?php
// Configuración de Keyrock
define('KEYROCK_URL', 'http://localhost:3005'); // URL de Keyrock
define('KEYROCK_CLIENT_ID', 'f1c841b4-31e0-40c6-96df-ebae18b82c35');
define('KEYROCK_CLIENT_SECRET', 'fd5754f9-e20d-478f-a891-d814584d407e');

// Configuración de Orion Context Broker
define('ORION_URL', 'http://localhost:1027'); // URL de Orion a través de Wilma

// Configuración de MongoDB
define('MONGO_HOST', 'localhost'); // Nombre del contenedor MongoDB
define('MONGO_PORT', 27017);
define('MONGO_DB', 'orion');

// Configuración de MySQL
define('MYSQL_HOST', 'localhost'); // Nombre del contenedor MySQL
define('MYSQL_USER', 'root');
define('MYSQL_PASSWORD', 'password');
define('MYSQL_DB', 'idm');

// JWT Token para Wilma PEP Proxy
define('JWT_SECRET', 'f6f8e79afb190580');
?>