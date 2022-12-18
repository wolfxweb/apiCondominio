<?php
include_once '../config/header.php';
include_once '../config/helper.php';
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

$areas->id = isset($_GET['id']) ? $_GET['id'] : die();
$areas->readOne();
 
if($areas->id!=null){
    $areas_arr = array(
        
"id" => $areas->id,
"area_id" => $areas->area_id,
"area_titulo" => html_entity_decode($areas->area_titulo),
"area_status" => $areas->area_status,
"area_fotos" => $areas->area_fotos,
"area_days" => $areas->area_days,
"area_time_start" => $areas->area_time_start,
"area_time_end" => $areas->area_time_end,
"cond_nome" => html_entity_decode($areas->cond_nome),
"cond_id" => $areas->cond_id,
"created_at" => $areas->created_at,
"updated_at" => $areas->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "areas found","document"=> $areas_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "areas does not exist.","document"=> ""));
}
?>
