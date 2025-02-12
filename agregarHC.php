<?php
session_start();


$token = $_SESSION['access_token'];
$email = $_SESSION['mail'] ;


$op = "agregarhc";

require("vendor/autoload.php"); // Asegúrate de tener instalado el paquete MongoDB con Composer

// Configuración para Wilma PEP-Proxy
$orion_url = "http://localhost:1027/v2/entities"; // URL del Orion Context Broker
$pep_proxy_url = "http://localhost:1027"; // URL de Wilma PEP-Proxy


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

?>


<!DOCTYPE html>
<html lang="en">	
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Modificar el producto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/mainadm.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/contenido.css">
  	<link rel="stylesheet" href="css/formulario.css">

	<title>Agregar nueva Historia Clinica</title>
</head>
<body>
<?php include ('headerfooter/header.php');?>
<br>


<div class="container">
<form id="formulario" action="php/guardarhistoria.php" method="POST" enctype="multipart/form-data">



<!-- /.row -->
    <div class="row">
      <div class="col-25">
        <label for="Fname">Nombre de Paciente:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fname" name="Fname" placeholder="Nombre de paciente" value="" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fced">Cédula:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fced" name="Fced" placeholder="Cedula de paciente" value="" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fnaci">Fecha de Nacimiento:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fnaci" name="Fnaci" placeholder="Fecha de nacimiento del paciente" value="" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fgene">Genero:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fgene" name="Fgene" placeholder="Genero del paciente" value="" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Fcelu">Celular:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fcelu" name="Fcelu" placeholder="Número célular del paciente" value="" required>
      </div>
    </div>
    
    <div class="row">
      <div class="col-25">
        <label for="Fdire">Dirección:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fdire" name="Fdire" placeholder="Dirección del paciente" value="" required>
      </div>
    </div> 

    <div class="row">
      <div class="col-25">
        <label for="Fmail">Emaill:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Fmail" name="Fmail" placeholder="Email del paciente" value="" required>
      </div>
    </div>    
    
    

<!--  end /.row -->
    <div class="row">
      <div class="col-25">
        <label for="FHM">Historia Medica:</label>
      </div>
      <div class="col-75">
        <input type="text" id="FHM" name="FHM" placeholder="Codigo de Hístoria" value="" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="FM">Medicación:</label>
      </div>
      <div class="col-75">
        <input type="text" id="FM" name="FM" placeholder="Medicacion" value="" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="Faler">Alergias:</label>
      </div>
      <div class="col-75">
        <input type="text" id="Faler" name="Faler" placeholder="Alergias" value="" required>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="FIngreso">Ultimo Ingreso:</label>
      </div>
      <div class="col-75">
        <input type="text" id="FIngreso" name="FIngreso" placeholder="fecha del Ultimo Ingreso" value="" required>
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