<?php
require("../vendor/autoload.php"); // Asegúrate de tener instalado el paquete MongoDB con Composer
require("SifradoAES3.php");

use MongoDB\Client;

$codigo= $_REQUEST['var1'];
$codigo2= $_REQUEST['var2'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Recibir datos del formulario

    $CodigoHC = $_POST['Fhistori'];
    $Idpac = $_POST['Fpaci'];
    $Nombre = $_POST['Fname'];
    $Cedula = $_POST['Fced'];
    $FNacimiento= $_POST['Fnaci'];
    $Genero = $_POST['Fgene'];
    $Celular = $_POST['Fcelu'];
    $Direccion = $_POST['Fdire'];
    $Email = $_POST['Fmail'];

    $HMedica= $_POST['FHM'];
    $Medicacion = $_POST['FM'];
    $Alergias = $_POST['Faler'];
    $UltimoI = $_POST['FIngreso'];
   
   // echo $CodigoHC;


    try {
        // Conectar con la base de datos MongoDB
        $client = new Client("mongodb://localhost:27017");
        //$collection = $client->historia_clinica->pacientes;
        $db = $client->selectDatabase("orion"); // Cambia por el nombre de tu base de datos
        $collection = $db->selectCollection("historiasclinicas"); // Cambia por el nombre de tu colección
        $collection2 = $db->selectCollection("pacientes"); // Cambia por el nombre de tu colección

        // Crear el documento 1
        $document = [
            "3_historial_med" =>  $aes->encrypt($HMedica),
            "4_medicacion" =>  $aes->encrypt($Medicacion),
            "5_alergia" => $aes->encrypt($Alergias),
            "6_ultimo_ingreso" => $UltimoI
            //"fecha_registro" => new MongoDB\BSON\UTCDateTime()
        ];
        // Crear el documento 2
        $document2 = [
            "2_nombre_paciente" => $aes->encrypt($Nombre),
            "3_cedula" => $aes->encrypt($Cedula),
            "4_fecha_nacimiento" => $aes->encrypt($FNacimiento),
            "5_genero" => $aes->encrypt($Genero),
            "6_celular" => $aes->encrypt($Celular),
            "7_direccion" => $aes->encrypt($Direccion),
            "8_email" => $aes->encrypt($Email)

        ];


        // Modificar el documento en la colección

        $result = $collection->updateOne(
            ['1_Id_HC' => $codigo],
            ['$set' => $document]
        );
        $result2 = $collection2->updateOne(
            ['1_Id_Pac' => $codigo2],
            ['$set' => $document2]
        );


        // Confirmación

        if ($result->getMatchedCount() > 0 || $result2->getMatchedCount() > 0) {
            if ($result->getModifiedCount() > 0 || $result2->getModifiedCount() > 0) {
                $mensaje = 'Datos de Historia clínica actualizada exitosamente.'; // Hubo cambios
            } else {
                $mensaje = 'No se realizaron cambios en los datos, pero los registros existen.'; // No hubo cambios
            }
        } else {
            $mensaje = 'No se encontró el registro.'; // No se encontró ningún documento
        }

        // Redirigir con el mensaje
        header("Location: ../pagadmin.php?Message=" . urlencode($mensaje));
        exit;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Método no permitido.";
}
?>






 