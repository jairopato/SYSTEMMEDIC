<?php
require("../vendor/autoload.php"); // Asegúrate de tener instalado el paquete MongoDB con Composer

use MongoDB\Client;

try {
        // Conectar con la base de datos MongoDB
        $client = new Client("mongodb://localhost:27017");
        //$collection = $client->historia_clinica->pacientes;
        $db = $client->selectDatabase("orion"); // Cambia por el nombre de tu base de datos
        $collection = $db->selectCollection("pacientes"); // Cambia por el nombre de tu colección
        $collection2 = $db->selectCollection("historiasclinicas"); // Cambia por el nombre de tu colección


        // Obtén el ID del objeto enviado desde el formulario
        $codigohc= $_REQUEST['var1'];
        $codigopac= $_REQUEST['var2'];

     //   echo 'cod hc '.$codigohc.'/n';
      //  echo 'cod pac '.$codigopac;

        // Eliminar el documento
        $result = $collection->deleteOne(['1_Id_Pac' => $codigopac]);
        $result2 = $collection2->deleteOne(['1_Id_HC' => $codigohc]);

        if ($result->getDeletedCount() > 0 && $result2->getDeletedCount() > 0) {
            //echo "El objeto con ID $id fue eliminado correctamente.";
            $mensaje = 'Historia clínica se elimino exitosamente.'; 
            header("Location: ../pagadmin.php?Message=" . urlencode($mensaje));
        } else {

            //echo "No se encontró ningún objeto con ID $id.";
            $mensaje = 'Historia clínica no se elimino o hay problemas de conexion.'; 
            header("Location: ../pagadmin.php?Message=" . urlencode($mensaje));
        }
    


} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>