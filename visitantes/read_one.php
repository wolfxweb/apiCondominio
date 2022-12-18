<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/visitantes.php';
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

$visitantes = new Visitantes($db);

$visitantes->id = isset($_GET['id']) ? $_GET['id'] : die();
$visitantes->readOne();
 
if($visitantes->id!=null){
    $visitantes_arr = array(
        
"id" => $visitantes->id,
"visi_nome" => html_entity_decode($visitantes->visi_nome),
"visi_cpf" => html_entity_decode($visitantes->visi_cpf),
"visi_rg" => html_entity_decode($visitantes->visi_rg),
"visi_telefone" => html_entity_decode($visitantes->visi_telefone),
"visi_obs" => $visitantes->visi_obs,
"unid_name" => html_entity_decode($visitantes->unid_name),
"unid_id" => $visitantes->unid_id,
"cond_nome" => html_entity_decode($visitantes->cond_nome),
"cond_id" => $visitantes->cond_id,
"created_at" => $visitantes->created_at,
"updated_at" => $visitantes->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "visitantes found","document"=> $visitantes_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "visitantes does not exist.","document"=> ""));
}
?>
