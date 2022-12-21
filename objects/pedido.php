<?php
class Pedido{
 
    private $conn;
    private $table_name = "pedido";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $ped_id;
public $ped_titulo;
public $ped_descricao;
public $usu_id;
public $ped_data;
public $ped_local_compra;
public $usu_email;
    
    public function __construct($db){
        $this->conn = $db;
    }

	function total_record_count() {
		$query = "select count(1) as total from ". $this->table_name ."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total'];
	}

	function search_count($searchKey) {
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join usuarios rrr on t.usu_id = rrr.usu_id  WHERE LOWER(t.ped_titulo) LIKE ? OR LOWER(t.ped_descricao) LIKE ?  OR LOWER(t.usu_id) LIKE ?  OR LOWER(t.ped_data) LIKE ?  OR LOWER(t.ped_local_compra) LIKE ? ";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total'];
	}
	
	function search_record_count($columnArray,$orAnd){
		$where="";
		$paramCount = 1;
		foreach ($columnArray as $col) {
			$pre_param = "P" . $paramCount . "_";
			$columnName=htmlspecialchars(strip_tags($col->columnName));
			$columnLogic=$col->columnLogic;
			if($where==""){
				$where="LOWER(t.".$columnName . ") ".$columnLogic." :".$pre_param.$columnName;
			}else{
				$where=$where." ". $orAnd ." LOWER(t." . $columnName . ") ".$columnLogic." :".$pre_param.$columnName;
			}
			 $paramCount++;
		}
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join usuarios rrr on t.usu_id = rrr.usu_id  WHERE ".$where."";
		
		$stmt = $this->conn->prepare($query);
		$paramCount=1;
		foreach ($columnArray as $col) {
			$columnName=htmlspecialchars(strip_tags($col->columnName));
		if(strtoupper($col->columnLogic)=="LIKE"){
		$columnValue="%".strtolower($col->columnValue)."%";
		}else{
		$columnValue=strtolower($col->columnValue);
		}
			$stmt->bindValue(":".$pre_param.$columnName, $columnValue);
			$paramCount++;
		}
		
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		 $num = $stmt->rowCount();
		if($num>0){
			return $row['total'];
		}else{
			return 0;
		}
	}
	function read(){
		if(isset($_GET["pageNo"])){
			$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  rrr.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios rrr on t.usu_id = rrr.usu_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  rrr.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios rrr on t.usu_id = rrr.usu_id  WHERE LOWER(t.ped_titulo) LIKE ? OR LOWER(t.ped_descricao) LIKE ?  OR LOWER(t.usu_id) LIKE ?  OR LOWER(t.ped_data) LIKE ?  OR LOWER(t.ped_local_compra) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
		$stmt->execute();
		return $stmt;
	}
	function searchByColumn($columnArray,$orAnd){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$where="";
		$paramCount = 1;
		foreach ($columnArray as $col) {
			$pre_param = "P" . $paramCount . "_";
			$columnName=htmlspecialchars(strip_tags($col->columnName));
			$columnLogic=$col->columnLogic;
			if($where==""){
				$where="LOWER(t.".$columnName . ") ".$columnLogic." :".$pre_param.$columnName;
			}else{
				$where=$where." ". $orAnd ." LOWER(t." . $columnName . ") ".$columnLogic." :".$pre_param.$columnName;
			}
			 $paramCount++;
		}
		$query = "SELECT  rrr.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios rrr on t.usu_id = rrr.usu_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
		$stmt = $this->conn->prepare($query);
		$paramCount=1;
		foreach ($columnArray as $col) {
			$pre_param = "P" . $paramCount . "_";
			$columnName=htmlspecialchars(strip_tags($col->columnName));
			if(strtoupper($col->columnLogic)=="LIKE"){
			$columnValue="%".strtolower($col->columnValue)."%";
			}else{
			$columnValue=strtolower($col->columnValue);
			}
			$stmt->bindValue(":".$pre_param.$columnName, $columnValue);
			$paramCount++;
		}
		
		$stmt->execute();
		return $stmt;
	}
	
	

	function readOne(){
		$query = "SELECT  rrr.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios rrr on t.usu_id = rrr.usu_id  WHERE t.ped_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->ped_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->ped_id = $row['ped_id'];
$this->ped_titulo = $row['ped_titulo'];
$this->ped_descricao = $row['ped_descricao'];
$this->usu_id = $row['usu_id'];
$this->usu_email = $row['usu_email'];
$this->ped_data = $row['ped_data'];
$this->ped_local_compra = $row['ped_local_compra'];
		}
		else{
			$this->ped_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET ped_titulo=:ped_titulo,ped_descricao=:ped_descricao,usu_id=:usu_id,ped_data=:ped_data,ped_local_compra=:ped_local_compra";
		$stmt = $this->conn->prepare($query);
		
$this->ped_titulo=htmlspecialchars(strip_tags($this->ped_titulo));
$this->ped_descricao=htmlspecialchars(strip_tags($this->ped_descricao));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->ped_data=htmlspecialchars(strip_tags($this->ped_data));
$this->ped_local_compra=htmlspecialchars(strip_tags($this->ped_local_compra));
		
$stmt->bindParam(":ped_titulo", $this->ped_titulo);
$stmt->bindParam(":ped_descricao", $this->ped_descricao);
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":ped_data", $this->ped_data);
$stmt->bindParam(":ped_local_compra", $this->ped_local_compra);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->ped_id!=null){
				$this->readOne();
				if($this->ped_id!=null){
					$lastInsertedId=$this->ped_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET ped_titulo=:ped_titulo,ped_descricao=:ped_descricao,usu_id=:usu_id,ped_data=:ped_data,ped_local_compra=:ped_local_compra WHERE ped_id = :ped_id";
		$stmt = $this->conn->prepare($query);
		
$this->ped_titulo=htmlspecialchars(strip_tags($this->ped_titulo));
$this->ped_descricao=htmlspecialchars(strip_tags($this->ped_descricao));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->ped_data=htmlspecialchars(strip_tags($this->ped_data));
$this->ped_local_compra=htmlspecialchars(strip_tags($this->ped_local_compra));
$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));
		
$stmt->bindParam(":ped_titulo", $this->ped_titulo);
$stmt->bindParam(":ped_descricao", $this->ped_descricao);
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":ped_data", $this->ped_data);
$stmt->bindParam(":ped_local_compra", $this->ped_local_compra);
$stmt->bindParam(":ped_id", $this->ped_id);
		$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
	}
	function update_patch($jsonObj) {
			$query ="UPDATE ".$this->table_name. " SET ";
			$setValue="";
			$colCount=1;
			foreach($jsonObj as $key => $value) 
			{
				$columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='ped_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE ped_id = :ped_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='ped_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":ped_id", $this->ped_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE ped_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));

		$stmt->bindParam(1, $this->ped_id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
function readByusu_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  rrr.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios rrr on t.usu_id = rrr.usu_id  WHERE t.usu_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usu_id);

$stmt->execute();
return $stmt;
}

}
?>
