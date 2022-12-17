<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/area_dias_indisponiveis.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
$area_dias_indisponiveis = new Area_Dias_Indisponiveis($db);
$data = json_decode(file_get_contents("php://input"));

$area_dias_indisponiveis->id = $data->id;

if(!isEmpty($area_dias_indisponiveis->id)){
 
if($area_dias_indisponiveis->update_patch($data)){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update area_dias_indisponiveis","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update area_dias_indisponiveis. Data is incomplete.","document"=> ""));
}
?>
