<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/moradores.php';
include_once '../token/validatetoken.php';

if (isset($decodedJWTData) && isset($decodedJWTData->tenant))
{
$database = new Database($decodedJWTData->tenant); 
}
else 
{
$database = new Database(); 
}

$db = $database->getConnection();

$moradores = new Moradores($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$moradores->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$moradores->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $moradores->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $moradores_arr=array();
	$moradores_arr["pageno"]=$moradores->pageNo;
	$moradores_arr["pagesize"]=$moradores->no_of_records_per_page;
    $moradores_arr["total_count"]=$moradores->search_count($searchKey);
    $moradores_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $moradores_item=array(
            
"id" => $id,
"mora_name" => html_entity_decode($mora_name),
"mora_obs" => html_entity_decode($mora_obs),
"mora_data_nacimento" => $mora_data_nacimento,
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($moradores_arr["records"], $moradores_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "moradores found","document"=> $moradores_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No moradores found.","document"=> ""));
}
 


