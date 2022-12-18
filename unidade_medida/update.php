<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidade_medida.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$unidade_medida = new Unidade_Medida($db);

$data = json_decode(file_get_contents("php://input"));
$unidade_medida->uni_id = $data->uni_id;

if(!isEmpty($data->uni_id)
&&!isEmpty($data->uni_sigla)
&&!isEmpty($data->uni_nome)){

if(!isEmpty($data->uni_id)) { 
$unidade_medida->uni_id = $data->uni_id;
} else { 
$unidade_medida->uni_id = '';
}
if(!isEmpty($data->uni_sigla)) { 
$unidade_medida->uni_sigla = $data->uni_sigla;
} else { 
$unidade_medida->uni_sigla = '';
}
if(!isEmpty($data->uni_nome)) { 
$unidade_medida->uni_nome = $data->uni_nome;
} else { 
$unidade_medida->uni_nome = '';
}
$unidade_medida->uni_padrao = $data->uni_padrao;
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
