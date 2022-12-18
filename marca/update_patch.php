<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/marca.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
$marca = new Marca($db);
$data = json_decode(file_get_contents("php://input"));

$marca->mar_id = $data->mar_id;

if(!isEmpty($marca->mar_id)){
 
if($marca->update_patch($data)){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update marca","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update marca. Data is incomplete.","document"=> ""));
}
?>