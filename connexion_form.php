<?php 
	$title = "Connexion";
	$css = "connexion.css";
?>
<?php require('fragments/head.php'); ?>

<body>
<div class="content">
	<?php require('fragments/nav.php'); ?>
	<?php require('fragments/nav_img.php'); ?>
	<h1>Connexion</h1>
	  
	 <form id="connexion" action="connexion.php" method="post">
	   <label for="mail">E-mail :</label>
		<input type="text" size="30" id="mail" name="mail" value="<?php if(isset($_POST['mail'])) echo $_POST['mail'];?>" pattern="^[a-zA-Z0-9-\.]+@[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,6}$" required/>
		
		<label for="password">Mot de passe :</label>
		<input type="password" id="password" size="30" name="mdp" required/>

		<input class="i_bouton" type="submit" name="login" value="Se connecter"/>
		<br>
		<!--<div> <a href="mdp-oublie.php"> Mot de passe oublié ?</a></div>-->
		<div>Pas de compte ? <a href="inscription.php">S'enregistrer</a></div>
		<?php
			if (isset($login)) {
				if ($login->messages) {
					foreach ($login->messages as $error) {
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
