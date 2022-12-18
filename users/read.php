<?php
include_once '../config/header.php';
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

$users->pageNo = isset($_GET['pageno']) ? $_GET['pageno'] : 1;
$users->no_of_records_per_page = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;

$stmt = $users->read();
$num = $stmt->rowCount();
if($num>0){
    $users_arr=array();
	$users_arr["pageno"]=$users->pageNo;
	$users_arr["pagesize"]=$users->no_of_records_per_page;
    $users_arr["total_count"]=$users->total_record_count();
    $users_arr["records"]=array();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
 
        $users_item=array(
            
"id" => $id,
"user_name" => html_entity_decode($user_name),
"user_email" => html_entity_decode($user_email),
"user_password" => html_entity_decode($user_password),
"user_cpf" => html_entity_decode($user_cpf),
"user_rg" => html_entity_decode($user_rg),
"user_foto" => html_entity_decode($user_foto),
"nive_id" => $nive_id,
"user_token" => html_entity_decode($user_token),
"cond_nome" => html_entity_decode($cond_nome),
"cond_id" => $cond_id,
"remember_token" => $remember_token,
"created_at" => $created_at,
"updated_at" => $updated_at
        );
         array_push($users_arr["records"], $users_item);
    }
    http_response_code(200);
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "users found","document"=> $users_arr));
}else{
    http_response_code(404);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "No users found.","document"=> ""));
}
 


