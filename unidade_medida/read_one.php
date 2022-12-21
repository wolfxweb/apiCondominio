<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidade_medida.php';
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

$unidade_medida = new Unidade_Medida($db);

$unidade_medida->unid_id = isset($_GET['id']) ? $_GET['id'] : die();
$unidade_medida->readOne();
 
if($unidade_medida->unid_id!=null){
    $unidade_medida_arr = array(
        
"unid_id" => $unidade_medida->unid_id,
"unid_slug" => $unidade_medida->unid_slug,
"unid_nome" => $unidade_medida->unid_nome,
"usu_email" => html_entity_decode($unidade_medida->usu_email),
"usu_id" => $unidade_medida->usu_id
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "unidade_medida found","document"=> $unidade_medida_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "unidade_medida does not exist.","document"=> ""));
}
?>
