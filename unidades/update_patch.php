<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidades.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
$unidades = new Unidades($db);
$data = json_decode(file_get_contents("php://input"));

$unidades->id = $data->id;

if(!isEmpty($unidades->id)){
 
if($unidades->update_patch($data)){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update unidades","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update unidades. Data is incomplete.","document"=> ""));
}
?>
