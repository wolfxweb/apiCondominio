<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/enquete_votos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$enquete_votos = new Enquete_Votos($db);

$enquete_votos->id = isset($_GET['id']) ? $_GET['id'] : die();
$enquete_votos->readOne();
 
if($enquete_votos->id!=null){
    $enquete_votos_arr = array(
        
"id" => $enquete_votos->id,
"envo_voto" => $enquete_votos->envo_voto,
"envo_horario" => $enquete_votos->envo_horario,
"user_name" => html_entity_decode($enquete_votos->user_name),
"user_id" => $enquete_votos->user_id,
"cond_nome" => html_entity_decode($enquete_votos->cond_nome),
"cond_id" => $enquete_votos->cond_id,
"created_at" => $enquete_votos->created_at,
"updated_at" => $enquete_votos->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "enquete_votos found","document"=> $enquete_votos_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "enquete_votos does not exist.","document"=> ""));
}
?>
