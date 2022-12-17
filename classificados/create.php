<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/classificados.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$classificados = new Classificados($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->class_titulo)
&&!isEmpty($data->class_fotos)
&&!isEmpty($data->class_descricao)
&&!isEmpty($data->class_preco)
&&!isEmpty($data->class_data)
&&!isEmpty($data->user_id)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->class_titulo)) { 
$classificados->class_titulo = $data->class_titulo;
} else { 
$classificados->class_titulo = '';
}
if(!isEmpty($data->class_fotos)) { 
$classificados->class_fotos = $data->class_fotos;
} else { 
$classificados->class_fotos = '';
}
if(!isEmpty($data->class_descricao)) { 
$classificados->class_descricao = $data->class_descricao;
} else { 
$classificados->class_descricao = '';
}
if(!isEmpty($data->class_preco)) { 
$classificados->class_preco = $data->class_preco;
} else { 
$classificados->class_preco = '';
}
if(!isEmpty($data->class_data)) { 
$classificados->class_data = $data->class_data;
} else { 
$classificados->class_data = '';
}
if(!isEmpty($data->user_id)) { 
$classificados->user_id = $data->user_id;
} else { 
$classificados->user_id = '';
}
if(!isEmpty($data->cond_id)) { 
$classificados->cond_id = $data->cond_id;
} else { 
$classificados->cond_id = '';
}
$classificados->created_at = $data->created_at;
$classificados->updated_at = $data->updated_at;
 	$lastInsertedId=$classificados->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create classificados","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create classificados. Data is incomplete.","document"=> ""));
}
?>
