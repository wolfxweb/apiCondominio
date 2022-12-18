<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/unidades.php';
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

$unidades = new Unidades($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$unidades->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$unidades->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $unidades->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $unidades_arr=array();
	$unidades_arr["pageno"]=$unidades->pageNo;
	$unidades_arr["pagesize"]=$unidades->no_of_records_per_page;
    $unidades_arr["total_count"]=$unidades->search_count($searchKey);
    $unidades_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $unidades_item=array(
            
"id" => $id,
"unid_name" => html_entity_decode($unid_name),
"unid_obs" => html_entity_decode($unid_obs),
"user_name" => html_entity_decode($user_name),
"user_id" => $user_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($unidades_arr["records"], $unidades_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "unidades found","document"=> $unidades_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No unidades found.","document"=> ""));
}
 


