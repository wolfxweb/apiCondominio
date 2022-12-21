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

$pedido_item->pedi_id = isset($_GET['id']) ? $_GET['id'] : die();
$pedido_item->readOne();
 
if($pedido_item->pedi_id!=null){
    $pedido_item_arr = array(
        
"pedi_id" => $pedido_item->pedi_id,
"prod_nome" => $pedido_item->prod_nome,
"pro_id" => $pedido_item->pro_id,
"ped_titulo" => $pedido_item->ped_titulo,
"ped_id" => $pedido_item->ped_id,
"pedi_qtd" => $pedido_item->pedi_qtd,
"pedi_valor_unitario" => $pedido_item->pedi_valor_unitario,
"pedi_valor_total" => $pedido_item->pedi_valor_total
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "pedido_item found","document"=> $pedido_item_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "pedido_item does not exist.","document"=> ""));
}
?>
