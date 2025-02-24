<?php

use library\CMailer;
use library\Context;

require './library/CMailer.php';
require './library/Context.php';

$mail = new CMailer();
$ctx = new Context();

// Email header
$to = ["Keivyssirit@gmail.com"]; //"info@matchalianzas.com"; // this is your Email address
$subject = "Form submission from web site";

// Content data
$data = [
  'email' => $_POST['inputEmail'],
  'first_name' => $_POST['inputName'],
  'last_name' => $_POST['inputSubject'],
  'message' => $_POST['inputMessage']
];

$body = $ctx->BuildEmailContent("/template/email", $data);

// Email
$mail->to = $to;
$mail->subject = $subject;
$mail->body = $body;

if ($mail->sendMail())
  echo json_encode(array('success' => 1));
else
  echo json_encode(array('success' => 0));


