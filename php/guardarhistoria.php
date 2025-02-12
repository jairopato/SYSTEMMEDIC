<?php
require("../vendor/autoload.php"); // Asegúrate de tener instalado el paquete MongoDB con Composer
require("SifradoAES3.php");
use MongoDB\Client;

try {
    // Conexión al servidor MongoDB
    $client = new Client("mongodb://localhost:27017");

    // Seleccionar la base de datos y la colección
    $db = $client->selectDatabase("orion");
    $collection = $db->selectCollection("pacientes"); // Cambia por el nombre de tu colección
    $collection2 = $db->selectCollection("historiasclinicas"); // Cambia por el nombre de tu colección

    $ultimoDocumento = $collection->findOne([],
    [
        'sort' => ['1_Id_Pac' => -1], // Orden descendente por _id
    ]
);
// Obtener el último documento basado en el campo _id Pacientes
$ultimoDocumento2 = $collection2->findOne([],
    [
        'sort' => ['1_Id_HC' => -1], // Orden descendente por _id
    ]
);

    // Crear el objeto a insertar
   //'fecha_registro' => new MongoDB\BSON\UTCDateTime()
    ///parte del codigo donde se crea o agrega  (POST)
   //$Idpac = $_POST['Fpaci'];
   $Nombre = $_POST['Fname'];
   $Cedula = $_POST['Fced'];
   $FechaNac = $_POST['Fnaci'];
   $Genero= $_POST['Fgene'];
   $Celular = $_POST['Fcelu'];
   $Direccion = $_POST['Fdire'];
   $Mail = $_POST['Fmail'];

   //$Idhc = $_POST['Fhistori'];
   $HistMedic = $_POST['FHM'];
   $Medicacion = $_POST['FM'];
   $Alergias = $_POST['Faler'];
   $UltimIng = $_POST['FIngreso'];

    $datos = [
        '1_Id_Pac' => strval($ultimoDocumento['1_Id_Pac'] + 1 ),
        '2_nombre_paciente' => $aes->encrypt($Nombre) ,
        '3_cedula' => $aes->encrypt($Cedula),
        '4_fecha_nacimiento' => $aes->encrypt($FechaNac),
        '5_genero' => $aes->encrypt($Genero),
        '6_celular' => $aes->encrypt($Celular),
        '7_direccion' => $aes->encrypt($Direccion),
        '8_email' => $aes->encrypt($Mail),
    ];

    $datos2 = [
        '1_Id_HC' => strval($ultimoDocumento2['1_Id_HC'] + 1 ),
        '2_Id_Pac' => strval($ultimoDocumento['1_Id_Pac'] + 1 ),
        '3_historial_med' => $aes->encrypt($HistMedic),
        '4_medicacion' => $aes->encrypt($Medicacion),
        '5_alergia' => $aes->encrypt($Alergias),
        '6_ultimo_ingreso' => $UltimIng,
    ];

    // Insertar el objeto en la colección
    $resultado = $collection->insertOne($datos);
    $resultado2 = $collection2->insertOne($datos2);

    // Verificar si ambas inserciones fueron exitosas
    if ($resultado->getInsertedCount() > 0 && $resultado2->getInsertedCount() > 0) {
       //echo "Ambas inserciones fueron exitosas.";
       $mensaje = 'Nueva Historia Clinica ingresada exitosamente.'; // se guarda en mensaje el texto que quieras mostrar
       header("Location: ../pagadmin.php?Message=" . urlencode($mensaje));
    } else {
       //echo "Una o ambas inserciones fallaron.";
       $mensaje = 'No se pudo realzar la creación de la Historia Clinica.'; // se guarda en mensaje el texto que quieras mostrar
       header("Location: ../pagadmin.php?Message=" . urlencode($mensaje));
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
