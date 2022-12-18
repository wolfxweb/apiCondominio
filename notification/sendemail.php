<?php
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: *");
header("HTTP/1.1 200 OK");
die();
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

include_once '../config/database.php';
include_once '../token/validatetoken.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));
if(
!empty($data->body)
&&!empty($data->to)
&&!empty($data->subject)
){
	
	if(sendEmail($data->to,$data->cc,$data->subject,$data->body)){
		
		http_response_code(200);
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Email sent Successfully","document"=> ""));
	}else{
		http_response_code(503);
        echo json_encode(array("status" => "error", "code" => 0,"message"=> "Email sent failed!","document"=> ""));	
	}
	
}
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create order_sum. Data is incomplete.","document"=> ""));
}

function sendEmail($to,$cc,$subject,$body){


	$mail = new PHPMailer\PHPMailer\PHPMailer();
	
	$mail->IsSMTP();
	$mail->PluginDir  ="../";
	$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
	                                           // 1 = errors and messages
	                                           // 2 = messages only

    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "smtp.google.com"; // sets the SMTP server
	$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "noreply@email.com"; // SMTP account username
	$mail->Password   = "PASSWORD";        // SMTP account password
	$mail->IsHTML(true);
	$mail->SetFrom('noreply@getautomator.com', 'Get Automator');
	
	//$mail->AddReplyTo("noreply@yourdomain.com","NoReply Name");
	
	$mail->Subject    = $subject;
	
	//$mail->AltBody    = ""; // optional, comment out and test
	
	$mail->Body=$body;
	$toArray = explode(',', $to);
	foreach ($toArray as $toEmail) {
		$mail->AddAddress($toEmail);
	};
	
	if(!empty($cc)){
		$ccArray = explode(',', $cc);
	foreach ($ccArray as $ccEmail) {
		$mail->AddCC($ccEmail);
	};
	
	}
	//$mail->Send();

	$mailStatus="";
	if(!$mail->Send()) {
		
		 $mail->ClearAddresses();
		 $mail->ClearAttachments();
       //$mailStatus= "Mailer Error "; $mail->ErrorInfo;
	   return false;
     } else {
      //$mailStatus= "Message has been sent";
	  return true;
     }
   
	return false;
}
?>
