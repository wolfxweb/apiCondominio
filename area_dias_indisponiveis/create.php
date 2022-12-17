<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/area_dias_indisponiveis.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$area_dias_indisponiveis = new Area_Dias_Indisponiveis($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->adin_id)
&&!isEmpty($data->adin_day)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->adin_id)) { 
$area_dias_indisponiveis->adin_id = $data->adin_id;
} else { 
$area_dias_indisponiveis->adin_id = '';
}
if(!isEmpty($data->adin_day)) { 
$area_dias_indisponiveis->adin_day = $data->adin_day;
} else { 
$area_dias_indisponiveis->adin_day = '';
}
if(!isEmpty($data->cond_id)) { 
$area_dias_indisponiveis->cond_id = $data->cond_id;
} else { 
$area_dias_indisponiveis->cond_id = '';
}
$area_dias_indisponiveis->created_at = $data->created_at;
$area_dias_indisponiveis->updated_at = $data->updated_at;
 	$lastInsertedId=$area_dias_indisponiveis->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create area_dias_indisponiveis","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create area_dias_indisponiveis. Data is incomplete.","document"=> ""));
}
?>
