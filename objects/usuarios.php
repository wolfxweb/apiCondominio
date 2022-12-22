<?php

include_once '../token/token.php';
include_once '../model/model.php';
include_once '../notification/response.php';
include_once '../notification/emailRecuperacaoSenha.php';

include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;

class Usuarios extends Model{
 
 
    private $table_name = "usuarios usu";

	private $camposRetornoUsuario =" usu.usu_id , usu.usu_name , usu.usu_email , usus.usus_nome, usus.usus_sigla,usun.usun_nome,usun.usun_sigla ";
	private $joinCadastroUsuario =[	'usu_id'=>" JOIN usuario_status usus ON usus.usus_id = usu.usus_id ",
									'usn_id'=>" JOIN usuario_nivel_acesso usun ON usun.usun_id = usu.usun_id "
								];

    public function __construct($data){
	  $this->tabela  = " usuarios usu ";

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
		if($this->insert('usuarios',$this->dados) == TRUE){
		//	Response::response(CADASTRADO_CRIADO,"",REQ_CRIADO);
		    return	$this->validaLogin();
		}else{
			Response::response(ERROR_CADASTRO, "",REQ_ERROR_SERVIDOR,ERROR,0 );
		} 
	}

	public function recuperarSenha(){

		$usuario = $this->select($this->camposRetornoUsuario,$this->joinCadastroUsuario);
		if(count($usuario) == 0){
			Response::response('Usuário não encontrado', "",REQ_ERROR_DADOS_ENVIADO,ERROR,0 );
			return false;
		}
		$dados['usu_token_recuperar_senha'] =random_int(10000,99999);
		$where = "  where usu_id = {$usuario[0]['usu_id']}";
		$this->update($dados, $where );
        $email =[
          'destinatario'=>$usuario[0]['usu_email'],
		  'nomeDestinatario'=>$usuario[0]['usu_name'],
		  'titulo'=>'wolfx - Recuperacao senha.',
		  'assunto'=>'wolfx - Recuperacao senha.',
		  'body'=>"<p>Olá, {$usuario[0]["usu_name"]}<br>Segue o código {$dados["usu_token_recuperar_senha"]} para recuperar a senha </>",

		];
		$sendEmail = new EmailRecuperacaoSenha($email);
		$emailEnviado = $sendEmail->sendEmailRecuperarSenha();
        if($sendEmail->sendEmailRecuperarSenha()){
			Response::response(EMAIL_ENVIADO,"",REQ_CRIADO);
		}else{
			Response::response(EMAIL_FALHA_ENVIO,"",REQ_ERROR_DADOS_ENVIADO,ERROR,0 );
		}
		
	}

	private function novoToken($userLogado,$msgResponse,){
		if($userLogado > 0){
			$token["data"]= $this->dados["usu_email"];
			$jwt = JWT::encode($token, SECRET_KEY);
			$iat = time() + (1 * 24 * 60 * 60); 	  
			$tokenExp = $iat + 60 * 87600; 
			$tokenOutput = array( "access_token" => $jwt, "expires_in" => $tokenExp, "token_type" => "bearer","user"=>$this->selecionaUsuarioLogado());
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

	public function novaSenha(){
        $dados = $this->dados;
		unset($this->dados['usu_password']);
        $user = $this->coutTabela('usuarios', $this->dados);
        if($user > 0){
			$dados['usu_token_recuperar_senha'] =random_int(10000,99999);
			$where = "  where usu_email = '{$this->dados['usu_email']}'";
			if($this->update($dados, $where)){
				Response::response(CADASTRADO_ATUALIZADO,'',REQ_SOLICITACAO_OK,SUCCESS,1 );
			}else{
				Response::response(CAMPOS_INVALIDOS,"",REQ_ERROR_DADOS_ENVIADO,ERROR,0);
				return false;
			}
		 }else{
			Response::response(CAMPOS_INVALIDOS,"",REQ_ERROR_DADOS_ENVIADO,ERROR,0);
			return false;
		 }
	}
	private function selecionaUsuarioLogado(){
		$this->where = " WHERE usu.usu_email = maria6@gmail.com";
      $usuario =  $this->select($this->camposRetornoUsuario,$this->joinCadastroUsuario);
	  return $usuario[0];
	}
	
}
?>
