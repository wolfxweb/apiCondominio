<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/produto.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$produto = new Produto($db);

$produto->prod_id = isset($_GET['id']) ? $_GET['id'] : die();
$produto->readOne();
 
if($produto->prod_id!=null){
    $produto_arr = array(
        
"prod_id" => $produto->prod_id,
"prod_nome" => $produto->prod_nome,
"prod_preco" => $produto->prod_preco,
"cat_nome" => html_entity_decode($produto->cat_nome),
"cat_id" => $produto->cat_id,
"uni_sigla" => $produto->uni_sigla,
"uni_id" => $produto->uni_id,
"mar_nome" => html_entity_decode($produto->mar_nome),
"mar_id" => $produto->mar_id,
"itep_id" => $produto->itep_id,
"pro_url_img" => $produto->pro_url_img
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "produto found","document"=> $produto_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "produto does not exist.","document"=> ""));
}
?>
