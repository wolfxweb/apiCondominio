<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/nivel_acessos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$nivel_acessos = new Nivel_Acessos($db);

$nivel_acessos->id = isset($_GET['id']) ? $_GET['id'] : die();
$nivel_acessos->readOne();
 
if($nivel_acessos->id!=null){
    $nivel_acessos_arr = array(
        
"id" => $nivel_acessos->id,
"nive_titulo" => html_entity_decode($nivel_acessos->nive_titulo),
"cond_nome" => html_entity_decode($nivel_acessos->cond_nome),
"cond_id" => $nivel_acessos->cond_id,
"created_at" => $nivel_acessos->created_at,
"updated_at" => $nivel_acessos->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "nivel_acessos found","document"=> $nivel_acessos_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "nivel_acessos does not exist.","document"=> ""));
}
?>
