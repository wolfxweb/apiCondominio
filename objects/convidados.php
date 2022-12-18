<?php
class Convidados{
 
    private $conn;
    private $table_name = "convidados";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $id;
public $conv_nome;
public $conv_cpf;
public $conv_rg;
public $conv_telefone;
public $conv_status;
public $conv_data;
public $conv_obs;
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  WHERE LOWER(t.conv_nome) LIKE ? OR LOWER(t.conv_cpf) LIKE ?  OR LOWER(t.conv_rg) LIKE ?  OR LOWER(t.conv_telefone) LIKE ?  OR LOWER(t.conv_status) LIKE ?  OR LOWER(t.conv_data) LIKE ?  OR LOWER(t.conv_obs) LIKE ?  OR LOWER(t.unid_id) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ? ";
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  WHERE ".$where."";
		
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
		$query = "SELECT  ll.unid_name, z.cond_nome, t.* FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  ll.unid_name, z.cond_nome, t.* FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  WHERE LOWER(t.conv_nome) LIKE ? OR LOWER(t.conv_cpf) LIKE ?  OR LOWER(t.conv_rg) LIKE ?  OR LOWER(t.conv_telefone) LIKE ?  OR LOWER(t.conv_status) LIKE ?  OR LOWER(t.conv_data) LIKE ?  OR LOWER(t.conv_obs) LIKE ?  OR LOWER(t.unid_id) LIKE ?  OR LOWER(t.cond_id) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
		$query = "SELECT  ll.unid_name, z.cond_nome, t.* FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  ll.unid_name, z.cond_nome, t.* FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  WHERE t.id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->id = $row['id'];
$this->conv_nome = $row['conv_nome'];
$this->conv_cpf = $row['conv_cpf'];
$this->conv_rg = $row['conv_rg'];
$this->conv_telefone = $row['conv_telefone'];
$this->conv_status = $row['conv_status'];
$this->conv_data = $row['conv_data'];
$this->conv_obs = $row['conv_obs'];
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
		$query ="INSERT INTO ".$this->table_name." SET conv_nome=:conv_nome,conv_cpf=:conv_cpf,conv_rg=:conv_rg,conv_telefone=:conv_telefone,conv_status=:conv_status,conv_data=:conv_data,conv_obs=:conv_obs,unid_id=:unid_id,cond_id=:cond_id,created_at=:created_at,updated_at=:updated_at";
		$stmt = $this->conn->prepare($query);
		
$this->conv_nome=htmlspecialchars(strip_tags($this->conv_nome));
$this->conv_cpf=htmlspecialchars(strip_tags($this->conv_cpf));
$this->conv_rg=htmlspecialchars(strip_tags($this->conv_rg));
$this->conv_telefone=htmlspecialchars(strip_tags($this->conv_telefone));
$this->conv_status=htmlspecialchars(strip_tags($this->conv_status));
$this->conv_data=htmlspecialchars(strip_tags($this->conv_data));
$this->conv_obs=htmlspecialchars(strip_tags($this->conv_obs));
$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
		
$stmt->bindParam(":conv_nome", $this->conv_nome);
$stmt->bindParam(":conv_cpf", $this->conv_cpf);
$stmt->bindParam(":conv_rg", $this->conv_rg);
$stmt->bindParam(":conv_telefone", $this->conv_telefone);
$stmt->bindParam(":conv_status", $this->conv_status);
$stmt->bindParam(":conv_data", $this->conv_data);
$stmt->bindParam(":conv_obs", $this->conv_obs);
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
		$query ="UPDATE ".$this->table_name." SET conv_nome=:conv_nome,conv_cpf=:conv_cpf,conv_rg=:conv_rg,conv_telefone=:conv_telefone,conv_status=:conv_status,conv_data=:conv_data,conv_obs=:conv_obs,unid_id=:unid_id,cond_id=:cond_id,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
		$stmt = $this->conn->prepare($query);
		
$this->conv_nome=htmlspecialchars(strip_tags($this->conv_nome));
$this->conv_cpf=htmlspecialchars(strip_tags($this->conv_cpf));
$this->conv_rg=htmlspecialchars(strip_tags($this->conv_rg));
$this->conv_telefone=htmlspecialchars(strip_tags($this->conv_telefone));
$this->conv_status=htmlspecialchars(strip_tags($this->conv_status));
$this->conv_data=htmlspecialchars(strip_tags($this->conv_data));
$this->conv_obs=htmlspecialchars(strip_tags($this->conv_obs));
$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));
$this->cond_id=htmlspecialchars(strip_tags($this->cond_id));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
		
$stmt->bindParam(":conv_nome", $this->conv_nome);
$stmt->bindParam(":conv_cpf", $this->conv_cpf);
$stmt->bindParam(":conv_rg", $this->conv_rg);
$stmt->bindParam(":conv_telefone", $this->conv_telefone);
$stmt->bindParam(":conv_status", $this->conv_status);
$stmt->bindParam(":conv_data", $this->conv_data);
$stmt->bindParam(":conv_obs", $this->conv_obs);
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
$query = "SELECT  ll.unid_name, z.cond_nome, t.* FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  WHERE t.unid_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

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
$query = "SELECT  ll.unid_name, z.cond_nome, t.* FROM ". $this->table_name ." t  join unidades ll on t.unid_id = ll.id  join condominios z on t.cond_id = z.id  WHERE t.cond_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->cond_id);

$stmt->execute();
return $stmt;
}

}
?>
