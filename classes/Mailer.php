<?php

/*
	Estamos usando la libreria de PHPMailer para enviar los correos.
	Pueden encontrar ejemplos y documentacion en la siguiente liga: (https://github.com/PHPMailer/PHPMailer)
*/

require 'PHPMailer/PHPMailerAutoload.php';

class Mailer {
	private $_db;
	private $systemMail;
	private $systemName;

	public function __construct($token = null) {
		$this->_db = DB::getInstance();
		$this->systemMail = 'noreply@lsatitesm.com';
	    $this->systemName = 'LSAT';
	}

	/*Enviar un mail generico*/
	public function send ($to, $subject, $message) {
		$to = 'luis.chapa01@gmail.com';
		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		//Set who the message is to be sent from
		$mail->setFrom($this->systemMail, $this->systemName);
		//Set an alternative reply-to address
		$mail->addReplyTo($this->systemMail, $this->systemName);
		//Set who the message is to be sent to
		$mail->addAddress($to, '');
		//Set the subject line
		$mail->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($message, dirname(__FILE__));
		//Replace the plain text body with one created manually
		$mail->AltBody = $message;

		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		}
	}


	/*Enviar un mail si el alumno respondio mal todas als preguntas de un nivel */
	public function sendLevelFailed ($to, $username) {
		
		$subject = "LSAT - Alumno fallo todo el nivel";
		$message = file_get_contents('../includes/templates/mails/levelFailed.html');
		$message = str_replace('$username', $username, $message);

		$this->send($to, $subject, $message);
	}


}