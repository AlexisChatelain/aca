<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() || !$login->isAdmin()){
		header('Location: /aca');
		die();
	}
?>

<h1>Liste des adhérents</h1>
	
	<br>
	<?php
	require('../scripts/saison.php');
	if (isset($_GET["maj"])){
	$maj=true;
	$checked="checked";
	$lien_maj="&maj=1";}
	else{
	$maj=false;
	$lien_maj="";
	$checked="";}
	echo "<div class='avant_table'><label> Cacher les dossiers déjà validés
	
    <input type='checkbox' id='valides' ".$checked." value='checked' onchange='maj();' />
	</label><br></div>";
	?>
	<script>
	function maj(){
	if (valides.checked==true){
	document.location.href='dossier_membres.php?maj=1';
	}else{
	document.location.href='dossier_membres.php';}
	}
	</script>
	
	<table id="membres">	

		<thead>
			<tr>
			  <th>ID dossier</th>
			  <th>Validation par admin</th>
			  <th>Adresse mail confirmée</th>
			  <th>Type licence</th>
			  <th>Club d'origine</th>
			  <th>IUF</th>
			  <th>Nom</th>
			  <th>Nationalité</th>
			  <th>Sexe</th>
			  <th>Naissance</th>
			  <th>Adresse</th>
			  <th>Mail</th>
			  <th>Mail 2</th>
			  <th>Téléphone 1</th>
			  <th>Téléphone 2</th>
			  <th>Téléphone 3</th>
			  <th>Groupe</th>
			  <th>Certificat médical</th>
			  <th>Questionnaire santé</th>
			  <th>QS sport</th>
			  <th>Assurance</th>			  
			  <th>Droit à l'image</th>
			  <th>Parent</th>
			  <th>Commentaire</th>
			  <th>Paiement</th>
			  <th>Montant cotisation</th>
			  <th>Signature 1</th>
			  <th>Signature 2</th>
			  <th>Signature 3</th>
			  <th>Signature 4</th>
			  <th>Date création</th>
			  <th>ID utilisateur</th>
			  <th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				function recup_cheques($db, $id_dossier){
				$query = $db->query("SELECT id_cheque,banque,numero,montant FROM cheques WHERE id_dossier=".$id_dossier);				
				$tab_cheques="<table class=tab_cheques><tr><th>id_cheque</th><th>banque</th><th>numero de chèque</th><th>montant</th>";
				while($row = $query->fetch_array()){	
					$tab_cheques=$tab_cheques."<tr>";
					$tab_cheques=$tab_cheques."<td>".strval($row["id_cheque"])."</td>";
					$tab_cheques=$tab_cheques."<td>".strval($row["banque"])."</td>";
					$tab_cheques=$tab_cheques."<td>".strval($row["numero"])."</td>";
					$tab_cheques=$tab_cheques."<td>".strval($row["montant"])." €</td>";	
					$tab_cheques=$tab_cheques."</tr>";}
				$tab_cheques=$tab_cheques."</table>";
				return $tab_cheques;
			}
				function creation_lien($fichier,$type,$id){
				return "<a target='_blank' href='/aca/scripts/get-file.php?fichier={$fichier}&type={$type}&id={$id}'>Cliquez ici pour afficher le fichier</a>";
				}
					
				require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");		
				$requete="SELECT dossier.*,categorie.nom AS groupe FROM dossier,categorie WHERE dossier.id_categorie=categorie.id_categorie ";
				if ($maj)
				$requete=$requete."and dossier.validation_admin<>'".$saison."'";
				$query = $db->query($requete);				
				while($row = $query->fetch_array()){					
					$id_dossier = $row["id_dossier"];				
					$validation_admin = $row["validation_admin"];
					$confirmation = $row["confirmation"];
					$type_licence = $row["type_licence"];
					$club_origine = $row["club_origine"];
					$IUF = $row["IUF"];
					$nom_prenom = $row["nom"]." ".$row["prenom"];
					$nationalite = $row["nationalite"];
					$sexe = $row["sexe"];
					$naissance = $row["date_naissance"];
					$adresse = $row["numero_rue"]." ".$row["rue"]." ".$row["code_postal"]." ".$row["ville"];
					$mail = $row["mail"];
					$mail2 = $row["mail2"];
					$tel1 = $row["tel1"];
					$tel2 = $row["tel2"];
					$tel3 = $row["tel3"];
					$groupe= $row["groupe"];
					$fichier1= creation_lien("fichier1","type_fichier1",$id_dossier);
					$fichier2= creation_lien("fichier2","type_fichier2",$id_dossier);
					$fichier3= creation_lien("fichier3","type_fichier3",$id_dossier);
					$fichier4= creation_lien("fichier4","type_fichier4",$id_dossier);
					 if ($row["accepte_lil"]=="1" || $row["accepte_lil"]==1)
					 $lil="Oui";
					 else
					 $lil="Non";
					$parent = $row["nom_parent"]." ".$row["prenom_parent"];
					$commentaire = $row["commentaire"];
					if ($row["nbcheques"]==0)
					$cheques = "Espèces";
					elseif ($row["nbcheques"]==1)
					$cheques = $row["nbcheques"]." chèque".recup_cheques($db, $id_dossier);
					else
					$cheques = $row["nbcheques"]." chèques".recup_cheques($db, $id_dossier);
					
					$montant_cotisation= $row["montant_cotisation"]." €";
					$signature1= $row["signature1"];
					$signature2= $row["signature2"];
					$signature3= $row["signature3"];
					$signature4= $row["signature4"]; 																							
					$date_creation = $row["date_creation"];
					$id_utilisateur = $row["id_utilisateur"];
					
					if ($signature3==null){
					$signature3="Pas de signature";					
					}else{
					$signature3="<img alt='signature 3' src='".$signature3."'>";	
					}
					
					if ($signature4==null){
					$signature4="Pas de signature";					
					}else{
					$signature4="<img alt='signature 4' src='".$signature4."'>";	
					}
					
					echo "<tr>
					<td>".$id_dossier."</td>
					<td>".$validation_admin."</td>
					<td>".$confirmation."</td>
					<td>".$type_licence."</td>
					<td>".$club_origine."</td>
					<td>".$IUF."</td>
					<td>".$nom_prenom."</td>
					<td>".$nationalite."</td>
					<td>".$sexe."</td>
					<td>".$naissance."</td>
					<td>".$adresse."</td>
					<td>".$mail."</td>
					<td>".$mail2."</td>
					<td>".$tel1."</td>
					<td>".$tel2."</td>
					<td>".$tel3."</td>
					<td>".$groupe."</td>
					<td>".$fichier1."</td>
					<td>".$fichier2."</td>
					<td>".$fichier3."</td>
					<td>".$fichier4."</td>
					<td>".$lil."</td>
					<td>".$parent."</td>
					<td>".$commentaire."</td>
					<td>".$cheques."</td>
					<td>".$montant_cotisation."</td>
					<td><img alt='signature 1' src='".$signature1."'></td>
					<td><img alt='signature 2' src='".$signature2."'></td>
					<td>".$signature3."</td>
					<td>".$signature4."</td>
					<td>".$date_creation."</td>
					<td>".$id_utilisateur."</td>
					<td>
					<a onclick=\"valid_dossier(".$id_dossier.")\" class=\"valid btn\"><i class=\"fa fa-check\"></i></a>
					<a href=\"../edit.php?id=".$id_dossier.$lien_maj."\" class=\"edit btn\"><i class=\"fa fa-edit\"></i></a>
					<a href=\"erreur_inscription.php?id_dossier=".$id_dossier.$lien_maj."\" class=\"exclamation btn\"><i class=\"fa fa-exclamation\"></i></a>
					<a onclick=\"delete_dossier(".$id_dossier.")\" class=\"delete btn\"><i class=\"fa fa-close\"></i></a>
					</td>

					</tr>";
				}
			?>
		</tbody>
		</table>