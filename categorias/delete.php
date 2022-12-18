<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/categorias.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$categorias = new Categorias($db);

$data = json_decode(file_get_contents("php://input"));

$categorias->cat_id = $data->cat_id;


if($categorias->delete()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Categorias was deleted","document"=> ""));
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete categorias.","document"=> ""));
}
?>
