<?php

include_once '../token/token.php';
include_once '../model/model.php';
include_once '../notification/response.php';
include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;

class Usuarios extends Model{
 
 
    private $table_name = "usuarios";
	public $pageNo = 1;
	public  $no_of_records_per_page=30;
	
	public $usu_id;
	public $usu_nome;
	public $usu_email;
	public $usu_password;
	public $usu_reset_token;
	public $sta_id;
	public $usut_id;
	public $sta_nome;
	public $usut_nome;
	public $dados =[];
		
    public function __construct($data){
	  foreach($data as $key => $value ){
		if($key == "usu_password"){
			$this->$key = md5($value) ;
		}else{
			$this->$key = $value;
		}
		$this->dados[$key]=$this->$key ;
	  }
    }
	public  function validaLogin(){
	   $userLogado =  $this->coutTabela($this->table_name, $this->dados);
	   $this->novoToken($userLogado ,LOGIN_VALIDO);
	}
	public function cadastro(){
		if( $this->coutTabela($this->table_name, $this->dados)> 0){
			Response::response(EMAIL_INVALIDO, "",REQ_ERROR_DADOS_ENVIADO,ERROR,0 );
			return  false;
		}
		if($this->insert($this->table_name,$this->dados) == TRUE){
			Response::response(CADASTRADO_CRIADO,"",REQ_CRIADO);
		    return	$this->validaLogin();
		}else{
			Response::response(ERROR_CADASTRO, "",REQ_ERROR_SERVIDOR,ERROR,0 );
		} 
	}

	public function recuperarSenha(){

		$tokenReset = '123456';



	}

	private function novoToken($userLogado,$msgResponse,){
		if($userLogado > 0){
			$token["data"]= $this->dados["usu_email"];
			$jwt = JWT::encode($token, SECRET_KEY);
			$iat = time() + (1 * 24 * 60 * 60); 	  
			$tokenExp = $iat + 60 * 87600; 
			$tokenOutput = array( "access_token" => $jwt, "expires_in" => $tokenExp, "token_type" => "bearer");
			Response::response($msgResponse,$tokenOutput,REQ_SOLICITACAO_OK,SUCCESS,1 );
            return true;
		}else{
			Response::response(CAMPOS_INVALIDOS,"",REQ_ERROR_DADOS_ENVIADO,ERROR,0);
			return false;
		}
	}

	public function responseError($string){
		Response::response(Response::fraseValidacao($string), "",REQ_ERROR_DADOS_ENVIADO,ERROR,0);
		return false;
	}

		
}
?>
