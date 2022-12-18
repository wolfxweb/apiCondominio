<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pedido.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$pedido = new Pedido($db);

$pedido->ped_id = isset($_GET['id']) ? $_GET['id'] : die();
$pedido->readOne();
 
if($pedido->ped_id!=null){
    $pedido_arr = array(
        
"ped_id" => $pedido->ped_id,
"ped_nome" => html_entity_decode($pedido->ped_nome),
"ped_public" => $pedido->ped_public,
"ped_slug" => $pedido->ped_slug,
"ped_data_finalizacao" => $pedido->ped_data_finalizacao,
"ped_data_abetura" => $pedido->ped_data_abetura,
"sta_name" => $pedido->sta_name,
"sta_id" => $pedido->sta_id,
"nome" => html_entity_decode($pedido->nome),
"loj_id" => $pedido->loj_id,
"usu_email" => html_entity_decode($pedido->usu_email),
"usu_id" => $pedido->usu_id,
"itep_id" => $pedido->itep_id
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "pedido found","document"=> $pedido_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "pedido does not exist.","document"=> ""));
}
?>
