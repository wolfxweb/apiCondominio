<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/visitantes.php';
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

$visitantes = new Visitantes($db);

$data = json_decode(file_get_contents("php://input"));
$visitantes->id = $data->id;

if(!isEmpty($data->visi_nome)
&&!isEmpty($data->visi_cpf)
&&!isEmpty($data->visi_rg)
&&!isEmpty($data->visi_telefone)
&&!isEmpty($data->visi_obs)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->visi_nome)) { 
$visitantes->visi_nome = $data->visi_nome;
} else { 
$visitantes->visi_nome = '';
}
if(!isEmpty($data->visi_cpf)) { 
$visitantes->visi_cpf = $data->visi_cpf;
} else { 
$visitantes->visi_cpf = '';
}
if(!isEmpty($data->visi_rg)) { 
$visitantes->visi_rg = $data->visi_rg;
} else { 
$visitantes->visi_rg = '';
}
if(!isEmpty($data->visi_telefone)) { 
$visitantes->visi_telefone = $data->visi_telefone;
} else { 
$visitantes->visi_telefone = '';
}
if(!isEmpty($data->visi_obs)) { 
$visitantes->visi_obs = $data->visi_obs;
} else { 
$visitantes->visi_obs = '';
}
if(!isEmpty($data->unid_id)) { 
$visitantes->unid_id = $data->unid_id;
} else { 
$visitantes->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$visitantes->cond_id = $data->cond_id;
} else { 
$visitantes->cond_id = '';
}
$visitantes->created_at = $data->created_at;
$visitantes->updated_at = $data->updated_at;
if($visitantes->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update visitantes","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update visitantes. Data is incomplete.","document"=> ""));
}
?>
