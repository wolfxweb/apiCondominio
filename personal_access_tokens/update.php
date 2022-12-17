<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/personal_access_tokens.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$personal_access_tokens = new Personal_Access_Tokens($db);

$data = json_decode(file_get_contents("php://input"));
$personal_access_tokens->id = $data->id;

if(!isEmpty($data->tokenable_type)
&&!isEmpty($data->tokenable_id)
&&!isEmpty($data->name)
&&!isEmpty($data->token)){

if(!isEmpty($data->tokenable_type)) { 
$personal_access_tokens->tokenable_type = $data->tokenable_type;
} else { 
$personal_access_tokens->tokenable_type = '';
}
if(!isEmpty($data->tokenable_id)) { 
$personal_access_tokens->tokenable_id = $data->tokenable_id;
} else { 
$personal_access_tokens->tokenable_id = '';
}
if(!isEmpty($data->name)) { 
$personal_access_tokens->name = $data->name;
} else { 
$personal_access_tokens->name = '';
}
if(!isEmpty($data->token)) { 
$personal_access_tokens->token = $data->token;
} else { 
$personal_access_tokens->token = '';
}
$personal_access_tokens->abilities = $data->abilities;
$personal_access_tokens->last_used_at = $data->last_used_at;
$personal_access_tokens->expires_at = $data->expires_at;
$personal_access_tokens->created_at = $data->created_at;
$personal_access_tokens->updated_at = $data->updated_at;
if($personal_access_tokens->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update personal_access_tokens","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update personal_access_tokens. Data is incomplete.","document"=> ""));
}
?>
