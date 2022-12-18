<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pets.php';
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

$pets = new Pets($db);

$data = json_decode(file_get_contents("php://input"));
$pets->id = $data->id;

if(!isEmpty($data->pets_nome)
&&!isEmpty($data->pets_raca)
&&!isEmpty($data->pets_tipo)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->pets_nome)) { 
$pets->pets_nome = $data->pets_nome;
} else { 
$pets->pets_nome = '';
}
if(!isEmpty($data->pets_raca)) { 
$pets->pets_raca = $data->pets_raca;
} else { 
$pets->pets_raca = '';
}
if(!isEmpty($data->pets_tipo)) { 
$pets->pets_tipo = $data->pets_tipo;
} else { 
$pets->pets_tipo = '';
}
if(!isEmpty($data->unid_id)) { 
$pets->unid_id = $data->unid_id;
} else { 
$pets->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$pets->cond_id = $data->cond_id;
} else { 
$pets->cond_id = '';
}
$pets->created_at = $data->created_at;
$pets->updated_at = $data->updated_at;
if($pets->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update pets","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update pets. Data is incomplete.","document"=> ""));
}
?>
