<?php 
	$title = "Envoi planning";
	//$css = "connexion.css";
	require('../fragments/head.php');	
	if (!$login->isUserLoggedIn() || !$login->isAdmin()) {
		header('Location: /aca');
	}
?>
<body>
<div class="content">
<?php 
require('../fragments/nav.php');
require('../fragments/nav_img.php');
require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
if (isset($_POST["id_categorie"])){
	$jour=$_POST["jour"];
	$debut=$_POST["debut"];
	$duree=$_POST["duree"];
	$couloir=$_POST["couloir"];
	$id_categorie=$_POST["id_categorie"];
	if (count($jour)==count($debut) && count($jour)==count($duree) && count($jour)==count($couloir) && count($jour)==count($id_categorie) && count($jour)!=0){
		$db->query("TRUNCATE TABLE cours");
		for($i=0;$i<count($jour);$i++){
			switch($jour[$i]){
				case 2:
				$jour[$i]="mardi";
				break;
				case 3:
				$jour[$i]="mercredi";
				break;
				case 4:
				$jour[$i]="jeudi";
				break;
				case 5:
				$jour[$i]="vendredi";
				break;
				case 6:
				$jour[$i]="samedi";
				break;
			}
			
			$duree[$i]="01:00:00";
			
			if (intval($debut[$i])==$debut[$i]){
				if ($debut[$i]==21){
					$debut[$i]="20:50:00";	
					$duree[$i]="00:55:00";}
				else if($debut[$i]==20){	
					$debut[$i]="20:00:00";	
					$duree[$i]="00:55:00";}
				else if ($debut[$i]<10)
					$debut[$i]="0".intval($debut[$i]).":00:00";			
				else
					$debut[$i]=intval($debut[$i]).":00:00";		
			}
			else{		
			if ($debut[$i]<10)	
				$debut[$i]="0".intval($debut[$i]).":30:00";				
			else
				$debut[$i]=intval($debut[$i]).":30:00";
			}			
			
			$requete="INSERT cours (jour,debut,duree,couloir,id_categorie)
			VALUES(
			'".$jour[$i]."',  
			'".$debut[$i]."', 
			'".$duree[$i]."', 
			".$couloir[$i].", 
			".$id_categorie[$i].");";
			$result = $db->query($requete);	
		}
				
	echo "Les modifications ont bien été enregistrées ! Vous pouvez quitter cette page."; 	
	}
	else{
	echo "Une erreur est survenue !";
	}
}
else{
	echo "Une erreur est survenue ...";
}
$db->close();
?>
</div>  
<?php require('../fragments/bas.php'); ?>
</body>
</html>