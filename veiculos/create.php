<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/veiculos.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();
 
$veiculos = new Veiculos($db);
$data = json_decode(file_get_contents("php://input"));

if(!isEmpty($data->veic_modelo)
&&!isEmpty($data->veic_marca)
&&!isEmpty($data->veic_cor)
&&!isEmpty($data->veic_placa)
&&!isEmpty($data->veic_obs)
&&!isEmpty($data->unid_id)
&&!isEmpty($data->cond_id)){
	
    
if(!isEmpty($data->veic_modelo)) { 
$veiculos->veic_modelo = $data->veic_modelo;
} else { 
$veiculos->veic_modelo = '';
}
if(!isEmpty($data->veic_marca)) { 
$veiculos->veic_marca = $data->veic_marca;
} else { 
$veiculos->veic_marca = '';
}
if(!isEmpty($data->veic_cor)) { 
$veiculos->veic_cor = $data->veic_cor;
} else { 
$veiculos->veic_cor = '';
}
if(!isEmpty($data->veic_placa)) { 
$veiculos->veic_placa = $data->veic_placa;
} else { 
$veiculos->veic_placa = '';
}
if(!isEmpty($data->veic_obs)) { 
$veiculos->veic_obs = $data->veic_obs;
} else { 
$veiculos->veic_obs = '';
}
if(!isEmpty($data->unid_id)) { 
$veiculos->unid_id = $data->unid_id;
} else { 
$veiculos->unid_id = '';
}
if(!isEmpty($data->cond_id)) { 
$veiculos->cond_id = $data->cond_id;
} else { 
$veiculos->cond_id = '';
}
$veiculos->created_at = $data->created_at;
$veiculos->updated_at = $data->updated_at;
 	$lastInsertedId=$veiculos->create();
    if($lastInsertedId!=0){
        http_response_code(201);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","document"=> $lastInsertedId));
    }
    else{
        http_response_code(503);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create veiculos","document"=> ""));
    }
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create veiculos. Data is incomplete.","document"=> ""));
}
?>
