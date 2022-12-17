<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/migrations.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$migrations = new Migrations($db);

$migrations->id = isset($_GET['id']) ? $_GET['id'] : die();
$migrations->readOne();
 
if($migrations->id!=null){
    $migrations_arr = array(
        
"id" => $migrations->id,
"migration" => html_entity_decode($migrations->migration),
"batch" => $migrations->batch
    );
    http_response_code(200);
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "migrations found","document"=> $migrations_arr));
}
else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "migrations does not exist.","document"=> ""));
}
?>
