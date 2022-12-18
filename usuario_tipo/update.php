<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_tipo.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$usuario_tipo = new Usuario_Tipo($db);

$data = json_decode(file_get_contents("php://input"));
$usuario_tipo->usut_id = $data->usut_id;

if(!isEmpty($data->usut_id)){

if(!isEmpty($data->usut_id)) { 
$usuario_tipo->usut_id = $data->usut_id;
} else { 
$usuario_tipo->usut_id = '';
}
$usuario_tipo->usut_nome = $data->usut_nome;
$usuario_tipo->usut_siglar = $data->usut_siglar;
if($usuario_tipo->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update usuario_tipo","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update usuario_tipo. Data is incomplete.","document"=> ""));
}
?>
