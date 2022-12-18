<?php
class Itens_Pedido{
 
    private $conn;
    private $table_name = "itens_pedido";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $itep_id;
public $ped_id;
public $prod_id;
public $itep_quantidade;
public $itep_valor_total;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  WHERE LOWER(t.ped_id) LIKE ? OR LOWER(t.prod_id) LIKE ?  OR LOWER(t.itep_quantidade) LIKE ?  OR LOWER(t.itep_valor_total) LIKE ? ";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  WHERE ".$where."";
		
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE LOWER(t.ped_id) LIKE ? OR LOWER(t.prod_id) LIKE ?  OR LOWER(t.itep_quantidade) LIKE ?  OR LOWER(t.itep_valor_total) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.itep_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->itep_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->itep_id = $row['itep_id'];
$this->ped_id = $row['ped_id'];
$this->prod_id = $row['prod_id'];
$this->itep_quantidade = $row['itep_quantidade'];
$this->itep_valor_total = $row['itep_valor_total'];
		}
		else{
			$this->itep_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET itep_id=:itep_id,ped_id=:ped_id,prod_id=:prod_id,itep_quantidade=:itep_quantidade,itep_valor_total=:itep_valor_total";
		$stmt = $this->conn->prepare($query);
		
$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));
$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));
$this->prod_id=htmlspecialchars(strip_tags($this->prod_id));
$this->itep_quantidade=htmlspecialchars(strip_tags($this->itep_quantidade));
$this->itep_valor_total=htmlspecialchars(strip_tags($this->itep_valor_total));
		
$stmt->bindParam(":itep_id", $this->itep_id);
$stmt->bindParam(":ped_id", $this->ped_id);
$stmt->bindParam(":prod_id", $this->prod_id);
$stmt->bindParam(":itep_quantidade", $this->itep_quantidade);
$stmt->bindParam(":itep_valor_total", $this->itep_valor_total);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->itep_id!=null){
				$this->readOne();
				if($this->itep_id!=null){
					$lastInsertedId=$this->itep_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET itep_id=:itep_id,ped_id=:ped_id,prod_id=:prod_id,itep_quantidade=:itep_quantidade,itep_valor_total=:itep_valor_total WHERE itep_id = :itep_id";
		$stmt = $this->conn->prepare($query);
		
$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));
$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));
$this->prod_id=htmlspecialchars(strip_tags($this->prod_id));
$this->itep_quantidade=htmlspecialchars(strip_tags($this->itep_quantidade));
$this->itep_valor_total=htmlspecialchars(strip_tags($this->itep_valor_total));
$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));
		
$stmt->bindParam(":itep_id", $this->itep_id);
$stmt->bindParam(":ped_id", $this->ped_id);
$stmt->bindParam(":prod_id", $this->prod_id);
$stmt->bindParam(":itep_quantidade", $this->itep_quantidade);
$stmt->bindParam(":itep_valor_total", $this->itep_valor_total);
$stmt->bindParam(":itep_id", $this->itep_id);
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
				if($columnName!='itep_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE itep_id = :itep_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='itep_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":itep_id", $this->itep_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE itep_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));

		$stmt->bindParam(1, $this->itep_id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
}
?>
