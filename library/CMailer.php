<?php 

namespace library;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './library/PHPMailer/src/Exception.php';
require './library/PHPMailer/src/PHPMailer.php';
require './library/PHPMailer/src/SMTP.php';

class CMailer
{
  // Destinatario
  public $to;

  // Asunto
  public $subject;

  // Contenido
  public $body;

  /**
   *
   */
  public function sendMail()
  {
    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->isSMTP();
      $mail->Host = 'matchalianzas.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'info@matchalianzas.com';
      $mail->Password = 'Match.2024*';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port = 465;
  
      //Recipients
      $mail->setFrom('info@matchalianzas.com', 'Contacto desde la pagina web');
      $this->setTo($mail, $this->to);
  
      //Content
      $mail->isHTML(true);
      $mail->Subject = $this->subject;
      $mail->Body = $this->body;
  
      return $mail->send();
    } 
    catch (Exception $e) {
      return false;
    }
  }

  public function setTo(PHPMailer $mail, array $to)
  {
    foreach ($to as $email) {
      $mail->addAddress($email);
    }
  }

}