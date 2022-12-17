<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/ocorrencias.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$ocorrencias = new Ocorrencias($db);

$ocorrencias->id = isset($_GET['id']) ? $_GET['id'] : die();
$ocorrencias->readOne();
 
if($ocorrencias->id!=null){
    $ocorrencias_arr = array(
        
"id" => $ocorrencias->id,
"ocor_id" => $ocorrencias->ocor_id,
"ocor_titulos" => html_entity_decode($ocorrencias->ocor_titulos),
"ocor_status" => html_entity_decode($ocorrencias->ocor_status),
"ocor_data_cadastro" => $ocorrencias->ocor_data_cadastro,
"ocor_fotos" => $ocorrencias->ocor_fotos,
"unid_name" => html_entity_decode($ocorrencias->unid_name),
"unid_id" => $ocorrencias->unid_id,
"cond_nome" => html_entity_decode($ocorrencias->cond_nome),
"cond_id" => $ocorrencias->cond_id,
"created_at" => $ocorrencias->created_at,
"updated_at" => $ocorrencias->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ocorrencias found","document"=> $ocorrencias_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ocorrencias does not exist.","document"=> ""));
}
?>
