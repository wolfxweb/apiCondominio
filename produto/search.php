<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/produto.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$produto = new Produto($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$produto->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$produto->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $produto->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $produto_arr=array();
	$produto_arr["pageno"]=$produto->pageNo;
	$produto_arr["pagesize"]=$produto->no_of_records_per_page;
    $produto_arr["total_count"]=$produto->search_count($searchKey);
    $produto_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $produto_item=array(
            
"prod_id" => $prod_id,
"prod_nome" => $prod_nome,
"prod_preco" => $prod_preco,
"cat_nome" => html_entity_decode($cat_nome),
"cat_id" => $cat_id,
"uni_sigla" => $uni_sigla,
"uni_id" => $uni_id,
"mar_nome" => html_entity_decode($mar_nome),
"mar_id" => $mar_id,
"itep_id" => $itep_id,
"pro_url_img" => $pro_url_img
        );
        array_push($produto_arr["records"], $produto_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "produto found","document"=> $produto_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No produto found.","document"=> ""));
}
 


