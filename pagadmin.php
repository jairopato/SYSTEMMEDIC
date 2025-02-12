<?php
session_start();
//obteiene el token
$token = $_SESSION['access_token'];
$email = $_SESSION['mail'] ;
$varpaso = null;
//para la actualizacion de la pagina
if (isset($_GET['Message'])) {
    echo '<script type="text/javascript">alert("' . $_GET['Message']. '")</script>';
    header('Refresh: 7; URL=pagadmin.php');
     }

//llama al codigo del cifrado aes
require("php/SifradoAES3.php");
require("vendor/autoload.php"); // Asegúrate de tener instalado el paquete MongoDB con Composer

// Configuración para Wilma PEP-Proxy
$orion_url = "http://localhost:1027/v2/entities"; // URL del Orion Context Broker
$pep_proxy_url = "http://localhost:1027"; // URL de Wilma PEP-Proxy

//echo $email;

use MongoDB\Client;
//----------------------------------------------------------------
$op = "principal";

// Verificar si el token está disponible
if (!isset($_SESSION['access_token'])) {
    header("Location: index.php");
    exit;
}else{

try {
        // 2. Enviar solicitud a través de PEP-Proxy PARA LA ACCESIBILIDAD
        $ch = curl_init("$pep_proxy_url/v2/entities");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-Auth-Token: $token",
          // "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Error de conexión a la coleccion: ' . curl_error($ch));
        }
    
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($status_code !== 200) {
           // throw new Exception("Acceso denegado. Código de estado: $status_code");
            throw new Exception("<script>alert('Acceso denegado. Código de estado: $status_code');</script>");
        }
//----------------------------------------------------------------
?>

<!doctype html>
<html class="no-js" lang="">

    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Historias Clinicas</title>
    <link rel="stylesheet" href="css/menu.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mainadm.css">
    <link rel="stylesheet" href="css/tablapers.css">
    <link rel="stylesheet" href="css/tabla.css">
    <script src="/js/buscarTabla.js"></script>
       
    </head>
	  <body>



<!-- --------Incluye el menú----------------------------------------------------- -->   

<?php  include ('headerfooter/header.php');?>
 <!-- --------Contenido de la pagina------------------------------------------------------------------------------------------ -->   
 <br>
    <form>
        Buscar <input id="searchTerm" type="text" onkeyup="doSearch()" />
    </form>
<br>
<?php
    // Conexión a MongoDB-----------------------------------------------------------------------
    $client = new Client("mongodb://localhost:27017");
    $db = $client->selectDatabase("orion"); // Cambia por el nombre de tu base de datos
    $collection = $db->selectCollection("historiasclinicas"); // Cambia por el nombre de tu colección
    $collection2 = $db->selectCollection("pacientes"); // Cambia por el nombre de tu colección
    // Obtiene los datos
    //$documentos = $collection->find();
    //$documentos = $collection->findOne(['_id.id' => 'Hce:001']);//Muestra un solo paciente
    $documentos = $collection->find();
?>
       <div id="div1" class="datagrid">
         <table id = "tabdatos" >
          <thead>
               <tr><th>ID_HC</th><th>Nombre Paciente</th><th>Cédula</th><th>Fecha de Nacimiento</th><th>Género</th><th>Contacto</th>
               <th>Historial Médico</th><th>Medicación</th><th>Alergias</th><th>Último Ingreso</th><th colspan="2" >Opciones</th></tr>
          </thead>
          <tfoot>
         </tfoot>
          <tbody>

<?php
//Tabla ----------------------------------------------------------------

        foreach ($documentos as $doc) {
//----------------------------------------------------------------
echo "<tr>";
echo "<td>" . ($doc['1_Id_HC']  ?? 'N/A') . "</td>";
$varpaso=$doc['1_Id_HC'];

//----------------------------------------------Otra coleccion------------------
//mostrar la informacion de las HCE desencriptando 
$documentos2 = $collection2->findOne(['1_Id_Pac' => $doc['2_Id_Pac']]);//Muestra un solo paciente
               //  foreach ($documentos2 as $doc2) {

                         echo "<td>" . ($aes->decrypt($documentos2['2_nombre_paciente'])  ?? 'N/A') . "</td>";
                         echo "<td>" . ($aes->decrypt($documentos2['3_cedula'])  ?? 'N/A') . "";
                         echo "<td>" . ($aes->decrypt($documentos2['4_fecha_nacimiento'])  ?? 'N/A') . "</td>";
                         echo "<td>" . ($aes->decrypt($documentos2['5_genero'])  ?? 'N/A') . "</td>";
                         echo "<td> Celular: " . ($aes->decrypt($documentos2['6_celular'])  ?? 'N/A') . " <br> Dirección: " . ($aes->decrypt($documentos2['7_direccion'])  ?? 'N/A') . " <br> Email: " . ($aes->decrypt($documentos2['8_email'])  ?? 'N/A') . "</td>";
                         $varpaso2=$documentos2['1_Id_Pac'];          
                //     }

//----------------------------------------------------------------


echo "<td>" . ($aes->decrypt($doc['3_historial_med'])  ?? 'N/A') . "</td>";
echo "<td>" . ($aes->decrypt($doc['4_medicacion']) ?? 'N/A') . "</td>";
echo "<td>" . ($aes->decrypt($doc['5_alergia'])  ?? 'N/A') . "</td>";
echo "<td>" . ($doc['6_ultimo_ingreso']  ?? 'N/A') . "</td>";
//parte del codigo para modificar o eliminar la hce segun el rol
if ($menuuser==2){
echo "<td><a href='modificarHC.php?var1=$varpaso&var2=$varpaso2'>MODIFICAR</a></td>";
echo "<td><a href='php/eliminarHC.php?var1=$varpaso&var2=$varpaso2'>ELIMINAR</a></td>";
}elseif ($menuuser==3){
echo "<td colspan='2'><a href='modificadoc.php?var1=$varpaso&var2=$varpaso2'>MODIFICAR</a></td>";
}

echo "</tr>";
}

?>
          <tr class='noSearch hide'> <td colspan="6"></td> </tr>
    <!-- ------------------------------------------------------------------------------------- -->

          </tbody>
         </table>
        </div>

        <br> 
    <!--------------------------------------------------------------------------------------->


   <div>
    <!-- editar footer ---------------------------->
    <?php include ('headerfooter/footer.php');
    $_GET['Message']=NULL;
    ?>
  </div>        
<?php


//----------------------------------------------------------------
        
    } catch (Exception $e) {
       echo "Error: " . $e->getMessage();
       header("Location: index.php");
    }



?>
<!-- ------------------------------------------------------------------------------------- -->
    </body>
</html>
<?php
  $_GET['Message']=NULL;
}
?>