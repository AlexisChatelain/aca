<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn()){
		header('Location: /aca');
		die();
	}
	
	$id = (isset($_GET["id"])) ? $_GET["id"] : NULL;
	$kit = (isset($_GET["kit"])) ? $_GET["kit"] : NULL;

	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	if ($kit!=NULL){
	$delete = "DELETE FROM commande WHERE id_kit={$kit} and id_commande>={$_GET["commande"]} and confirmation=false and id_utilisateur={$_SESSION["id"]} LIMIT {$_GET["limit"]} ";	
	}else{
	$delete = "DELETE FROM commande WHERE id_commande={$id} and confirmation=false and id_utilisateur={$_SESSION["id"]}";
	}
	$db->query($delete);	
	$db->close();