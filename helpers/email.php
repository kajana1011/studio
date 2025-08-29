<?php
// session_start();
require_once 'includes/config.php';
require_once 'helpers/functions.php';

// this is email.php for handling email sending when user submits the booking form
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;  
// require 'vendor/autoload.php';
function sendBookingEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = ''; // SMTP username 
        $mail->Password   = 'yourpassword'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587; // TCP port to connect to
        $mail->setFrom('kajanarevocatus@gmail.com', 'b25studio');
        $mail->addAddress($to); // Add a recipient      
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body); // Plain text version for non-HTML email clients
        $mail->send();      
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;   
    }
}
?>
