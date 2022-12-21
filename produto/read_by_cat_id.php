<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/produto.php';
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

$produto = new Produto($db);

$produto->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$produto->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$produto->cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : die();

$stmt = $produto->readBycat_id();
$num = $stmt->rowCount();

if($num>0){
    $produto_arr=array();
	$produto_arr["pageno"]=$produto->pageNo;
	$produto_arr["pagesize"]=$produto->no_of_records_per_page;
    $produto_arr["total_count"]=$produto->total_record_count();
    $produto_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $produto_item=array(
            
"pro_id" => $pro_id,
"prod_nome" => $prod_nome,
"prod_descricao" => html_entity_decode($prod_descricao),
"prod_preco" => $prod_preco,
"unid_id" => $unid_id,
"cat_nome" => html_entity_decode($cat_nome),
"cat_id" => $cat_id,
"usu_email" => html_entity_decode($usu_email),
"usu_id" => $usu_id
        );
        array_push($produto_arr["records"], $produto_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "produto found","document"=> $produto_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No produto found.","document"=> ""));
}
 


