<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_nivel_acesso.php';
include_once '../token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();

$usuario_nivel_acesso = new Usuario_Nivel_Acesso($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$usuario_nivel_acesso->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$usuario_nivel_acesso->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $usuario_nivel_acesso->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $usuario_nivel_acesso_arr=array();
	$usuario_nivel_acesso_arr["pageno"]=$usuario_nivel_acesso->pageNo;
	$usuario_nivel_acesso_arr["pagesize"]=$usuario_nivel_acesso->no_of_records_per_page;
    $usuario_nivel_acesso_arr["total_count"]=$usuario_nivel_acesso->search_record_count($data,$orAnd);
    $usuario_nivel_acesso_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $usuario_nivel_acesso_item=array(
            
"usun_id" => $usun_id,
"usun_nome" => $usun_nome,
"usun_sigla" => $usun_sigla
        );
 
        array_push($usuario_nivel_acesso_arr["records"], $usuario_nivel_acesso_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuario_nivel_acesso found","document"=> $usuario_nivel_acesso_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No usuario_nivel_acesso found.","document"=> ""));
}
 


