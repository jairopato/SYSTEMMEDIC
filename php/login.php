<?php
// Configuración
require("../headerfooter/dbmsql.php");

$keyrock_url = "http://localhost:3005"; // URL de Keyrock
$client_id = "f1c841b4-31e0-40c6-96df-ebae18b82c35"; // Reemplaza con tu client_id registrado en Keyrock
$client_secret = "fd5754f9-e20d-478f-a891-d814584d407e"; // Reemplaza con tu client_secret registrado en Keyrock

// Capturar datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];


try {
    // 1. Solicitar token a oauth2/token
    $ch = curl_init("$keyrock_url/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'password', // Tipo de flujo OAuth2
        'username' => $email,
        'password' => $password,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception('Error de conexión: ' . curl_error($ch));
    }

    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status_code !== 200) {
        throw new Exception("Credenciales inválidas o error en Keyrock. Código de estado: $status_code");
    }

    $response_data = json_decode($response, true);
    $token = $response_data['access_token']; // Acceso al token

    // Guardar el token en sesión
    session_start();
    $_SESSION['access_token'] = $token;
    $_SESSION['mail'] = $email;
    //----------------------------------------------------------------
    // Consulta con WHERE usando una variable, dnde se le asigna de forma manual y ver quien entra al SC
    $sql = "SELECT * FROM user WHERE email = :email";
    $stmt = $conn->prepare($sql);
    // Asignar el valor de la variable al parámetro
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $menuuser = $fila['tipo'] ;
    }

    if($menuuser==4){
        header("Location: ../pagpaciente.php");
    }else{

       header("Location: ../pagadmin.php");
    }
    //----------------------------------------------------------------
    exit;
} catch (Exception $e) {
   echo "Error: " . $e->getMessage();
}
?>