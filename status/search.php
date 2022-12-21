<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/status.php';
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

$status = new Status($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$status->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$status->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $status->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $status_arr=array();
	$status_arr["pageno"]=$status->pageNo;
	$status_arr["pagesize"]=$status->no_of_records_per_page;
    $status_arr["total_count"]=$status->search_count($searchKey);
    $status_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $status_item=array(
            
"sta_id" => $sta_id,
"sta_sigla" => $sta_sigla,
"sta_nome" => $sta_nome
        );
        array_push($status_arr["records"], $status_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "status found","document"=> $status_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No status found.","document"=> ""));
}
 


