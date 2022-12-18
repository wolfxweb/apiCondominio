<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/moradores.php';
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
 
$moradores = new Moradores($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->mora_name)
&&!isEmpty($data->mora_obs)
&&!isEmpty($data->mora_data_nacimento)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->mora_name)) { 
$moradores->mora_name = $data->mora_name;
} else { 
$moradores->mora_name = '';
}
if(!isEmpty($data->mora_obs)) { 
$moradores->mora_obs = $data->mora_obs;
} else { 
$moradores->mora_obs = '';
}
if(!isEmpty($data->mora_data_nacimento)) { 
$moradores->mora_data_nacimento = $data->mora_data_nacimento;
} else { 
$moradores->mora_data_nacimento = '';
}
if(!isEmpty($data->unid_id)) { 
$moradores->unid_id = $data->unid_id;
} else { 
$moradores->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$moradores->cond_id = $data->cond_id;
} else { 
$moradores->cond_id = '';
}
$moradores->created_at = $data->created_at;
$moradores->updated_at = $data->updated_at;
 	$lastInsertedId=$moradores->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create moradores","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create moradores. Data is incomplete.","document"=> ""));
}
?>
