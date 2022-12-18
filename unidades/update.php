<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidades.php';
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

$unidades = new Unidades($db);

$data = json_decode(file_get_contents("php://input"));
$unidades->id = $data->id;

if(!isEmpty($data->unid_name)
&&!isEmpty($data->unid_obs)
&&!isEmpty($data->user_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->unid_name)) { 
$unidades->unid_name = $data->unid_name;
} else { 
$unidades->unid_name = '';
}
if(!isEmpty($data->unid_obs)) { 
$unidades->unid_obs = $data->unid_obs;
} else { 
$unidades->unid_obs = '';
}
if(!isEmpty($data->user_id)) { 
$unidades->user_id = $data->user_id;
} else { 
$unidades->user_id = '';
}
if(!isEmpty($data->cond_id)) { 
$unidades->cond_id = $data->cond_id;
} else { 
$unidades->cond_id = '';
}
$unidades->created_at = $data->created_at;
$unidades->updated_at = $data->updated_at;
if($unidades->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update unidades","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update unidades. Data is incomplete.","document"=> ""));
}
?>
