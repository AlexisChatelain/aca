 <?php 
	$title = "Inscription validée";
?>

<?php 
	require('fragments/head.php');
	if (!$login->isUserLoggedIn()) {
		header('Location: /aca/connexion.php');
	}
?>

<body>
<div class="content">
<?php require('fragments/nav.php'); ?>
<?php require('fragments/nav_img.php'); ?>
 
  <?php
$erreurs=0;
require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
$req = "SELECT confirmation FROM dossier WHERE confirmation='".$_GET['texte']."' and mail='".$_GET['mail']."';";
$res = $db->query($req);		
if(!$res){
trigger_error($db->error);
$erreurs=1;}				
$row = $res->fetch_assoc();
if ($row['confirmation']==$_GET['texte']){

$req="UPDATE cheques SET confirmation='OK' WHERE id_dossier=(Select id_dossier from dossier where id_utilisateur='". $_SESSION['id']."') and confirmation='".$_GET['texte']."';" ;
$result = $db->query($req);		
	  
$req="UPDATE dossier SET confirmation='OK' WHERE id_utilisateur='".$_SESSION['id']."' and confirmation='".$_GET['texte']."';";
$result = $db->query($req);		
if(!$result){
//trigger_error($db->error);
$erreurs=1;}
if ($erreurs!=1){
echo "Votre inscription est validée.<br>";
echo "Le club a été prévenu de l'envoi de votre dossier : ";
		require('config/mail.php');
		$adress = array($mail_from, "Club", "Natation");
		$subject = 'Nouveau dossier créé par '.$_SESSION["user"].' en attente de validation';
		$body = '<html>
		  <head>
		   <title>Nouveau dossier créé par '.$_SESSION["user"].' en attente de de validation</title>	   
			<meta charset="UTF-8" />
		  </head>
		  <body>	  
		   <img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
		   <p>Bonjour,<br>
			Ceci est un message automatique envoyé par le ste web et s\'adresse à ses administrateurs.<br>
			Un nouveau dossier a été créé par '.$_SESSION["user"].' et est donc en attente de validation par les administateurs.<br>
			Rendez-vous sur l\'espace administateur du site internet pour la validation de ce dossier.<br>
			Merci de ne pas répondre à ce mail :)
			</p>
		  </body>
		 </html>';
		$alt='(logo du club) Bonjour,
		Ceci est un message texte automatique envoyé par le ste web et s\'adresse à ses administrateurs.
		Un nouveau dossier a été créé par '.$_SESSION["user"].' et est donc en attente de validation par les administateurs.
		Rendez-vous sur l\'espace administateur du site internet pour la validation de ce dossier.
		Merci de ne pas répondre à ce mail :)<br>';
		sendMail($construction_mail, $adress, $subject, $body, $alt);
echo "<h2>ATTENTION concernant le matériel (paiement à part de la licence et de l'adhésion)</h2>
	  <h2>Les compétiteurs devront avoir leur propre matériel, celui proposé par le club ou non et devront aussi aux compétitions porter les couleurs du club (minimum bonnet et maillot de bain).<br>
      Vous pouvez soit quitter cette page soit vous rendre sur la boutique en ligne du club en cliquant sur le lien suivant : <br>
	  <a href='boutique.php'> Boutique en ligne </a></h2>
	  ";}
}else{
	header("location:confirmation_dossier.php?mail=".$_GET['mail']."&erreur=1");}
?>
</div>
<?php require('fragments/bas.php'); ?>

 </body>
</html>