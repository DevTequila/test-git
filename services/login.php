<?php 
session_start();
$resArray['estado'] = "Error"; 
$data = json_decode(file_get_contents("php://input"));

if($data->usuario == "" || $data->pass==""){
    $_SESSION['estado'] = "Error: datos vacios";
    $resArray = $_SESSION; 
}else {
    
     
    require('../scripts/conexion.php');
    require('../clases/Admin.php');
        
    
    $a = new Admin($conData);
    $res = $a->getLogin($data->usuario, $data->pass); 
    if($res['estado']=="OK"){
        if($res['filas']==1)
        {
            foreach($res['datos'] as $fila){
                $_SESSION['usuario']=$fila['user']; 
                $_SESSION['pass']=$fila['pass'];
                $_SESSION['id']=$fila['idadmin'];
            }
            $resArray['estado'] = "OK"; 
        }
        else {
            $resArray['estado']="0 o mas resultados";
            session_destroy();
        }
    }else {
        $resArray['estado']=$res['estado'];
        session_destroy();
    }
    
    /*
    if($data->usuario=="coby" && $data->pass="root"){
        $_SESSION['estado'] = "OK";   
         $resArray = $_SESSION; 
    } else {
        $_SESSION['estado'] = "ERROR: USUARIO INVALIDO";    
         $resArray = $_SESSION; 
        session_destroy();
    }*/ 
}

echo json_encode($resArray);




?>