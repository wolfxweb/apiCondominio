<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/achados_perdidos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$achados_perdidos = new Achados_Perdidos($db);

$data = json_decode(file_get_contents("php://input"));

$achados_perdidos->id = $data->id;


if($achados_perdidos->delete()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Achados_Perdidos was deleted","document"=> ""));
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete achados_perdidos.","document"=> ""));
}
?>
