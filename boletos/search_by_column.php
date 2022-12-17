<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/boletos.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$boletos = new Boletos($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$boletos->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$boletos->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $boletos->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $boletos_arr=array();
	$boletos_arr["pageno"]=$boletos->pageNo;
	$boletos_arr["pagesize"]=$boletos->no_of_records_per_page;
    $boletos_arr["total_count"]=$boletos->search_record_count($data,$orAnd);
    $boletos_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $boletos_item=array(
            
"id" => $id,
"bole_id" => $bole_id,
"bole_titulos" => html_entity_decode($bole_titulos),
"bole_url" => html_entity_decode($bole_url),
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($boletos_arr["records"], $boletos_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "boletos found","document"=> $boletos_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No boletos found.","document"=> ""));
}
 


