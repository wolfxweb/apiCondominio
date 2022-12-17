<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/convidados.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$convidados = new Convidados($db);

$convidados->id = isset($_GET['id']) ? $_GET['id'] : die();
$convidados->readOne();
 
if($convidados->id!=null){
    $convidados_arr = array(
        
"id" => $convidados->id,
"conv_nome" => html_entity_decode($convidados->conv_nome),
"conv_cpf" => html_entity_decode($convidados->conv_cpf),
"conv_rg" => html_entity_decode($convidados->conv_rg),
"conv_telefone" => html_entity_decode($convidados->conv_telefone),
"conv_status" => html_entity_decode($convidados->conv_status),
"conv_data" => $convidados->conv_data,
"conv_obs" => $convidados->conv_obs,
"unid_name" => html_entity_decode($convidados->unid_name),
"unid_id" => $convidados->unid_id,
"cond_nome" => html_entity_decode($convidados->cond_nome),
"cond_id" => $convidados->cond_id,
"created_at" => $convidados->created_at,
"updated_at" => $convidados->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "convidados found","document"=> $convidados_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "convidados does not exist.","document"=> ""));
}
?>
