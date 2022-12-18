<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/achados_perdidos.php';
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

$achados_perdidos = new Achados_Perdidos($db);

$searchKey = isset($_GET['key']) ? $_GET['key'] : die();
$achados_perdidos->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$achados_perdidos->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $achados_perdidos->search($searchKey);
$num = $stmt->rowCount();
 
if($num>0){
    $achados_perdidos_arr=array();
	$achados_perdidos_arr["pageno"]=$achados_perdidos->pageNo;
	$achados_perdidos_arr["pagesize"]=$achados_perdidos->no_of_records_per_page;
    $achados_perdidos_arr["total_count"]=$achados_perdidos->search_count($searchKey);
    $achados_perdidos_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $achados_perdidos_item=array(
            
"id" => $id,
"acha_id" => $acha_id,
"acha_titulo" => html_entity_decode($acha_titulo),
"ocor_status" => html_entity_decode($ocor_status),
"acha_data_cadastro" => $acha_data_cadastro,
"acha_fotos" => $acha_fotos,
"acha_descricao" => $acha_descricao,
"user_name" => html_entity_decode($user_name),
"usu_id" => $usu_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($achados_perdidos_arr["records"], $achados_perdidos_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "achados_perdidos found","document"=> $achados_perdidos_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No achados_perdidos found.","document"=> ""));
}
 


