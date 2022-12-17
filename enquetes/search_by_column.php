<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/enquetes.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$enquetes = new Enquetes($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$enquetes->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$enquetes->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $enquetes->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $enquetes_arr=array();
	$enquetes_arr["pageno"]=$enquetes->pageNo;
	$enquetes_arr["pagesize"]=$enquetes->no_of_records_per_page;
    $enquetes_arr["total_count"]=$enquetes->search_record_count($data,$orAnd);
    $enquetes_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $enquetes_item=array(
            
"id" => $id,
"enqu_titulo" => html_entity_decode($enqu_titulo),
"enqu_descricao" => $enqu_descricao,
"enqu_inicio" => $enqu_inicio,
"enqu_final" => $enqu_final,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
 
        array_push($enquetes_arr["records"], $enquetes_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "enquetes found","document"=> $enquetes_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No enquetes found.","document"=> ""));
}
 

