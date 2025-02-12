<?php
require("../vendor/autoload.php"); // Asegúrate de tener instalado el paquete MongoDB con Composer
require("SifradoAES3.php");

use MongoDB\Client;

$codigo= $_REQUEST['var1'];
$codigo2= $_REQUEST['var2'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Recibir datos del formulario
    $HMedica= $_POST['FHM'];
    $Medicacion = $_POST['FM'];
    $Alergias = $_POST['Faler'];

echo 'codigo: '.$codigo.'<br>';

 echo  $HMedica;
 echo '<br>';
 echo  $Medicacion;
 echo '<br>';
 echo  $Alergias;


    try {

        // Conectar con la base de datos MongoDB
        $client = new Client("mongodb://localhost:27017");
        //$collection = $client->historia_clinica->pacientes;
        $db = $client->selectDatabase("orion"); // Cambia por el nombre de tu base de datos
        $collection = $db->selectCollection("historiasclinicas"); // Cambia por el nombre de tu colección
     
        // Crear el documento 1
        $document = [
            "3_historial_med" =>  $aes->encrypt($HMedica),
            "4_medicacion" =>  $aes->encrypt($Medicacion),
            "5_alergia" => $aes->encrypt($Alergias)
        ];
        echo '<br>';
        echo  $aes->encrypt($HMedica);
        echo '<br>';
        echo  $aes->encrypt($Medicacion);
        echo '<br>';
        echo $aes->encrypt($Alergias);



        // Modificar el documento en la colección

        $result = $collection->updateOne(
            ['1_Id_HC' => $codigo],
            ['$set' => $document]
        );




        // Confirmación
        if ($result->getModifiedCount() > 0 ) {

            //echo "Datos de Historia clínica actualizada exitosamente.";
            $mensaje = 'Datos de Historia clínica actualizada exitosamente.'; // se guarda en mensaje el texto que quieras mostrar
            header("Location: ../pagadmin.php?Message=" . urlencode($mensaje));

        } else {
            //echo "No se encontró el registro o no hubo cambios.";
            $mensaje = 'No se encontró el registro o no hubo cambios.'; // se guarda en mensaje el texto que quieras mostrar
            header("Location: ../pagadmin.php?Message=" . urlencode($mensaje));

        }



    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }




} else {
    echo "Método no permitido.";
}
?>