<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/status.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$status = new Status($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->sta_name)){
	
    
$status->sta_sigla = $data->sta_sigla;
if(!isEmpty($data->sta_name)) { 
$status->sta_name = $data->sta_name;
} else { 
$status->sta_name = '';
}
 	$lastInsertedId=$status->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create status","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create status. Data is incomplete.","document"=> ""));
}
?>
