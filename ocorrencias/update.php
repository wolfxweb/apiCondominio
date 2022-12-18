<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/ocorrencias.php';
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

$ocorrencias = new Ocorrencias($db);

$data = json_decode(file_get_contents("php://input"));
$ocorrencias->id = $data->id;

if(!isEmpty($data->ocor_id)
&&!isEmpty($data->ocor_titulos)
&&!isEmpty($data->ocor_status)
&&!isEmpty($data->ocor_data_cadastro)
&&!isEmpty($data->ocor_fotos)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->ocor_id)) { 
$ocorrencias->ocor_id = $data->ocor_id;
} else { 
$ocorrencias->ocor_id = '';
}
if(!isEmpty($data->ocor_titulos)) { 
$ocorrencias->ocor_titulos = $data->ocor_titulos;
} else { 
$ocorrencias->ocor_titulos = '';
}
if(!isEmpty($data->ocor_status)) { 
$ocorrencias->ocor_status = $data->ocor_status;
} else { 
$ocorrencias->ocor_status = 'Em aberto';
}
if(!isEmpty($data->ocor_data_cadastro)) { 
$ocorrencias->ocor_data_cadastro = $data->ocor_data_cadastro;
} else { 
$ocorrencias->ocor_data_cadastro = '';
}
if(!isEmpty($data->ocor_fotos)) { 
$ocorrencias->ocor_fotos = $data->ocor_fotos;
} else { 
$ocorrencias->ocor_fotos = '';
}
if(!isEmpty($data->unid_id)) { 
$ocorrencias->unid_id = $data->unid_id;
} else { 
$ocorrencias->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$ocorrencias->cond_id = $data->cond_id;
} else { 
$ocorrencias->cond_id = '';
}
$ocorrencias->created_at = $data->created_at;
$ocorrencias->updated_at = $data->updated_at;
if($ocorrencias->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ocorrencias","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ocorrencias. Data is incomplete.","document"=> ""));
}
?>
