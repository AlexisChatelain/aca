<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() && !$login->isAdmin()){
		header('Location: /aca');
		die();
	}
	
	$id = (isset($_GET["id"])) ? $_GET["id"] : NULL;

	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$delete_dossier = "DELETE FROM cheques WHERE id_dossier={$id};";
	$delete_dossier .= "DELETE FROM dossier WHERE id_dossier={$id};";	
	$db->multi_query($delete_dossier);
	$db->close();