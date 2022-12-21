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
 	$lastInsertedId=$produto->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create produto","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create produto. Data is incomplete.","document"=> ""));
}
?>
