<?php
class Unidade_Medida{
 
    private $conn;
    private $table_name = "unidade_medida";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $unid_id;
public $unid_slug;
public $unid_nome;
public $usu_id;
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join usuarios aaaa on t.usu_id = aaaa.usu_id  WHERE LOWER(t.unid_slug) LIKE ? OR LOWER(t.unid_nome) LIKE ?  OR LOWER(t.usu_id) LIKE ? ";
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join usuarios aaaa on t.usu_id = aaaa.usu_id  WHERE ".$where."";
		
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
		$query = "SELECT  aaaa.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios aaaa on t.usu_id = aaaa.usu_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  aaaa.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios aaaa on t.usu_id = aaaa.usu_id  WHERE LOWER(t.unid_slug) LIKE ? OR LOWER(t.unid_nome) LIKE ?  OR LOWER(t.usu_id) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
		$query = "SELECT  aaaa.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios aaaa on t.usu_id = aaaa.usu_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  aaaa.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios aaaa on t.usu_id = aaaa.usu_id  WHERE t.unid_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->unid_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->unid_id = $row['unid_id'];
$this->unid_slug = $row['unid_slug'];
$this->unid_nome = $row['unid_nome'];
$this->usu_id = $row['usu_id'];
$this->usu_email = $row['usu_email'];
		}
		else{
			$this->unid_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET unid_slug=:unid_slug,unid_nome=:unid_nome,usu_id=:usu_id";
		$stmt = $this->conn->prepare($query);
		
$this->unid_slug=htmlspecialchars(strip_tags($this->unid_slug));
$this->unid_nome=htmlspecialchars(strip_tags($this->unid_nome));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
		
$stmt->bindParam(":unid_slug", $this->unid_slug);
$stmt->bindParam(":unid_nome", $this->unid_nome);
$stmt->bindParam(":usu_id", $this->usu_id);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->unid_id!=null){
				$this->readOne();
				if($this->unid_id!=null){
					$lastInsertedId=$this->unid_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET unid_slug=:unid_slug,unid_nome=:unid_nome,usu_id=:usu_id WHERE unid_id = :unid_id";
		$stmt = $this->conn->prepare($query);
		
$this->unid_slug=htmlspecialchars(strip_tags($this->unid_slug));
$this->unid_nome=htmlspecialchars(strip_tags($this->unid_nome));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));
		
$stmt->bindParam(":unid_slug", $this->unid_slug);
$stmt->bindParam(":unid_nome", $this->unid_nome);
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":unid_id", $this->unid_id);
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
				if($columnName!='unid_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE unid_id = :unid_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='unid_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":unid_id", $this->unid_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE unid_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));

		$stmt->bindParam(1, $this->unid_id);

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
$query = "SELECT  aaaa.usu_email, t.* FROM ". $this->table_name ." t  left join usuarios aaaa on t.usu_id = aaaa.usu_id  WHERE t.usu_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usu_id);

$stmt->execute();
return $stmt;
}

}
?>
