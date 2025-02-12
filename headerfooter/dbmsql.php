<?php
// Configuración de la base de datos
$host = "localhost:3306"; // Usar 'localhost' o el nombre del servicio en Docker Compose
$usuario = "root"; // Cambiar según tus credenciales
$password = "password"; // Cambiar según tus credenciales
$baseDeDatos = "idm";
 // Variable para el filtro
 //$mail = 'pedro.diaz@gmail.com'; // Cambia este valor según lo que desees buscar

try {
    // Usar PDO para conectar a MySQL
    $dsn = "mysql:host=$host;dbname=$baseDeDatos;charset=utf8mb4";
    $conn = new PDO($dsn, $usuario, $password);

    // Configurar el modo de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 /*  

    // Consulta con WHERE usando una variable
    $sql = "SELECT * FROM user WHERE email = :mail";
    $stmt = $conn->prepare($sql);

    // Asignar el valor de la variable al parámetro
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    // Mostrar los datos en una tabla HTML
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Tipo</th></tr>";

    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $fila['id'] . "</td>";
        echo "<td>" . $fila['username'] . "</td>";
        echo "<td>" . $fila['tipo'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";*/
} catch (PDOException $e) {
    echo "Error de conexión o consulta: " . $e->getMessage();
}

// Cerrar la conexión (opcional)
//$conn = null;
?>