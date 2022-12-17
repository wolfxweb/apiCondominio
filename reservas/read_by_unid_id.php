<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/reservas.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$reservas = new Reservas($db);

$reservas->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$reservas->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$reservas->unid_id = isset($_GET['unid_id']) ? $_GET['unid_id'] : die();

$stmt = $reservas->readByunid_id();
$num = $stmt->rowCount();

if($num>0){
    $reservas_arr=array();
	$reservas_arr["pageno"]=$reservas->pageNo;
	$reservas_arr["pagesize"]=$reservas->no_of_records_per_page;
    $reservas_arr["total_count"]=$reservas->total_record_count();
    $reservas_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $reservas_item=array(
            
"id" => $id,
"area_id" => $area_id,
"rese_day" => $rese_day,
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($reservas_arr["records"], $reservas_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "reservas found","document"=> $reservas_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No reservas found.","document"=> ""));
}
 


