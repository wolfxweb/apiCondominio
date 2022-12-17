<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/condominios.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$condominios = new Condominios($db);

$condominios->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$condominios->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$condominios->plan_id = isset($_GET['plan_id']) ? $_GET['plan_id'] : die();

$stmt = $condominios->readByplan_id();
$num = $stmt->rowCount();

if($num>0){
    $condominios_arr=array();
	$condominios_arr["pageno"]=$condominios->pageNo;
	$condominios_arr["pagesize"]=$condominios->no_of_records_per_page;
    $condominios_arr["total_count"]=$condominios->total_record_count();
    $condominios_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $condominios_item=array(
            
"id" => $id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_rua" => html_entity_decode($cond_rua),
"cond_barirro" => html_entity_decode($cond_barirro),
"cond_cidade" => html_entity_decode($cond_cidade),
"cond_estado" => html_entity_decode($cond_estado),
"cond_cep" => html_entity_decode($cond_cep),
"cond_numero" => html_entity_decode($cond_numero),
"cond_status" => html_entity_decode($cond_status),
"plan_nome" => html_entity_decode($plan_nome),
"plan_id" => $plan_id,
"cond_slug" => html_entity_decode($cond_slug),
"cond_obs" => html_entity_decode($cond_obs),
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($condominios_arr["records"], $condominios_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "condominios found","document"=> $condominios_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No condominios found.","document"=> ""));
}
 


