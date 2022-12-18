<?php
include_once '../config/header.php';
include_once '../token/token.php';
include_once '../jwt/BeforeValidException.php';
include_once '../jwt/ExpiredException.php';
include_once '../jwt/SignatureInvalidException.php';
include_once '../jwt/JWT.php';
use \Firebase\JWT\JWT;
 
$decodedJWTData=null; //Use this variable to read data anywhere in any other endpoint
$data = json_decode(file_get_contents("php://input"));

/** 
 * Get header Authorization
 * */
function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
try{
	  $jwtToken=  getBearerToken(); //token from header
	  //if header[Authorization] is coming null then set the .htaccess file with SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1 
	  if(!$jwtToken){
	    http_response_code(403);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Error No Token Found","document"=> "Please set .htaccess file with [SetEnvIf Authorization '(.*)' HTTP_AUTHORIZATION=$1]"));
		exit();
	   }else{
       $decodedJWTData = JWT::decode($jwtToken,SECRET_KEY, array(ALGORITHM));
	   }

    }catch(\Firebase\JWT\ExpiredException $e){
		http_response_code(403);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Error : " .$e->getMessage(),"document"=> ""));
		exit();
    }catch(Exception $e){
		http_response_code(403);
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Invalid Token","document"=> $e-getMessage()));
		exit();
	}

?>
