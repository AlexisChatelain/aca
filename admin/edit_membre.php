<?php 
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$getUser = $db->query("SELECT nom, prenom, mail, admin FROM utilisateur WHERE id_utilisateur={$id};");
	
	if ($getUser->num_rows == 1) {
		$result_row = $getUser->fetch_object();

		$nom = $result_row->nom;
		$prenom = $result_row->prenom;
		$mail = $result_row->mail;
		$admin = $result_row->admin;
	} else {
		header('Location: /aca/admin/membres.php');
	}
?>


<h1>Edition de l'utilisateur <?php if ($login->isAdmin())echo $id;?> :</h1>


<form action="/aca/scripts/edit_membre.php" method="post">
	 <input type="hidden" name="id" value="<?php echo $id;?>">

	<label>Nom :
		<input type="text" name="nom" placeholder="Nom .." value="<?php echo $nom; ?>" required/>
	</label>
	<label>Prénom :
		<input type="text" placeholder="Prenom .." name="prenom" value="<?php echo $prenom; ?>" required/>
	</label>
	<label>E-mail :
		<input type="email" name="mail" placeholder="Adresse mail .." value="<?php echo $mail; ?>" required/>
	</label>
	<label>Nouveau mot de passe :
		<input type="password" name="mdp" placeholder="Mot de passe .."/>
	</label>
	<label>Rôle :
		<select name="role" required <?= !$_SESSION['admin'] ? 'disabled' : ''?> size="2">
			<?php if($admin){
					echo "<option value=\"1\" selected=\"selected\">Administrateur</option>
					<option value=\"0\">Utilisateur</option>";
				}else{
					echo "<option value=\"1\">Administrateur</option>
					<option value=\"0\" selected=\"selected\">Invité</option>";
			}?>
		</select>	
	</label>
	
	<input class="i_bouton" type="submit" value="Enregistrer"/><br>


</form>