<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuarios.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$usuarios = new Usuarios($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->usu_id)
&&!isEmpty($data->usu_email)){
	
    
if(!isEmpty($data->usu_id)) { 
$usuarios->usu_id = $data->usu_id;
} else { 
$usuarios->usu_id = '';
}
$usuarios->usu_name = $data->usu_name;
if(!isEmpty($data->usu_email)) { 
$usuarios->usu_email = $data->usu_email;
} else { 
$usuarios->usu_email = '';
}
if(!isEmpty($data->usu_password)) { 
$usuarios->usu_password = md5($data->usu_password);
}
$usuarios->usu_token_recuperar_senha = $data->usu_token_recuperar_senha;
$usuarios->usut_id = $data->usut_id;
$usuarios->usun_id = $data->usun_id;
$usuarios->usus_id = $data->usus_id;
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
