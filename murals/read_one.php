<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/murals.php';
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

$murals = new Murals($db);

$murals->id = isset($_GET['id']) ? $_GET['id'] : die();
$murals->readOne();
 
if($murals->id!=null){
    $murals_arr = array(
        
"id" => $murals->id,
"mura_titulo" => html_entity_decode($murals->mura_titulo),
"mura_msg" => html_entity_decode($murals->mura_msg),
"mura_status" => html_entity_decode($murals->mura_status),
"unid_name" => html_entity_decode($murals->unid_name),
"unid_id" => $murals->unid_id,
"cond_nome" => html_entity_decode($murals->cond_nome),
"cond_id" => $murals->cond_id,
"created_at" => $murals->created_at,
"updated_at" => $murals->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "murals found","document"=> $murals_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "murals does not exist.","document"=> ""));
}
?>
