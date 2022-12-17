<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/documentos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$documentos = new Documentos($db);

$data = json_decode(file_get_contents("php://input"));
$documentos->id = $data->id;

if(!isEmpty($data->docs_id)
&&!isEmpty($data->docs_titulos)
&&!isEmpty($data->docs_url)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->docs_id)) { 
$documentos->docs_id = $data->docs_id;
} else { 
$documentos->docs_id = '';
}
if(!isEmpty($data->docs_titulos)) { 
$documentos->docs_titulos = $data->docs_titulos;
} else { 
$documentos->docs_titulos = '';
}
if(!isEmpty($data->docs_url)) { 
$documentos->docs_url = $data->docs_url;
} else { 
$documentos->docs_url = '';
}
if(!isEmpty($data->cond_id)) { 
$documentos->cond_id = $data->cond_id;
} else { 
$documentos->cond_id = '';
}
$documentos->created_at = $data->created_at;
$documentos->updated_at = $data->updated_at;
if($documentos->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update documentos","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update documentos. Data is incomplete.","document"=> ""));
}
?>
