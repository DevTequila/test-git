<?php
//phpinfo();

class Admin {
    
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
    
    function getLogin($u, $p){
        $R['estado']= "OK"; 
        try {
            $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db,$this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare('SELECT * FROM admin WHERE user=:puser AND pass=:ppass');
            $sql->execute(array('puser'=>$u ,'ppass'=>$p));
            $R['filas'] = $sql->rowCount(); 
            if($R['filas']>0)
                $R['datos'] = $sql->fetchAll(); 
            $conn = null; 
        }catch(PDOException $e){
            $R['estado'] = "Error: " . $e->getMessage(); 
        }
        return $R; 
    }
}

?>