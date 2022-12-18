<?php
class Unidade_Medida{
 
    private $conn;
    private $table_name = "unidade_medida";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $uni_id;
public $uni_sigla;
public $uni_nome;
public $uni_padrao;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  WHERE LOWER(t.uni_sigla) LIKE ? OR LOWER(t.uni_nome) LIKE ?  OR LOWER(t.uni_padrao) LIKE ? ";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE LOWER(t.uni_sigla) LIKE ? OR LOWER(t.uni_nome) LIKE ?  OR LOWER(t.uni_padrao) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.uni_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->uni_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->uni_id = $row['uni_id'];
$this->uni_sigla = $row['uni_sigla'];
$this->uni_nome = $row['uni_nome'];
$this->uni_padrao = $row['uni_padrao'];
		}
		else{
			$this->uni_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET uni_id=:uni_id,uni_sigla=:uni_sigla,uni_nome=:uni_nome,uni_padrao=:uni_padrao";
		$stmt = $this->conn->prepare($query);
		
$this->uni_id=htmlspecialchars(strip_tags($this->uni_id));
$this->uni_sigla=htmlspecialchars(strip_tags($this->uni_sigla));
$this->uni_nome=htmlspecialchars(strip_tags($this->uni_nome));
$this->uni_padrao=htmlspecialchars(strip_tags($this->uni_padrao));
		
$stmt->bindParam(":uni_id", $this->uni_id);
$stmt->bindParam(":uni_sigla", $this->uni_sigla);
$stmt->bindParam(":uni_nome", $this->uni_nome);
$stmt->bindParam(":uni_padrao", $this->uni_padrao);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->uni_id!=null){
				$this->readOne();
				if($this->uni_id!=null){
					$lastInsertedId=$this->uni_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET uni_id=:uni_id,uni_sigla=:uni_sigla,uni_nome=:uni_nome,uni_padrao=:uni_padrao WHERE uni_id = :uni_id";
		$stmt = $this->conn->prepare($query);
		
$this->uni_id=htmlspecialchars(strip_tags($this->uni_id));
$this->uni_sigla=htmlspecialchars(strip_tags($this->uni_sigla));
$this->uni_nome=htmlspecialchars(strip_tags($this->uni_nome));
$this->uni_padrao=htmlspecialchars(strip_tags($this->uni_padrao));
$this->uni_id=htmlspecialchars(strip_tags($this->uni_id));
		
$stmt->bindParam(":uni_id", $this->uni_id);
$stmt->bindParam(":uni_sigla", $this->uni_sigla);
$stmt->bindParam(":uni_nome", $this->uni_nome);
$stmt->bindParam(":uni_padrao", $this->uni_padrao);
$stmt->bindParam(":uni_id", $this->uni_id);
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
				if($columnName!='uni_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE uni_id = :uni_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='uni_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":uni_id", $this->uni_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE uni_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->uni_id=htmlspecialchars(strip_tags($this->uni_id));

		$stmt->bindParam(1, $this->uni_id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
}
?>
