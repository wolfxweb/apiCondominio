<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/users.php';
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
 
$users = new Users($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->user_name)
&&!isEmpty($data->user_email)
&&!isEmpty($data->user_cpf)
&&!isEmpty($data->user_rg)
&&!isEmpty($data->user_foto)
&&!isEmpty($data->nive_id)
&&!isEmpty($data->user_token)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->user_name)) { 
$users->user_name = $data->user_name;
} else { 
$users->user_name = '';
}
if(!isEmpty($data->user_email)) { 
$users->user_email = $data->user_email;
} else { 
$users->user_email = '';
}
if(!isEmpty($data->user_password)) { 
$users->user_password = md5($data->user_password);
}
if(!isEmpty($data->user_cpf)) { 
$users->user_cpf = $data->user_cpf;
} else { 
$users->user_cpf = '';
}
if(!isEmpty($data->user_rg)) { 
$users->user_rg = $data->user_rg;
} else { 
$users->user_rg = '';
}
if(!isEmpty($data->user_foto)) { 
$users->user_foto = $data->user_foto;
} else { 
$users->user_foto = '';
}
if(!isEmpty($data->nive_id)) { 
$users->nive_id = $data->nive_id;
} else { 
$users->nive_id = '';
}
if(!isEmpty($data->user_token)) { 
$users->user_token = $data->user_token;
} else { 
$users->user_token = '';
}
if(!isEmpty($data->cond_id)) { 
$users->cond_id = $data->cond_id;
} else { 
$users->cond_id = '';
}
$users->remember_token = $data->remember_token;
$users->created_at = $data->created_at;
$users->updated_at = $data->updated_at;
 	$lastInsertedId=$users->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create users","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create users. Data is incomplete.","document"=> ""));
}
?>
