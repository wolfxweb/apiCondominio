<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/murallikes.php';
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

$murallikes = new Murallikes($db);

$murallikes->id = isset($_GET['id']) ? $_GET['id'] : die();
$murallikes->readOne();
 
if($murallikes->id!=null){
    $murallikes_arr = array(
        
"id" => $murallikes->id,
"mura_id" => $murallikes->mura_id,
"unid_name" => html_entity_decode($murallikes->unid_name),
"unid_id" => $murallikes->unid_id,
"cond_nome" => html_entity_decode($murallikes->cond_nome),
"cond_id" => $murallikes->cond_id,
"created_at" => $murallikes->created_at,
"updated_at" => $murallikes->updated_at
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "murallikes found","document"=> $murallikes_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "murallikes does not exist.","document"=> ""));
}
?>
