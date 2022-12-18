<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidades.php';
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

$unidades = new Unidades($db);

$unidades->id = isset($_GET['id']) ? $_GET['id'] : die();
$unidades->readOne();
 
if($unidades->id!=null){
    $unidades_arr = array(
        
"id" => $unidades->id,
"unid_name" => html_entity_decode($unidades->unid_name),
"unid_obs" => html_entity_decode($unidades->unid_obs),
"user_name" => html_entity_decode($unidades->user_name),
"user_id" => $unidades->user_id,
"cond_nome" => html_entity_decode($unidades->cond_nome),
"cond_id" => $unidades->cond_id,
"created_at" => $unidades->created_at,
"updated_at" => $unidades->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "unidades found","document"=> $unidades_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "unidades does not exist.","document"=> ""));
}
?>
