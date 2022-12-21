<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/categorias.php';
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

$categorias = new Categorias($db);

$categorias->cat_id = isset($_GET['id']) ? $_GET['id'] : die();
$categorias->readOne();
 
if($categorias->cat_id!=null){
    $categorias_arr = array(
        
"cat_id" => $categorias->cat_id,
"cat_nome" => html_entity_decode($categorias->cat_nome),
"cat_descricao" => html_entity_decode($categorias->cat_descricao),
"cat_padrao" => $categorias->cat_padrao,
"usu_email" => html_entity_decode($categorias->usu_email),
"usu_id" => $categorias->usu_id
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "categorias found","document"=> $categorias_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "categorias does not exist.","document"=> ""));
}
?>
