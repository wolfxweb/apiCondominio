<?php
class Usuarios{
 
    private $conn;
    private $table_name = "usuarios";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $usu_id;
public $usu_name;
public $usu_email;
public $usu_password;
public $usu_token_recuperar_senha;
public $usut_id;
public $usun_id;
public $usus_id;
public $usut_nome;
public $usun_nome;
public $usus_nome;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE LOWER(t.usu_name) LIKE ? OR LOWER(t.usu_email) LIKE ?  OR LOWER(t.usu_password) LIKE ?  OR LOWER(t.usu_token_recuperar_senha) LIKE ?  OR LOWER(t.usut_id) LIKE ?  OR LOWER(t.usun_id) LIKE ?  OR LOWER(t.usus_id) LIKE ? ";
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE ".$where."";
		
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
		$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE LOWER(t.usu_name) LIKE ? OR LOWER(t.usu_email) LIKE ?  OR LOWER(t.usu_password) LIKE ?  OR LOWER(t.usu_token_recuperar_senha) LIKE ?  OR LOWER(t.usut_id) LIKE ?  OR LOWER(t.usun_id) LIKE ?  OR LOWER(t.usus_id) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
		$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
	
	function login_validation(){ 
$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE t.usu_email = ? AND t.usu_password=? LIMIT 0,1";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(1, $this->usu_email);
$stmt->bindParam(2, $this->usu_password);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$num = $stmt->rowCount();
if($num>0){

$this->usu_id = $row['usu_id'];
$this->usu_name = $row['usu_name'];
$this->usu_email = $row['usu_email'];
$this->usu_password = $row['usu_password'];
$this->usu_token_recuperar_senha = $row['usu_token_recuperar_senha'];
$this->usut_id = $row['usut_id'];
$this->usut_nome = $row['usut_nome'];
$this->usun_id = $row['usun_id'];
$this->usun_nome = $row['usun_nome'];
$this->usus_id = $row['usus_id'];
$this->usus_nome = $row['usus_nome'];
}
else{
$this->usu_id=null;
}
}


	function readOne(){
		$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE t.usu_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->usu_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->usu_id = $row['usu_id'];
$this->usu_name = $row['usu_name'];
$this->usu_email = $row['usu_email'];
$this->usu_password = $row['usu_password'];
$this->usu_token_recuperar_senha = $row['usu_token_recuperar_senha'];
$this->usut_id = $row['usut_id'];
$this->usut_nome = $row['usut_nome'];
$this->usun_id = $row['usun_id'];
$this->usun_nome = $row['usun_nome'];
$this->usus_id = $row['usus_id'];
$this->usus_nome = $row['usus_nome'];
		}
		else{
			$this->usu_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET usu_id=:usu_id,usu_name=:usu_name,usu_email=:usu_email,usu_password=:usu_password,usu_token_recuperar_senha=:usu_token_recuperar_senha,usut_id=:usut_id,usun_id=:usun_id,usus_id=:usus_id";
		$stmt = $this->conn->prepare($query);
		
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->usu_name=htmlspecialchars(strip_tags($this->usu_name));
$this->usu_email=htmlspecialchars(strip_tags($this->usu_email));
$this->usu_password=htmlspecialchars(strip_tags($this->usu_password));
$this->usu_token_recuperar_senha=htmlspecialchars(strip_tags($this->usu_token_recuperar_senha));
$this->usut_id=htmlspecialchars(strip_tags($this->usut_id));
$this->usun_id=htmlspecialchars(strip_tags($this->usun_id));
$this->usus_id=htmlspecialchars(strip_tags($this->usus_id));
		
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":usu_name", $this->usu_name);
$stmt->bindParam(":usu_email", $this->usu_email);
$stmt->bindParam(":usu_password", $this->usu_password);
$stmt->bindParam(":usu_token_recuperar_senha", $this->usu_token_recuperar_senha);
$stmt->bindParam(":usut_id", $this->usut_id);
$stmt->bindParam(":usun_id", $this->usun_id);
$stmt->bindParam(":usus_id", $this->usus_id);
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
		$query ="UPDATE ".$this->table_name." SET usu_id=:usu_id,usu_name=:usu_name,usu_email=:usu_email,usu_password=:usu_password,usu_token_recuperar_senha=:usu_token_recuperar_senha,usut_id=:usut_id,usun_id=:usun_id,usus_id=:usus_id WHERE usu_id = :usu_id";
		$stmt = $this->conn->prepare($query);
		
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->usu_name=htmlspecialchars(strip_tags($this->usu_name));
$this->usu_email=htmlspecialchars(strip_tags($this->usu_email));
$this->usu_password=htmlspecialchars(strip_tags($this->usu_password));
$this->usu_token_recuperar_senha=htmlspecialchars(strip_tags($this->usu_token_recuperar_senha));
$this->usut_id=htmlspecialchars(strip_tags($this->usut_id));
$this->usun_id=htmlspecialchars(strip_tags($this->usun_id));
$this->usus_id=htmlspecialchars(strip_tags($this->usus_id));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
		
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":usu_name", $this->usu_name);
$stmt->bindParam(":usu_email", $this->usu_email);
$stmt->bindParam(":usu_password", $this->usu_password);
$stmt->bindParam(":usu_token_recuperar_senha", $this->usu_token_recuperar_senha);
$stmt->bindParam(":usut_id", $this->usut_id);
$stmt->bindParam(":usun_id", $this->usun_id);
$stmt->bindParam(":usus_id", $this->usus_id);
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

	
function readByusut_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE t.usut_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usut_id);

$stmt->execute();
return $stmt;
}

function readByusun_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE t.usun_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usun_id);

$stmt->execute();
return $stmt;
}

function readByusus_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  p.usut_nome, lll.usun_nome, www.usus_nome, t.* FROM ". $this->table_name ." t  left join usuario_tipo p on t.usut_id = p.usut_id  left join usuario_nivel_acesso lll on t.usun_id = lll.usun_id  left join usuario_status www on t.usus_id = www.usus_id  WHERE t.usus_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usus_id);

$stmt->execute();
return $stmt;
}

}
?>
