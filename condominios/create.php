<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/condominios.php';
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
 
$condominios = new Condominios($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->cond_nome)
&&!isEmpty($data->cond_rua)
&&!isEmpty($data->cond_barirro)
&&!isEmpty($data->cond_cidade)
&&!isEmpty($data->cond_estado)
&&!isEmpty($data->cond_cep)
&&!isEmpty($data->cond_numero)
&&!isEmpty($data->cond_status)
&&!isEmpty($data->plan_id)
&&!isEmpty($data->cond_slug)
&&!isEmpty($data->cond_obs)){
	
    
if(!isEmpty($data->cond_nome)) { 
$condominios->cond_nome = $data->cond_nome;
} else { 
$condominios->cond_nome = '';
}
if(!isEmpty($data->cond_rua)) { 
$condominios->cond_rua = $data->cond_rua;
} else { 
$condominios->cond_rua = '';
}
if(!isEmpty($data->cond_barirro)) { 
$condominios->cond_barirro = $data->cond_barirro;
} else { 
$condominios->cond_barirro = '';
}
if(!isEmpty($data->cond_cidade)) { 
$condominios->cond_cidade = $data->cond_cidade;
} else { 
$condominios->cond_cidade = '';
}
if(!isEmpty($data->cond_estado)) { 
$condominios->cond_estado = $data->cond_estado;
} else { 
$condominios->cond_estado = '';
}
if(!isEmpty($data->cond_cep)) { 
$condominios->cond_cep = $data->cond_cep;
} else { 
$condominios->cond_cep = '';
}
if(!isEmpty($data->cond_numero)) { 
$condominios->cond_numero = $data->cond_numero;
} else { 
$condominios->cond_numero = '';
}
if(!isEmpty($data->cond_status)) { 
$condominios->cond_status = $data->cond_status;
} else { 
$condominios->cond_status = '';
}
if(!isEmpty($data->plan_id)) { 
$condominios->plan_id = $data->plan_id;
} else { 
$condominios->plan_id = '';
}
if(!isEmpty($data->cond_slug)) { 
$condominios->cond_slug = $data->cond_slug;
} else { 
$condominios->cond_slug = '';
}
if(!isEmpty($data->cond_obs)) { 
$condominios->cond_obs = $data->cond_obs;
} else { 
$condominios->cond_obs = '';
}
$condominios->created_at = $data->created_at;
$condominios->updated_at = $data->updated_at;
 	$lastInsertedId=$condominios->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create condominios","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create condominios. Data is incomplete.","document"=> ""));
}
?>
