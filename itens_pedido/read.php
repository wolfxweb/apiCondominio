<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/itens_pedido.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$itens_pedido = new Itens_Pedido($db);

$itens_pedido->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$itens_pedido->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $itens_pedido->read();
$num = $stmt->rowCount();
if($num>0){
    $itens_pedido_arr=array();
	$itens_pedido_arr["pageno"]=$itens_pedido->pageNo;
	$itens_pedido_arr["pagesize"]=$itens_pedido->no_of_records_per_page;
    $itens_pedido_arr["total_count"]=$itens_pedido->total_record_count();
    $itens_pedido_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $itens_pedido_item=array(
            
"itep_id" => $itep_id,
"ped_id" => $ped_id,
"prod_id" => $prod_id,
"itep_quantidade" => $itep_quantidade,
"itep_valor_total" => $itep_valor_total
        );
         array_push($itens_pedido_arr["records"], $itens_pedido_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "itens_pedido found","document"=> $itens_pedido_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No itens_pedido found.","document"=> ""));
}
 


