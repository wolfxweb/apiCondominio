<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/documentos.php';
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

$documentos = new Documentos($db);

$documentos->id = isset($_GET['id']) ? $_GET['id'] : die();
$documentos->readOne();
 
if($documentos->id!=null){
    $documentos_arr = array(
        
"id" => $documentos->id,
"docs_id" => $documentos->docs_id,
"docs_titulos" => html_entity_decode($documentos->docs_titulos),
"docs_url" => html_entity_decode($documentos->docs_url),
"cond_nome" => html_entity_decode($documentos->cond_nome),
"cond_id" => $documentos->cond_id,
"created_at" => $documentos->created_at,
"updated_at" => $documentos->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "documentos found","document"=> $documentos_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "documentos does not exist.","document"=> ""));
}
?>
