<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/murallikes.php';
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

$murallikes = new Murallikes($db);

$data = json_decode(file_get_contents("php://input"));
$murallikes->id = $data->id;

if(!isEmpty($data->mura_id)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->mura_id)) { 
$murallikes->mura_id = $data->mura_id;
} else { 
$murallikes->mura_id = '';
}
if(!isEmpty($data->unid_id)) { 
$murallikes->unid_id = $data->unid_id;
} else { 
$murallikes->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$murallikes->cond_id = $data->cond_id;
} else { 
$murallikes->cond_id = '';
}
$murallikes->created_at = $data->created_at;
$murallikes->updated_at = $data->updated_at;
if($murallikes->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update murallikes","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update murallikes. Data is incomplete.","document"=> ""));
}
?>
