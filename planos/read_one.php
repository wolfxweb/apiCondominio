<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/planos.php';
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

$planos = new Planos($db);

$planos->id = isset($_GET['id']) ? $_GET['id'] : die();
$planos->readOne();
 
if($planos->id!=null){
    $planos_arr = array(
        
"id" => $planos->id,
"plan_nome" => html_entity_decode($planos->plan_nome),
"plan_status" => html_entity_decode($planos->plan_status),
"plan_preco" => html_entity_decode($planos->plan_preco),
"plan_obs" => html_entity_decode($planos->plan_obs),
"created_at" => $planos->created_at,
"updated_at" => $planos->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "planos found","document"=> $planos_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "planos does not exist.","document"=> ""));
}
?>
