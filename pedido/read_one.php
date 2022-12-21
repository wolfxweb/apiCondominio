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

$pedido->ped_id = isset($_GET['id']) ? $_GET['id'] : die();
$pedido->readOne();
 
if($pedido->ped_id!=null){
    $pedido_arr = array(
        
"ped_id" => $pedido->ped_id,
"ped_titulo" => $pedido->ped_titulo,
"ped_descricao" => html_entity_decode($pedido->ped_descricao),
"usu_email" => html_entity_decode($pedido->usu_email),
"usu_id" => $pedido->usu_id,
"ped_data" => $pedido->ped_data,
"ped_local_compra" => html_entity_decode($pedido->ped_local_compra)
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "pedido found","document"=> $pedido_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "pedido does not exist.","document"=> ""));
}
?>
