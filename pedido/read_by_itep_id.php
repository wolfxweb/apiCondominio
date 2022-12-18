<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pedido.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$pedido = new Pedido($db);

$pedido->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$pedido->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$pedido->itep_id = isset($_GET['itep_id']) ? $_GET['itep_id'] : die();

$stmt = $pedido->readByitep_id();
$num = $stmt->rowCount();

if($num>0){
    $pedido_arr=array();
	$pedido_arr["pageno"]=$pedido->pageNo;
	$pedido_arr["pagesize"]=$pedido->no_of_records_per_page;
    $pedido_arr["total_count"]=$pedido->total_record_count();
    $pedido_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $pedido_item=array(
            
"ped_id" => $ped_id,
"ped_nome" => html_entity_decode($ped_nome),
"ped_public" => $ped_public,
"ped_slug" => $ped_slug,
"ped_data_finalizacao" => $ped_data_finalizacao,
"ped_data_abetura" => $ped_data_abetura,
"sta_name" => $sta_name,
"sta_id" => $sta_id,
"nome" => html_entity_decode($nome),
"loj_id" => $loj_id,
"usu_email" => html_entity_decode($usu_email),
"usu_id" => $usu_id,
"itep_id" => $itep_id
        );
        array_push($pedido_arr["records"], $pedido_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "pedido found","document"=> $pedido_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No pedido found.","document"=> ""));
}
 


