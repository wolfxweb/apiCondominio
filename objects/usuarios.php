<?php
class Usuarios{
 
    private $conn;
    private $table_name = "usuarios";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $usu_id;
public $usu_nome;
public $usu_email;
public $usu_password;
public $usu_reset_token;
public $sta_id;
public $usut_id;
public $sta_nome;
public $usut_nome;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  WHERE LOWER(t.usu_nome) LIKE ? OR LOWER(t.usu_email) LIKE ?  OR LOWER(t.usu_password) LIKE ?  OR LOWER(t.usu_reset_token) LIKE ?  OR LOWER(t.sta_id) LIKE ?  OR LOWER(t.usut_id) LIKE ? ";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  WHERE ".$where."";
		
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
		$query = "SELECT  xxxx.sta_nome, w.usut_nome, t.* FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  xxxx.sta_nome, w.usut_nome, t.* FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  WHERE LOWER(t.usu_nome) LIKE ? OR LOWER(t.usu_email) LIKE ?  OR LOWER(t.usu_password) LIKE ?  OR LOWER(t.usu_reset_token) LIKE ?  OR LOWER(t.sta_id) LIKE ?  OR LOWER(t.usut_id) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
$stmt->bindParam(6, $searchKey);
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
		$query = "SELECT  xxxx.sta_nome, w.usut_nome, t.* FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  xxxx.sta_nome, w.usut_nome, t.* FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  WHERE t.usu_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->usu_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->usu_id = $row['usu_id'];
$this->usu_nome = $row['usu_nome'];
$this->usu_email = $row['usu_email'];
$this->usu_password = $row['usu_password'];
$this->usu_reset_token = $row['usu_reset_token'];
$this->sta_id = $row['sta_id'];
$this->sta_nome = $row['sta_nome'];
$this->usut_id = $row['usut_id'];
$this->usut_nome = $row['usut_nome'];
		}
		else{
			$this->usu_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET usu_nome=:usu_nome,usu_email=:usu_email,usu_password=:usu_password,usu_reset_token=:usu_reset_token,sta_id=:sta_id,usut_id=:usut_id";
		$stmt = $this->conn->prepare($query);
		
$this->usu_nome=htmlspecialchars(strip_tags($this->usu_nome));
$this->usu_email=htmlspecialchars(strip_tags($this->usu_email));
$this->usu_password=htmlspecialchars(strip_tags($this->usu_password));
$this->usu_reset_token=htmlspecialchars(strip_tags($this->usu_reset_token));
$this->sta_id=htmlspecialchars(strip_tags($this->sta_id));
$this->usut_id=htmlspecialchars(strip_tags($this->usut_id));
		
$stmt->bindParam(":usu_nome", $this->usu_nome);
$stmt->bindParam(":usu_email", $this->usu_email);
$stmt->bindParam(":usu_password", $this->usu_password);
$stmt->bindParam(":usu_reset_token", $this->usu_reset_token);
$stmt->bindParam(":sta_id", $this->sta_id);
$stmt->bindParam(":usut_id", $this->usut_id);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->usu_id!=null){
				$this->readOne();
				if($this->usu_id!=null){
					$lastInsertedId=$this->usu_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET usu_nome=:usu_nome,usu_email=:usu_email,usu_password=:usu_password,usu_reset_token=:usu_reset_token,sta_id=:sta_id,usut_id=:usut_id WHERE usu_id = :usu_id";
		$stmt = $this->conn->prepare($query);
		
$this->usu_nome=htmlspecialchars(strip_tags($this->usu_nome));
$this->usu_email=htmlspecialchars(strip_tags($this->usu_email));
$this->usu_password=htmlspecialchars(strip_tags($this->usu_password));
$this->usu_reset_token=htmlspecialchars(strip_tags($this->usu_reset_token));
$this->sta_id=htmlspecialchars(strip_tags($this->sta_id));
$this->usut_id=htmlspecialchars(strip_tags($this->usut_id));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
		
$stmt->bindParam(":usu_nome", $this->usu_nome);
$stmt->bindParam(":usu_email", $this->usu_email);
$stmt->bindParam(":usu_password", $this->usu_password);
$stmt->bindParam(":usu_reset_token", $this->usu_reset_token);
$stmt->bindParam(":sta_id", $this->sta_id);
$stmt->bindParam(":usut_id", $this->usut_id);
$stmt->bindParam(":usu_id", $this->usu_id);
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
				if($columnName!='usu_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE usu_id = :usu_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='usu_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":usu_id", $this->usu_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE usu_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));

		$stmt->bindParam(1, $this->usu_id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
function readBysta_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  xxxx.sta_nome, w.usut_nome, t.* FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  WHERE t.sta_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->sta_id);

$stmt->execute();
return $stmt;
}

function readByusut_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  xxxx.sta_nome, w.usut_nome, t.* FROM ". $this->table_name ." t  left join status xxxx on t.sta_id = xxxx.sta_id  left join usuario_tipo w on t.usut_id = w.usut_id  WHERE t.usut_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usut_id);

$stmt->execute();
return $stmt;
}

}
?>
