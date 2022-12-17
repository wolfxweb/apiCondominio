<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/reservas.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$reservas = new Reservas($db);

$data = json_decode(file_get_contents("php://input"));
$reservas->id = $data->id;

if(!isEmpty($data->area_id)
&&!isEmpty($data->rese_day)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->area_id)) { 
$reservas->area_id = $data->area_id;
} else { 
$reservas->area_id = '';
}
if(!isEmpty($data->rese_day)) { 
$reservas->rese_day = $data->rese_day;
} else { 
$reservas->rese_day = '';
}
if(!isEmpty($data->unid_id)) { 
$reservas->unid_id = $data->unid_id;
} else { 
$reservas->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$reservas->cond_id = $data->cond_id;
} else { 
$reservas->cond_id = '';
}
$reservas->created_at = $data->created_at;
$reservas->updated_at = $data->updated_at;
if($reservas->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update reservas","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update reservas. Data is incomplete.","document"=> ""));
}
?>
