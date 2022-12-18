<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/boletos.php';
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

$boletos = new Boletos($db);

$data = json_decode(file_get_contents("php://input"));
$boletos->id = $data->id;

if(!isEmpty($data->bole_id)
&&!isEmpty($data->bole_titulos)
&&!isEmpty($data->bole_url)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->bole_id)) { 
$boletos->bole_id = $data->bole_id;
} else { 
$boletos->bole_id = '';
}
if(!isEmpty($data->bole_titulos)) { 
$boletos->bole_titulos = $data->bole_titulos;
} else { 
$boletos->bole_titulos = '';
}
if(!isEmpty($data->bole_url)) { 
$boletos->bole_url = $data->bole_url;
} else { 
$boletos->bole_url = '';
}
if(!isEmpty($data->unid_id)) { 
$boletos->unid_id = $data->unid_id;
} else { 
$boletos->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$boletos->cond_id = $data->cond_id;
} else { 
$boletos->cond_id = '';
}
$boletos->created_at = $data->created_at;
$boletos->updated_at = $data->updated_at;
if($boletos->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update boletos","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update boletos. Data is incomplete.","document"=> ""));
}
?>
