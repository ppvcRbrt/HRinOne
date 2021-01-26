<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once("CandidateInfoQueries.php");

class Mailer
{

    function mailCandidate($candidateID)
    {
        $mail = new PHPMailer(true);
        $candidateQuery = new CandidateInfoQueries();

        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'br588427@gmail.com';                     // SMTP username
            $mail->Password   = 'aPassword123';                          // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            $candidateEmail = $candidateQuery->getCandidateEmail($candidateID);
            //Recipients
            $mail->setFrom('whoSentDis@gmail.com', 'Who knows');
            $mail->addAddress($candidateEmail[0]);     // Add a recipient


            // Attachments

            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Your PACE Report!';
            $mail->Body    = 'Below you can find the pace report attached :)';
            $mail->AltBody = 'Below you can find the pace report attached :)';

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
