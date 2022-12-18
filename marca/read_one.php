<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/marca.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$marca = new Marca($db);

$marca->mar_id = isset($_GET['id']) ? $_GET['id'] : die();
$marca->readOne();
 
if($marca->mar_id!=null){
    $marca_arr = array(
        
"mar_id" => $marca->mar_id,
"mar_nome" => html_entity_decode($marca->mar_nome),
"mar_padrao" => $marca->mar_padrao
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "marca found","document"=> $marca_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "marca does not exist.","document"=> ""));
}
?>
