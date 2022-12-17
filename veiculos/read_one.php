<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/veiculos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$veiculos = new Veiculos($db);

$veiculos->id = isset($_GET['id']) ? $_GET['id'] : die();
$veiculos->readOne();
 
if($veiculos->id!=null){
    $veiculos_arr = array(
        
"id" => $veiculos->id,
"veic_modelo" => html_entity_decode($veiculos->veic_modelo),
"veic_marca" => html_entity_decode($veiculos->veic_marca),
"veic_cor" => html_entity_decode($veiculos->veic_cor),
"veic_placa" => html_entity_decode($veiculos->veic_placa),
"veic_obs" => html_entity_decode($veiculos->veic_obs),
"unid_name" => html_entity_decode($veiculos->unid_name),
"unid_id" => $veiculos->unid_id,
"cond_nome" => html_entity_decode($veiculos->cond_nome),
"cond_id" => $veiculos->cond_id,
"created_at" => $veiculos->created_at,
"updated_at" => $veiculos->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "veiculos found","document"=> $veiculos_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "veiculos does not exist.","document"=> ""));
}
?>
