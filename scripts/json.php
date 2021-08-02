<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$sql="SELECT id_evenement AS id, titre AS title, couleur AS color, date_debut AS start, all_day AS allDay, date_fin AS end FROM evenements;";

	$result = $db->query($sql);

	$events=array();
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		   array_push($events,$row); // ajouter la ligne au tableau $myData
		}
	} 
	$result->free();
	$db->close();
	echo json_encode($events);
?>