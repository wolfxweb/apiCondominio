<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/enquete_votos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$enquete_votos = new Enquete_Votos($db);

$enquete_votos->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$enquete_votos->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$enquete_votos->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $enquete_votos->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $enquete_votos_arr=array();
	$enquete_votos_arr["pageno"]=$enquete_votos->pageNo;
	$enquete_votos_arr["pagesize"]=$enquete_votos->no_of_records_per_page;
    $enquete_votos_arr["total_count"]=$enquete_votos->total_record_count();
    $enquete_votos_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $enquete_votos_item=array(
            
"id" => $id,
"envo_voto" => $envo_voto,
"envo_horario" => $envo_horario,
"user_name" => html_entity_decode($user_name),
"user_id" => $user_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($enquete_votos_arr["records"], $enquete_votos_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "enquete_votos found","document"=> $enquete_votos_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No enquete_votos found.","document"=> ""));
}
 


