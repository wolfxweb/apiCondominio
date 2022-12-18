<?php
class Areas{
 
    private $conn;
    private $table_name = "areas";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $id;
public $area_id;
public $area_titulo;
public $area_status;
public $area_fotos;
public $area_days;
public $area_time_start;
public $area_time_end;
public $cond_id;
public $created_at;
public $updated_at;
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join condominios x on t.cond_id = x.id  WHERE LOWER(t.area_id) LIKE ? OR LOWER(t.area_titulo) LIKE ?  OR LOWER(t.area_status) LIKE ?  OR LOWER(t.area_fotos) LIKE ?  OR LOWER(t.area_days) LIKE ?  OR LOWER(t.area_time_start) LIKE ?  OR LOWER(t.area_time_end) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ? ";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
$stmt->bindParam(7, $searchKey);
$stmt->bindParam(8, $searchKey);
$stmt->bindParam(9, $searchKey);
$stmt->bindParam(10, $searchKey);
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join condominios x on t.cond_id = x.id  WHERE ".$where."";
		
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
		$query = "SELECT  x.cond_nome, t.* FROM ". $this->table_name ." t  join condominios x on t.cond_id = x.id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  x.cond_nome, t.* FROM ". $this->table_name ." t  join condominios x on t.cond_id = x.id  WHERE LOWER(t.area_id) LIKE ? OR LOWER(t.area_titulo) LIKE ?  OR LOWER(t.area_status) LIKE ?  OR LOWER(t.area_fotos) LIKE ?  OR LOWER(t.area_days) LIKE ?  OR LOWER(t.area_time_start) LIKE ?  OR LOWER(t.area_time_end) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
$stmt->bindParam(7, $searchKey);
$stmt->bindParam(8, $searchKey);
$stmt->bindParam(9, $searchKey);
$stmt->bindParam(10, $searchKey);
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
		$query = "SELECT  x.cond_nome, t.* FROM ". $this->table_name ." t  join condominios x on t.cond_id = x.id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  x.cond_nome, t.* FROM ". $this->table_name ." t  join condominios x on t.cond_id = x.id  WHERE t.id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->id = $row['id'];
$this->area_id = $row['area_id'];
$this->area_titulo = $row['area_titulo'];
$this->area_status = $row['area_status'];
$this->area_fotos = $row['area_fotos'];
$this->area_days = $row['area_days'];
$this->area_time_start = $row['area_time_start'];
$this->area_time_end = $row['area_time_end'];
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
		$query ="INSERT INTO ".$this->table_name." SET area_id=:area_id,area_titulo=:area_titulo,area_status=:area_status,area_fotos=:area_fotos,area_days=:area_days,area_time_start=:area_time_start,area_time_end=:area_time_end,cond_id=:cond_id,created_at=:created_at,updated_at=:updated_at";
		$stmt = $this->conn->prepare($query);
		
$this->area_id=htmlspecialchars(strip_tags($this->area_id));
$this->area_titulo=htmlspecialchars(strip_tags($this->area_titulo));
$this->area_status=htmlspecialchars(strip_tags($this->area_status));
$this->area_fotos=htmlspecialchars(strip_tags($this->area_fotos));
$this->area_days=htmlspecialchars(strip_tags($this->area_days));
$this->area_time_start=htmlspecialchars(strip_tags($this->area_time_start));
$this->area_time_end=htmlspecialchars(strip_tags($this->area_time_end));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
		
$stmt->bindParam(":area_id", $this->area_id);
$stmt->bindParam(":area_titulo", $this->area_titulo);
$stmt->bindParam(":area_status", $this->area_status);
$stmt->bindParam(":area_fotos", $this->area_fotos);
$stmt->bindParam(":area_days", $this->area_days);
$stmt->bindParam(":area_time_start", $this->area_time_start);
$stmt->bindParam(":area_time_end", $this->area_time_end);
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
		$query ="UPDATE ".$this->table_name." SET area_id=:area_id,area_titulo=:area_titulo,area_status=:area_status,area_fotos=:area_fotos,area_days=:area_days,area_time_start=:area_time_start,area_time_end=:area_time_end,cond_id=:cond_id,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
		$stmt = $this->conn->prepare($query);
		
$this->area_id=htmlspecialchars(strip_tags($this->area_id));
$this->area_titulo=htmlspecialchars(strip_tags($this->area_titulo));
$this->area_status=htmlspecialchars(strip_tags($this->area_status));
$this->area_fotos=htmlspecialchars(strip_tags($this->area_fotos));
$this->area_days=htmlspecialchars(strip_tags($this->area_days));
$this->area_time_start=htmlspecialchars(strip_tags($this->area_time_start));
$this->area_time_end=htmlspecialchars(strip_tags($this->area_time_end));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
		
$stmt->bindParam(":area_id", $this->area_id);
$stmt->bindParam(":area_titulo", $this->area_titulo);
$stmt->bindParam(":area_status", $this->area_status);
$stmt->bindParam(":area_fotos", $this->area_fotos);
$stmt->bindParam(":area_days", $this->area_days);
$stmt->bindParam(":area_time_start", $this->area_time_start);
$stmt->bindParam(":area_time_end", $this->area_time_end);
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

	
function readBycond_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  x.cond_nome, t.* FROM ". $this->table_name ." t  join condominios x on t.cond_id = x.id  WHERE t.cond_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->cond_id);

$stmt->execute();
return $stmt;
}

}
?>
