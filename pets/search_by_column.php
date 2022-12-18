<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pets.php';
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

$pets = new Pets($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$pets->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$pets->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $pets->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $pets_arr=array();
	$pets_arr["pageno"]=$pets->pageNo;
	$pets_arr["pagesize"]=$pets->no_of_records_per_page;
    $pets_arr["total_count"]=$pets->search_record_count($data,$orAnd);
    $pets_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $pets_item=array(
            
"id" => $id,
"pets_nome" => html_entity_decode($pets_nome),
"pets_raca" => html_entity_decode($pets_raca),
"pets_tipo" => html_entity_decode($pets_tipo),
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($pets_arr["records"], $pets_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "pets found","document"=> $pets_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No pets found.","document"=> ""));
}
 


