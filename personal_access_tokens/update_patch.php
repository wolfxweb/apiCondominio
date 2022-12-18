<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/personal_access_tokens.php';
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
$personal_access_tokens = new Personal_Access_Tokens($db);
$data = json_decode(file_get_contents("php://input"));

$personal_access_tokens->id = $data->id;

if(!isEmpty($personal_access_tokens->id)){
 
if($personal_access_tokens->update_patch($data)){
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
