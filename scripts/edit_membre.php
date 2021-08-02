<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() || isset($_POST["id"])==false){
		header('Location: /aca');
		die();
	}
	
	$id = (isset($_POST["id"])) ? $_POST["id"] : NULL;
	$nom = (isset($_POST["nom"])) ? $_POST["nom"] : NULL;
	$prenom = (isset($_POST["prenom"])) ? $_POST["prenom"] : NULL;
	$email = (isset($_POST["mail"])) ? $_POST["mail"] : NULL;
	$mdp = (isset($_POST["mdp"])) ? $_POST["mdp"] : NULL;
	$role = (isset($_POST["role"])) ? $_POST["role"] : NULL;

	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$nom = $db->real_escape_string(htmlentities($nom, ENT_QUOTES));
	$prenom = $db->real_escape_string(htmlentities($prenom, ENT_QUOTES));
	$email = $db->real_escape_string(htmlentities($email, ENT_QUOTES));
	if ($login->isAdmin()){
		if(!$mdp){
			$update_user = "UPDATE utilisateur SET nom='{$nom}', prenom='{$prenom}', mail='{$email}', admin={$role} WHERE id_utilisateur={$id};";
		} else {        
			$mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
			$update_user = "UPDATE utilisateur SET nom='{$nom}', prenom='{$prenom}', mail='{$email}', mdp='{$mdp_hash}', admin={$role} WHERE id_utilisateur={$id};";
		}
	} else {
		if(!$mdp){
			$update_user = "UPDATE utilisateur SET nom='{$nom}', prenom='{$prenom}', mail='{$email}' WHERE id_utilisateur={$_SESSION['id']};";
		} else {        
			$mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
			$update_user = "UPDATE utilisateur SET nom='{$nom}', prenom='{$prenom}', mail='{$email}', mdp='{$mdp_hash}' WHERE id_utilisateur={$_SESSION['id']};";
		}
	}

	$db->query($update_user);
	$db->close();
	header('Location: /aca/admin/membres.php');