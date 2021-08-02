<?php
	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() && !$login->isAdmin()){
		header('Location: /aca');
		die();
	}
	$id_album = (isset($_GET["id"])) ? $_GET["id"] : NULL;
	
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$query = "DELETE FROM media WHERE id_album={$id_album};";
	$query .= "DELETE FROM album WHERE id_album={$id_album};";
	$db->multi_query($query);
	$db->close();