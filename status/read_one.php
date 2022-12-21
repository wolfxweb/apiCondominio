<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/status.php';
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

$status = new Status($db);

$status->sta_id = isset($_GET['id']) ? $_GET['id'] : die();
$status->readOne();
 
if($status->sta_id!=null){
    $status_arr = array(
        
"sta_id" => $status->sta_id,
"sta_sigla" => $status->sta_sigla,
"sta_nome" => $status->sta_nome
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "status found","document"=> $status_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "status does not exist.","document"=> ""));
}
?>
