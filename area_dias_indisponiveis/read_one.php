<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/area_dias_indisponiveis.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$area_dias_indisponiveis = new Area_Dias_Indisponiveis($db);

$area_dias_indisponiveis->id = isset($_GET['id']) ? $_GET['id'] : die();
$area_dias_indisponiveis->readOne();
 
if($area_dias_indisponiveis->id!=null){
    $area_dias_indisponiveis_arr = array(
        
"id" => $area_dias_indisponiveis->id,
"adin_id" => $area_dias_indisponiveis->adin_id,
"adin_day" => $area_dias_indisponiveis->adin_day,
"cond_nome" => html_entity_decode($area_dias_indisponiveis->cond_nome),
"cond_id" => $area_dias_indisponiveis->cond_id,
"created_at" => $area_dias_indisponiveis->created_at,
"updated_at" => $area_dias_indisponiveis->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "area_dias_indisponiveis found","document"=> $area_dias_indisponiveis_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "area_dias_indisponiveis does not exist.","document"=> ""));
}
?>
