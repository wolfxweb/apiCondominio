<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/enquete_votos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$enquete_votos = new Enquete_Votos($db);

$data = json_decode(file_get_contents("php://input"));

$enquete_votos->id = $data->id;


if($enquete_votos->delete()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Enquete_Votos was deleted","document"=> ""));
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete enquete_votos.","document"=> ""));
}
?>
