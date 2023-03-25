<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use \App\Config;

class Mail {


    public static function send($to, $subject, $text, $html) {

        $mail = new PHPMailer(true);

        try {

            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->CharSet = "UTF-8";
            $mail->SMTPDebug = 0;                                         //Enable verbose debug output
            $mail->isSMTP();                                              //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                         //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                     //Enable SMTP authentication
            $mail->Username   = Config::PHP_MAILER_LOGIN;                 //SMTP username
            $mail->Password   = Config::PHP_MAILER_KEY;                   //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;              //Enable implicit TLS encryption
            $mail->Port       = 465;                                      //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('no-reply@wp.pl', 'Finance Web Aplication');
            $mail->addAddress($to);                                        //Add a recipient
            $mail->addReplyTo('biuro@domena.pl', 'Office');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');                //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');           //Optional name

            //Content
            $mail->isHTML(true);                                           //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->AltBody = $text;

            $mail->send();
            echo 'Message has been sent';
        }

        catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

}