<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pedido.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$pedido = new Pedido($db);

$data = json_decode(file_get_contents("php://input"));
$pedido->ped_id = $data->ped_id;

if(!isEmpty($data->ped_nome)
&&!isEmpty($data->ped_data_abetura)){

if(!isEmpty($data->ped_nome)) { 
$pedido->ped_nome = $data->ped_nome;
} else { 
$pedido->ped_nome = '';
}
$pedido->ped_public = $data->ped_public;
$pedido->ped_slug = $data->ped_slug;
$pedido->ped_data_finalizacao = $data->ped_data_finalizacao;
if(!isEmpty($data->ped_data_abetura)) { 
$pedido->ped_data_abetura = $data->ped_data_abetura;
} else { 
$pedido->ped_data_abetura = 'current_timestamp()';
}
$pedido->sta_id = $data->sta_id;
$pedido->loj_id = $data->loj_id;
$pedido->usu_id = $data->usu_id;
$pedido->itep_id = $data->itep_id;
if($pedido->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update pedido","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update pedido. Data is incomplete.","document"=> ""));
}
?>
