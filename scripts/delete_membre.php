<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() && !$login->isAdmin()){
		header('Location: /aca');
		die();
	}
	
	$id = (isset($_GET["id"])) ? $_GET["id"] : NULL;

	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$delete_user = $db->query("DELETE FROM utilisateur WHERE id_utilisateur={$id}");	
	$db->close();