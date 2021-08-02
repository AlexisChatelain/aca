<?php 
	$title = "Contact";
	$css = "contact.css";
	$messages = array();

	if(isset($_POST["send"])){
		$nom = (isset($_POST["nom"])) ? $_POST["nom"] : NULL;
		$prenom = (isset($_POST["prenom"])) ? $_POST["prenom"] : NULL;
		$email = (isset($_POST["email"])) ? $_POST["email"] : NULL;
		$probleme = (isset($_POST["probleme"])) ? $_POST["probleme"] : NULL;

		setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
		require('config/mail.php');		
		$adress = array($mail_from, "Club", "natation");
		
		$subject = "Ouverture d'un nouveau ticket par ".$prenom." ".$nom;

		$body = '
		 <html>
		  <head>
		   <title>Ouverture d\'un nouveau ticket</title>	   
			<meta charset="UTF-8" />
		  </head>
		  <body>
		  <img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
		   <p>Bonjour,<br>
			Ceci est un message automatique envoyé par le site internet du club de natation.<br>'.
			$prenom.' '.$nom.' a ouvert un nouveau ticket le '.strftime("%A %d %B %Y, %H:%M").'.<br>
			Voici son adresse mail : <a href="mailto:'.$email.'">'.$email.'</a> <br>
			Description du problème : <br><br>'.
			$probleme.'<br><br>
			Merci de ne pas répondre à ce mail :)
			</p>
		  </body>
		 </html>
		 ';
		$alt = "(logo du club) 
			Bonjour
			Ceci est un message automatique envoyé par le site internet du club de natation.".
			$prenom." ".$nom." a ouvert un nouveau ticket le ".strftime("%A %d %B %Y, %H:%M")."
			Voici son adresse mail : ".$email."
			Description du problème : ".
			
			$probleme."
			
			Merci de ne pas répondre à ce mail :)";
			
			if (sendMail($construction_mail, $adress, $subject, $body, $alt, false))	
				$messages[] = "Votre demande a été envoyée.";
			else 	
				$messages[] = "Une erreur est survenue, votre demande n'a pas été envoyée.";
			
	}
?>
<?php require('fragments/head.php'); ?>

<body>
<div class="content">

	<?php require('fragments/nav.php'); ?>
	<?php require('fragments/nav_img.php'); ?>
	
	<h1>Formulaire de contact</h1>
	<h3> Merci de remplir attentivement les champs ci-dessous.</h3>

	<form action="<?=($_SERVER['PHP_SELF'])?>" method="post">
		<h2 class="rq_contact">Qui êtes-vous ?</h2>
		<label>Nom
		<input type="text"
				pattern="^[a-zA-Z]{1}[a-zA-Z\- ]*[a-zA-Z]{1}"
				title="Votre nom"
				placeholder="Dupont"
				maxlength="30" 
				name="nom" required/></label> 

		<label>Prénom
		<input type="text"
				pattern="^[a-zA-Z]{1}[a-zA-Z\- ]*[a-zA-Z]{1}"
				title="Votre prénom"
				placeholder="Eric"
				name="prenom" 
				maxlength="30" required/></label>		
				
		<label>Adresse e-mail	
		<input type="text"
				pattern="^[a-zA-Z1-9-\.]+@[a-zA-Z1-9-\.]+\.[a-zA-Z]{2,6}$"
				title="Votre mail"
				placeholder="exemple@mail.fr"
				name="email" 
				maxlength="40" 
				required /></label>	

		<h2 class="rq_contact"> Pourquoi souhaitez-vous nous contacter ? </h2>	
		<p id="st"> Merci d'être le plus précis possible, afin que nous répondions dans les meilleurs délais. </p> 

		<label>Description du problème
				<!--pattern="^[a-zA-Z]{1}[a-zA-Z ]*[a-zA-Z]{1}"-->
			<textarea
				name="probleme"
				title="Votre problème"
				placeholder="Je vous contacte à propos de ..."
				maxlength="1000" 
				cols="40"
				rows="4" required ></textarea></label>
			
		<input type="submit" name="send" value="Valider">
		<br>
		<?php
			if ($messages) {
				foreach ($messages as $msg) {
					echo "<p style='
						font-size: inherit;
						text-align: center;
					'> $msg	</p>";
				}
			}
		?>
	</form>
</div>

	<?php require('fragments/bas.php'); ?>
</body>
</html>