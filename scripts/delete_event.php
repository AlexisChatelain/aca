<?php
	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() && !$login->isAdmin()){
		header('Location: /aca');
		die();
	}
	$id_event = (isset($_GET["id"])) ? $_GET["id"] : NULL;
	
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$checkevent = $db->query("DELETE FROM evenements WHERE id_evenement=".$id_event);
	$db->close();