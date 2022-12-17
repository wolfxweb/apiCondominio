<?php
class Condominios{
 
    private $conn;
    private $table_name = "condominios";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $id;
public $cond_nome;
public $cond_rua;
public $cond_barirro;
public $cond_cidade;
public $cond_estado;
public $cond_cep;
public $cond_numero;
public $cond_status;
public $plan_id;
public $cond_slug;
public $cond_obs;
public $created_at;
public $updated_at;
public $plan_nome;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join planos q on t.plan_id = q.id  WHERE LOWER(t.cond_nome) LIKE ? OR LOWER(t.cond_rua) LIKE ?  OR LOWER(t.cond_barirro) LIKE ?  OR LOWER(t.cond_cidade) LIKE ?  OR LOWER(t.cond_estado) LIKE ?  OR LOWER(t.cond_cep) LIKE ?  OR LOWER(t.cond_numero) LIKE ?  OR LOWER(t.cond_status) LIKE ?  OR LOWER(t.plan_id) LIKE ?  OR LOWER(t.cond_slug) LIKE ?  OR LOWER(t.cond_obs) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ? ";
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
$stmt->bindParam(13, $searchKey);
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  join planos q on t.plan_id = q.id  WHERE ".$where."";
		
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
		$query = "SELECT  q.plan_nome, t.* FROM ". $this->table_name ." t  join planos q on t.plan_id = q.id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  q.plan_nome, t.* FROM ". $this->table_name ." t  join planos q on t.plan_id = q.id  WHERE LOWER(t.cond_nome) LIKE ? OR LOWER(t.cond_rua) LIKE ?  OR LOWER(t.cond_barirro) LIKE ?  OR LOWER(t.cond_cidade) LIKE ?  OR LOWER(t.cond_estado) LIKE ?  OR LOWER(t.cond_cep) LIKE ?  OR LOWER(t.cond_numero) LIKE ?  OR LOWER(t.cond_status) LIKE ?  OR LOWER(t.plan_id) LIKE ?  OR LOWER(t.cond_slug) LIKE ?  OR LOWER(t.cond_obs) LIKE ?  OR LOWER(t.created_at) LIKE ?  OR LOWER(t.updated_at) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
$stmt->bindParam(13, $searchKey);
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
		$query = "SELECT  q.plan_nome, t.* FROM ". $this->table_name ." t  join planos q on t.plan_id = q.id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  q.plan_nome, t.* FROM ". $this->table_name ." t  join planos q on t.plan_id = q.id  WHERE t.id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->id = $row['id'];
$this->cond_nome = $row['cond_nome'];
$this->cond_rua = $row['cond_rua'];
$this->cond_barirro = $row['cond_barirro'];
$this->cond_cidade = $row['cond_cidade'];
$this->cond_estado = $row['cond_estado'];
$this->cond_cep = $row['cond_cep'];
$this->cond_numero = $row['cond_numero'];
$this->cond_status = $row['cond_status'];
$this->plan_id = $row['plan_id'];
$this->plan_nome = $row['plan_nome'];
$this->cond_slug = $row['cond_slug'];
$this->cond_obs = $row['cond_obs'];
$this->created_at = $row['created_at'];
$this->updated_at = $row['updated_at'];
		}
		else{
			$this->id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET cond_nome=:cond_nome,cond_rua=:cond_rua,cond_barirro=:cond_barirro,cond_cidade=:cond_cidade,cond_estado=:cond_estado,cond_cep=:cond_cep,cond_numero=:cond_numero,cond_status=:cond_status,plan_id=:plan_id,cond_slug=:cond_slug,cond_obs=:cond_obs,created_at=:created_at,updated_at=:updated_at";
		$stmt = $this->conn->prepare($query);
		
$this->cond_nome=htmlspecialchars(strip_tags($this->cond_nome));
$this->cond_rua=htmlspecialchars(strip_tags($this->cond_rua));
$this->cond_barirro=htmlspecialchars(strip_tags($this->cond_barirro));
$this->cond_cidade=htmlspecialchars(strip_tags($this->cond_cidade));
$this->cond_estado=htmlspecialchars(strip_tags($this->cond_estado));
$this->cond_cep=htmlspecialchars(strip_tags($this->cond_cep));
$this->cond_numero=htmlspecialchars(strip_tags($this->cond_numero));
$this->cond_status=htmlspecialchars(strip_tags($this->cond_status));
$this->plan_id=htmlspecialchars(strip_tags($this->plan_id));
$this->cond_slug=htmlspecialchars(strip_tags($this->cond_slug));
$this->cond_obs=htmlspecialchars(strip_tags($this->cond_obs));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
		
$stmt->bindParam(":cond_nome", $this->cond_nome);
$stmt->bindParam(":cond_rua", $this->cond_rua);
$stmt->bindParam(":cond_barirro", $this->cond_barirro);
$stmt->bindParam(":cond_cidade", $this->cond_cidade);
$stmt->bindParam(":cond_estado", $this->cond_estado);
$stmt->bindParam(":cond_cep", $this->cond_cep);
$stmt->bindParam(":cond_numero", $this->cond_numero);
$stmt->bindParam(":cond_status", $this->cond_status);
$stmt->bindParam(":plan_id", $this->plan_id);
$stmt->bindParam(":cond_slug", $this->cond_slug);
$stmt->bindParam(":cond_obs", $this->cond_obs);
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
		$query ="UPDATE ".$this->table_name." SET cond_nome=:cond_nome,cond_rua=:cond_rua,cond_barirro=:cond_barirro,cond_cidade=:cond_cidade,cond_estado=:cond_estado,cond_cep=:cond_cep,cond_numero=:cond_numero,cond_status=:cond_status,plan_id=:plan_id,cond_slug=:cond_slug,cond_obs=:cond_obs,created_at=:created_at,updated_at=:updated_at WHERE id = :id";
		$stmt = $this->conn->prepare($query);
		
$this->cond_nome=htmlspecialchars(strip_tags($this->cond_nome));
$this->cond_rua=htmlspecialchars(strip_tags($this->cond_rua));
$this->cond_barirro=htmlspecialchars(strip_tags($this->cond_barirro));
$this->cond_cidade=htmlspecialchars(strip_tags($this->cond_cidade));
$this->cond_estado=htmlspecialchars(strip_tags($this->cond_estado));
$this->cond_cep=htmlspecialchars(strip_tags($this->cond_cep));
$this->cond_numero=htmlspecialchars(strip_tags($this->cond_numero));
$this->cond_status=htmlspecialchars(strip_tags($this->cond_status));
$this->plan_id=htmlspecialchars(strip_tags($this->plan_id));
$this->cond_slug=htmlspecialchars(strip_tags($this->cond_slug));
$this->cond_obs=htmlspecialchars(strip_tags($this->cond_obs));
$this->created_at=htmlspecialchars(strip_tags($this->created_at));
$this->updated_at=htmlspecialchars(strip_tags($this->updated_at));
$this->id=htmlspecialchars(strip_tags($this->id));
		
$stmt->bindParam(":cond_nome", $this->cond_nome);
$stmt->bindParam(":cond_rua", $this->cond_rua);
$stmt->bindParam(":cond_barirro", $this->cond_barirro);
$stmt->bindParam(":cond_cidade", $this->cond_cidade);
$stmt->bindParam(":cond_estado", $this->cond_estado);
$stmt->bindParam(":cond_cep", $this->cond_cep);
$stmt->bindParam(":cond_numero", $this->cond_numero);
$stmt->bindParam(":cond_status", $this->cond_status);
$stmt->bindParam(":plan_id", $this->plan_id);
$stmt->bindParam(":cond_slug", $this->cond_slug);
$stmt->bindParam(":cond_obs", $this->cond_obs);
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

	
function readByplan_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  q.plan_nome, t.* FROM ". $this->table_name ." t  join planos q on t.plan_id = q.id  WHERE t.plan_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->plan_id);

$stmt->execute();
return $stmt;
}

}
?>
