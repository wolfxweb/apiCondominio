<?php
class Pedido_Item{
 
    private $conn;
    private $table_name = "pedido_item";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
public $pedi_id;
public $pro_id;
public $ped_id;
public $pedi_qtd;
public $pedi_valor_unitario;
public $pedi_valor_total;
public $prod_nome;
public $ped_titulo;
    
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  WHERE LOWER(t.pro_id) LIKE ? OR LOWER(t.ped_id) LIKE ?  OR LOWER(t.pedi_qtd) LIKE ?  OR LOWER(t.pedi_valor_unitario) LIKE ?  OR LOWER(t.pedi_valor_total) LIKE ? ";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
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
		$query = "SELECT count(1) as total FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  WHERE ".$where."";
		
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
		$query = "SELECT  j.prod_nome, gggg.ped_titulo, t.* FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function search($searchKey){
		if(isset($_GET["pageNo"])){
		$this->pageNo=$_GET["pageNo"];
		}
		$offset = ($this->pageNo-1) * $this->no_of_records_per_page; 
		$query = "SELECT  j.prod_nome, gggg.ped_titulo, t.* FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  WHERE LOWER(t.pro_id) LIKE ? OR LOWER(t.ped_id) LIKE ?  OR LOWER(t.pedi_qtd) LIKE ?  OR LOWER(t.pedi_valor_unitario) LIKE ?  OR LOWER(t.pedi_valor_total) LIKE ?  LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		$stmt = $this->conn->prepare($query);
		$searchKey="%".strtolower($searchKey)."%";
		
$stmt->bindParam(1, $searchKey);
$stmt->bindParam(2, $searchKey);
$stmt->bindParam(3, $searchKey);
$stmt->bindParam(4, $searchKey);
$stmt->bindParam(5, $searchKey);
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
		$query = "SELECT  j.prod_nome, gggg.ped_titulo, t.* FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  WHERE ".$where." LIMIT ".$offset." , ". $this->no_of_records_per_page."";
		
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
		$query = "SELECT  j.prod_nome, gggg.ped_titulo, t.* FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  WHERE t.pedi_id = ? LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(1, $this->pedi_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if($num>0){
			
$this->pedi_id = $row['pedi_id'];
$this->pro_id = $row['pro_id'];
$this->prod_nome = $row['prod_nome'];
$this->ped_id = $row['ped_id'];
$this->ped_titulo = $row['ped_titulo'];
$this->pedi_qtd = $row['pedi_qtd'];
$this->pedi_valor_unitario = $row['pedi_valor_unitario'];
$this->pedi_valor_total = $row['pedi_valor_total'];
		}
		else{
			$this->pedi_id=null;
		}
	}
	function create(){
		$query ="INSERT INTO ".$this->table_name." SET pro_id=:pro_id,ped_id=:ped_id,pedi_qtd=:pedi_qtd,pedi_valor_unitario=:pedi_valor_unitario,pedi_valor_total=:pedi_valor_total";
		$stmt = $this->conn->prepare($query);
		
$this->pro_id=htmlspecialchars(strip_tags($this->pro_id));
$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));
$this->pedi_qtd=htmlspecialchars(strip_tags($this->pedi_qtd));
$this->pedi_valor_unitario=htmlspecialchars(strip_tags($this->pedi_valor_unitario));
$this->pedi_valor_total=htmlspecialchars(strip_tags($this->pedi_valor_total));
		
$stmt->bindParam(":pro_id", $this->pro_id);
$stmt->bindParam(":ped_id", $this->ped_id);
$stmt->bindParam(":pedi_qtd", $this->pedi_qtd);
$stmt->bindParam(":pedi_valor_unitario", $this->pedi_valor_unitario);
$stmt->bindParam(":pedi_valor_total", $this->pedi_valor_total);
		$lastInsertedId=0;
		if($stmt->execute()){
			$lastInsertedId = $this->conn->lastInsertId();
			if($lastInsertedId==0 && $this->pedi_id!=null){
				$this->readOne();
				if($this->pedi_id!=null){
					$lastInsertedId=$this->pedi_id;
					}
			}
		}
	
		return $lastInsertedId;
	}
	function update(){
		$query ="UPDATE ".$this->table_name." SET pro_id=:pro_id,ped_id=:ped_id,pedi_qtd=:pedi_qtd,pedi_valor_unitario=:pedi_valor_unitario,pedi_valor_total=:pedi_valor_total WHERE pedi_id = :pedi_id";
		$stmt = $this->conn->prepare($query);
		
$this->pro_id=htmlspecialchars(strip_tags($this->pro_id));
$this->ped_id=htmlspecialchars(strip_tags($this->ped_id));
$this->pedi_qtd=htmlspecialchars(strip_tags($this->pedi_qtd));
$this->pedi_valor_unitario=htmlspecialchars(strip_tags($this->pedi_valor_unitario));
$this->pedi_valor_total=htmlspecialchars(strip_tags($this->pedi_valor_total));
$this->pedi_id=htmlspecialchars(strip_tags($this->pedi_id));
		
$stmt->bindParam(":pro_id", $this->pro_id);
$stmt->bindParam(":ped_id", $this->ped_id);
$stmt->bindParam(":pedi_qtd", $this->pedi_qtd);
$stmt->bindParam(":pedi_valor_unitario", $this->pedi_valor_unitario);
$stmt->bindParam(":pedi_valor_total", $this->pedi_valor_total);
$stmt->bindParam(":pedi_id", $this->pedi_id);
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
				if($columnName!='pedi_id'){
				if($colCount===1){
					$setValue = $columnName."=:".$columnName;
				}else{
					$setValue = $setValue . "," .$columnName."=:".$columnName;
				}
				$colCount++;
				}
			}
			$setValue = rtrim($setValue,',');
			$query = $query . " " . $setValue . " WHERE pedi_id = :pedi_id"; 
			$stmt = $this->conn->prepare($query);
			foreach($jsonObj as $key => $value) 
			{
			    $columnName=htmlspecialchars(strip_tags($key));
				if($columnName!='pedi_id'){
				$colValue=htmlspecialchars(strip_tags($value));
				$stmt->bindValue(":".$columnName, $colValue);
				}
			}
			$stmt->bindParam(":pedi_id", $this->pedi_id);
			$stmt->execute();

			if($stmt->rowCount()) {
				return true;
			} else {
				return false;
			}
	}
	function delete(){
		$query = "DELETE FROM " . $this->table_name . " WHERE pedi_id = ? ";
		$stmt = $this->conn->prepare($query);
		$this->pedi_id=htmlspecialchars(strip_tags($this->pedi_id));

		$stmt->bindParam(1, $this->pedi_id);

	 	$stmt->execute();

	 if($stmt->rowCount()) {
			return true;
		} else {
		   return false;
		}
		 
	}

	
function readBypro_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  j.prod_nome, gggg.ped_titulo, t.* FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  WHERE t.pro_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->pro_id);

$stmt->execute();
return $stmt;
}

function readByped_id(){

if (isset($_GET["pageNo"]))
{
$this->pageNo =$_GET["pageNo"]; } 
$offset = ($this->pageNo - 1) * $this->no_of_records_per_page;
$query = "SELECT  j.prod_nome, gggg.ped_titulo, t.* FROM ". $this->table_name ." t  left join produto j on t.pro_id = j.pro_id  left join pedido gggg on t.ped_id = gggg.ped_id  WHERE t.ped_id = ? LIMIT ".$offset." , ". $this->no_of_records_per_page."";

$stmt = $this->conn->prepare( $query );
$stmt->bindParam(1, $this->ped_id);

$stmt->execute();
return $stmt;
}

}
?>
