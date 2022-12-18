<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/murallikes.php';
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

$murallikes = new Murallikes($db);

$murallikes->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$murallikes->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$murallikes->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $murallikes->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $murallikes_arr=array();
	$murallikes_arr["pageno"]=$murallikes->pageNo;
	$murallikes_arr["pagesize"]=$murallikes->no_of_records_per_page;
    $murallikes_arr["total_count"]=$murallikes->total_record_count();
    $murallikes_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $murallikes_item=array(
            
"id" => $id,
"mura_id" => $mura_id,
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($murallikes_arr["records"], $murallikes_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "murallikes found","document"=> $murallikes_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No murallikes found.","document"=> ""));
}
 


