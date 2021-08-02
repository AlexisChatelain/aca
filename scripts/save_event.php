<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() && !$login->isAdmin()){
		header('Location: /aca');
		die();
	}
	$exist = false;
	$id_event = (isset($_GET["id"])) ? $_GET["id"] : NULL;
	$title = (isset($_GET["t"])) ? $_GET["t"] : NULL;
	$creator_id = (isset($_GET["c"])) ? $_GET["c"] : NULL;
	$color = (isset($_GET["r"])) ? $_GET["r"] : NULL;
	$sy = (isset($_GET["sy"])) ? $_GET["sy"] : NULL; // start year
	$sm = (isset($_GET["sm"])) ? $_GET["sm"] : NULL; // start month
	$sm += 1;
	$sd = (isset($_GET["sd"])) ? $_GET["sd"] : NULL; // start day
	$allDay = (isset($_GET["allday"])) ? '1' : '0';
	$sh = (isset($_GET["sh"])) ? $_GET["sh"] : NULL; // start hour 
	$smn = (isset($_GET["smn"])) ? $_GET["smn"] : NULL; //start minute
	$ey = (isset($_GET["ey"])) ? $_GET["ey"] : NULL; //end year
	$em = (isset($_GET["em"])) ? $_GET["em"] : NULL; //end month
	$em += 1;
	$ed = (isset($_GET["ed"])) ? $_GET["ed"] : NULL; //end day
	$eh = (isset($_GET["eh"])) ? $_GET["eh"] : NULL; //end hour 
	$emn = (isset($_GET["emn"])) ? $_GET["emn"] : NULL; //end minute
	
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$title = $db->real_escape_string($title);
	$color = $db->real_escape_string($color);
	$checkevent = $db->query("SELECT id_evenement FROM evenements;");
	while($row = $checkevent->fetch_array()){
		$id = $row[0];
		if($id_event == $id){
			$exist = true;
			break;
		}
	}
	//https://www.php.net/manual/en/function.date-parse.php
	
	$query = "";
	if(!$allDay and $ed != NULL){
		$start = $sy."-".$sm."-".$sd." ".$sh.":".$smn.":00";
		$end = $ey."-".$em."-".$ed." ".$eh.":".$emn.":00";
		if ($exist) {
			$query = "UPDATE evenements SET titre='".$title."' ,couleur='".$color."' , date_debut='".$start."', all_day='".$allDay."', date_fin='".$end."' WHERE id_evenement=".$id_event;
		} else {
			$query = "INSERT INTO evenements (titre, couleur, date_debut, all_day, date_fin, id_utilisateur) VALUES ('".$title."','".$color."','".$start."','".$allDay."','".$end."','".$creator_id."');";
		}
		
	} else if($allDay and $ed == NULL){
		$start = $sy."-".$sm."-".$sd." 00:00:00";
		if ($exist) {
			$query = "UPDATE evenements SET titre='".$title."' ,couleur='".$color."' , date_debut='".$start."', all_day='".$allDay."' WHERE id_evenement=".$id_event;
		} else {
			$query = "INSERT INTO evenements (titre, couleur, date_debut, all_day, id_utilisateur) VALUES ('".$title."','".$color."','".$start."','".$allDay."','".$creator_id."');";
		}
	} else if($allDay and $ed != NULL){
		$start = $sy."-".$sm."-".$sd." 00:00:00";
		$end = $ey."-".$em."-".$ed." 00:00:00";
		if ($exist) {
			$query = "UPDATE evenements SET titre='".$title."' ,couleur='".$color."' , date_debut='".$start."', all_day='".$allDay."', date_fin='".$end."' WHERE id_evenement=".$id_event;
		} else {
			$query = "INSERT INTO evenements (titre, couleur, date_debut, all_day, date_fin, id_utilisateur) VALUES ('".$title."','".$color."','".$start."','".$allDay."','".$end."','".$creator_id."');";
		}
	} else if(!$allDay and $ed == NULL){
		$start = $sy."-".$sm."-".$sd." ".$sh.":".$smn.":00";
		if ($exist) {
			$query = "UPDATE evenements SET titre='".$title."' ,couleur='".$color."' , date_debut='".$start."', all_day='".$allDay."' WHERE id_evenement=".$id_event;
		} else {
			$query = "INSERT INTO evenements (titre, couleur, date_debut, all_day, id_utilisateur) VALUES ('".$title."','".$color."','".$start."','".$allDay."','".$creator_id."');";
		}
	}
	
	$db->query($query);
	$db->close();
	