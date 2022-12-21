<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/usuarios.php';
include_once '../token/token.php';
include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database(); 
$db = $database->getConnection();
 

$data = json_decode(file_get_contents("php://input"));
if(!empty($data->username) && !empty($data->password)){

    $user = new Usuarios($db);
    $userLogado =   $user->validaLogin($data);
    $userLogado;
    if($userLogado > 0){
        $token["data"]=$data->username;
        $jwt = JWT::encode($token, SECRET_KEY);
        $tokenOutput = array( "access_token" => $jwt, "expires_in" => $tokenExp, "token_type" => "bearer");
        http_response_code(200);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Login realizado com sucesso","document"=>  $tokenOutput));

    }else{
      http_response_code(400);
	  echo json_encode(array("status" => "error", "code" => 0,"message"=> "Invalid login.","document"=> ""));
    }


}