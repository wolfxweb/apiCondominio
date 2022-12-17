<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/enquete_votos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$enquete_votos = new Enquete_Votos($db);

$data = json_decode(file_get_contents("php://input"));
$enquete_votos->id = $data->id;

if(!isEmpty($data->envo_voto)
&&!isEmpty($data->envo_horario)
&&!isEmpty($data->user_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->envo_voto)) { 
$enquete_votos->envo_voto = $data->envo_voto;
} else { 
$enquete_votos->envo_voto = '';
}
if(!isEmpty($data->envo_horario)) { 
$enquete_votos->envo_horario = $data->envo_horario;
} else { 
$enquete_votos->envo_horario = '';
}
if(!isEmpty($data->user_id)) { 
$enquete_votos->user_id = $data->user_id;
} else { 
$enquete_votos->user_id = '';
}
if(!isEmpty($data->cond_id)) { 
$enquete_votos->cond_id = $data->cond_id;
} else { 
$enquete_votos->cond_id = '';
}
$enquete_votos->created_at = $data->created_at;
$enquete_votos->updated_at = $data->updated_at;
if($enquete_votos->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update enquete_votos","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update enquete_votos. Data is incomplete.","document"=> ""));
}
?>
