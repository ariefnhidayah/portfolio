<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

// Replace this with your own email address
$siteOwnersEmail = 'ariefnhidayah@gmail.com';


if($_POST) {

   $name = trim(stripslashes($_POST['contactName']));
   $email = trim(stripslashes($_POST['contactEmail']));
   $subject = trim(stripslashes($_POST['contactSubject']));
   $contact_message = trim(stripslashes($_POST['contactMessage']));

   // Check Name
	if (strlen($name) < 2) {
		$error['name'] = "Please enter your name.";
	}
	// Check Email
	if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email)) {
		$error['email'] = "Please enter a valid email address.";
	}
	// Check Message
	if (strlen($contact_message) < 15) {
		$error['message'] = "Please enter your message. It should have at least 15 characters.";
	}
   // Subject
	if ($subject == '') { $subject = "Contact Form Submission"; }


   // Set Message
   $message = '';
   $message .= "Email from: " . $name . "<br />";
   $message .= "Email address: " . $email . "<br />";
   $message .= "Message: <br />";
   $message .= $contact_message;
   $message .= "<br /> ----- <br /> This email was sent from your site's contact form. <br />";

   // Set From: header
   $from =  $name . " <" . $email . ">";

   $mail = new PHPMailer(true);
   if (!isset($error)) {
			try {
				$mail->SMTPDebug = SMTP::DEBUG_SERVER;
				$mail->isSMTP();                      
				$mail->Host       = 'smtp.gmail.com'; 
				$mail->SMTPAuth   = true;             
				$mail->Username   = $siteOwnersEmail; 
				$mail->Password   = '';    
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
				$mail->Port       = 465;                

				$mail->setFrom($email, $name);
				$mail->addAddress($siteOwnersEmail, "Arief");
				$mail->addReplyTo($siteOwnersEmail, 'Information');
				$mail->isHTML(true);
				$mail->Subject = $subject;
				$mail->Body = $message;
				$mail->send();

				echo "OK";

		  	} catch (Exception $error) {
				echo "Something went wrong. Please try again.";
		  	}
		
	} # end if - no validation error

	else {

		$response = (isset($error['name'])) ? $error['name'] . "<br /> \n" : null;
		$response .= (isset($error['email'])) ? $error['email'] . "<br /> \n" : null;
		$response .= (isset($error['message'])) ? $error['message'] . "<br />" : null;
		
		echo $response;

	} # end if - there was a validation error

}

?>