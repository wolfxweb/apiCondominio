<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_status.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$usuario_status = new Usuario_Status($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$usuario_status->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$usuario_status->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $usuario_status->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $usuario_status_arr=array();
	$usuario_status_arr["pageno"]=$usuario_status->pageNo;
	$usuario_status_arr["pagesize"]=$usuario_status->no_of_records_per_page;
    $usuario_status_arr["total_count"]=$usuario_status->search_record_count($data,$orAnd);
    $usuario_status_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $usuario_status_item=array(
            
"usus_id" => $usus_id,
"usus_nome" => $usus_nome,
"usus_sigla" => $usus_sigla
        );
 
        array_push($usuario_status_arr["records"], $usuario_status_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuario_status found","document"=> $usuario_status_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No usuario_status found.","document"=> ""));
}
 


