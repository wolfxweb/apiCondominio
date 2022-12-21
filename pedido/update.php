<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pedido.php';
include_once '../token/validatetoken.php';

if (isset($decodedJWTData) && isset($decodedJWTData->tenant))
{
$database = new Database($decodedJWTData->tenant); 
}
else 
{
$database = new Database(); 
}

$db = $database->getConnection();

$pedido = new Pedido($db);

$data = json_decode(file_get_contents("php://input"));
$pedido->ped_id = $data->ped_id;

if(!isEmpty($data->ped_titulo)
&&!isEmpty($data->ped_data)){

if(!isEmpty($data->ped_titulo)) { 
$pedido->ped_titulo = $data->ped_titulo;
} else { 
$pedido->ped_titulo = '';
}
$pedido->ped_descricao = $data->ped_descricao;
$pedido->usu_id = $data->usu_id;
if(!isEmpty($data->ped_data)) { 
$pedido->ped_data = $data->ped_data;
} else { 
$pedido->ped_data = 'current_timestamp()';
}
$pedido->ped_local_compra = $data->ped_local_compra;
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
