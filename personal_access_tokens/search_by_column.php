<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/personal_access_tokens.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$personal_access_tokens = new Personal_Access_Tokens($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$personal_access_tokens->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$personal_access_tokens->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $personal_access_tokens->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $personal_access_tokens_arr=array();
	$personal_access_tokens_arr["pageno"]=$personal_access_tokens->pageNo;
	$personal_access_tokens_arr["pagesize"]=$personal_access_tokens->no_of_records_per_page;
    $personal_access_tokens_arr["total_count"]=$personal_access_tokens->search_record_count($data,$orAnd);
    $personal_access_tokens_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $personal_access_tokens_item=array(
            
"id" => $id,
"tokenable_type" => html_entity_decode($tokenable_type),
"tokenable_id" => $tokenable_id,
"name" => html_entity_decode($name),
"token" => $token,
"abilities" => $abilities,
"last_used_at" => $last_used_at,
"expires_at" => $expires_at,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($personal_access_tokens_arr["records"], $personal_access_tokens_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "personal_access_tokens found","document"=> $personal_access_tokens_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No personal_access_tokens found.","document"=> ""));
}
 


