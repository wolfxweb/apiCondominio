<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_tipo.php';
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

$usuario_tipo = new Usuario_Tipo($db);

$usuario_tipo->usut_id = isset($_GET['id']) ? $_GET['id'] : die();
$usuario_tipo->readOne();
 
if($usuario_tipo->usut_id!=null){
    $usuario_tipo_arr = array(
        
"usut_id" => $usuario_tipo->usut_id,
"usut_nome" => $usuario_tipo->usut_nome,
"usut_sigla" => $usuario_tipo->usut_sigla
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuario_tipo found","document"=> $usuario_tipo_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "usuario_tipo does not exist.","document"=> ""));
}
?>
