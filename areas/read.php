<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/areas.php';
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
 
$areas = new Areas($db);

$areas->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$areas->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $areas->read();
$num = $stmt->rowCount();
if($num>0){
    $areas_arr=array();
	$areas_arr["pageno"]=$areas->pageNo;
	$areas_arr["pagesize"]=$areas->no_of_records_per_page;
    $areas_arr["total_count"]=$areas->total_record_count();
    $areas_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $areas_item=array(
            
"id" => $id,
"area_id" => $area_id,
"area_titulo" => html_entity_decode($area_titulo),
"area_status" => $area_status,
"area_fotos" => $area_fotos,
"area_days" => $area_days,
"area_time_start" => $area_time_start,
"area_time_end" => $area_time_end,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
         array_push($areas_arr["records"], $areas_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "areas found","document"=> $areas_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No areas found.","document"=> ""));
}
 


