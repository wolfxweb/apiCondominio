<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../config/helper.php';
include_once '../token/token.php';
include_once '../objects/usuarios.php';
include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();

$db = $database->getConnection();
 
$data = json_decode(file_get_contents("php://input"));
if(!empty($data->username) && !empty($data->password)){

//validate your username and password from database call (copy the code from other generated table files or user table (if you have user or admin table)
$users = new Usuarios($db);

$users->usu_email = $data->username;
$users->usu_password = md5($data->password);
$users->login_validation();

if($users->usu_id!=null){
$token["data"]=$users;

$jwt = JWT::encode($token, SECRET_KEY);
$tokenOutput = array("access_token" => $jwt, "expires_in" => $tokenExp, "token_type" => "bearer");
$userData=$users;
http_response_code(200);
echo json_encode(array("status" => "success", "code" => 1,"message"=> "Token Generated","document"=> $tokenOutput,"user"=>$userData));
}else{
	http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Invalid login.","document"=> ""));
	}
}
else{
    http_response_code(400);
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to login.","document"=> ""));
}
?>

