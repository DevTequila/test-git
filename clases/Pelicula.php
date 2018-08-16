<?php
class Pelicula {
    
    private $host; 
    private $user; 
    private $pass; 
    private $db; 
    
    public function __construct($datos){
        $this->host=$datos['host'];
        $this->user=$datos['user'];
        $this->pass=$datos['pass'];
        $this->db=$datos['db'];
    }
    
    function inserta($titulo, $sinopsis, $imagen){
        $R['estado']= "OK"; 
        try {
            $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db,$this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare("INSERT INTO peliculas(titulo, sinopsis, ruta, comentario) VALUES(:titulo,:sinopsis,:ruta,'Comentarios:')");
            $sql->execute(array('titulo'=>$titulo ,'sinopsis'=>$sinopsis,'ruta'=>$imagen));
            
            $conn = null; 
        }catch(PDOException $e){
            $R['estado'] = "Error: " . $e->getMessage(); 
        }
        return $R; 
    }
    
    function consulta($id){
        $R['estado']= "OK"; 
        try {
            $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db,$this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($id=='0')
                $sql=$conn->query('SELECT * FROM peliculas');
            else {
                $sql = $conn->prepare('SELECT * FROM peliculas WHERE idpeliculas=:id');
                $sql->execute(array('id'=>$id));
            }
            $R['filas'] = $sql->rowCount(); 
            if($R['filas']>0)
                $R['datos'] = $sql->fetchAll(); 
            $conn = null; 
        }catch(PDOException $e){
            $R['estado'] = "Error: " . $e->getMessage(); 
        }
        return $R; 
    }
    
    function addComentario($id, $comenario){
        $R['estado']= "OK"; 
        try {
            $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db,$this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare("UPDATE peliculas SET comentario=CONCAT(comentario,'#',:pcomentario) WHERE idpeliculas=:id");
            $sql->execute(array('pcomentario'=>$comenario ,'id'=>$id));
            
            $conn = null; 
        }catch(PDOException $e){
            $R['estado'] = "Error: " . $e->getMessage(); 
        }
        return $R; 
    }
    
}
?>