<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/enquetes.php';
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

$enquetes = new Enquetes($db);

$enquetes->id = isset($_GET['id']) ? $_GET['id'] : die();
$enquetes->readOne();
 
if($enquetes->id!=null){
    $enquetes_arr = array(
        
"id" => $enquetes->id,
"enqu_titulo" => html_entity_decode($enquetes->enqu_titulo),
"enqu_descricao" => $enquetes->enqu_descricao,
"enqu_inicio" => $enquetes->enqu_inicio,
"enqu_final" => $enquetes->enqu_final,
"cond_nome" => html_entity_decode($enquetes->cond_nome),
"cond_id" => $enquetes->cond_id,
"created_at" => $enquetes->created_at,
"updated_at" => $enquetes->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "enquetes found","document"=> $enquetes_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "enquetes does not exist.","document"=> ""));
}
?>
