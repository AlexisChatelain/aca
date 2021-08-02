<?php

	require_once("../ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() || !isset($_GET["nom"])){
		header('Location: /aca');
		die();
	}
	
	$adresse = "https://abcnatation.fr/club_manager/clubs/nageurs/060370001";
	$page = file_get_contents($adresse);
	$nom = $_GET["nom"];
	$pos = stripos($page, $nom); // position du nom dans la page
	if ($pos === false){
	echo "Vous pouvez quitter cette page";
	echo "<script language='javascript'> alert('Malheureusement, votre iuf n\'a pas pu être trouvé. Merci de le trouver par vous-même.'); window.close()</script>";	
	}else{
	$fin= substr($page, $pos);	// chaine de toute la fin de la page à partir du nom
	$pos1 = strpos($fin, "</a>"); // position du 1er </a> à partir du nom
	$donnee=substr($fin, 0, $pos1); //chaine du nom jusqu'au premier </a>
	$pos_club = strpos($donnee, "060370001"); //position de l'iuf du club étant 060370001
	$fin_donnee=substr($donnee, $pos_club); // chaine de l'iuf du club jusqu'à </a>
	$pos_slash=strpos($fin_donnee, "/"); // position du / (après le / il y a l'iuf du nageur)
	$pos_guillemet=strpos($fin_donnee, '"');// position du " (l'iuf du nageur est entre le / et le ")
	$club_et_iuf=substr($fin_donnee, 0, $pos_guillemet); // chaine iuf club  / iuf nageur
	$iuf=substr($club_et_iuf, $pos_slash+1); // chaine iuf nageur
	
	echo "<script> 	
			alert('A moins d\'une erreur, votre IUF est ".$iuf." . Vous pouvez le noter. Si cet IUF est le bon, vous allez être redirigé vers la page de la FFN contenant tous vos résultats.');
			document.location.href='https://ffn.extranat.fr/webffn/nat_recherche.php?idrch_id=".$iuf."';
		</script>";
	}
