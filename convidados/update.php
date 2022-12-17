<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/convidados.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$convidados = new Convidados($db);

$data = json_decode(file_get_contents("php://input"));
$convidados->id = $data->id;

if(!isEmpty($data->conv_nome)
&&!isEmpty($data->conv_cpf)
&&!isEmpty($data->conv_rg)
&&!isEmpty($data->conv_telefone)
&&!isEmpty($data->conv_status)
&&!isEmpty($data->conv_data)
&&!isEmpty($data->conv_obs)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){

if(!isEmpty($data->conv_nome)) { 
$convidados->conv_nome = $data->conv_nome;
} else { 
$convidados->conv_nome = '';
}
if(!isEmpty($data->conv_cpf)) { 
$convidados->conv_cpf = $data->conv_cpf;
} else { 
$convidados->conv_cpf = '';
}
if(!isEmpty($data->conv_rg)) { 
$convidados->conv_rg = $data->conv_rg;
} else { 
$convidados->conv_rg = '';
}
if(!isEmpty($data->conv_telefone)) { 
$convidados->conv_telefone = $data->conv_telefone;
} else { 
$convidados->conv_telefone = '';
}
if(!isEmpty($data->conv_status)) { 
$convidados->conv_status = $data->conv_status;
} else { 
$convidados->conv_status = '';
}
if(!isEmpty($data->conv_data)) { 
$convidados->conv_data = $data->conv_data;
} else { 
$convidados->conv_data = '';
}
if(!isEmpty($data->conv_obs)) { 
$convidados->conv_obs = $data->conv_obs;
} else { 
$convidados->conv_obs = '';
}
if(!isEmpty($data->unid_id)) { 
$convidados->unid_id = $data->unid_id;
} else { 
$convidados->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$convidados->cond_id = $data->cond_id;
} else { 
$convidados->cond_id = '';
}
$convidados->created_at = $data->created_at;
$convidados->updated_at = $data->updated_at;
if($convidados->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update convidados","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update convidados. Data is incomplete.","document"=> ""));
}
?>
