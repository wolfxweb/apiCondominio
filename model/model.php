<?php


include_once '../config/database.php';
class Model{
    private $database;
    private $conn;
    private $query ;
    protected $where = " WHERE 1=1 ";
    private $arrayTypes =[
        'integer'=>"integer",
        "double"=>"double"
    ];
    private $camposRetorno;
    private $join =[];
    private $arrayTypestabela;
    private $dados =[];
    protected $tabela;

    public function select($camposRetorno = "", $joinConsulta=[]){
        try {
            $this->getConn();
            $this->camposRetorno = !empty($camposRetorno)?$camposRetorno:" * ";
            $this->join = $joinConsulta;
           // $valores = array_values($this->dados);           
           
            foreach($this->dados as $key => $dado){
                $type = gettype($dado);
                $prefixo = explode("_",$key);
                $coluna = "{$prefixo}.{$key}";
                $this->where .=$this->setColunas($type,$coluna,$dado, " AND ");
            }
            $this->query = "SELECT {$this->camposRetorno}  FROM {$this->tabela} ";
            if(count($this->join) >0){
               $this->montaJoin() ;
            }
            $this->query .= $this->where ;
            $stmt =  $this->conn->prepare($this->query );
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } catch (\PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    private function montaJoin(){
        foreach($this->join as $key => $value){
            $this->query .= $value;
        }
       

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
                $this->where .= $this->setColunas($type, $key,$dado, " AND ");
            }
            $query = "SELECT count(1) as total FROM {$table_name} ";
            $query .= $this->where ;
            $stmt =  $this->conn->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->where = " WHERE 1=1 ";
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
    protected function update($dados, $where ){
        try {
            $this->getConn();
            $setValues = " SET  ";
            $loop = true;
            foreach($dados as $key => $dado){
                $type = gettype($dado);
                 if($loop){
                     $setValues .=  $this->setColunas($type, $key,$dado);
                     $loop = false;
                 }else{
                     $setValues .=  $this->setColunas($type, $key,$dado,",");
                 }
                
             }
             $query = "UPDATE {$this->tabela} ";
             $query .= $setValues;
             $query .= $where ;
            
             return  $this->conn->query($query);
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