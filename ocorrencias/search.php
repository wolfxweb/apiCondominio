<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/ocorrencias.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$ocorrencias = new Ocorrencias($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$ocorrencias->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$ocorrencias->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $ocorrencias->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $ocorrencias_arr=array();
	$ocorrencias_arr["pageno"]=$ocorrencias->pageNo;
	$ocorrencias_arr["pagesize"]=$ocorrencias->no_of_records_per_page;
    $ocorrencias_arr["total_count"]=$ocorrencias->search_count($searchKey);
    $ocorrencias_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $ocorrencias_item=array(
            
"id" => $id,
"ocor_id" => $ocor_id,
"ocor_titulos" => html_entity_decode($ocor_titulos),
"ocor_status" => html_entity_decode($ocor_status),
"ocor_data_cadastro" => $ocor_data_cadastro,
"ocor_fotos" => $ocor_fotos,
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($ocorrencias_arr["records"], $ocorrencias_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "ocorrencias found","document"=> $ocorrencias_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No ocorrencias found.","document"=> ""));
}
 


