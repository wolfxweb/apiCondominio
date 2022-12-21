<?php
include_once '../config/header.php';
include_once '../config/database.php';
include_once '../objects/usuarios.php';
include_once '../notification/response.php';
include_once '../token/token.php';
include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;
$database = new Database(); 
$db = $database->getConnection();
 

$data = json_decode(file_get_contents("php://input"));
if(!empty($data->usu_email) && !empty($data->usu_password)){
    $user = new Usuarios($data);
    $user->validaLogin();
 }