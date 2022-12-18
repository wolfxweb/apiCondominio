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

if(!isEmpty($data->usut_id)){
	
    
if(!isEmpty($data->usut_id)) { 
$usuario_tipo->usut_id = $data->usut_id;
} else { 
$usuario_tipo->usut_id = '';
}
$usuario_tipo->usut_nome = $data->usut_nome;
$usuario_tipo->usut_siglar = $data->usut_siglar;
 	$lastInsertedId=$usuario_tipo->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create usuario_tipo","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create usuario_tipo. Data is incomplete.","document"=> ""));
}
?>
