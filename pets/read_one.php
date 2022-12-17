<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pets.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$pets = new Pets($db);

$pets->id = isset($_GET['id']) ? $_GET['id'] : die();
$pets->readOne();
 
if($pets->id!=null){
    $pets_arr = array(
        
"id" => $pets->id,
"pets_nome" => html_entity_decode($pets->pets_nome),
"pets_raca" => html_entity_decode($pets->pets_raca),
"pets_tipo" => html_entity_decode($pets->pets_tipo),
"unid_name" => html_entity_decode($pets->unid_name),
"unid_id" => $pets->unid_id,
"cond_nome" => html_entity_decode($pets->cond_nome),
"cond_id" => $pets->cond_id,
"created_at" => $pets->created_at,
"updated_at" => $pets->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "pets found","document"=> $pets_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "pets does not exist.","document"=> ""));
}
?>
