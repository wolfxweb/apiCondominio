<?php
class Produto{
 
    private $conn;
    private $table_name = "produto";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $pro_id;
public $prod_nome;
public $prod_descricao;
public $prod_preco;
public $unid_id;
public $cat_id;
public $usu_id;
public $unid_slug;
public $cat_nome;
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE LOWER(t.prod_nome) LIKE ? OR LOWER(t.prod_descricao) LIKE ?  OR LOWER(t.prod_preco) LIKE ?  OR LOWER(t.unid_id) LIKE ?  OR LOWER(t.cat_id) LIKE ?  OR LOWER(t.usu_id) LIKE ? ";
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE ".$where."";
		
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
		$query = "SELECT  a.unid_slug, jjj.cat_nome, m.usu_email, t.* FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  a.unid_slug, jjj.cat_nome, m.usu_email, t.* FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE LOWER(t.prod_nome) LIKE ? OR LOWER(t.prod_descricao) LIKE ?  OR LOWER(t.prod_preco) LIKE ?  OR LOWER(t.unid_id) LIKE ?  OR LOWER(t.cat_id) LIKE ?  OR LOWER(t.usu_id) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
		$query = "SELECT  a.unid_slug, jjj.cat_nome, m.usu_email, t.* FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  a.unid_slug, jjj.cat_nome, m.usu_email, t.* FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE t.pro_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->pro_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->pro_id = $row['pro_id'];
$this->prod_nome = $row['prod_nome'];
$this->prod_descricao = $row['prod_descricao'];
$this->prod_preco = $row['prod_preco'];
$this->unid_id = $row['unid_id'];
$this->unid_slug = $row['unid_slug'];
$this->cat_id = $row['cat_id'];
$this->cat_nome = $row['cat_nome'];
$this->usu_id = $row['usu_id'];
$this->usu_email = $row['usu_email'];
		}
		else{
			$this->pro_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET prod_nome=:prod_nome,prod_descricao=:prod_descricao,prod_preco=:prod_preco,unid_id=:unid_id,cat_id=:cat_id,usu_id=:usu_id";
		$stmt = $this->conn->prepare($query);
		
$this->prod_nome=htmlspecialchars(strip_tags($this->prod_nome));
$this->prod_descricao=htmlspecialchars(strip_tags($this->prod_descricao));
$this->prod_preco=htmlspecialchars(strip_tags($this->prod_preco));
$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));
$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
		
$stmt->bindParam(":prod_nome", $this->prod_nome);
$stmt->bindParam(":prod_descricao", $this->prod_descricao);
$stmt->bindParam(":prod_preco", $this->prod_preco);
$stmt->bindParam(":unid_id", $this->unid_id);
$stmt->bindParam(":cat_id", $this->cat_id);
$stmt->bindParam(":usu_id", $this->usu_id);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->pro_id!=null){
				$this->readOne();
				if($this->pro_id!=null){
					$lastInsertedId=$this->pro_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET prod_nome=:prod_nome,prod_descricao=:prod_descricao,prod_preco=:prod_preco,unid_id=:unid_id,cat_id=:cat_id,usu_id=:usu_id WHERE pro_id = :pro_id";
		$stmt = $this->conn->prepare($query);
		
$this->prod_nome=htmlspecialchars(strip_tags($this->prod_nome));
$this->prod_descricao=htmlspecialchars(strip_tags($this->prod_descricao));
$this->prod_preco=htmlspecialchars(strip_tags($this->prod_preco));
$this->unid_id=htmlspecialchars(strip_tags($this->unid_id));
$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
$this->usu_id=htmlspecialchars(strip_tags($this->usu_id));
$this->pro_id=htmlspecialchars(strip_tags($this->pro_id));
		
$stmt->bindParam(":prod_nome", $this->prod_nome);
$stmt->bindParam(":prod_descricao", $this->prod_descricao);
$stmt->bindParam(":prod_preco", $this->prod_preco);
$stmt->bindParam(":unid_id", $this->unid_id);
$stmt->bindParam(":cat_id", $this->cat_id);
$stmt->bindParam(":usu_id", $this->usu_id);
$stmt->bindParam(":pro_id", $this->pro_id);
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
				if($columnName!='pro_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE pro_id = :pro_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='pro_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":pro_id", $this->pro_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE pro_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->pro_id=htmlspecialchars(strip_tags($this->pro_id));

		$stmt->bindParam(1, $this->pro_id);

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
$query = "SELECT  a.unid_slug, jjj.cat_nome, m.usu_email, t.* FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE t.unid_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->unid_id);

$stmt->execute();
return $stmt;
}

function readBycat_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  a.unid_slug, jjj.cat_nome, m.usu_email, t.* FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE t.cat_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->cat_id);

$stmt->execute();
return $stmt;
}

function readByusu_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  a.unid_slug, jjj.cat_nome, m.usu_email, t.* FROM ". $this->table_name ." t  left join unidade_medida a on t.unid_id = a.unid_id  left join categorias jjj on t.cat_id = jjj.cat_id  left join usuarios m on t.usu_id = m.usu_id  WHERE t.usu_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->usu_id);

$stmt->execute();
return $stmt;
}

}
?>
