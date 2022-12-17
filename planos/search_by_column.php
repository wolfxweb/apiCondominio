<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/planos.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$planos = new Planos($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$planos->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$planos->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $planos->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $planos_arr=array();
	$planos_arr["pageno"]=$planos->pageNo;
	$planos_arr["pagesize"]=$planos->no_of_records_per_page;
    $planos_arr["total_count"]=$planos->search_record_count($data,$orAnd);
    $planos_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $planos_item=array(
            
"id" => $id,
"plan_nome" => html_entity_decode($plan_nome),
"plan_status" => html_entity_decode($plan_status),
"plan_preco" => html_entity_decode($plan_preco),
"plan_obs" => html_entity_decode($plan_obs),
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($planos_arr["records"], $planos_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "planos found","document"=> $planos_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No planos found.","document"=> ""));
}
 


