<?php


include_once '../config/database.php';
class Model{
    private  $database;
    private $conn;
    private $arrayTypes =[
        'integer'=>"integer",
        "double"=>"double"
    ];
    public function __construct(){
             
    }
    public function select(){
        return "select";
    }
    private function getConn(){
        $database = new Database(); 
        $this->conn = $database->getConnection();
    }

    protected function coutTabela($table_name,$dados ){
        /** esta função não esta funcinado com campos numericos de forma dinamica */
        try {
            $this->getConn();
            $valores = array_values($dados);
            $loop= true;
            $where ="  where  ";
            foreach($dados as $key => $dado){
                $type = gettype($dado);
                if($loop){
                    $where .= $this->setColunas($type, $key,$dado);
                    $loop = false;
                }else{
                    $where .= $this->setColunas($type, $key,$dado, " and ");
                }
            }
            $where .= " ; ";
            $query = "select count(1) as total from {$table_name} ";
            $query .= $where ;
            $stmt =  $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
	        return $row['total'];
        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }


    protected function insert($table_name,$dados ){
        try {
            $this->getConn();
            $loop= true;
            $setValues = " SET  ";
            foreach($dados as $key => $dado){
               $type = gettype($dado);
                if($loop){
                    $setValues .=  $this->setColunas($type, $key,$dado);
                    $loop = false;
                }else{
                    $setValues .=  $this->setColunas($type, $key,$dado,",");
                }
            }
            $query = "INSERT INTO ".$table_name;
            $query .= $setValues;
            return $this->conn->query($query);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    protected function update($table_name,$dados ){
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    
    private function setColunas($type, $key,$dado ,$operador =""){
        if(in_array($type, $this->arrayTypes)){
            return " {$operador} {$key} = {$dado} ";
           }else{
            return  " {$operador} {$key} = '{$dado}' ";
           }
    }





}