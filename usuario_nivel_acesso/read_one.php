<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_nivel_acesso.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$usuario_nivel_acesso = new Usuario_Nivel_Acesso($db);

$usuario_nivel_acesso->usun_id = isset($_GET['id']) ? $_GET['id'] : die();
$usuario_nivel_acesso->readOne();
 
if($usuario_nivel_acesso->usun_id!=null){
    $usuario_nivel_acesso_arr = array(
        
"usun_id" => $usuario_nivel_acesso->usun_id,
"usun_nome" => $usuario_nivel_acesso->usun_nome,
"usun_sigla" => $usuario_nivel_acesso->usun_sigla
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuario_nivel_acesso found","document"=> $usuario_nivel_acesso_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "usuario_nivel_acesso does not exist.","document"=> ""));
}
?>
