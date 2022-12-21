<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/pedido_item.php';
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

$pedido_item = new Pedido_Item($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$pedido_item->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$pedido_item->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $pedido_item->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $pedido_item_arr=array();
	$pedido_item_arr["pageno"]=$pedido_item->pageNo;
	$pedido_item_arr["pagesize"]=$pedido_item->no_of_records_per_page;
    $pedido_item_arr["total_count"]=$pedido_item->search_count($searchKey);
    $pedido_item_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $pedido_item_item=array(
            
"pedi_id" => $pedi_id,
"prod_nome" => $prod_nome,
"pro_id" => $pro_id,
"ped_titulo" => $ped_titulo,
"ped_id" => $ped_id,
"pedi_qtd" => $pedi_qtd,
"pedi_valor_unitario" => $pedi_valor_unitario,
"pedi_valor_total" => $pedi_valor_total
        );
        array_push($pedido_item_arr["records"], $pedido_item_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "pedido_item found","document"=> $pedido_item_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No pedido_item found.","document"=> ""));
}
 


