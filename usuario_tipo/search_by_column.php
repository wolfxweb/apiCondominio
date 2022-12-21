<?php
include_once '../config/header.php';
include_once '../config/helper.php';
include_once '../config/database.php';
include_once '../objects/usuario_tipo.php';
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

$usuario_tipo = new Usuario_Tipo($db);

$data = json_decode(file_get_contents("php://input"));
$orAnd = isset($_GET['orAnd']) ? $_GET['orAnd'] : "OR";

$usuario_tipo->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$usuario_tipo->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $usuario_tipo->searchByColumn($data,$orAnd);

$num = $stmt->rowCount();
if($num>0){
    $usuario_tipo_arr=array();
	$usuario_tipo_arr["pageno"]=$usuario_tipo->pageNo;
	$usuario_tipo_arr["pagesize"]=$usuario_tipo->no_of_records_per_page;
    $usuario_tipo_arr["total_count"]=$usuario_tipo->search_record_count($data,$orAnd);
    $usuario_tipo_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $usuario_tipo_item=array(
            
"usut_id" => $usut_id,
"usut_nome" => $usut_nome,
"usut_sigla" => $usut_sigla
        );
 
        array_push($usuario_tipo_arr["records"], $usuario_tipo_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "usuario_tipo found","document"=> $usuario_tipo_arr));
    
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No usuario_tipo found.","document"=> ""));
}
 


