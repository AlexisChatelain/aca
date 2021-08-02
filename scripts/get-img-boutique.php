<?php
	if(!isset($_GET["id"])){
		echo 'Aucune image sélectionnée.';
		die();
	}
	$id = $_GET["id"];
	
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
	if (isset($_GET["nb"])){
		$query = $db->query("SELECT type_image2, image2 FROM boutique WHERE id_boutique=".$id.";");
		$row = $query->fetch_object();
		header("Content-type: " . $row->type_image2);
		echo $row->image2;
	}else{
		$query = $db->query("SELECT type_image, image FROM boutique WHERE id_boutique=".$id.";");
		$row = $query->fetch_object();
		header("Content-type: " . $row->type_image);
		echo $row->image;
	}	
	$query->free();
	$db->close();