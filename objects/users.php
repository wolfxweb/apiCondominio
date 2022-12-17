<?php
class Users{
 
    private $conn;
    private $table_name = "users";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $id;
public $user_name;
public $user_email;
public $user_password;
public $user_cpf;
public $user_rg;
public $user_foto;
public $nive_id;
public $user_token;
public $cond_id;
public $remember_token;
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join condominios mmmm on t.cond_id = mmmm.id  WHERE LOWER(t.user_name) LIKE ? OR LOWER(t.user_email) LIKE ?  OR LOWER(t.user_password) LIKE ?  OR LOWER(t.user_cpf) LIKE ?  OR LOWER(t.user_rg) LIKE ?  OR LOWER(t.user_foto) LIKE ?  OR LOWER(t.nive_id) LIKE ?  OR LOWER(t.user_token) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.remember_token) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ? ";
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
$stmt->bindParam(11, $searchKey);
$stmt->bindParam(12, $searchKey);
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join condominios mmmm on t.cond_id = mmmm.id  WHERE ".$where."";
		
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
		$query = "SELECT  mmmm.cond_nome, t.* FROM ". $this->table_name ." t  join condominios mmmm on t.cond_id = mmmm.id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  mmmm.cond_nome, t.* FROM ". $this->table_name ." t  join condominios mmmm on t.cond_id = mmmm.id  WHERE LOWER(t.user_name) LIKE ? OR LOWER(t.user_email) LIKE ?  OR LOWER(t.user_password) LIKE ?  OR LOWER(t.user_cpf) LIKE ?  OR LOWER(t.user_rg) LIKE ?  OR LOWER(t.user_foto) LIKE ?  OR LOWER(t.nive_id) LIKE ?  OR LOWER(t.user_token) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.remember_token) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
$stmt->bindParam(11, $searchKey);
$stmt->bindParam(12, $searchKey);
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
		$query = "SELECT  mmmm.cond_nome, t.* FROM ". $this->table_name ." t  join condominios mmmm on t.cond_id = mmmm.id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  mmmm.cond_nome, t.* FROM ". $this->table_name ." t  join condominios mmmm on t.cond_id = mmmm.id  WHERE t.id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->id = $row['id'];
$this->user_name = $row['user_name'];
$this->user_email = $row['user_email'];
$this->user_password = $row['user_password'];
$this->user_cpf = $row['user_cpf'];
$this->user_rg = $row['user_rg'];
$this->user_foto = $row['user_foto'];
$this->nive_id = $row['nive_id'];
$this->user_token = $row['user_token'];
$this->cond_id = $row['cond_id'];
$this->cond_nome = $row['cond_nome'];
$this->remember_token = $row['remember_token'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
			$this->id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET user_name=:user_name,user_email=:user_email,user_password=:user_password,user_cpf=:user_cpf,user_rg=:user_rg,user_foto=:user_foto,nive_id=:nive_id,user_token=:user_token,cond_id=:cond_id,remember_token=:remember_token,created_at=:created_at,updated_at=:updated_at";
		$stmt = $this->conn->prepare($query);
		
$this->user_name=htmlspecialchars(strip_tags($this->user_name));
$this->user_email=htmlspecialchars(strip_tags($this->user_email));
$this->user_password=htmlspecialchars(strip_tags($this->user_password));
$this->user_cpf=htmlspecialchars(strip_tags($this->user_cpf));
$this->user_rg=htmlspecialchars(strip_tags($this->user_rg));
$this->user_foto=htmlspecialchars(strip_tags($this->user_foto));
$this->nive_id=htmlspecialchars(strip_tags($this->nive_id));
$this->user_token=htmlspecialchars(strip_tags($this->user_token));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->remember_token=htmlspecialchars(strip_tags($this->remember_token));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
		
$stmt->bindParam(":user_name", $this->user_name);
$stmt->bindParam(":user_email", $this->user_email);
$stmt->bindParam(":user_password", $this->user_password);
$stmt->bindParam(":user_cpf", $this->user_cpf);
$stmt->bindParam(":user_rg", $this->user_rg);
$stmt->bindParam(":user_foto", $this->user_foto);
$stmt->bindParam(":nive_id", $this->nive_id);
$stmt->bindParam(":user_token", $this->user_token);
$stmt->bindParam(":cond_id", $this->cond_id);
$stmt->bindParam(":remember_token", $this->remember_token);
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
		$query ="UPDATE ".$this->table_name." SET user_name=:user_name,user_email=:user_email,user_password=:user_password,user_cpf=:user_cpf,user_rg=:user_rg,user_foto=:user_foto,nive_id=:nive_id,user_token=:user_token,cond_id=:cond_id,remember_token=:remember_token,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
		$stmt = $this->conn->prepare($query);
		
$this->user_name=htmlspecialchars(strip_tags($this->user_name));
$this->user_email=htmlspecialchars(strip_tags($this->user_email));
$this->user_password=htmlspecialchars(strip_tags($this->user_password));
$this->user_cpf=htmlspecialchars(strip_tags($this->user_cpf));
$this->user_rg=htmlspecialchars(strip_tags($this->user_rg));
$this->user_foto=htmlspecialchars(strip_tags($this->user_foto));
$this->nive_id=htmlspecialchars(strip_tags($this->nive_id));
$this->user_token=htmlspecialchars(strip_tags($this->user_token));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->remember_token=htmlspecialchars(strip_tags($this->remember_token));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
		
$stmt->bindParam(":user_name", $this->user_name);
$stmt->bindParam(":user_email", $this->user_email);
$stmt->bindParam(":user_password", $this->user_password);
$stmt->bindParam(":user_cpf", $this->user_cpf);
$stmt->bindParam(":user_rg", $this->user_rg);
$stmt->bindParam(":user_foto", $this->user_foto);
$stmt->bindParam(":nive_id", $this->nive_id);
$stmt->bindParam(":user_token", $this->user_token);
$stmt->bindParam(":cond_id", $this->cond_id);
$stmt->bindParam(":remember_token", $this->remember_token);
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
$query = "SELECT  mmmm.cond_nome, t.* FROM ". $this->table_name ." t  join condominios mmmm on t.cond_id = mmmm.id  WHERE t.cond_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->cond_id);

$stmt->execute();
return $stmt;
}

}
?>
