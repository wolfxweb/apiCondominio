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

$personal_access_tokens->id = isset($_GET['id']) ? $_GET['id'] : die();
$personal_access_tokens->readOne();
 
if($personal_access_tokens->id!=null){
    $personal_access_tokens_arr = array(
        
"id" => $personal_access_tokens->id,
"tokenable_type" => html_entity_decode($personal_access_tokens->tokenable_type),
"tokenable_id" => $personal_access_tokens->tokenable_id,
"name" => html_entity_decode($personal_access_tokens->name),
"token" => $personal_access_tokens->token,
"abilities" => $personal_access_tokens->abilities,
"last_used_at" => $personal_access_tokens->last_used_at,
"expires_at" => $personal_access_tokens->expires_at,
"created_at" => $personal_access_tokens->created_at,
"updated_at" => $personal_access_tokens->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "personal_access_tokens found","document"=> $personal_access_tokens_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "personal_access_tokens does not exist.","document"=> ""));
}
?>
