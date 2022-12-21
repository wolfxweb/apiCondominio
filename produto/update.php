<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/produto.php';
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

$produto = new Produto($db);

$data = json_decode(file_get_contents("php://input"));
$produto->pro_id = $data->pro_id;

if(!isEmpty($data->prod_nome)){

if(!isEmpty($data->prod_nome)) { 
$produto->prod_nome = $data->prod_nome;
} else { 
$produto->prod_nome = '';
}
$produto->prod_descricao = $data->prod_descricao;
$produto->prod_preco = $data->prod_preco;
$produto->unid_id = $data->unid_id;
$produto->cat_id = $data->cat_id;
$produto->usu_id = $data->usu_id;
if($produto->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update produto","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update produto. Data is incomplete.","document"=> ""));
}
?>
