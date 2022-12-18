<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/loja.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$loja = new Loja($db);

$loja->loj_id = isset($_GET['id']) ? $_GET['id'] : die();
$loja->readOne();
 
if($loja->loj_id!=null){
    $loja_arr = array(
        
"loj_id" => $loja->loj_id,
"nome" => html_entity_decode($loja->nome)
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "loja found","document"=> $loja_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "loja does not exist.","document"=> ""));
}
?>
