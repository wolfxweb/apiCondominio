<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuarios.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$usuarios = new Usuarios($db);

$usuarios->usu_id = isset($_GET['id']) ? $_GET['id'] : die();
$usuarios->readOne();
 
if($usuarios->usu_id!=null){
    $usuarios_arr = array(
        
"usu_id" => $usuarios->usu_id,
"usu_name" => $usuarios->usu_name,
"usu_email" => html_entity_decode($usuarios->usu_email),
"usu_password" => $usuarios->usu_password,
"usu_token_recuperar_senha" => $usuarios->usu_token_recuperar_senha,
"usut_id" => $usuarios->usut_id,
"usun_nome" => $usuarios->usun_nome,
"usun_id" => $usuarios->usun_id,
"usus_nome" => $usuarios->usus_nome,
"usus_id" => $usuarios->usus_id
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuarios found","document"=> $usuarios_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "usuarios does not exist.","document"=> ""));
}
?>
