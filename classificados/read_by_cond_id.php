<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/classificados.php';
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

$classificados = new Classificados($db);

$classificados->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$classificados->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$classificados->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $classificados->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $classificados_arr=array();
	$classificados_arr["pageno"]=$classificados->pageNo;
	$classificados_arr["pagesize"]=$classificados->no_of_records_per_page;
    $classificados_arr["total_count"]=$classificados->total_record_count();
    $classificados_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $classificados_item=array(
            
"id" => $id,
"class_titulo" => html_entity_decode($class_titulo),
"class_fotos" => $class_fotos,
"class_descricao" => $class_descricao,
"class_preco" => $class_preco,
"class_data" => $class_data,
"user_name" => html_entity_decode($user_name),
"user_id" => $user_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($classificados_arr["records"], $classificados_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "classificados found","document"=> $classificados_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No classificados found.","document"=> ""));
}
 


