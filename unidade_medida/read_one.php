<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidade_medida.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$unidade_medida = new Unidade_Medida($db);

$unidade_medida->uni_id = isset($_GET['id']) ? $_GET['id'] : die();
$unidade_medida->readOne();
 
if($unidade_medida->uni_id!=null){
    $unidade_medida_arr = array(
        
"uni_id" => $unidade_medida->uni_id,
"uni_sigla" => $unidade_medida->uni_sigla,
"uni_nome" => $unidade_medida->uni_nome,
"uni_padrao" => $unidade_medida->uni_padrao
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "unidade_medida found","document"=> $unidade_medida_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "unidade_medida does not exist.","document"=> ""));
}
?>
