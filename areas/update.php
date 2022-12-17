<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/areas.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$areas = new Areas($db);

$data = json_decode(file_get_contents("php://input"));
$areas->id = $data->id;

if(!isEmpty($data->area_id)
&&!isEmpty($data->area_titulo)
&&!isEmpty($data->area_status)
&&!isEmpty($data->area_fotos)
&&!isEmpty($data->area_days)
&&!isEmpty($data->area_time_start)
&&!isEmpty($data->area_time_end)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->area_id)) { 
$areas->area_id = $data->area_id;
} else { 
$areas->area_id = '';
}
if(!isEmpty($data->area_titulo)) { 
$areas->area_titulo = $data->area_titulo;
} else { 
$areas->area_titulo = '';
}
if(!isEmpty($data->area_status)) { 
$areas->area_status = $data->area_status;
} else { 
$areas->area_status = '1';
}
if(!isEmpty($data->area_fotos)) { 
$areas->area_fotos = $data->area_fotos;
} else { 
$areas->area_fotos = '';
}
if(!isEmpty($data->area_days)) { 
$areas->area_days = $data->area_days;
} else { 
$areas->area_days = '';
}
if(!isEmpty($data->area_time_start)) { 
$areas->area_time_start = $data->area_time_start;
} else { 
$areas->area_time_start = '';
}
if(!isEmpty($data->area_time_end)) { 
$areas->area_time_end = $data->area_time_end;
} else { 
$areas->area_time_end = '';
}
if(!isEmpty($data->cond_id)) { 
$areas->cond_id = $data->cond_id;
} else { 
$areas->cond_id = '';
}
$areas->created_at = $data->created_at;
$areas->updated_at = $data->updated_at;
if($areas->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update areas","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update areas. Data is incomplete.","document"=> ""));
}
?>
