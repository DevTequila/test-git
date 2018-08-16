<?php

$dir_subdir = "services/img/"; 
$archivo_subido = $dir_subdir . basename($_FILES['imagen']['name']); 
if(move_uploaded_file($_FILES['imagen']['tmp_name'],$archivo_subido))
{
    $nombre = explode(".",$archivo_subido);
    $extension = "." . $nombre[count($nombre)-1];
    $nuevo_nombre = $dir_subdir . time() . $extension; 
    rename($archivo_subido,$nuevo_nombre);
    
    require("../scripts/conexion.php"); 
    require("../clases/Pelicula.php"); 
    
    $p = new Pelicula($conData);    
    
    $res = $p->inserta($_POST['titulo'] , $_POST['sinopsis'], $nuevo_nombre);
    if($res['estado']=="OK"){
        ?>
        <script>
            parent.recarga();              
        </script>
        <?php
    }else  {
        unlink($nuevo_nombre);
        ?>
        <script>parent.alert("<?=$nuevo_nombre ?> Error: <?=$res['estado']?>")</script>
        <?php
    }
}


?>