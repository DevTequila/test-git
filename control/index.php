<?php
session_start(); 
if(!isset($_SESSION['usuario']))
{
    header("location: ../");
}

if(isset($_GET['salir'])){
    session_destroy();
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aplicacion de pelicula </title>
</head>
<body>
 <a href="index.php?salir=1">Salir</a>   
 
 <form name="forma" action="upload.php" id="forma" method="post" enctype="multipart/form-data"
    target="peliculaFrame">
     <fieldset>
         <legend>
             Agregar Prelicula
         </legend>
         <label for="titulo">Titulo</label>
         <input type="text" id="titulo" placeholder="Titulo...." name="titulo"><br> 
         <label for="sinopsis">Sinopsis</label>
         <textarea name="sinopsis" id="sinopsis" cols="50" rows="3"></textarea><br>
         <label for="imagen">Imagen:</label>
         <input type="file" name="imagen" id="imagen"><br><br>
         <input type="submit" value="Agregar" > 
         | <input type="reset" value="limpiar" >
     </fieldset>
 </form>
 
 <iframe name="peliculaFrame" style="display:none"> </iframe>
     <h4>Lista de peliculas</h4>
     <table>
         <tr>
             <td>ID</td>
             <td>Foto</td>
             <td>Titulo</td>
             <td>Sinopsis</td>
             <td>Comentarios</td>
             <td>Agregar</td>
         </tr>
         <?php 
         require("../scripts/conexion.php"); 
         require("../clases/Pelicula.php");
         $p = new Pelicula($conData);
         
         $res = $p->consulta('0'); 
         if($res['estado']!="OK" || $res['filas']==0){
             echo "<tr><td colspan='4'>NO HAY RESULTADOS A MOSTRAR </td></tr>";
         } else {
             foreach($res['datos'] as $fila)
             {
                echo "<tr>"; 
                echo "<td>" . $fila['idpeliculas']."</td>";
                echo "<td><img src='" . $fila['ruta']."' width=120 height=100></td>";
                echo "<td>" . $fila['titulo']."</td>";
                echo "<td>" . $fila['sinopsis']."</td>";                 
                echo "<td>" . $fila['comentario']."</td>"; 
                echo "<td><textarea id='comentario_".$fila['idpeliculas']."'></textarea><br><input type='button' onclick='javascript:addComentario(".$fila['idpeliculas'].")' value='Enviar'></td>"; 
                echo "<tr>"; 
             }
         }
         ?>
     </table>
    <script src="../scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function recarga() {
            window.location.href="index.php";
        }
        
        function addComentario(id){
            obj = document.getElementById('comentario_'+id); 
            if(obj.value==null)
                {
                    alert("El comentario esta vacio");
                    obj.focus();
                    return; 
                }
            var comentario = new Object(); 
            comentario.id = id; 
            comentario.comment = obj.value; 
            json = JSON.stringify(comentario); 
            // alert(json);
             $.post(
                            "http://localhost/curso_web/control/services/add.php",
                            json, 
                            function(responseText, status){
                                try{
                                    if(status == "success"){
                                        console.log(responseText);
                                        res = JSON.parse(responseText);
                                        if(res.estado=="OK"){
                                            console.warn("Login Success!");
                                            alert("Comentario agregado");
                                            recarga();                                     
                                        }else {
                                        alert(res.estado);
                                        console.error("Status: " + res.estado);
                                    }
                                    } 
                                }catch(e){
                                    console.log("Error " + e);
                                }
                            }
                        );
        }
    </script>
 </body>
</html>

