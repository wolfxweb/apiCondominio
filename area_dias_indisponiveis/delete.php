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


if($area_dias_indisponiveis->delete()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Area_Dias_Indisponiveis was deleted","document"=> ""));
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete area_dias_indisponiveis.","document"=> ""));
}
?>
