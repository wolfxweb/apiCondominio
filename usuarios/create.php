<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuarios.php';
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
 
$usuarios = new Usuarios($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->usu_email)
&&!isEmpty($data->usu_password)){
	
    
$usuarios->usu_nome = $data->usu_nome;
if(!isEmpty($data->usu_email)) { 
$usuarios->usu_email = $data->usu_email;
} else { 
$usuarios->usu_email = '';
}
if(!isEmpty($data->usu_password)) { 
$usuarios->usu_password = $data->usu_password;
} else { 
$usuarios->usu_password = '';
}
$usuarios->usu_reset_token = $data->usu_reset_token;
$usuarios->sta_id = $data->sta_id;
$usuarios->usut_id = $data->usut_id;
 	$lastInsertedId=$usuarios->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create usuarios","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create usuarios. Data is incomplete.","document"=> ""));
}
?>
