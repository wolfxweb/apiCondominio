<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/achados_perdidos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$achados_perdidos = new Achados_Perdidos($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->acha_id)
&&!isEmpty($data->acha_titulo)
&&!isEmpty($data->ocor_status)
&&!isEmpty($data->acha_data_cadastro)
&&!isEmpty($data->acha_fotos)
&&!isEmpty($data->acha_descricao)
&&!isEmpty($data->usu_id)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->acha_id)) { 
$achados_perdidos->acha_id = $data->acha_id;
} else { 
$achados_perdidos->acha_id = '';
}
if(!isEmpty($data->acha_titulo)) { 
$achados_perdidos->acha_titulo = $data->acha_titulo;
} else { 
$achados_perdidos->acha_titulo = '';
}
if(!isEmpty($data->ocor_status)) { 
$achados_perdidos->ocor_status = $data->ocor_status;
} else { 
$achados_perdidos->ocor_status = 'Item perdido';
}
if(!isEmpty($data->acha_data_cadastro)) { 
$achados_perdidos->acha_data_cadastro = $data->acha_data_cadastro;
} else { 
$achados_perdidos->acha_data_cadastro = '';
}
if(!isEmpty($data->acha_fotos)) { 
$achados_perdidos->acha_fotos = $data->acha_fotos;
} else { 
$achados_perdidos->acha_fotos = '';
}
if(!isEmpty($data->acha_descricao)) { 
$achados_perdidos->acha_descricao = $data->acha_descricao;
} else { 
$achados_perdidos->acha_descricao = '';
}
if(!isEmpty($data->usu_id)) { 
$achados_perdidos->usu_id = $data->usu_id;
} else { 
$achados_perdidos->usu_id = '';
}
if(!isEmpty($data->cond_id)) { 
$achados_perdidos->cond_id = $data->cond_id;
} else { 
$achados_perdidos->cond_id = '';
}
$achados_perdidos->created_at = $data->created_at;
$achados_perdidos->updated_at = $data->updated_at;
 	$lastInsertedId=$achados_perdidos->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create achados_perdidos","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create achados_perdidos. Data is incomplete.","document"=> ""));
}
?>
