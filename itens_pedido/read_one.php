<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/itens_pedido.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$itens_pedido = new Itens_Pedido($db);

$itens_pedido->itep_id = isset($_GET['id']) ? $_GET['id'] : die();
$itens_pedido->readOne();
 
if($itens_pedido->itep_id!=null){
    $itens_pedido_arr = array(
        
"itep_id" => $itens_pedido->itep_id,
"ped_id" => $itens_pedido->ped_id,
"prod_id" => $itens_pedido->prod_id,
"itep_quantidade" => $itens_pedido->itep_quantidade,
"itep_valor_total" => $itens_pedido->itep_valor_total
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "itens_pedido found","document"=> $itens_pedido_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "itens_pedido does not exist.","document"=> ""));
}
?>
