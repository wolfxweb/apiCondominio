<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/murals.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$murals = new Murals($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->mura_titulo)
&&!isEmpty($data->mura_msg)
&&!isEmpty($data->mura_status)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->mura_titulo)) { 
$murals->mura_titulo = $data->mura_titulo;
} else { 
$murals->mura_titulo = '';
}
if(!isEmpty($data->mura_msg)) { 
$murals->mura_msg = $data->mura_msg;
} else { 
$murals->mura_msg = '';
}
if(!isEmpty($data->mura_status)) { 
$murals->mura_status = $data->mura_status;
} else { 
$murals->mura_status = '';
}
if(!isEmpty($data->unid_id)) { 
$murals->unid_id = $data->unid_id;
} else { 
$murals->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$murals->cond_id = $data->cond_id;
} else { 
$murals->cond_id = '';
}
$murals->created_at = $data->created_at;
$murals->updated_at = $data->updated_at;
 	$lastInsertedId=$murals->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create murals","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create murals. Data is incomplete.","document"=> ""));
}
?>
