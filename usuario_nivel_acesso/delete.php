<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_nivel_acesso.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$usuario_nivel_acesso = new Usuario_Nivel_Acesso($db);

$data = json_decode(file_get_contents("php://input"));

$usuario_nivel_acesso->usun_id = $data->usun_id;


if($usuario_nivel_acesso->delete()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Usuario_Nivel_Acesso was deleted","document"=> ""));
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to delete usuario_nivel_acesso.","document"=> ""));
}
?>
