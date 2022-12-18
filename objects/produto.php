<?php
class Produto{
 
    private $conn;
    private $table_name = "produto";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $prod_id;
public $prod_nome;
public $prod_preco;
public $cat_id;
public $uni_id;
public $mar_id;
public $itep_id;
public $pro_url_img;
public $cat_nome;
public $uni_sigla;
public $mar_nome;
public $ped_id;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE LOWER(t.prod_nome) LIKE ? OR LOWER(t.prod_preco) LIKE ?  OR LOWER(t.cat_id) LIKE ?  OR LOWER(t.uni_id) LIKE ?  OR LOWER(t.mar_id) LIKE ?  OR LOWER(t.itep_id) LIKE ?  OR LOWER(t.pro_url_img) LIKE ? ";
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE ".$where."";
		
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
		$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE LOWER(t.prod_nome) LIKE ? OR LOWER(t.prod_preco) LIKE ?  OR LOWER(t.cat_id) LIKE ?  OR LOWER(t.uni_id) LIKE ?  OR LOWER(t.mar_id) LIKE ?  OR LOWER(t.itep_id) LIKE ?  OR LOWER(t.pro_url_img) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
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
		$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE t.prod_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->prod_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->prod_id = $row['prod_id'];
$this->prod_nome = $row['prod_nome'];
$this->prod_preco = $row['prod_preco'];
$this->cat_id = $row['cat_id'];
$this->cat_nome = $row['cat_nome'];
$this->uni_id = $row['uni_id'];
$this->uni_sigla = $row['uni_sigla'];
$this->mar_id = $row['mar_id'];
$this->mar_nome = $row['mar_nome'];
$this->itep_id = $row['itep_id'];
$this->ped_id = $row['ped_id'];
$this->pro_url_img = $row['pro_url_img'];
		}
		else{
			$this->prod_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET prod_nome=:prod_nome,prod_preco=:prod_preco,cat_id=:cat_id,uni_id=:uni_id,mar_id=:mar_id,itep_id=:itep_id,pro_url_img=:pro_url_img";
		$stmt = $this->conn->prepare($query);
		
$this->prod_nome=htmlspecialchars(strip_tags($this->prod_nome));
$this->prod_preco=htmlspecialchars(strip_tags($this->prod_preco));
$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
$this->uni_id=htmlspecialchars(strip_tags($this->uni_id));
$this->mar_id=htmlspecialchars(strip_tags($this->mar_id));
$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));
$this->pro_url_img=htmlspecialchars(strip_tags($this->pro_url_img));
		
$stmt->bindParam(":prod_nome", $this->prod_nome);
$stmt->bindParam(":prod_preco", $this->prod_preco);
$stmt->bindParam(":cat_id", $this->cat_id);
$stmt->bindParam(":uni_id", $this->uni_id);
$stmt->bindParam(":mar_id", $this->mar_id);
$stmt->bindParam(":itep_id", $this->itep_id);
$stmt->bindParam(":pro_url_img", $this->pro_url_img);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->prod_id!=null){
				$this->readOne();
				if($this->prod_id!=null){
					$lastInsertedId=$this->prod_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET prod_nome=:prod_nome,prod_preco=:prod_preco,cat_id=:cat_id,uni_id=:uni_id,mar_id=:mar_id,itep_id=:itep_id,pro_url_img=:pro_url_img WHERE prod_id = :prod_id";
		$stmt = $this->conn->prepare($query);
		
$this->prod_nome=htmlspecialchars(strip_tags($this->prod_nome));
$this->prod_preco=htmlspecialchars(strip_tags($this->prod_preco));
$this->cat_id=htmlspecialchars(strip_tags($this->cat_id));
$this->uni_id=htmlspecialchars(strip_tags($this->uni_id));
$this->mar_id=htmlspecialchars(strip_tags($this->mar_id));
$this->itep_id=htmlspecialchars(strip_tags($this->itep_id));
$this->pro_url_img=htmlspecialchars(strip_tags($this->pro_url_img));
$this->prod_id=htmlspecialchars(strip_tags($this->prod_id));
		
$stmt->bindParam(":prod_nome", $this->prod_nome);
$stmt->bindParam(":prod_preco", $this->prod_preco);
$stmt->bindParam(":cat_id", $this->cat_id);
$stmt->bindParam(":uni_id", $this->uni_id);
$stmt->bindParam(":mar_id", $this->mar_id);
$stmt->bindParam(":itep_id", $this->itep_id);
$stmt->bindParam(":pro_url_img", $this->pro_url_img);
$stmt->bindParam(":prod_id", $this->prod_id);
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
				if($columnName!='prod_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE prod_id = :prod_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='prod_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":prod_id", $this->prod_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE prod_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->prod_id=htmlspecialchars(strip_tags($this->prod_id));

		$stmt->bindParam(1, $this->prod_id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
function readBycat_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE t.cat_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->cat_id);

$stmt->execute();
return $stmt;
}

function readByuni_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE t.uni_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->uni_id);

$stmt->execute();
return $stmt;
}

function readBymar_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE t.mar_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->mar_id);

$stmt->execute();
return $stmt;
}

function readByitep_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  eeee.cat_nome, jjjj.uni_sigla, jj.mar_nome, r.ped_id, t.* FROM ". $this->table_name ." t  left join categorias eeee on t.cat_id = eeee.cat_id  left join unidade_medida jjjj on t.uni_id = jjjj.uni_id  left join marca jj on t.mar_id = jj.mar_id  left join itens_pedido r on t.itep_id = r.itep_id  WHERE t.itep_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->itep_id);

$stmt->execute();
return $stmt;
}

}
?>
