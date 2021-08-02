<?php 
	$title = "Inscription";
	$css = "connexion.css";

?>
<?php require('fragments/head.php'); ?>

<body>
<div class="content">

	<?php require('fragments/nav.php'); ?>
	<?php require('fragments/nav_img.php'); ?>
	<h1>Inscription</h1>
		<form id="inscription" action="inscription.php" method="post">
			<label>
			 Nom :
				<input type="text" maxlength="40" size="30" name="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom'];?>"  required/>
			</label>
			<label>
			 Prénom :
				<input type="text" maxlength="40" size="30" name="prenom" value="<?php if(isset($_POST['prenom'])) echo $_POST['prenom'];?>"  required/>
			</label>
			<label>
			 E-mail :
				<input type="email" maxlength="40" size="30" name="mail" value="<?php if(isset($_POST['mail'])) echo $_POST['mail'];?>" pattern="^[a-zA-Z0-9-\.]+@[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,6}$" required/>
			</label>
			<label>
			 Confirmation e-mail :
				<input type="email" maxlength="40" size="30" name="confirm_mail" value="<?php if(isset($_POST['confirm_mail'])) echo $_POST['confirm_mail'];?>" pattern="^[a-zA-Z0-9-\.]+@[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,6}$" autocomplete="off" required/>   
			</label>
			<label>
			 Mot de passe :
				<input type="password" maxlength="40" size="30" name="mdp" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$" title="Mot de passe de 8 caractères minimum (Majuscules, minuscules, nombres et caractères spéciaux acceptés)" required/>     
			</label>
			<label>
			 Confirmation mot de passe :
				<input type="password" maxlength="40" size="30" name="confirm_mdp" title="Mot de passe de 8 caractères minimum (Majuscules, minuscules, nombres et caractères spéciaux acceptés)" required/>
			</label>
			<input type="submit" name="register" class="i_bouton" value="S'inscrire"/>
			
			<div>Déja un compte ? <a href="connexion.php">Se connecter</a></div>
			<?php
			if (isset($registration)) {
				if ($registration->messages) {
					foreach ($registration->messages as $error) {
						echo $error;
					}
				}
			}
			?>

		</form>
		</div>

	<?php require('fragments/bas.php'); ?>
 </body>
</html>
