<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/documentos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$documentos = new Documentos($db);

$documentos->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$documentos->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$documentos->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $documentos->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $documentos_arr=array();
	$documentos_arr["pageno"]=$documentos->pageNo;
	$documentos_arr["pagesize"]=$documentos->no_of_records_per_page;
    $documentos_arr["total_count"]=$documentos->total_record_count();
    $documentos_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $documentos_item=array(
            
"id" => $id,
"docs_id" => $docs_id,
"docs_titulos" => html_entity_decode($docs_titulos),
"docs_url" => html_entity_decode($docs_url),
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($documentos_arr["records"], $documentos_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "documentos found","document"=> $documentos_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No documentos found.","document"=> ""));
}
 


