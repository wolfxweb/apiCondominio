<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pedido_item.php';
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

$pedido_item = new Pedido_Item($db);

$data = json_decode(file_get_contents("php://input"));
$pedido_item->pedi_id = $data->pedi_id;

if(true){

$pedido_item->pro_id = $data->pro_id;
$pedido_item->ped_id = $data->ped_id;
$pedido_item->pedi_qtd = $data->pedi_qtd;
$pedido_item->pedi_valor_unitario = $data->pedi_valor_unitario;
$pedido_item->pedi_valor_total = $data->pedi_valor_total;
if($pedido_item->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update pedido_item","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update pedido_item. Data is incomplete.","document"=> ""));
}
?>
