

<?php
include_once '../util/constantes.php';
class Response{

    public static function response($msg = ACAO_REALIZADA_SUCESSO, $data = "",$httpCode = REQ_SOLICITACAO_OK ,$status =SUCCESS, $code = 1 ){
        http_response_code($httpCode);
	    echo json_encode(array("status" => $status, "code" =>$code ,"message"=> $msg ,"document"=> $data));
    }
    public  static function fraseValidacao($string=""){
		if(!empty($string)){

			return CAMPOS_OBRIGATORIO .$string ;
		}else{
			return  CAMPOS_INVALIDOS;
		}
		
	}
}