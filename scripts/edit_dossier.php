<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn()){
		header('Location: /aca');
		die();
	}
	if ($login->isAdmin())
	$admin=", membre des administateurs du site web,";
	else
	$admin="";
	
	$maj = (isset($_POST["maj"])) ? $_POST["maj"] : NULL;
	$id = (isset($_POST["id"])) ? $_POST["id"] : NULL;
	$id_dossier = (isset($_POST["id_dossier"])) ? $_POST["id_dossier"] : NULL;
	$old_club = (isset($_POST["old_club"])) ? $_POST["old_club"] : NULL;
	$IUF = (isset($_POST["iuf"])) ? $_POST["iuf"] : NULL;
	$nom = (isset($_POST["nom"])) ? $_POST["nom"] : NULL;
	$prenom = (isset($_POST["prenom"])) ? $_POST["prenom"] : NULL;
	$numero_rue = (isset($_POST["numero"])) ? $_POST["numero"] : NULL;
	$rue = (isset($_POST["rue"])) ? $_POST["rue"] : NULL;
	$code_postal = (isset($_POST["cp"])) ? $_POST["cp"] : NULL;
	$ville = (isset($_POST["ville"])) ? $_POST["ville"] : NULL;
	$mail = (isset($_POST["mail1"])) ? $_POST["mail1"] : NULL;
	$mail2 = (isset($_POST["mail2"])) ? $_POST["mail2"] : NULL;
	$tel1 = (isset($_POST["telephone1"])) ? $_POST["telephone1"] : NULL;
	$tel2 = (isset($_POST["telephone2"])) ? $_POST["telephone2"] : NULL;
	$tel3 = (isset($_POST["telephone3"])) ? $_POST["telephone3"] : NULL;
	$nom_parent = (isset($_POST["nom_parent"])) ? $_POST["nom_parent"] : NULL;
	$prenom_parent = (isset($_POST["prenom_parent"])) ? $_POST["prenom_parent"] : NULL;
	
	$type_licence=(isset($_POST["type_licence"])) ? $_POST["type_licence"] : NULL;
	$nationalite=(isset($_POST["nationalite"])) ? $_POST["nationalite"] : NULL; 
	$sexe=(isset($_POST["sexe"])) ? $_POST["sexe"] : NULL;
	$date=substr($_POST['naissance'], 3, 3).substr($_POST['naissance'], 0, 3).substr($_POST['naissance'], 6, 4);	
	// format mm/dd/aaaa
	$naissance = date('Y-m-d',strtotime($date));
	$id_categorie=(isset($_POST["id_categorie"])) ? $_POST["id_categorie"] : NULL; 
	$accepte_lil=(isset($_POST["accepte_lil"])) ? $_POST["accepte_lil"] : NULL;

	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$old_club = $db->real_escape_string(htmlentities($old_club, ENT_QUOTES));
	$IUF = $db->real_escape_string(htmlentities($IUF, ENT_QUOTES));
	$nom = $db->real_escape_string(htmlentities($nom, ENT_QUOTES));
	$prenom = $db->real_escape_string(htmlentities($prenom, ENT_QUOTES));
	$numero_rue = $db->real_escape_string(htmlentities($numero_rue, ENT_QUOTES));
	$rue = $db->real_escape_string(htmlentities($rue, ENT_QUOTES));
	$code_postal = $db->real_escape_string(htmlentities($code_postal, ENT_QUOTES));
	$ville = $db->real_escape_string(htmlentities($ville, ENT_QUOTES));
	$mail = $db->real_escape_string(htmlentities($mail, ENT_QUOTES));
	$mail2 = $db->real_escape_string(htmlentities($mail2, ENT_QUOTES));
	$tel1 = $db->real_escape_string(htmlentities($tel1, ENT_QUOTES));
	$tel2 = $db->real_escape_string(htmlentities($tel2, ENT_QUOTES));
	$tel3 = $db->real_escape_string(htmlentities($tel3, ENT_QUOTES));
	$nom_parent = $db->real_escape_string(htmlentities($nom_parent, ENT_QUOTES));
	$prenom_parent = $db->real_escape_string(htmlentities($prenom_parent, ENT_QUOTES));
	
		
	$update_dossier = "UPDATE dossier SET ";
	if ($old_club != NULL)
	$update_dossier=$update_dossier."club_origine='{$old_club}',";
	if ($IUF != NULL)
	$update_dossier=$update_dossier."IUF='{$IUF}',";
	if ($nom != NULL)
	$update_dossier=$update_dossier."nom='{$nom}',";
	if ($prenom != NULL)
	$update_dossier=$update_dossier."prenom='{$prenom}',";
	if ($numero_rue != NULL)
	$update_dossier=$update_dossier."numero_rue='{$numero_rue}',";
	if ($rue != NULL)
	$update_dossier=$update_dossier."rue='{$rue}',";
	if ($code_postal != NULL)
	$update_dossier=$update_dossier."code_postal='{$code_postal}',"; 
	if ($ville != NULL)
	$update_dossier=$update_dossier."ville='{$ville}',";
	if ($mail != NULL)
	$update_dossier=$update_dossier."mail='{$mail}',";
	if ($mail2 != NULL)
	$update_dossier=$update_dossier."mail2='{$mail2}',";
	if ($tel1 != NULL)
	$update_dossier=$update_dossier."tel1='{$tel1}',";
	if ($tel2 != NULL)
	$update_dossier=$update_dossier."tel2='{$tel2}',";
	if ($tel3 != NULL)
	$update_dossier=$update_dossier."tel3='{$tel3}',";
	if ($nom_parent != NULL)
	$update_dossier=$update_dossier."nom_parent='{$nom_parent}',";
	if ($prenom_parent != NULL)
	$update_dossier=$update_dossier."prenom_parent='{$prenom_parent}',";
	
	if ($type_licence != NULL)
	$update_dossier=$update_dossier."type_licence='{$type_licence}',";
	if ($nationalite != NULL)
	$update_dossier=$update_dossier."nationalite='{$nationalite}',";
	if ($sexe != NULL)
	$update_dossier=$update_dossier."sexe='{$sexe}',";
	if ($naissance != NULL)
	$update_dossier=$update_dossier."date_naissance='{$naissance}',";
	if ($id_categorie != NULL)
	$update_dossier=$update_dossier."id_categorie='{$id_categorie}',";
	if ($accepte_lil != NULL)
	$update_dossier=$update_dossier."accepte_lil='{$accepte_lil}',";
	
	$update_dossier = substr($update_dossier, 0, -1);
	$update_dossier =$update_dossier." WHERE id_dossier={$id_dossier} ";	
	
	setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
	
	if (!$login->isAdmin())
	$update_dossier=$update_dossier."and id_utilisateur={$id}";
	$db->query($update_dossier);
	
	if (isset($_POST['id_cheque'])){
	echo "coucou";
	for ($i=0;$i<count($_POST['id_cheque']);$i++){
	$update_cheques = "UPDATE cheques SET
	banque='".$_POST['banque'][$i]."', 
	numero=".$_POST['numero_banque'][$i].", 
	montant=".$_POST['montant_cheque'][$i]."
	WHERE id_dossier={$id_dossier} and id_cheque=".$_POST['id_cheque'][$i];
	$db->query($update_cheques);
	}}
	
	
		$nom_session=$_SESSION['user'];
		require('../config/mail.php');
		
		$adress = array($mail_from, "Club", "natation");

		$subject = "Modification d'un dossier d'adhésion"; //'Sujet de l\'email'	

	$body = '
     <html>
      <head>
       <title>Modification d\'un dossier d\'adhésion</title>	   
		<meta charset="UTF-8" />
      </head>
      <body>
	  <img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
       <p>Bonjour,<br>
		Ceci est un message automatique envoyé par votre club de natation.<br>'.
		$nom_session.$admin.' a modifié le dossier d\'inscription de '.$prenom.' '.$nom.' le '.strftime("%A %d %B %Y, %H:%M").'.<br>
		Merci de ne pas répondre à ce mail :)
		</p>
      </body>
     </html>
     ';
		$alt = "(logo du club) 
		Bonjour ,
		Ceci est un message texte automatique envoyé par votre club de natation."
		.$nom_session.$admin." a modifié le dossier d\'inscription de ".$prenom." ".$nom." le ".strftime("%A %d %B %Y, %H:%M").".
		Merci de ne pas répondre à ce mail :)";
		
		if (sendMail($construction_mail, $adress, $subject, $body, $alt)){		
			if ($maj==NULL)
			header('Location: /aca/admin/dossier_membres.php');
			else
			header('Location: /aca/admin/dossier_membres.php?maj='.$maj);
		}