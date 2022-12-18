<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_status.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$usuario_status = new Usuario_Status($db);

$usuario_status->usus_id = isset($_GET['id']) ? $_GET['id'] : die();
$usuario_status->readOne();
 
if($usuario_status->usus_id!=null){
    $usuario_status_arr = array(
        
"usus_id" => $usuario_status->usus_id,
"usus_nome" => $usuario_status->usus_nome,
"usus_sigla" => $usuario_status->usus_sigla
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuario_status found","document"=> $usuario_status_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "usuario_status does not exist.","document"=> ""));
}
?>
