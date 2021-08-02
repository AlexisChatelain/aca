<?php
	$title = "Confirmation";
	$css = "dossier.css";
?>

<?php 
	require('fragments/head.php');
?>

<body>

<div class="content">
	<?php require('fragments/nav.php'); 
	require('fragments/nav_img.php'); 
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");?>

	<div id="div_confirmation">
	<h1>Confirmation INDISPENSABLE de l'inscription.</h1>
	<p>
	Merci de saisir ici le code envoyé sur votre adresse e-mail pour confirmer votre inscription et vous assurer que votre adresse mail est valide.<br>
	Votre compre se supprimera automatiquement dans 24 heures si vous ne confirmez pas votre inscription
	</p>
	<p>
	<?php
		if ($_SERVER['REQUEST_METHOD'] =="POST"){
			$query = $db->query("SELECT confirmation FROM utilisateur WHERE mail='".$_POST["mail"]."'");
			$row = $query->fetch_object();
		if (strval($row->confirmation) == $_POST["texte"]){
			$query = $db->query("UPDATE utilisateur SET confirmation ='OK' WHERE confirmation='".$_POST["texte"]."' and  mail='".$_POST["mail"]."'");
			if(!$query)
				trigger_error($db->error);
			else
				echo "Votre compte est créé. Veuillez vous connecter.";
		}else{
			$incorrect="Code incorrect.";}
		}

	?>
	</p>
	<form id="confirmation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">  
		<input hidden type="text" name="mail" value="<?php if(isset($_GET['mail'])){ echo $_GET['mail']; }else{ echo $_POST['mail'];}?>" />
		<input id="confirm" type="text" maxlength="15" name="texte" placeholder="Saisissez le code ici..." title="Saisir ou coller le code reçu par mail" value="<?php if(isset($incorrect)){ echo $incorrect;} ?>" required />
		<br>
		<input id="valid" type="submit" name="soumet" value="Valider"/>
	</form>
	</div>  
</div>  
<?php require('fragments/bas.php'); ?>
</body>
</html>