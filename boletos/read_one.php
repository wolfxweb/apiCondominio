<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/boletos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$boletos = new Boletos($db);

$boletos->id = isset($_GET['id']) ? $_GET['id'] : die();
$boletos->readOne();
 
if($boletos->id!=null){
    $boletos_arr = array(
        
"id" => $boletos->id,
"bole_id" => $boletos->bole_id,
"bole_titulos" => html_entity_decode($boletos->bole_titulos),
"bole_url" => html_entity_decode($boletos->bole_url),
"unid_name" => html_entity_decode($boletos->unid_name),
"unid_id" => $boletos->unid_id,
"cond_nome" => html_entity_decode($boletos->cond_nome),
"cond_id" => $boletos->cond_id,
"created_at" => $boletos->created_at,
"updated_at" => $boletos->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "boletos found","document"=> $boletos_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "boletos does not exist.","document"=> ""));
}
?>
