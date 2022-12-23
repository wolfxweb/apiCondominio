<?php
include_once '../config/header.php';
include_once '../objects/usuarios.php';
include_once '../notification/response.php';

 

$data = json_decode(file_get_contents("php://input"));
if(!empty($data->usu_email) && !empty($data->usu_password)){
    $user = new Usuarios($data);
    $user->validaLogin();
 }else{
    Response::response(CAMPOS_INVALIDOS,"",REQ_ERROR_DADOS_ENVIADO,ERROR,0);
    return false;
 }