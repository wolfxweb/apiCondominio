<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/nivel_acessos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$nivel_acessos = new Nivel_Acessos($db);

$data = json_decode(file_get_contents("php://input"));

$nivel_acessos->id = $data->id;


if($nivel_acessos->delete()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Nivel_Acessos was deleted","document"=> ""));
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete nivel_acessos.","document"=> ""));
}
?>