<?php
class Pets{
 
    private $conn;
    private $table_name = "pets";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $id;
public $pets_nome;
public $pets_raca;
public $pets_tipo;
public $unid_id;
public $cond_id;
public $created_at;
public $updated_at;
public $unid_name;
public $cond_nome;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  WHERE LOWER(t.pets_nome) LIKE ? OR LOWER(t.pets_raca) LIKE ?  OR LOWER(t.pets_tipo) LIKE ?  OR LOWER(t.unid_id) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ? ";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
$stmt->bindParam(7, $searchKey);
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  WHERE ".$where."";
		
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
		$query = "SELECT  iii.unid_name, nnnn.cond_nome, t.* FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  iii.unid_name, nnnn.cond_nome, t.* FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  WHERE LOWER(t.pets_nome) LIKE ? OR LOWER(t.pets_raca) LIKE ?  OR LOWER(t.pets_tipo) LIKE ?  OR LOWER(t.unid_id) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
$stmt->bindParam(7, $searchKey);
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
		$query = "SELECT  iii.unid_name, nnnn.cond_nome, t.* FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  iii.unid_name, nnnn.cond_nome, t.* FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  WHERE t.id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->id = $row['id'];
$this->pets_nome = $row['pets_nome'];
$this->pets_raca = $row['pets_raca'];
$this->pets_tipo = $row['pets_tipo'];
$this->unid_id = $row['unid_id'];
$this->unid_name = $row['unid_name'];
$this->cond_id = $row['cond_id'];
$this->cond_nome = $row['cond_nome'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
			$this->id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET pets_nome=:pets_nome,pets_raca=:pets_raca,pets_tipo=:pets_tipo,unid_id=:unid_id,cond_id=:cond_id,created_at=:created_at,updated_at=:updated_at";
		$stmt = $this->conn->prepare($query);
		
$this->pets_nome=htmlspecialchars(strip_tags($this->pets_nome));
$this->pets_raca=htmlspecialchars(strip_tags($this->pets_raca));
$this->pets_tipo=htmlspecialchars(strip_tags($this->pets_tipo));
$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
		
$stmt->bindParam(":pets_nome", $this->pets_nome);
$stmt->bindParam(":pets_raca", $this->pets_raca);
$stmt->bindParam(":pets_tipo", $this->pets_tipo);
$stmt->bindParam(":unid_id", $this->unid_id);
$stmt->bindParam(":cond_id", $this->cond_id);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->id!=null){
				$this->readOne();
				if($this->id!=null){
					$lastInsertedId=$this->id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET pets_nome=:pets_nome,pets_raca=:pets_raca,pets_tipo=:pets_tipo,unid_id=:unid_id,cond_id=:cond_id,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
		$stmt = $this->conn->prepare($query);
		
$this->pets_nome=htmlspecialchars(strip_tags($this->pets_nome));
$this->pets_raca=htmlspecialchars(strip_tags($this->pets_raca));
$this->pets_tipo=htmlspecialchars(strip_tags($this->pets_tipo));
$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
		
$stmt->bindParam(":pets_nome", $this->pets_nome);
$stmt->bindParam(":pets_raca", $this->pets_raca);
$stmt->bindParam(":pets_tipo", $this->pets_tipo);
$stmt->bindParam(":unid_id", $this->unid_id);
$stmt->bindParam(":cond_id", $this->cond_id);
$stmt->bindParam(":created_at", $this->created_at);
$stmt->bindParam(":updated_at", $this->updated_at);
$stmt->bindParam(":id", $this->id);
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
				if($columnName!='id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE id = :id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":id", $this->id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->id=htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
function readByunid_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  iii.unid_name, nnnn.cond_nome, t.* FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  WHERE t.unid_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->unid_id);

$stmt->execute();
return $stmt;
}

function readBycond_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  iii.unid_name, nnnn.cond_nome, t.* FROM ". $this->table_name ." t  join unidades iii on t.unid_id = iii.id  join condominios nnnn on t.cond_id = nnnn.id  WHERE t.cond_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->cond_id);

$stmt->execute();
return $stmt;
}

}
?>
