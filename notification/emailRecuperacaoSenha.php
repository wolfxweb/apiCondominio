<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


class EmailRecuperacaoSenha{

    private $titulo;
    private $destinatario;
    private $nomeDestinatario;
    private $assunto;
    private $body;

    public function __construct($email){
        $this->titulo = $email['titulo'];
        $this->destinatario = $email['destinatario'];
        $this->nomeDestinatario = $email['nomeDestinatario'];
        $this->assunto = $email['assunto'];
        $this->body = $email['body'];
       // $this->sendEmailRecuperarSenha();
    }
    public  function sendEmailRecuperarSenha(){

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = EMAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = EMAIL_USER;                     //SMTP username
            $mail->Password   = EMAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = EMAIL_PROT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom(EMAIL_USER, $this->titulo);
            $mail->addAddress($this->destinatario,  $this->nomeDestinatario);     //Add a recipient
          //  $mail->addAddress('ellen@example.com');               //Name is optional
          //  $mail->addReplyTo('info@example.com', 'Information');
           // $mail->addCC('cc@example.com');
         //  $mail->addBCC('bcc@example.com');
        
            //Attachments
        //   $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
       //     $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject =  $this->assunto;
            $mail->Body    = $this->body;
          //  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            $mail->send();
            return true;
        } catch (Exception $e) {
           // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            Response::response($mail->ErrorInfo,"",EMAIL_FALHA_ENVIO,ERROR,0 );
        }
    }
}