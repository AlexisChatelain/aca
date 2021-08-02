<?php 
	$title = "Confirmation";
	$css = "dossier.css";
?>

<?php 
	require('fragments/head.php');
	// la ligne suivante sert à lutter contre l'injection 
	if (!$login->isUserLoggedIn() || (strstr($_SERVER['HTTP_REFERER'], "http://".$_SERVER['HTTP_HOST']."/aca/dossier.php")==false && strstr($_SERVER['HTTP_REFERER'], "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'])==false)){
	header('Location: /aca/connexion.php');
	}
?>

<body>

<div class="content">
<?php require('fragments/nav.php'); ?>
<?php require('fragments/nav_img.php'); ?>

 <div id="div_confirmation">
 <h1>Confirmation INDISPENSABLE de l'inscription.</h1>

 Merci de saisir ici le code envoyé sur votre adresse e-mail pour confirmer votre inscription et vous assurer que votre adresse mail est valide.<br>
 <p>
 
 <?php
 
  function generation_codemail(){
	$code="";
	for ($i=0; $i<15; $i++) {
	$code.= chr(rand(65, 90));}
	return $code;}
if ($_SERVER['REQUEST_METHOD'] =="POST"){

 $code=generation_codemail();

	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
	foreach ($_POST as $key => $value){
		if ($key== "old_club" || $key=="iuf" ||	$key=="mail2" || $key=="telephone2" || $key=="telephone3" 
		|| $key=="commentaire" || $key=="nom_parent" || $key=="prenom_parent" ){
			if ($value=="") {
				$_POST[$key]="null";				
			}else{
				$_POST[$key]="'".addslashes($value)."'";
			}
		}
	}
	if ($_POST['type_paiement']=='especes'){
	$_POST["nb"]='0';}				
				
	$iuf = $db->real_escape_string(htmlentities($_POST['iuf'], ENT_QUOTES));
	$nom = $db->real_escape_string(htmlentities($_POST['nom'], ENT_QUOTES));
	$prenom = $db->real_escape_string(htmlentities($_POST['prenom'], ENT_QUOTES));
	$date=substr($_POST['naissance'], 3, 3).substr($_POST['naissance'], 0, 3).substr($_POST['naissance'], 6, 4);	
	// format mm/dd/aaaa
	$naissance = date('Y-m-d',strtotime($date));
	// format Y-m-d pour la base
	$rue = $db->real_escape_string(htmlentities($_POST['rue'], ENT_QUOTES));
	$ville = $db->real_escape_string(htmlentities($_POST['ville'], ENT_QUOTES));
	$email = $db->real_escape_string(htmlentities($_POST['mail1'], ENT_QUOTES));
	$email2 = $_POST['mail2'];
	$tel1 = $_POST['telephone1'];
	$tel2 = $_POST['telephone2'];
	$tel3 = $_POST['telephone3'];
	$fichier1 = addslashes(file_get_contents($_FILES['doc1']['tmp_name']));
	$fichier2 = addslashes(file_get_contents($_FILES['doc2']['tmp_name']));
	$fichier3 = addslashes(file_get_contents($_FILES['doc3']['tmp_name']));
	$fichier4 = addslashes(file_get_contents($_FILES['doc4']['tmp_name']));				
	$typefichier1 = $_FILES['doc1']['type'];			
	$typefichier2 = $_FILES['doc2']['type'];			
	$typefichier3 = $_FILES['doc3']['type'];			
	$typefichier4 = $_FILES['doc4']['type'];		
	$date_signature = date('Y-m-d');
	$ville_signature = $db->real_escape_string(htmlentities($_POST['ville_adhesion'], ENT_QUOTES));
	if ($_POST['choix_lil']=="Autorisation")
		$_POST['choix_lil']=true;
	else
		$_POST['choix_lil']=false;
	if ($_POST['myCanvas4']!="null")
		$_POST['myCanvas4']="'".$_POST['myCanvas4']."'";
	if ($_POST['myCanvas3']!="null")
		$_POST['myCanvas3']="'".$_POST['myCanvas3']."'";
	if (($_POST["type_licence"]=="Renouvellement" || $_POST["erreur"]!="") && $_POST["renouvellement_auto"]!=""){

		$delete = $db->query("DELETE FROM cheques WHERE id_dossier=".$_POST["renouvellement_auto"]);
		$requete = "UPDATE dossier
					SET validation_admin = 'non',
						confirmation = '".$code."',
						type_licence = '" . $_POST['type_licence']."',
						club_origine = " .$_POST['old_club'].",
						IUF = " .$_POST['iuf'].",
						nom = '" .$nom."',
						prenom = '" .$prenom."',
						nationalite = '" .$_POST['nationalite']."',
						sexe = '" .$_POST['sexe']."',
						date_naissance = '" .$naissance."',
						numero_rue = '" .$_POST['numero']."',
						rue =  '" .$rue."',
						code_postal = '" .$_POST['cp']."',
						ville = '" .$ville."',
						mail = '" .$email."',
						mail2 =" .$email2.", 
						tel1 = '" .$tel1."',
						tel2 = " .$tel2.",
						tel3 = " .$tel3.",
						id_categorie = '" .$_POST['resultat_groupe']."',
						fichier1 = '" .$fichier1."',
						type_fichier1 = '" .$typefichier1."',
						fichier2 = '" .$fichier2."',
						type_fichier2 = '" .$typefichier2."',
						fichier3 = '" .$fichier3."',
						type_fichier3 = '" .$typefichier3."',
						fichier4 = '" .$fichier4."',
						type_fichier4 = '" .$typefichier4."',
						date_signature = '" .$date_signature."',
						ville_signature = '" .$ville_signature."', 
						accepte_lil = '" .$_POST['choix_lil']."',
						nom_parent = " .$_POST['nom_parent'].",
						prenom_parent = " .$_POST['prenom_parent'].",
						commentaire = " .$_POST['commentaire'].",
						nbcheques = '" .$_POST['nb']."',
						montant_cotisation = '" .$_POST['montant_cotisation']."',
						signature1 = '" .$_POST['myCanvas1']."',
						signature2 = '" .$_POST['myCanvas2']."',
						signature3 = " .$_POST['myCanvas3'].",
						signature4 = " .$_POST['myCanvas4']."
					WHERE id_dossier=".$_POST["renouvellement_auto"];
	
	}else{
		$requete="INSERT INTO dossier (validation_admin, confirmation, type_licence, club_origine, IUF, nom, prenom, nationalite, sexe, date_naissance, numero_rue, rue, code_postal, ville, mail, mail2,	
		tel1, tel2, tel3, id_categorie, fichier1, type_fichier1, fichier2, type_fichier2 ,fichier3, type_fichier3, fichier4, type_fichier4, date_signature, ville_signature, accepte_lil, 
		nom_parent, prenom_parent, commentaire, nbcheques, montant_cotisation, signature1, signature2, signature3, signature4, id_utilisateur)
		VALUES('non','".$code."', '" . $_POST['type_licence'] . "', " . $_POST['old_club']. ", " . $_POST['iuf'] . ", '" . $nom  . "', '" . $prenom . "', '" . $_POST['nationalite'] . "', 
		'" . $_POST['sexe'] . "', '" . $naissance . "', '" . $_POST["numero"] . "', '" . $rue . "', '" . $_POST["cp"] . "', '" . $ville . "', '" . $email . "', " . $email2 . ", 
		'" . $tel1 . "', " . $tel2 . ", " . $tel3 . ",'" . $_POST["resultat_groupe"] ."', '" . $fichier1 ."', '" . $typefichier1 . "', '" . $fichier2 ."', '" . $typefichier2 . "', 
		'" . $fichier3 ."', '" . $typefichier3 . "', '" . $fichier4 ."', '" . $typefichier4 . "', '" . $date_signature . "', '" . $ville_signature . "',
		" . $_POST['choix_lil'] . ", " . $_POST['nom_parent'] . ", " . $_POST['prenom_parent'] . ", " . $_POST["commentaire"] . ", '" . $_POST["nb"] . "', '" . $_POST['montant_cotisation'] . "', 
		'" . $_POST['myCanvas1'] . "', '" .  $_POST['myCanvas2'] . "', " .  $_POST['myCanvas3'] . " , " . $_POST['myCanvas4'] . ", '" . $_SESSION['id'] . "'
		)";
	}
	$result = $db->query($requete);		
	//echo "<br>" . $requete ."<br>";
	/*if(!$result){
		trigger_error($db->error);
	}*/
	
	if ($_POST['type_paiement']!='especes'){
	
		$requete="INSERT INTO cheques (confirmation, banque, numero, montant, id_dossier) VALUES";
		
		for ($i=0;$i<count($_POST['banque']);$i++){
			$requete=$requete."('" . $code . "', '" . $_POST['banque'][$i] . "', " 
			. $_POST['numero_banque'][$i]. ", " . $_POST['montant_cheque'][$i]
			.", (Select id_dossier from dossier where confirmation = '" . $code  
			. "' and id_utilisateur='". $_SESSION['id']."'))";	
			if ($i<count($_POST['banque'])-1)
				$requete=$requete.',';
		}
		$result = $db->query($requete);	
		/*if(!$result){
			trigger_error($db->error);}*/
	}
		
	require('config/mail.php');
	$adress = array($email, $prenom, $nom);
	$subject = 'Confirmation de votre adresse mail et de votre inscription';
	$body = '<html>
	<head>
	<title>Confirmation de votre adresse mail</title>	   
	<meta charset="UTF-8" />
	</head>
	<body>	  
	<img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
	<p>Bonjour '.$prenom.',<br>
	Ceci est un message automatique envoyé par votre club de natation.<br>
	Voici le code pour confirmer votre inscription et vous assurer que votre adresse mail est correcte : '.$code.'<br>
	A bientôt dans l\'eau, <br>
	Merci de ne pas répondre à ce mail :)<br>
	</p>
	</body>
	</html>';
	$alt='(logo du club) 
	Bonjour '.$prenom.',
	Ceci est un message texte automatique envoyé par votre club de natation.
	Voici le code pour confirmer votre inscription et vous assurer que votre adresse mail est correcte : '.$code.
	'A bientôt dans l\'eau,
	Merci de ne pas répondre à ce mail :)';
	sendMail($construction_mail, $adress, $subject, $body, $alt);

}else{
	if(isset($_GET['erreur'])){
		$err = $_GET['erreur'];
		if($err==1 || $err==2){
			$incorrect="Code incorrect";
		}
	}
}
?>
 </p>		

  <form id="confirmation" action="confirme_dossier.php" method="get">  
   <input hidden type="text" name="mail" value="<?php if(isset($_GET['mail'])){ echo $_GET['mail']; }else{ echo $_POST['mail1'];}?>" />
   <input id="confirm" type="text" maxlength="15" name="texte" placeholder="Saisissez le code ici..." title="Saisir ou coller le code reçu par mail" value="<?php if(isset($incorrect)){ echo $incorrect;} ?>" pattern="[A-Z]{15}" required />
   <br>
   <input id="valid" type="submit" name="soumet" value="Valider"/>
  </form>
  </div>  
  </div>  
 <?php require('fragments/bas.php'); ?>
 </body>
</html>