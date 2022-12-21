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

$data = json_decode(file_get_contents("php://input"));
$unidade_medida->unid_id = $data->unid_id;

if(true){

$unidade_medida->unid_slug = $data->unid_slug;
$unidade_medida->unid_nome = $data->unid_nome;
$unidade_medida->usu_id = $data->usu_id;
if($unidade_medida->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update unidade_medida","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update unidade_medida. Data is incomplete.","document"=> ""));
}
?>
