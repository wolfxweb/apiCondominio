<?php
class Database{
    private $host;
    private $db_name;
    private $username;
    private $password;
	private $port;
    public $conn;

    function __construct($dbName="appCondominios") {
        $this->db_name = $dbName;
        /* configuração amazon
            $this->host = "appcondominio.crrjtkq5nq1n.us-east-1.rds.amazonaws.com";
            $this->username = "admin";
            $this->password = "wolfx2020";
        */
        $this->host = "localhost";
        $this->username = "root";
       $this->password = "";
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
