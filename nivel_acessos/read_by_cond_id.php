<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/nivel_acessos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$nivel_acessos = new Nivel_Acessos($db);

$nivel_acessos->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$nivel_acessos->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$nivel_acessos->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $nivel_acessos->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $nivel_acessos_arr=array();
	$nivel_acessos_arr["pageno"]=$nivel_acessos->pageNo;
	$nivel_acessos_arr["pagesize"]=$nivel_acessos->no_of_records_per_page;
    $nivel_acessos_arr["total_count"]=$nivel_acessos->total_record_count();
    $nivel_acessos_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $nivel_acessos_item=array(
            
"id" => $id,
"nive_titulo" => html_entity_decode($nive_titulo),
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($nivel_acessos_arr["records"], $nivel_acessos_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "nivel_acessos found","document"=> $nivel_acessos_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No nivel_acessos found.","document"=> ""));
}
 


