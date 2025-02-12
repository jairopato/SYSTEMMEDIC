<?php
session_start();


$token = $_SESSION['access_token'];
$email = $_SESSION['mail'] ;


$op = "modificardoc";

require("php/SifradoAES3.php");
require("vendor/autoload.php"); // Asegúrate de tener instalado el paquete MongoDB con Composer

// Configuración para Wilma PEP-Proxy
$orion_url = "http://localhost:1027/v2/entities"; // URL del Orion Context Broker
$pep_proxy_url = "http://localhost:1027"; // URL de Wilma PEP-Proxy

 $variable1=($_GET['var1']);

   use MongoDB\Client;
//----------------------------------------------------------------
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


//----------------------------------------------------------------
   $client = new Client("mongodb://localhost:27017");
   $db = $client->selectDatabase("orion"); // Cambia por el nombre de tu base de datos
   $collection = $db->selectCollection("historiasclinicas"); // Cambia por el nombre de tu colección
   $collection2 = $db->selectCollection("pacientes"); // Cambia por el nombre de tu colección

   // Obtiene los datos
$documentos = $collection->findOne(['1_Id_HC' =>  $variable1]);//Muestra un solo paciente
$documentos2 = $collection2->findOne(['1_Id_Pac' => $documentos['2_Id_Pac']]);//Muestra un solo paciente


?>


<!DOCTYPE html>
<html lang="en">	
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Modificar Historia Clinica</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mainadm.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/contenido.css">
  	<link rel="stylesheet" href="css/formulario.css">

</head>
<body>
<?php include ('headerfooter/header.php');?>
<br>


<div class="container">
<form id="formulario" action="php/moddoc.php?var1=<?php echo  ($documentos['1_Id_HC']  ?? 'N/A');?>&var2=<?php echo  ($documentos['2_Id_Pac']  ?? 'N/A');?>
" method="POST" enctype="multipart/form-data">


    <div class="row" style="display: none;">
      <div class="col-25">
        <label for="Fhistori">ID de la historia clínica:</label>
      </div>
      <div class="col-75">
        <input  type="text" id="Fhistori" name="Fhistori" placeholder="Codigo de Hístoria" value="<?php echo  ($documentos['1_Id_HC']  ?? 'N/A');?>" disabled required>
      </div>
    </div>

    <div class="row" style="display: none;">
      <div class="col-25">
        <label for="Fpaci">ID de Paciente:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fpaci" name="Fpaci" placeholder="Codigo de Hístoria" value="<?php echo  ($documentos['2_Id_Pac']  ?? 'N/A');?>" disabled required>
      </div>
    </div>
<!-- /.row -->
    <div class="row">
      <div class="col-25">
        <label for="Fname">Nombre de Paciente:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fname" name="Fname" placeholder="Nombre de paciente" value="<?php echo  ($aes->decrypt($documentos2['2_nombre_paciente'])  ?? 'N/A');?>" disabled required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fced">Cédula:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fced" name="Fced" placeholder="Cedula de paciente" value="<?php echo  ($aes->decrypt($documentos2['3_cedula'])  ?? 'N/A');?>" disabled required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fnaci">Fecha de Nacimiento:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fnaci" name="Fnaci" placeholder="Fecha de nacimiento del paciente" value="<?php echo  ($aes->decrypt($documentos2['4_fecha_nacimiento'])  ?? 'N/A');?>" disabled required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fgene">Genero:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fgene" name="Fgene" placeholder="Genero del paciente" value="<?php echo  ($aes->decrypt($documentos2['5_genero'])  ?? 'N/A');?>" disabled required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fcelu">Celular:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fcelu" name="Fcelu" placeholder="Número célular del paciente" value="<?php echo  ($aes->decrypt($documentos2['6_celular'])  ?? 'N/A');?>" disabled required>
      </div>
    </div>
    
    <div class="row">
      <div class="col-25">
        <label for="Fdire">Dirección:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fdire" name="Fdire" placeholder="Dirección del paciente" value="<?php echo  ($aes->decrypt($documentos2['7_direccion'])  ?? 'N/A');?>" disabled required>
      </div>
    </div> 

    <div class="row">
      <div class="col-25">
        <label for="Fmail">Emaill:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fmail" name="Fmail" placeholder="Email del paciente" value="<?php echo  ($aes->decrypt($documentos2['8_email'])  ?? 'N/A');?>" disabled required>
      </div>
    </div>    
    
    

<!--  end /.row -->
    <div class="row">
      <div class="col-25">
        <label for="FHM">Historia Medica:</label>
      </div>
      <div class="col-75">
        <input type="text" id="FHM" name="FHM" placeholder="Codigo de Hístoria" value="<?php echo  ($aes->decrypt($documentos['3_historial_med'])  ?? 'N/A');?>"  required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="FM">Medicación:</label>
      </div>
      <div class="col-75">
        <input type="text" id="FM" name="FM" placeholder="Medicacion" value="<?php echo  ($aes->decrypt($documentos['4_medicacion'])  ?? 'N/A');?>"  required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Faler">Alergias:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Faler" name="Faler" placeholder="Alergias" value="<?php echo  ($aes->decrypt($documentos['5_alergia'])  ?? 'N/A');?>" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="FIngreso">Ultimo Ingreso:</label>
      </div>
      <div class="col-75">
        <input type="text" id="FIngreso" name="FIngreso" placeholder="fecha del Ultimo Ingreso" value="<?php echo  ($documentos['6_ultimo_ingreso']  ?? 'N/A');?>" disabled required>
      </div>
    </div>


    <br>
    <div class="row">
      <input type="button" onclick="location.href='pagadmin.php';" value="Cancelar" required>
      
      <input type="submit" value="Guardar">
    </div>
  </form>
</div>
    
<div class="mt-3" id="respuesta" style="display: none">
            
</div>

<br> 
<div>
    <!-- editar footer ------------------------------------------------------------------------------------->
    <?php include ('headerfooter/footer.php');?>
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