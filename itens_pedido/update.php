<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/itens_pedido.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$itens_pedido = new Itens_Pedido($db);

$data = json_decode(file_get_contents("php://input"));
$itens_pedido->itep_id = $data->itep_id;

if(!isEmpty($data->itep_id)){

if(!isEmpty($data->itep_id)) { 
$itens_pedido->itep_id = $data->itep_id;
} else { 
$itens_pedido->itep_id = '';
}
$itens_pedido->ped_id = $data->ped_id;
$itens_pedido->prod_id = $data->prod_id;
$itens_pedido->itep_quantidade = $data->itep_quantidade;
$itens_pedido->itep_valor_total = $data->itep_valor_total;
if($itens_pedido->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update itens_pedido","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update itens_pedido. Data is incomplete.","document"=> ""));
}
?>
