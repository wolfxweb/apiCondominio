<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/enquetes.php';
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
 
$enquetes = new Enquetes($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->enqu_titulo)
&&!isEmpty($data->enqu_descricao)
&&!isEmpty($data->enqu_inicio)
&&!isEmpty($data->enqu_final)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->enqu_titulo)) { 
$enquetes->enqu_titulo = $data->enqu_titulo;
} else { 
$enquetes->enqu_titulo = '';
}
if(!isEmpty($data->enqu_descricao)) { 
$enquetes->enqu_descricao = $data->enqu_descricao;
} else { 
$enquetes->enqu_descricao = '';
}
if(!isEmpty($data->enqu_inicio)) { 
$enquetes->enqu_inicio = $data->enqu_inicio;
} else { 
$enquetes->enqu_inicio = '';
}
if(!isEmpty($data->enqu_final)) { 
$enquetes->enqu_final = $data->enqu_final;
} else { 
$enquetes->enqu_final = '';
}
if(!isEmpty($data->cond_id)) { 
$enquetes->cond_id = $data->cond_id;
} else { 
$enquetes->cond_id = '';
}
$enquetes->created_at = $data->created_at;
$enquetes->updated_at = $data->updated_at;
 	$lastInsertedId=$enquetes->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create enquetes","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create enquetes. Data is incomplete.","document"=> ""));
}
?>
