<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/planos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$planos = new Planos($db);

$data = json_decode(file_get_contents("php://input"));
$planos->id = $data->id;

if(!isEmpty($data->plan_nome)
&&!isEmpty($data->plan_status)
&&!isEmpty($data->plan_preco)
&&!isEmpty($data->plan_obs)){

if(!isEmpty($data->plan_nome)) { 
$planos->plan_nome = $data->plan_nome;
} else { 
$planos->plan_nome = '';
}
if(!isEmpty($data->plan_status)) { 
$planos->plan_status = $data->plan_status;
} else { 
$planos->plan_status = '';
}
if(!isEmpty($data->plan_preco)) { 
$planos->plan_preco = $data->plan_preco;
} else { 
$planos->plan_preco = '';
}
if(!isEmpty($data->plan_obs)) { 
$planos->plan_obs = $data->plan_obs;
} else { 
$planos->plan_obs = '';
}
$planos->created_at = $data->created_at;
$planos->updated_at = $data->updated_at;
if($planos->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update planos","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update planos. Data is incomplete.","document"=> ""));
}
?>
