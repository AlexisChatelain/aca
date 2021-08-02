<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() || !isset($_GET) || count($_GET) != 3){
		header('Location: /aca');
		die();
	}
		
		
	$fichier=$_GET["fichier"];
	$type=$_GET["type"];
	$id = $_GET["id"];	
		
	
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
	$query = $db->query("SELECT id_utilisateur FROM dossier WHERE id_dossier=".$id);
	$row = $query->fetch_object();
	
	if (strval($row->id_utilisateur)!=$_SESSION['id'] && !$login->isAdmin()) {
		header('Location: /aca');
		die();
	}else{	
		$query->free();
		$query = $db->query("SELECT $fichier, $type FROM dossier WHERE id_dossier=".$id.";");
		$row = $query->fetch_object();
		
		$contenuFichier = $row->$fichier;
		$tailleFichier = strlen($contenuFichier);
		
		$query->free();
		header("Content-Type:".$row->$type);
		header("Content-Length: $tailleFichier");
		
		echo $contenuFichier;
	}
	$db->close();
?>