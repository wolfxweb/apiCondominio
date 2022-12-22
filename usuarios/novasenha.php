<?php

include_once '../config/header.php';

include_once '../objects/usuarios.php';


$data = json_decode(file_get_contents("php://input"));

$user = new Usuarios($data);

if(empty($data->usu_email)){
    $user->responseError("email");
    return false;
}
if(empty($data->usu_token_recuperar_senha)){
    $user->responseError("token");
    return false;
}
if(empty($data->usu_password)){
    $user->responseError("token");
    return false;
}

if(!empty($data->usu_token_recuperar_senha) &&!empty($data->usu_email)){
    $user->novaSenha();
}else{
    $user->responseError("");
    return false;
}