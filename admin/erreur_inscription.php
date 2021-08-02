<?php 
	$title = "Erreur inscription";
	$css = "dossier.css";
?>

<?php 
	require("{$_SERVER['DOCUMENT_ROOT']}/aca/fragments/head.php");
	if(!$login->isUserLoggedIn() || !$login->isAdmin() || (!isset($_GET["id_dossier"]) && !isset($_POST["liste"]) )){	
		header('Location: /');
		die();}				
		
		if ($_SERVER['REQUEST_METHOD'] =="GET"){  
			$id= $_GET['id_dossier'];
			require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");				
				$query = $db->query("SELECT nom, prenom, mail, mail2 FROM dossier WHERE id_dossier=".$_GET['id_dossier'].";");
				while($row = $query->fetch_array()){
					$nom = $row["nom"];
					$prenom = $row["prenom"];	
					$email = $row["mail"];	
					$email2 = $row["mail2"];				
				}		
		}else{
					$nom = $_POST["nom"];
					$prenom = $_POST["prenom"];	
					$email = $_POST["email"];	
					$email2 = $_POST["email2"];
					$id= $_POST['id_dossier'];
			}
		
			?>

<body>
<div class="content">
<?php require("{$_SERVER['DOCUMENT_ROOT']}/aca/fragments/nav.php"); ?>
<?php require("{$_SERVER['DOCUMENT_ROOT']}/aca/fragments/nav_img.php"); ?>
<div id="centre">
 <h1>Erreur dans l'inscription de <?php echo $prenom." ".$nom; ?></h1>
 <p>Merci de cocher les cases ci-dessous concernant les problèmes dans l'inscription de cette personne.</p>
 <p>Un mail lui sera envoyé.</p>
 <form action="erreur_inscription.php" method="post">
 <table id="table_erreur_inscription">
 <tr><td>
	<input type="hidden" name="id_dossier" value="<?php echo $id; ?>" />
	<input type="hidden" name="nom" value="<?php echo $nom; ?>" />
	<input type="hidden" name="prenom" value="<?php echo $prenom; ?>" />
	<input type="hidden" name="email" value="<?php echo $email; ?>" />
	<input type="hidden" name="email2" value="<?php echo $email2; ?>" />
	<input type="hidden" name="maj" value="<?php if (isset($_GET["maj"])) echo $_GET["maj"]; ?>" />
    <label><input type="checkbox" name="liste[]" value="Type licence" />Type licence<br/></label>
    <label><input type="checkbox" name="liste[]" value="Club d'origine" />Club d'origine<br/></label>
    <label><input type="checkbox" name="liste[]" value="IUF" />IUF<br/></label>
    <label><input type="checkbox" name="liste[]" value="Orthographe du Nom" />Orthographe du Nom<br/></label>
    <label><input type="checkbox" name="liste[]" value="Nationalité" />Nationalité<br/></label>
    <label><input type="checkbox" name="liste[]" value="Naissance" />Naissance<br/></label>
    <label><input type="checkbox" name="liste[]" value="Adresse" />Adresse<br/></label>
    <label><input type="checkbox" name="liste[]" value="Mail 1" />Mail 1<br/></label>
    <label><input type="checkbox" name="liste[]" value="Mail 2" />Mail 2<br/></label>
    <label><input type="checkbox" name="liste[]" value="Téléphone 1" />Téléphone 1<br/></label>
    <label><input type="checkbox" name="liste[]" value="Téléphone 2" />Téléphone 2<br/></label>
    <label><input type="checkbox" name="liste[]" value="Téléphone 3" />Téléphone 3<br/></label>	
  </td>
  <td>
    <label><input type="checkbox" name="liste[]" value="Groupe" />Groupe<br/></label>
    <label><input type="checkbox" name="liste[]" value="Certificat médical" />Certificat médical<br/></label>
    <label><input type="checkbox" name="liste[]" value="Questionnaire santé" />Questionnaire santé<br/></label>
    <label><input type="checkbox" name="liste[]" value="QS sport" />QS sport<br/></label>
    <label><input type="checkbox" name="liste[]" value="Assurance" />Assurance<br/></label>
    <!--<label><input type="checkbox" name="liste[]" value="Droit image" />Droit image<br/></label> -->
    <label><input type="checkbox" name="liste[]" value="Nom du Parent" />Nom du Parent<br/></label>
    <label><input type="checkbox" name="liste[]" value="Commentaire" />Commentaire<br/></label>
    <label><input type="checkbox" name="liste[]" value="Chèques" />Chèques<br/></label>
    <label><input type="checkbox" name="liste[]" value="Signature 1" />Signature 1<br/></label>
    <label><input type="checkbox" name="liste[]" value="Signature 2" />Signature 2<br/></label>
    <label><input type="checkbox" name="liste[]" value="Signature 3" />Signature 3<br/></label>
    <label><input type="checkbox" name="liste[]" value="Signature 4" />Signature 4<br/></label>
   </td></tr>
 </table>
  <?php
  
  function liste_defauts(){
  $var="";
  foreach ($_POST["liste"] as $key => $value)
	$var=$var."- $value <br>"; 
  return $var;
  }
  
if ($_SERVER['REQUEST_METHOD'] =="POST"){  

		$maj = (isset($_POST["maj"])) ? $_POST["maj"] : NULL;			
		require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");	
	    $erreur_dossier = $db->query("UPDATE dossier SET validation_admin='erreur' WHERE id_dossier={$id}");
		$nom_admin=$_SESSION['user'];
		$mail_admin= $_SESSION['email'];
		require('../config/mail.php');		
		$adress = array($email, $prenom, $nom);
		if ($email2!="")
		array_push ($adress, $email2);
		$subject = 'Erreur(s) dans votre inscription';	
		$body = '
     <html>
      <head>
       <title>Confirmation de votre adresse mail</title>	   
		<meta charset="UTF-8" />
      </head>
      <body>
	  <img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
       <p>Bonjour '.$prenom.',<br>
		Ceci est un message automatique envoyé par votre club de natation.<br>'.
		$nom_admin .', membre des administrateurs du site internet de votre club de natation, a examiné votre dossier d\'inscription.<br>
		Malheuresement, il a trouvé des erreurs et/ou des manques d\'informations. Merci de retourner sur votre <a href="http://'.$_SERVER['HTTP_HOST'].'/aca/espace-utilisateur.php#"> votre espace utilisateur </a>
		et de corriger les éléments suivants :<br>'.
		liste_defauts().'
		
		<br>Bon courage, <br>
		Merci de ne pas répondre à ce mail :)
		</p>
      </body>
     </html>
     ';
		$alt = "(logo du club) 
		Bonjour ".$prenom.",
		Ceci est un message texte automatique envoyé par votre club de natation.
		".$nom_admin.", membre des administrateurs du site internet de votre club de natation, a examiné votre dossier d'inscription.
		Malheuresement, il a trouvé des erreurs et/ou des manques d'information. Merci de retourner sur votre espace utilisateur
		et de corriger les éléments suivants :".
		liste_defauts()."	
		
		Bon courage, 
		Merci de ne pas répondre à ce mail :)";		
		
		if (sendMail($construction_mail, $adress, $subject, $body, $alt)){
			if ($maj==NULL)
			echo "<script>alert('Le message a bien été envoyé !'); document.location.href='dossier_membres.php';</script>";
			else
			echo "<script>alert('Le message a bien été envoyé !'); document.location.href='dossier_membres.php?maj=1';</script>";
		}
		
			
}
		
?>
   <input id="valid" type="submit" value="Valider"/>
   </form>
  </div> 
  </div>
 <?php require("{$_SERVER['DOCUMENT_ROOT']}/aca/fragments/bas.php"); ?>
  </body>
</html>