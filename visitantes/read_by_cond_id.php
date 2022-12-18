<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/visitantes.php';
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

$visitantes = new Visitantes($db);

$visitantes->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$visitantes->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
$visitantes->cond_id = isset($_GET['cond_id']) ? $_GET['cond_id'] : die();

$stmt = $visitantes->readBycond_id();
$num = $stmt->rowCount();

if($num>0){
    $visitantes_arr=array();
	$visitantes_arr["pageno"]=$visitantes->pageNo;
	$visitantes_arr["pagesize"]=$visitantes->no_of_records_per_page;
    $visitantes_arr["total_count"]=$visitantes->total_record_count();
    $visitantes_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $visitantes_item=array(
            
"id" => $id,
"visi_nome" => html_entity_decode($visi_nome),
"visi_cpf" => html_entity_decode($visi_cpf),
"visi_rg" => html_entity_decode($visi_rg),
"visi_telefone" => html_entity_decode($visi_telefone),
"visi_obs" => $visi_obs,
"unid_name" => html_entity_decode($unid_name),
"unid_id" => $unid_id,
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
        array_push($visitantes_arr["records"], $visitantes_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "visitantes found","document"=> $visitantes_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No visitantes found.","document"=> ""));
}
 


