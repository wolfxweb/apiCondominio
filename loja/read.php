<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/loja.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$loja = new Loja($db);

$loja->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$loja->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $loja->read();
$num = $stmt->rowCount();
if($num>0){
    $loja_arr=array();
	$loja_arr["pageno"]=$loja->pageNo;
	$loja_arr["pagesize"]=$loja->no_of_records_per_page;
    $loja_arr["total_count"]=$loja->total_record_count();
    $loja_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $loja_item=array(
            
"loj_id" => $loj_id,
"nome" => html_entity_decode($nome)
        );
         array_push($loja_arr["records"], $loja_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "loja found","document"=> $loja_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No loja found.","document"=> ""));
}
 


