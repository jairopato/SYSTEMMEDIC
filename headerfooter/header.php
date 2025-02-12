<?php 
  require("dbmsql.php");

    $mail=$_SESSION['mail'];
    // Consulta con WHERE usando una variable
    $sql = "SELECT * FROM user WHERE email = :mail";
    $stmt = $conn->prepare($sql);
    // Asignar el valor de la variable al parámetro
    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);

    // Ejecutar la consulta
    $stmt->execute();

    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $menuuser = $fila['tipo'] ;
          //$menuuser = 3;
 // }
 ?>
 
<nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        
    <?php if ($menuuser == 2){?>

        <ul>
            <?php if ($op == "principal"){?>
            <li><a href="agregarHC.php">Agregar Historia CLinica</a></li>
            <?php }
            elseif ($op == "modificarhc") {?>
            <li><a href="pagadmin.php">Inicio</a></li>
            <?php }
            elseif ($op == "agregarhc") {?>
            <li><a href="pagadmin.php">Inicio</a></li>
            <?php }?>
            <li><a href='php/salir.php'>Salir</a></li>
        </ul>

    <?php } elseif ($menuuser == 3) {?>
    
        <ul>
            <?php if ($op == "principal"){?>
            <?php }
            elseif ($op == "modificardoc") {?>
                <li><a href="pagadmin.php">Inicio</a></li>
            <?php }?>
            <li><a href='php/salir.php'>Salir</a></li>
        </ul>

    <?php } elseif ($menuuser == 4) { ?>

        <ul>
            <?php if ($op == "modificarpac") {?>
                <li><a href='php/salir.php'>Salir</a></li>
            <?php }?>
        </ul>

    <?php } ?>
<?php } ?>

</nav>