<?php
class Categorias{
 
    private $conn;
    private $table_name = "categorias";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $cat_id;
public $cat_nome;
public $cat_descricao;
public $cat_padrao;
public $cat_img_url;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  WHERE LOWER(t.cat_nome) LIKE ? OR LOWER(t.cat_descricao) LIKE ?  OR LOWER(t.cat_padrao) LIKE ?  OR LOWER(t.cat_img_url) LIKE ? ";
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE LOWER(t.cat_nome) LIKE ? OR LOWER(t.cat_descricao) LIKE ?  OR LOWER(t.cat_padrao) LIKE ?  OR LOWER(t.cat_img_url) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
		$query = "SELECT  t.* FROM ". $this->table_name ." t  WHERE t.cat_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->cat_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->cat_id = $row['cat_id'];
$this->cat_nome = $row['cat_nome'];
$this->cat_descricao = $row['cat_descricao'];
$this->cat_padrao = $row['cat_padrao'];
$this->cat_img_url = $row['cat_img_url'];
		}
		else{
			$this->cat_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET cat_id=:cat_id,cat_nome=:cat_nome,cat_descricao=:cat_descricao,cat_padrao=:cat_padrao,cat_img_url=:cat_img_url";
		$stmt = $this->conn->prepare($query);
		
$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
$this->cat_nome=htmlspecialchars(strip_tags($this->cat_nome));
$this->cat_descricao=htmlspecialchars(strip_tags($this->cat_descricao));
$this->cat_padrao=htmlspecialchars(strip_tags($this->cat_padrao));
$this->cat_img_url=htmlspecialchars(strip_tags($this->cat_img_url));
		
$stmt->bindParam(":cat_id", $this->cat_id);
$stmt->bindParam(":cat_nome", $this->cat_nome);
$stmt->bindParam(":cat_descricao", $this->cat_descricao);
$stmt->bindParam(":cat_padrao", $this->cat_padrao);
$stmt->bindParam(":cat_img_url", $this->cat_img_url);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->cat_id!=null){
				$this->readOne();
				if($this->cat_id!=null){
					$lastInsertedId=$this->cat_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET cat_id=:cat_id,cat_nome=:cat_nome,cat_descricao=:cat_descricao,cat_padrao=:cat_padrao,cat_img_url=:cat_img_url WHERE cat_id = :cat_id";
		$stmt = $this->conn->prepare($query);
		
$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
$this->cat_nome=htmlspecialchars(strip_tags($this->cat_nome));
$this->cat_descricao=htmlspecialchars(strip_tags($this->cat_descricao));
$this->cat_padrao=htmlspecialchars(strip_tags($this->cat_padrao));
$this->cat_img_url=htmlspecialchars(strip_tags($this->cat_img_url));
$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
		
$stmt->bindParam(":cat_id", $this->cat_id);
$stmt->bindParam(":cat_nome", $this->cat_nome);
$stmt->bindParam(":cat_descricao", $this->cat_descricao);
$stmt->bindParam(":cat_padrao", $this->cat_padrao);
$stmt->bindParam(":cat_img_url", $this->cat_img_url);
$stmt->bindParam(":cat_id", $this->cat_id);
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
				if($columnName!='cat_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE cat_id = :cat_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='cat_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":cat_id", $this->cat_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE cat_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));

		$stmt->bindParam(1, $this->cat_id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
}
?>
