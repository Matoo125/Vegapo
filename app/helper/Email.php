<?php

namespace app\helper;

class Email 
{
  public static function send (string $subject, array $body, array $recipient)
  {
    $mail = new \PHPMailer;

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = 'veganpotraviny@gmail.com';
    $mail->Password = EMAIL_PASSWORD;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $mail->setFrom('veganpotraviny@gmail.com', 'Vegapo');
    $mail->addAddress($recipient['email'], $recipient['name']);
    $mail->Subject      =   $subject;
    $mail->Body         =   $body['html'];
    $mail->AltBody      =   $body['text'];

    return $mail->send();

  }
}