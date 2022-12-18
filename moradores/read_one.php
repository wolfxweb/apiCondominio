<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/moradores.php';
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

$moradores = new Moradores($db);

$moradores->id = isset($_GET['id']) ? $_GET['id'] : die();
$moradores->readOne();
 
if($moradores->id!=null){
    $moradores_arr = array(
        
"id" => $moradores->id,
"mora_name" => html_entity_decode($moradores->mora_name),
"mora_obs" => html_entity_decode($moradores->mora_obs),
"mora_data_nacimento" => $moradores->mora_data_nacimento,
"unid_name" => html_entity_decode($moradores->unid_name),
"unid_id" => $moradores->unid_id,
"cond_nome" => html_entity_decode($moradores->cond_nome),
"cond_id" => $moradores->cond_id,
"created_at" => $moradores->created_at,
"updated_at" => $moradores->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "moradores found","document"=> $moradores_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "moradores does not exist.","document"=> ""));
}
?>
