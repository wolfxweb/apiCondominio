<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/convidados.php';
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
 
$convidados = new Convidados($db);

$convidados->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$convidados->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $convidados->read();
$num = $stmt->rowCount();
if($num>0){
    $convidados_arr=array();
	$convidados_arr["pageno"]=$convidados->pageNo;
	$convidados_arr["pagesize"]=$convidados->no_of_records_per_page;
    $convidados_arr["total_count"]=$convidados->total_record_count();
    $convidados_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $convidados_item=array(
            
"id" => $id,
"conv_nome" => html_entity_decode($conv_nome),
"conv_cpf" => html_entity_decode($conv_cpf),
"conv_rg" => html_entity_decode($conv_rg),
"conv_telefone" => html_entity_decode($conv_telefone),
"conv_status" => html_entity_decode($conv_status),
"conv_data" => $conv_data,
"conv_obs" => $conv_obs,
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
         array_push($convidados_arr["records"], $convidados_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "convidados found","document"=> $convidados_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No convidados found.","document"=> ""));
}
 


