<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/reservas.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$reservas = new Reservas($db);

$reservas->id = isset($_GET['id']) ? $_GET['id'] : die();
$reservas->readOne();
 
if($reservas->id!=null){
    $reservas_arr = array(
        
"id" => $reservas->id,
"area_id" => $reservas->area_id,
"rese_day" => $reservas->rese_day,
"unid_name" => html_entity_decode($reservas->unid_name),
"unid_id" => $reservas->unid_id,
"cond_nome" => html_entity_decode($reservas->cond_nome),
"cond_id" => $reservas->cond_id,
"created_at" => $reservas->created_at,
"updated_at" => $reservas->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "reservas found","document"=> $reservas_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "reservas does not exist.","document"=> ""));
}
?>
