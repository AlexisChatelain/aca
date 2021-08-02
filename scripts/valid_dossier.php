<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() || !$login->isAdmin() || isset($_GET["id"])==false){
		header('Location: /aca');
		die();
	}
	
	$id = $_GET["id"];
	
	require('../scripts/saison.php');
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
	$valid_dossier = $db->query("UPDATE dossier SET validation_admin='".$saison."' WHERE id_dossier={$id}");
	$query = $db->query("SELECT mail,mail2,nom,prenom FROM dossier WHERE id_dossier=".$id);
	$row = $query->fetch_object();
		$email=$row->mail;
		$email2=$row->mail2;
		$prenom=$row->prenom;
		$nom=$row->nom;
		$nom_admin=$_SESSION['user'];
		$mail_admin= $_SESSION['email'];
		require('../config/mail.php');		
		$adress = array($email, $prenom, $nom);
		if ($email2!="")
		array_push ($adress, $email2);
		$subject = 'Votre inscription est validée';	

	$body= '
     <html>
      <head>
       <title>Votre inscription est validée</title>	   
		<meta charset="UTF-8" />
      </head>
      <body>
	   <img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
       <p>Bonjour '.$prenom.',<br>
		Ceci est un message automatique envoyé par votre club de natation.<br>'.
		$nom_admin .', membre des administrateurs du site internet de votre club de natation, a examiné et validé votre dossier d\'inscription.<br>
		Vous pouvez maintenant vous présenter aux entrainements.
		<br>A bientôt dans l\'eau, <br>
		Merci de ne pas répondre à ce mail :)
		</p>
      </body>
     </html>
     ';
		$alt = "(logo du club) 
		Bonjour ".$prenom.",
		Ceci est un message texte automatique envoyé par votre club de natation."
		.$nom_admin .", membre des administrateurs du site internet de votre club de natation, a examiné et validé votre dossier d\'inscription.<br>
		Vous pouvez maintenant vous présenter aux entrainements.
		A bientôt dans l'eau, <br>
		Merci de ne pas répondre à ce mail :)";
	sendMail($construction_mail, $adress, $subject, $body, $alt);
	$db->close();