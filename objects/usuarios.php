<?php
class Usuarios{
 
    private $conn;
    private $table_name = "usuarios";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $usu_id;
public $usu_nome;
public $usu_email;
public $usu_password;
public $usu_reset_token;
public $sta_id;
public $usut_id;
public $sta_nome;
public $usut_nome;
    
    public function __construct($db){
        $this->conn = $db;
    }
	public  function validaLogin($data){
	   $username =$data->username;
       $password = md5($data->password);
	   $query = "select count(1) as total from ". $this->table_name ."";
	   $query .= "	where usu_email ='{$username}' and usu_password ='{$password}'";
	   $stmt = $this->conn->prepare($query);
	   $stmt->execute();
	   $row = $stmt->fetch(PDO::FETCH_ASSOC);
	    return $row['total'];

	}
	
}
?>
