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

$produto->pro_id = isset($_GET['id']) ? $_GET['id'] : die();
$produto->readOne();
 
if($produto->pro_id!=null){
    $produto_arr = array(
        
"pro_id" => $produto->pro_id,
"prod_nome" => $produto->prod_nome,
"prod_descricao" => html_entity_decode($produto->prod_descricao),
"prod_preco" => $produto->prod_preco,
"unid_id" => $produto->unid_id,
"cat_nome" => html_entity_decode($produto->cat_nome),
"cat_id" => $produto->cat_id,
"usu_email" => html_entity_decode($produto->usu_email),
"usu_id" => $produto->usu_id
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "produto found","document"=> $produto_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "produto does not exist.","document"=> ""));
}
?>
