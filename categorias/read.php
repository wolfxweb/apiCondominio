<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/categorias.php';
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
 
$categorias = new Categorias($db);

$categorias->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$categorias->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $categorias->read();
$num = $stmt->rowCount();
if($num>0){
    $categorias_arr=array();
	$categorias_arr["pageno"]=$categorias->pageNo;
	$categorias_arr["pagesize"]=$categorias->no_of_records_per_page;
    $categorias_arr["total_count"]=$categorias->total_record_count();
    $categorias_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $categorias_item=array(
            
"cat_id" => $cat_id,
"cat_nome" => html_entity_decode($cat_nome),
"cat_descricao" => html_entity_decode($cat_descricao),
"cat_padrao" => $cat_padrao,
"usu_email" => html_entity_decode($usu_email),
"usu_id" => $usu_id
        );
         array_push($categorias_arr["records"], $categorias_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "categorias found","document"=> $categorias_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No categorias found.","document"=> ""));
}
 


