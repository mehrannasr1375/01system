<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/Exception.php";
require_once "PHPMailer/SMTP.php";

class Base
{
    protected static function connect()
    {
        try{
            $dsn = "mysql:host=".HOST_NAME.";dbname=".DB_NAME.";charset=utf8";
            $conn = new PDO($dsn,DB_USER,DB_PASS);
            //$conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);   //auto exception made
            $conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);        //disble errors
            //$conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);     //return all errors
            return $conn;//returns 'false' or an object from 'PDOStatement' class
        }
        catch (Exception $e){
            return "DATABASE CONNECTION ERROR: " . $e -> getMessage();
        }
    }
    protected static function disconnect($conn)
    {
        unset($conn);
    }
    public static function sendMail($target_email,$subject,$content)
    {
        $mail=new PHPMailer(true);           // Passing `true` enables exceptions
        try {
            $mail -> SMTPDebug = 0;                      // Enable verbose debug output
            $mail -> isSMTP();                           // Set mailer to use SMTP
            $mail -> Host = 'smtp.gmail.com';            // Specify main and backup SMTP servers
            $mail -> SMTPAuth = true;                    // Enable SMTP authentication
            $mail -> Username = EMAIL_USER_NAME;         // SMTP username
            $mail -> Password = EMAIL_PASSWORD;          // SMTP password
            $mail -> SMTPSecure = 'tls';                 // Enable TLS encryption, `ssl` also accepted
            $mail -> Port = 587;                         // TCP port to connect to
            $mail -> CharSet = 'utf-8';
            $mail -> isHTML(true);
            $mail -> Subject = $subject;
            $mail -> Body    = $content;
            $mail -> AltBody = $content;
            $mail -> addAddress($target_email);
            $mail -> FromName='zero 1 system';
            if($mail -> send())
                return [true,"Email has been sent!"];
            else
                return [false,"an Error has been eccured!"];
        }
        catch (Exception $e){
            return [false,"$mail->ErrorInfo"];
        }

    }
}