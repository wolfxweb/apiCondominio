<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/marca.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$marca = new Marca($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$marca->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$marca->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $marca->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $marca_arr=array();
	$marca_arr["pageno"]=$marca->pageNo;
	$marca_arr["pagesize"]=$marca->no_of_records_per_page;
    $marca_arr["total_count"]=$marca->search_count($searchKey);
    $marca_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $marca_item=array(
            
"mar_id" => $mar_id,
"mar_nome" => html_entity_decode($mar_nome),
"mar_padrao" => $mar_padrao
        );
        array_push($marca_arr["records"], $marca_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "marca found","document"=> $marca_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No marca found.","document"=> ""));
}
 


