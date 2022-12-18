<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/murals.php';
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

$murals = new Murals($db);

$murals->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$murals->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$murals->unid_id = isset($_GET['unid_id']) ? $_GET['unid_id'] : die();

$stmt = $murals->readByunid_id();
$num = $stmt->rowCount();

if($num>0){
    $murals_arr=array();
	$murals_arr["pageno"]=$murals->pageNo;
	$murals_arr["pagesize"]=$murals->no_of_records_per_page;
    $murals_arr["total_count"]=$murals->total_record_count();
    $murals_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $murals_item=array(
            
"id" => $id,
"mura_titulo" => html_entity_decode($mura_titulo),
"mura_msg" => html_entity_decode($mura_msg),
"mura_status" => html_entity_decode($mura_status),
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($murals_arr["records"], $murals_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "murals found","document"=> $murals_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No murals found.","document"=> ""));
}
 


