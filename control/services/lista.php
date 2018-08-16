<?php 
header("Content-type:application/json"); 
$resultado = "";

require("../../scripts/conexion.php");
require("../../clases/Pelicula.php");

$p = new Pelicula($conData); 

$res = $p->consulta('0');

if($res['estado']=="OK")
{
    if($res['filas']>0){
        $aux =""; 
        foreach($res['datos'] as $fila){
            $aux['id'] = $fila['idpeliculas']; 
            $aux['name'] = $fila['titulo']; 
            $ruta = str_replace("services/","",$fila['ruta']);
            if(is_readable($ruta))
                $aux['pathImg']=$ruta; 
            else 
                $aux['pathImg']="none";
            $resultado[] = $aux; 
            unset($aux);
        }
    }else {
        $resultado="[No hay resultados a mostrar]"; 
    }
}else {
    $resultado = "[".$res['estado']."]";
}

echo json_encode($resultado);

?>