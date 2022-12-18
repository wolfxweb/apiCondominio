<?php
class Database{
    private $host;
    private $db_name;
    private $username;
    private $password;
	private $port;
    public $conn;

    function __construct($dbName="appLista") {
        $this->db_name = $dbName;
        $this->host = "137.184.184.226";
        $this->username = "wolf";
        $this->password = "wolf";
        $this->port = "3306";
        $this->conn = null;
    }
    public function getConnection(){
        try
        {   
		    if($this->port){
                $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                return $this->conn;
            }
            else{
			    $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
                return $this->conn;
		    }
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
            return null;
        }
 
        
    }
}
?>
