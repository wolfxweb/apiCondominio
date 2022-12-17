<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/achados_perdidos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$achados_perdidos = new Achados_Perdidos($db);

$achados_perdidos->id = isset($_GET['id']) ? $_GET['id'] : die();
$achados_perdidos->readOne();
 
if($achados_perdidos->id!=null){
    $achados_perdidos_arr = array(
        
"id" => $achados_perdidos->id,
"acha_id" => $achados_perdidos->acha_id,
"acha_titulo" => html_entity_decode($achados_perdidos->acha_titulo),
"ocor_status" => html_entity_decode($achados_perdidos->ocor_status),
"acha_data_cadastro" => $achados_perdidos->acha_data_cadastro,
"acha_fotos" => $achados_perdidos->acha_fotos,
"acha_descricao" => $achados_perdidos->acha_descricao,
"user_name" => html_entity_decode($achados_perdidos->user_name),
"usu_id" => $achados_perdidos->usu_id,
"cond_nome" => html_entity_decode($achados_perdidos->cond_nome),
"cond_id" => $achados_perdidos->cond_id,
"created_at" => $achados_perdidos->created_at,
"updated_at" => $achados_perdidos->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "achados_perdidos found","document"=> $achados_perdidos_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "achados_perdidos does not exist.","document"=> ""));
}
?>
