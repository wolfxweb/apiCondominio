<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/classificados.php';
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

$classificados = new Classificados($db);

$classificados->id = isset($_GET['id']) ? $_GET['id'] : die();
$classificados->readOne();
 
if($classificados->id!=null){
    $classificados_arr = array(
        
"id" => $classificados->id,
"class_titulo" => html_entity_decode($classificados->class_titulo),
"class_fotos" => $classificados->class_fotos,
"class_descricao" => $classificados->class_descricao,
"class_preco" => $classificados->class_preco,
"class_data" => $classificados->class_data,
"user_name" => html_entity_decode($classificados->user_name),
"user_id" => $classificados->user_id,
"cond_nome" => html_entity_decode($classificados->cond_nome),
"cond_id" => $classificados->cond_id,
"created_at" => $classificados->created_at,
"updated_at" => $classificados->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "classificados found","document"=> $classificados_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "classificados does not exist.","document"=> ""));
}
?>
