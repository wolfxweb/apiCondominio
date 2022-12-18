<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/users.php';
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

$users = new Users($db);

$users->id = isset($_GET['id']) ? $_GET['id'] : die();
$users->readOne();
 
if($users->id!=null){
    $users_arr = array(
        
"id" => $users->id,
"user_name" => html_entity_decode($users->user_name),
"user_email" => html_entity_decode($users->user_email),
"user_password" => html_entity_decode($users->user_password),
"user_cpf" => html_entity_decode($users->user_cpf),
"user_rg" => html_entity_decode($users->user_rg),
"user_foto" => html_entity_decode($users->user_foto),
"nive_id" => $users->nive_id,
"user_token" => html_entity_decode($users->user_token),
"cond_nome" => html_entity_decode($users->cond_nome),
"cond_id" => $users->cond_id,
"remember_token" => $users->remember_token,
"created_at" => $users->created_at,
"updated_at" => $users->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "users found","document"=> $users_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "users does not exist.","document"=> ""));
}
?>
