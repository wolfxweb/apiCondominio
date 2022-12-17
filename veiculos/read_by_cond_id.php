<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/veiculos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$veiculos = new Veiculos($db);

$veiculos->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$veiculos->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$veiculos->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $veiculos->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $veiculos_arr=array();
	$veiculos_arr["pageno"]=$veiculos->pageNo;
	$veiculos_arr["pagesize"]=$veiculos->no_of_records_per_page;
    $veiculos_arr["total_count"]=$veiculos->total_record_count();
    $veiculos_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $veiculos_item=array(
            
"id" => $id,
"veic_modelo" => html_entity_decode($veic_modelo),
"veic_marca" => html_entity_decode($veic_marca),
"veic_cor" => html_entity_decode($veic_cor),
"veic_placa" => html_entity_decode($veic_placa),
"veic_obs" => html_entity_decode($veic_obs),
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($veiculos_arr["records"], $veiculos_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "veiculos found","document"=> $veiculos_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No veiculos found.","document"=> ""));
}
 


