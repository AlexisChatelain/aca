<?php 
	$title = "Espace Utilisateur";
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
	<h1>Espace Utilisateur</h1>
	<p>Bienvenue <?= $_SESSION['user'] ?> sur votre espace utilisateur, voici la liste des fonctionnalités du site qui vous sont proposées.</p>
	
	<fieldset class="field-actions">
		<legend>Actions utilisateur</legend>
		<ul> <li> <a href="dossier.php"> Remplir un <strong> NOUVEAU </strong> dossier d'inscription <strong> VIERGE </strong> pour la saison en cours </a> </li>
		<?php						
		
			require("scripts/saison.php");
			require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

			$query = "SELECT validation_admin, id_dossier, nom, prenom FROM dossier WHERE confirmation='OK' and id_utilisateur = '".$_SESSION['id']."'";
			$result= $db->query($query);
			$result->data_seek(0);
			$renouvellement="";
			$dossiers="";
			$infos="";
			while($row=$result->fetch_assoc()){
				if ($row['validation_admin']==$saison){
				$dossiers=$dossiers."<li><strong>{$row['prenom']} {$row['nom']} : dossier validé </strong></li>";
				$infos=$infos."<li><a href='/aca/edit.php?id=".$row['id_dossier']."'>Dossier de <strong>{$row['prenom']} {$row['nom']}</strong></a></li>";
				}else if ($row['validation_admin']=="non"){
				$dossiers=$dossiers."<li><strong>{$row['prenom']} {$row['nom']} : dossier en attente de validation </strong></li>";
				$infos=$infos."<li><a href='/aca/edit.php?id=".$row['id_dossier']."'>Dossier de <strong>{$row['prenom']} {$row['nom']}</strong></a></li>";
				}else if ($row['validation_admin']=="erreur"){						
				$dossiers=$dossiers."<li><strong>{$row['prenom']} {$row['nom']} : erreur(s) détectée(s) après examen du dossier. Merci de le corriger.</strong></li>";
				$infos=$infos."<li> <a href='/aca/dossier.php?id_dossier={$row['id_dossier']}&erreur=1' >Corriger les erreurs du dossier de <strong>{$row['prenom']} {$row['nom']} </strong></a></li>";
				}else{		
				$renouvellement=$renouvellement."<li> <a href='/aca/dossier.php?id_dossier={$row['id_dossier']}' >
				<strong> RENOUVELER </strong> le dossier d'inscription de  <strong>{$row['prenom']} {$row['nom']}</strong> pour la saison {$saison}";
			}}
			if ($dossiers==""){
			$dossiers="<li>Vous n'avez aucun dossier rattaché à ce compte.</li>";
			}
			
			echo $renouvellement.
			     "<li onclick='if (dossiers.hidden==false){dossiers.hidden=true;}else{dossiers.hidden=false;}'><a href='#'>Suivi des dossiers d'adhésion</a></li>
				        <ul hidden id='dossiers'>".$dossiers."</ul>
			     <li onclick='if (infos.hidden==false){infos.hidden=true;}else{infos.hidden=false;}'><a href='#'>Modification de vos informations personnelles (compte et dossiers d'adhésion)</a></li>
			      <ul hidden id='infos'>
			     <li> <a href='/aca/edit.php'>Modification des informations personnelles et mot de passe <strong> du compte </strong></a></li>"
				 .$infos."</ul>";	
			
			?>			
			
			<li> <a href="/aca/galerie.php">Ajouter du contenu multimédia à la galerie du club</a> </li>
			<li> <a href="/aca/boutique.php">Aller sur la boutique en ligne du club</a> </li>
			<li> <a href="/aca/commande.php?id=<?php echo $_SESSION["id"]; ?>">Voir mon panier / Valider mes commandes</a> </li>
		</ul>
	</fieldset>
	
	<?php if($login->isAdmin()){?>
	<fieldset class="field-actions">
		<legend>Actions administrateur</legend>
		<ul>
			<li> <a href="/aca/admin/membres.php">Liste des utilisateurs inscrits</a></li>
			<li> <a href="/aca/admin/dossier_membres.php">Validation des dossiers d'inscription</a></li>
			<li> <a href="/aca/planning.php">Modification du planning</a></li>
			<li> <a href="/aca/admin/commande.php">Résumé des commandes validées des adhérents</a></li>
			<li> <a href="/aca/admin/commande.php?liste=1">Vue des commandes par adhérent</a></li>
		</ul>
	</fieldset>
	<?php } ?>
	<a id="logout" href="/aca/?logout">Se déconnecter</a>
</div>

	<?php require('fragments/bas.php'); ?>	
</body>

</html>