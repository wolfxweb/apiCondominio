<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/usuarios.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$usuarios = new Usuarios($db);

$usuarios->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$usuarios->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $usuarios->read();
$num = $stmt->rowCount();
if($num>0){
    $usuarios_arr=array();
	$usuarios_arr["pageno"]=$usuarios->pageNo;
	$usuarios_arr["pagesize"]=$usuarios->no_of_records_per_page;
    $usuarios_arr["total_count"]=$usuarios->total_record_count();
    $usuarios_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $usuarios_item=array(
            
"usu_id" => $usu_id,
"usu_name" => $usu_name,
"usu_email" => html_entity_decode($usu_email),
"usu_password" => $usu_password,
"usu_token_recuperar_senha" => $usu_token_recuperar_senha,
"usut_id" => $usut_id,
"usun_nome" => $usun_nome,
"usun_id" => $usun_id,
"usus_nome" => $usus_nome,
"usus_id" => $usus_id
        );
         array_push($usuarios_arr["records"], $usuarios_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuarios found","document"=> $usuarios_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No usuarios found.","document"=> ""));
}
 


