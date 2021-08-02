<?php
	#renseigner dans ce fichier l'adresse mail d'envoi et les données du serveur SMTP 
	require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/PHPMailer/class.phpmailer.php");
	require_once("{$_SERVER['DOCUMENT_ROOT']}/lib/PHPMailer/PHPMailerAutoload.php");
	
	$construction_mail = null;
	$mail_from='votre_adresse@mail';
function sendMail($construction_mail, $address, $subject, $body, $alt, $echo=true, $from_description='Alexis Chatelain (ne pas répondre)', $from='ne.pas.repondre.alexis.chatelain@gmail.com'){
	$construction_mail = new PHPmailer();
	$construction_mail->CharSet = 'UTF-8';
			
	$construction_mail->isSMTP(); // Paramétrer le Mailer pour utiliser SMTP 
	$construction_mail->Host = 'smtp.gmail.com'; // Spécifier le serveur SMTP
	$construction_mail->SMTPAuth = true; // Activer authentication SMTP
	$construction_mail->Username = $from; // Votre adresse email d'envoi
	$construction_mail->Password = 'le_mot_de_passe'; // Le mot de passe de cette adresse email
	$construction_mail->SMTPSecure = 'ssl'; // Accepter SSL
	$construction_mail->Port = 465;

	$construction_mail->setFrom($from, $from_description); // Personnaliser l'envoyeur
	#$construction_mail->addAddress('To2@example.com'); 
	$construction_mail->addReplyTo('adresse_mail_de_retour', 'Nom de la personne'); // L'adresse de réponse
	#$construction_mail->addCC('cc@example.com');
	#$construction_mail->addBCC('bcc@example.com');
	#$construction_mail->addAttachment('/var/tmp/file.tar.gz'); // Ajouter un attachement
	#$construction_mail->addAttachment('/tmp/image.jpg', 'new.jpg'); 
	$construction_mail->isHTML(true); // Paramétrer le format des emails en HTML ou non
    #------------------------------------------------------------------------------------------------#		
	$construction_mail->addAddress($address[0], $address[1].' '.$address[2]); // Ajouter le destinataire
	if (count($address)==4)
		$construction_mail->addAddress($address[3], $address[1].' '.$address[2]); // Ajouter le destinataire	
	$construction_mail->Subject = $subject;
	$construction_mail->Body = $body;
	$construction_mail->AltBody = $alt;
	if(!$construction_mail->send()) {
		$construction_mail->SMTPDebug = 1;
		echo 'Erreur, message non envoyé.<br>';
		echo 'Mailer Error: ' . $construction_mail->ErrorInfo.'<br>';
		return(false);
	} else {
		if ($echo==true)
		echo 'Le message a bien été envoyé !<br>';
		return(true);
	}
}
