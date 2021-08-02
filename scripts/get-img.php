<?php
	if(!isset($_GET["id"])){
		echo 'Aucune image sélectionnée.';
		die();
	}
	$id = $_GET["id"];
	
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
	
	$query = $db->query("SELECT id_photo, type, file FROM media WHERE id_photo=".$id.";");
	$row = $query->fetch_object();
	$query->free();
	$db->close();
	header("Content-type: " . $row->type);
	echo $row->file;