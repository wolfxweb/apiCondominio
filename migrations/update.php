<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/migrations.php';
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

$migrations = new Migrations($db);

$data = json_decode(file_get_contents("php://input"));
$migrations->id = $data->id;

if(!isEmpty($data->migration)
&&!isEmpty($data->batch)){

if(!isEmpty($data->migration)) { 
$migrations->migration = $data->migration;
} else { 
$migrations->migration = '';
}
if(!isEmpty($data->batch)) { 
$migrations->batch = $data->batch;
} else { 
$migrations->batch = '';
}
if($migrations->update()){
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","document"=> ""));
}
else{
    http_response_code(503);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update migrations","document"=> ""));
}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update migrations. Data is incomplete.","document"=> ""));
}
?>
