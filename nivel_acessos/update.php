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

if(!isEmpty($data->nive_titulo)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->nive_titulo)) { 
$nivel_acessos->nive_titulo = $data->nive_titulo;
} else { 
$nivel_acessos->nive_titulo = '';
}
if(!isEmpty($data->cond_id)) { 
$nivel_acessos->cond_id = $data->cond_id;
} else { 
$nivel_acessos->cond_id = '';
}
$nivel_acessos->created_at = $data->created_at;
$nivel_acessos->updated_at = $data->updated_at;
if($nivel_acessos->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update nivel_acessos","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update nivel_acessos. Data is incomplete.","document"=> ""));
}
?>
