<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/area_dias_indisponiveis.php';
include_once '../token/validatetoken.php';

if (isset($decodedJWTData) && isset($decodedJWTData->tenant))
{
$database = new Database($decodedJWTData->tenant); 
}
else 
{
$database = new Database(); 
}

$db = $database->getConnection();

$area_dias_indisponiveis = new Area_Dias_Indisponiveis($db);

$area_dias_indisponiveis->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$area_dias_indisponiveis->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$area_dias_indisponiveis->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $area_dias_indisponiveis->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $area_dias_indisponiveis_arr=array();
	$area_dias_indisponiveis_arr["pageno"]=$area_dias_indisponiveis->pageNo;
	$area_dias_indisponiveis_arr["pagesize"]=$area_dias_indisponiveis->no_of_records_per_page;
    $area_dias_indisponiveis_arr["total_count"]=$area_dias_indisponiveis->total_record_count();
    $area_dias_indisponiveis_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $area_dias_indisponiveis_item=array(
            
"id" => $id,
"adin_id" => $adin_id,
"adin_day" => $adin_day,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($area_dias_indisponiveis_arr["records"], $area_dias_indisponiveis_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "area_dias_indisponiveis found","document"=> $area_dias_indisponiveis_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No area_dias_indisponiveis found.","document"=> ""));
}
 


