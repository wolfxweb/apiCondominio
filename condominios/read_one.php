<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/condominios.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$condominios = new Condominios($db);

$condominios->id = isset($_GET['id']) ? $_GET['id'] : die();
$condominios->readOne();
 
if($condominios->id!=null){
    $condominios_arr = array(
        
"id" => $condominios->id,
"cond_nome" => html_entity_decode($condominios->cond_nome),
"cond_rua" => html_entity_decode($condominios->cond_rua),
"cond_barirro" => html_entity_decode($condominios->cond_barirro),
"cond_cidade" => html_entity_decode($condominios->cond_cidade),
"cond_estado" => html_entity_decode($condominios->cond_estado),
"cond_cep" => html_entity_decode($condominios->cond_cep),
"cond_numero" => html_entity_decode($condominios->cond_numero),
"cond_status" => html_entity_decode($condominios->cond_status),
"plan_nome" => html_entity_decode($condominios->plan_nome),
"plan_id" => $condominios->plan_id,
"cond_slug" => html_entity_decode($condominios->cond_slug),
"cond_obs" => html_entity_decode($condominios->cond_obs),
"created_at" => $condominios->created_at,
"updated_at" => $condominios->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "condominios found","document"=> $condominios_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "condominios does not exist.","document"=> ""));
}
?>
