<?php
class Pedido{
 
    private $conn;
    private $table_name = "pedido";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $ped_id;
public $ped_nome;
public $ped_public;
public $ped_slug;
public $ped_data_finalizacao;
public $ped_data_abetura;
public $sta_id;
public $loj_id;
public $usu_id;
public $itep_id;
public $sta_name;
public $nome;
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE LOWER(t.ped_nome) LIKE ? OR LOWER(t.ped_public) LIKE ?  OR LOWER(t.ped_slug) LIKE ?  OR LOWER(t.ped_data_finalizacao) LIKE ?  OR LOWER(t.ped_data_abetura) LIKE ?  OR LOWER(t.sta_id) LIKE ?  OR LOWER(t.loj_id) LIKE ?  OR LOWER(t.usu_id) LIKE ?  OR LOWER(t.itep_id) LIKE ? ";
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE ".$where."";
		
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
		$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE LOWER(t.ped_nome) LIKE ? OR LOWER(t.ped_public) LIKE ?  OR LOWER(t.ped_slug) LIKE ?  OR LOWER(t.ped_data_finalizacao) LIKE ?  OR LOWER(t.ped_data_abetura) LIKE ?  OR LOWER(t.sta_id) LIKE ?  OR LOWER(t.loj_id) LIKE ?  OR LOWER(t.usu_id) LIKE ?  OR LOWER(t.itep_id) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
		$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE t.ped_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->ped_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->ped_id = $row['ped_id'];
$this->ped_nome = $row['ped_nome'];
$this->ped_public = $row['ped_public'];
$this->ped_slug = $row['ped_slug'];
$this->ped_data_finalizacao = $row['ped_data_finalizacao'];
$this->ped_data_abetura = $row['ped_data_abetura'];
$this->sta_id = $row['sta_id'];
$this->sta_name = $row['sta_name'];
$this->loj_id = $row['loj_id'];
$this->nome = $row['nome'];
$this->usu_id = $row['usu_id'];
$this->usu_email = $row['usu_email'];
$this->itep_id = $row['itep_id'];
$this->ped_id = $row['ped_id'];
		}
		else{
			$this->ped_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET ped_nome=:ped_nome,ped_public=:ped_public,ped_slug=:ped_slug,ped_data_finalizacao=:ped_data_finalizacao,ped_data_abetura=:ped_data_abetura,sta_id=:sta_id,loj_id=:loj_id,usu_id=:usu_id,itep_id=:itep_id";
		$stmt = $this->conn->prepare($query);
		
$this->ped_nome=htmlspecialchars(strip_tags($this->ped_nome));
$this->ped_public=htmlspecialchars(strip_tags($this->ped_public));
$this->ped_slug=htmlspecialchars(strip_tags($this->ped_slug));
$this->ped_data_finalizacao=htmlspecialchars(strip_tags($this->ped_data_finalizacao));
$this->ped_data_abetura=htmlspecialchars(strip_tags($this->ped_data_abetura));
$this->sta_id=htmlspecialchars(strip_tags($this->sta_id));
$this->loj_id=htmlspecialchars(strip_tags($this->loj_id));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));
		
$stmt->bindParam(":ped_nome", $this->ped_nome);
$stmt->bindParam(":ped_public", $this->ped_public);
$stmt->bindParam(":ped_slug", $this->ped_slug);
$stmt->bindParam(":ped_data_finalizacao", $this->ped_data_finalizacao);
$stmt->bindParam(":ped_data_abetura", $this->ped_data_abetura);
$stmt->bindParam(":sta_id", $this->sta_id);
$stmt->bindParam(":loj_id", $this->loj_id);
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":itep_id", $this->itep_id);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->ped_id!=null){
				$this->readOne();
				if($this->ped_id!=null){
					$lastInsertedId=$this->ped_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET ped_nome=:ped_nome,ped_public=:ped_public,ped_slug=:ped_slug,ped_data_finalizacao=:ped_data_finalizacao,ped_data_abetura=:ped_data_abetura,sta_id=:sta_id,loj_id=:loj_id,usu_id=:usu_id,itep_id=:itep_id WHERE ped_id = :ped_id";
		$stmt = $this->conn->prepare($query);
		
$this->ped_nome=htmlspecialchars(strip_tags($this->ped_nome));
$this->ped_public=htmlspecialchars(strip_tags($this->ped_public));
$this->ped_slug=htmlspecialchars(strip_tags($this->ped_slug));
$this->ped_data_finalizacao=htmlspecialchars(strip_tags($this->ped_data_finalizacao));
$this->ped_data_abetura=htmlspecialchars(strip_tags($this->ped_data_abetura));
$this->sta_id=htmlspecialchars(strip_tags($this->sta_id));
$this->loj_id=htmlspecialchars(strip_tags($this->loj_id));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));
$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));
		
$stmt->bindParam(":ped_nome", $this->ped_nome);
$stmt->bindParam(":ped_public", $this->ped_public);
$stmt->bindParam(":ped_slug", $this->ped_slug);
$stmt->bindParam(":ped_data_finalizacao", $this->ped_data_finalizacao);
$stmt->bindParam(":ped_data_abetura", $this->ped_data_abetura);
$stmt->bindParam(":sta_id", $this->sta_id);
$stmt->bindParam(":loj_id", $this->loj_id);
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":itep_id", $this->itep_id);
$stmt->bindParam(":ped_id", $this->ped_id);
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
				if($columnName!='ped_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE ped_id = :ped_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='ped_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":ped_id", $this->ped_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE ped_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));

		$stmt->bindParam(1, $this->ped_id);

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
$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE t.sta_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->sta_id);

$stmt->execute();
return $stmt;
}

function readByloj_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE t.loj_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->loj_id);

$stmt->execute();
return $stmt;
}

function readByusu_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE t.usu_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usu_id);

$stmt->execute();
return $stmt;
}

function readByitep_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  zzzz.sta_name, yyyy.nome, nnnn.usu_email, uuuu.ped_id, t.* FROM ". $this->table_name ." t  left join status zzzz on t.sta_id = zzzz.sta_id  left join loja yyyy on t.loj_id = yyyy.loj_id  left join usuarios nnnn on t.usu_id = nnnn.usu_id  left join itens_pedido uuuu on t.itep_id = uuuu.itep_id  WHERE t.itep_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->itep_id);

$stmt->execute();
return $stmt;
}

}
?>
