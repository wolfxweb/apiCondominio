<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidade_medida.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$unidade_medida = new Unidade_Medida($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$unidade_medida->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$unidade_medida->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $unidade_medida->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $unidade_medida_arr=array();
	$unidade_medida_arr["pageno"]=$unidade_medida->pageNo;
	$unidade_medida_arr["pagesize"]=$unidade_medida->no_of_records_per_page;
    $unidade_medida_arr["total_count"]=$unidade_medida->search_count($searchKey);
    $unidade_medida_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $unidade_medida_item=array(
            
"uni_id" => $uni_id,
"uni_sigla" => $uni_sigla,
"uni_nome" => $uni_nome,
"uni_padrao" => $uni_padrao
        );
        array_push($unidade_medida_arr["records"], $unidade_medida_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "unidade_medida found","document"=> $unidade_medida_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No unidade_medida found.","document"=> ""));
}
 


