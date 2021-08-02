<?php 
	$title = "Dossier d'inscription";
	$css = "dossier.css";	

?>

<?php 
	require('fragments/head.php');	
	if (!$login->isUserLoggedIn()) {
		header('Location: /aca/connexion.php');
	}
 	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
	$dossier="";
	$cheques=array();
	//echo $_SERVER['HTTP_USER_AGENT'];
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE || 
		strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== FALSE || 
		strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE  ) {
		echo 'Internet Explorer/ Microsoft Edge (ancienne version) sont incompatibles !' ; // au cas où la redirection ne fonctionne pas
		header('Location: /aca/IE_incompatible.php');
		die();
	}
	
	function creation_lien($fichier,$type,$id){
		echo "<td><a target='_blank' href='/aca/scripts/get-file.php?fichier={$fichier}&type={$type}&id={$id}'>Cliquez ici pour afficher l'ancien fichier</a></td>";
	}

	function fonction($variable){
		global $db;
		$query = "SELECT nom FROM categorie WHERE id_categorie = '".$variable."'";
		$result= $db->query($query);
		$result_row = $result->fetch_object();
		$result->free();
		return($result_row->nom);
	}

	function reduction_cotisation(){
		global $db;
		$query = "SELECT count(id_dossier) AS nonbre_dossiers FROM dossier WHERE confirmation='OK' AND id_utilisateur = ".$_SESSION['id'];;
		$result= $db->query($query);
		$result_row = $result->fetch_object();
		$result->free();
		return($result_row->nonbre_dossiers);
	}

	function distrib_renouvellement(){
		global $dossier, $db;
		if (isset($_GET["id_dossier"])){
			$query = "SELECT * FROM dossier WHERE confirmation='OK' and id_dossier='".$_GET["id_dossier"]."' and id_utilisateur = '".$_SESSION['id']."'";
			$result= $db->query($query);	

			$result->data_seek(0);
			if ($result->num_rows != 1) {
				return -1;
			}
			else{
				while($row=$result->fetch_assoc()){
				$dossier=$row;
				}
			}
			$result->free();
		return 1;
		}
		else{
			return -1;
		}
	}
	function recup_cheques(){
		global $cheques, $db;
		$requete="SELECT banque,numero,montant FROM cheques WHERE id_dossier='".$_GET["id_dossier"]."'";
		$result= $db->query($requete);	
		while($row=$result->fetch_assoc())
		array_push ($cheques,array($row["banque"],$row["numero"],$row["montant"]));	
		$result->free();	
		return $cheques;
	}

	function affich($key){
		global $dossier;
		if (isset($dossier[$key]))
			return $dossier[$key];
		else
			return null;
	}

?>

<body>

<div class="content">
<?php require('fragments/nav.php');
	  require('fragments/nav_img.php'); 
	  require('scripts/saison.php');
?>

 <div id="dossier_inscription">
 
 <div id="div_entete">
 <div id="img1" class="entete">
 <img src="/aca/images/logo_ffn.png" width="150" alt="logo FFN">
 </div>
 <div id="titre1" class="entete">
<h1>Dossier d'inscription <?=$saison; ?></h1>
  </div>
 <div id="img2" class="entete">
<img id="logo" src="/aca/images/logo.png" alt="logo ACA" width="150">
 </div>
 </div>
 <div id="formulaire">
 <form enctype="multipart/form-data" id="inscription" action="/aca/confirmation_dossier.php" method="post"> 
 
 
 <fieldset id="section1">
 	<legend>Type de licence :</legend>
	<input type="hidden" id="renouvellement_auto" value="<?php 	if (isset($_GET['id_dossier'])) echo $_GET['id_dossier']; ?>" name="renouvellement_auto"  />	
	<input type="hidden" id="erreur"              value="<?php 	if (isset($_GET['erreur'])) echo $_GET['erreur']; ?>" name="erreur"  />
	<label><input type="radio" id="type_licence1" name="type_licence" checked value="Nouvellle licence" />Nouvellle licence</label>
	<label><input type="radio" id="type_licence2" name="type_licence" value="Transfert" />Transfert</label>
	<label><input type="radio" id="type_licence3" name="type_licence" value="Renouvellement" />Renouvellement</label>
   <br><label hidden for="old_club" id="label_old_club">Nom du club précédent :</label> 
     <input hidden id="old_club" type="text" name="old_club" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre ancien club : Première lettre en majuscule et pas d'espaces à la fin" />
   <br><label hidden for="iuf" id="label_iuf">IUF : </label> 
     <input hidden id="iuf" type="text" name="iuf" maxlength="7" pattern="[0-9]{6-7}" title="Votre IUF (Identifiant Unique Fédéral) : numéro de licence FFN à 7 chiffres" />
   <br><a hidden id="lien_iuf" href="https://ffn.extranat.fr/webffn/nat_recherche.php" target="_blank"> Recherche manuelle de l'IUF</a>
   <br><a hidden id='lien_iuf2' href='/aca/scripts/IUF.php<?php distrib_renouvellement(); echo "?nom=".affich('nom')."%20".affich('prenom'); ?>' target='_blank'> Recherche automatique de l'IUF</a>
  </fieldset>
  
   <fieldset hidden id="section2">
	<legend>Licencié :</legend>
   <label for="nom">Nom :</label> 
     <input type="text" name="nom" id="nom" onchange="remplissage_auto();" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Dupont" required />
   <br><label for="prenom">Prénom :</label> 
     <input type="text" name="prenom" id="prenom" onchange="remplissage_auto();" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Eric" required />
	<br><label for="nationalite" >Nationalité :</label> 
	<datalist id="datalist_nationalites">
		<option value="Française"></option>
	</datalist>
	 <input type="text" name="nationalite" id="nationalite" maxlength="30" title="Nationalité" list="datalist_nationalites" placeholder="Saisir ou choisir..."  required />
    <br><br><label for="sexe" >Sexe :</label>
	 <select name="sexe" id="sexe">
	  <option value="H" selected>H</option>
	  <option value="F">F</option>
     </select>
	<br><label for="naissance" >Date de naissance :</label> 
	 <input type="text" name="naissance" id="naissance" title="Date de naissance (jj/mm/aaaa)" maxlength="10" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" placeholder= "jj/mm/aaaa" required />
 </fieldset>
 
 
 <fieldset hidden id="section3" >
	<legend>Coordonnées du licencié :</legend>
	 <label for="numero"> N° </label>
     <input type="number" name="numero" min="0" id="numero" placeholder="3" required /> <br>
   <label for="rue"> Rue : </label>
	<input id="rue" type="text" size="30" name="rue" placeholder="Rue du Clos des Gardes" title="Rue" required />  
   <br><label for="cp"> Code postal : </label>
	<input  id="cp" type="text" size="30" name="cp" maxlength="5" pattern="[0-9]{5-5}" placeholder="37400" title="Code postal (5 chiffres)" required />
   <br><label for="ville"> Ville : </label>
	<input  id="ville" type="text" size="30" name="ville" placeholder="Amboise" title="Ville" required />
   <br><label for="mail1">E-mail 1 (obligatoire) :</label>
     <input type="email" name="mail1" id="mail1" pattern="^[a-zA-Z0-9-\.]+@[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,6}$" placeholder= "exemple@mail.fr" required />
   <br><label for="mail2">E-mail 2 :</label>
     <input type="email" name="mail2" id="mail2" pattern="^[a-zA-Z0-9-\.]+@[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,6}$" placeholder= "exemple@mail.fr" />
   <br><label for="telephone1">Téléphone 1 (obligatoire) :</label>
     <input type="tel" name="telephone1" id="telephone1" maxlength="14" placeholder="0x xx xx xx xx" pattern="0[1-9]([ ]?[0-9]{2}){4}" required />   
   <br><label for="telephone2">Téléphone 2 :</label>
     <input type="tel" name="telephone2" id="telephone2" maxlength="14" placeholder="0x xx xx xx xx" pattern="0[1-9]([ ]?[0-9]{2}){4}" />   
   <br><label for="telephone3">Téléphone 3 :</label>
     <input type="tel" name="telephone3" id="telephone3" maxlength="14" placeholder="0x xx xx xx xx" pattern="0[1-9]([ ]?[0-9]{2}){4}" />   
</fieldset> 


<fieldset hidden id="section4">
	<legend>Questionnaire pour détermination du groupe :</legend>
	<table>
	<tr id="ligne1"><td>Type de discipline :</td>
	<td><label><input type="radio" name="type_discipline" id="type_discipline1" value="Natation" />Natation "course" (discipline majoritaire et par défaut)</label></td>
	<td><label><input type="radio" name="type_discipline" id="type_discipline2" value="Water-polo"  >Water-polo</label></td>
	<td><label><input type="radio" name="type_discipline" id="type_discipline3" value="Palmes" />Nage avec palmes</label></td>
	<td><label><input type="radio" name="type_discipline" id="type_discipline4" value="Aquabike" />Aquabike (vélo dans l'eau)</label></td>
	</tr>
	<tr id="ligne2" hidden> <td>Catégorie de la séance :</td>
	<td><label><input type="radio" name="type_seance_enfant" id="type_seance_enfant1" value="Competition"  >Compétition</label></td>
	<td><label><input type="radio" name="type_seance_enfant" id="type_seance_enfant2" value="Loisirs" />Loisirs</label></td>
	<td><label><input type="radio" name="type_seance_enfant" id="type_seance_enfant3" value="Savoir_Nager" />Dispositif de l'Etat : Savoir Nager</label></td>	
	<td></td>
	</tr>
	<tr id="ligne3" hidden><td>Catégorie de la séance :</td>
	<td><label><input type="radio" name="type_seance_adulte" id="type_seance_adulte1" value="Competition"  >Compétition</label></td>
	<td><label><input type="radio" name="type_seance_adulte" id="type_seance_adulte2" value="Loisirs" />Loisirs</label></td>
	<td><label><input type="radio" name="type_seance_adulte" id="type_seance_adulte3" value="NFS" />Dispositif de l'Etat : Nagez Forme Santé</label></td>	
	<td></td>
	</tr>
	<tr id="ligne4" hidden><td>Diplôme le plus prestigieux validé lors de sessions ENF :<br>
	<a href="https://www.natationpourtous.com/espace-pro/enseignement/ecole-natation-francaise.php" target="_blank"> Lien vers l'ENF (Explications sur les diplômes de la FFN) </a><br>
	<a href="https://www.ffnatation.fr/apprendre-nager-au-sein-lecole-natation-francaise" target="_blank"> Lien alternatif </a></td>
	<td><label><input type="radio" name="type_ENF" id="type_ENF1" value="Aucun"  >Aucun</label></td>
	<td><label><input type="radio" name="type_ENF" id="type_ENF2" value="Sauv'nage" />Sauv'nage (Pass'club)</label></td>
	<td><label><input type="radio" name="type_ENF" id="type_ENF3" value="Pass'sport" />Pass'sport de l'eau </label></td>
	<td><label><input type="radio" name="type_ENF" id="type_ENF4" value="Pass'compet" />Pass'compétition </label></td>
	</tr>
	</table>
	<div id="div_resultat" hidden>
		<h2><label>Notez-le bien ! D'après vos réponses, il a été déterminé que votre groupe pour cette saison est : </label><br></h2>
			<h2><strong>
					<output name="affichage_resultat_groupe" id="affichage_resultat_groupe">Pas assez d'informations (vérifiez les informations sexe et date de naissance)
					</output></strong></h2>
		<input hidden type="text" name="resultat_groupe" id="resultat_groupe" value="Pas assez d'informations (vérifiez les informations sexe et date de naissance)"/>
		<label>Votre affectation peut, dans certains cas, être sujette à modification par les maîtres nageurs. 
		<br>
			   Vous pouvez aussi convenir avec eux d'un changement de groupe en cas d'indisponibilités à ces horaires.	
		</label>
		<br>
		<a href="planning.php" target="_blank"> Lien vers le planning des créneaux </a>
	</div>	
	</fieldset> 
	
	
	<fieldset hidden id="section5">
	<legend>Pièces à joindre :</legend>
	<table>
		<tr>
			<td><ul><li>Certificat médical si 1ère inscription ou si votre certificat date de plus de 3ans</li></ul></td>
			<td><input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
			<input type="file" name="doc1" id="doc1" class="fichier" accept=".pdf" required  /></td>
			<?php
			if (isset($_GET["id_dossier"]))
			creation_lien("fichier1","type_fichier1",$_GET["id_dossier"]);
			?>
		</tr>
		
		<tr>
			<td><ul><li>Questionnaire santé sport rempli (si renouvellement et certificat de moins de 3 ans)</li></ul></td>
			<td><input type="file" name="doc2" id="doc2" class="fichier" accept=".pdf" required /></td>
			<?php
			if (isset($_GET["id_dossier"]))
			creation_lien("fichier2","type_fichier2",$_GET["id_dossier"]);
			?>
		</tr>
		
		<tr>
			<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="pdf/questionnaire_sante.pdf" target="_blank">Questionnaire santé vierge à télécharger</a></td>
			<td></td>		
			<?php
			if (isset($_GET["id_dossier"]))
			echo "<td></td>";
			?>
		</tr>
		
		<tr>
			<td><ul><li>Attestation de réponse négative à toutes les questions du QS sport (si renouvellement et certificat de moins de 3 ans)</li></ul></td>
			<td><input type="file" name="doc3" id="doc3" class="fichier" accept=".pdf" required /></td>
			<?php
			if (isset($_GET["id_dossier"])){
			creation_lien("fichier3","type_fichier3",$_GET["id_dossier"]);
			}
			?>
		</tr>
		
		<tr>
			<td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="pdf/reponse_negative.pdf" target="_blank">Attestion de réponse négative vierge à télécharger</a></td>
			<td></td>	
			<?php
			if (isset($_GET["id_dossier"]))
			echo "<td></td>";
			?>
		</tr>
		
		<tr>
			<td><ul><li>Copie d’attestation d’assurance Responsabilité civile obligatoire pour tous</li></ul></td>
			<td><input type="file" name="doc4" id="doc4" class="fichier" accept=".pdf" required /></td>
		<?php
		if (isset($_GET["id_dossier"]))
		creation_lien("fichier4","type_fichier4",$_GET["id_dossier"]);
		?>
		</tr>
	</table>
	</fieldset>
	
	
	<fieldset hidden id="section6">
	<legend>Adhésion au club :</legend>
	<table>
	<tr><td>
		<input type='hidden' name='myCanvas1' value='null'/>
		<canvas id="myCanvas1" class="signature" width="200" height="200" style="margin-right: 50px; border: solid 1pt black ;" >Votre navigateur ne supporte pas les canvas (pour réaliser une signature).</canvas>
		<input id="effacer1" type="button" onclick="arraysign[0].effacer(); arraysign[0].check=false;" value="Effacer la signature" />
    </td>
	<td>
	<p> Je soussigné 
   <label for="nom_adhesion">Nom : </label> 
     <input type="text" name="nom_adhesion" id="nom_adhesion" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Dupont" required />
   <label for="prenom_adhesion">Prénom :</label> 
     <input type="text" name="prenom_adhesion" id="prenom_adhesion" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Eric" required />
	reconnais avoir pris connaissance du règlement intérieur du club et m’engage à le respecter.(document disponible sur <a href="reglement_interieur.php" target="_blank">le site internet du club </a> ou au bureau du club) et m’engage aussi à respecter les statuts et règlements de la Fédération FFN (disponible sur le <a href="https://ffn.extranat.fr/" target="_blank">site internet </a>ou au bureau du club)
	<br>
	<strong>Signature du licencié (des parents ou du représentant légal si le licencié est mineur)
	<br>	
   <label for="date_adhesion">Le : </label> 
     <input disabled type="text" name="date_adhesion" id="date_adhesion"  title="Date de la signature (jj/mm/aaaa)" value="<?php  echo $maintenant; ?>" required />
	<label for="ville_adhesion"> A : </label>
	<input  id="ville_adhesion" onchange="remplissage_auto();" type="text" size="30" name="ville_adhesion" placeholder="" title="Ville" required />
   	</strong>
	</p>
	</td>
	</tr>
	</table>
	</fieldset>
	
	
 	<fieldset hidden id="section7">
	<legend>Loi Informatique et libertés (Loi du 6 janvier 1978) & Droit à l’image :</legend>
	<table>
	<tr><td>
		<input type='hidden' name='myCanvas2' value='null'/>
		<canvas id="myCanvas2" class="signature" width="200" height="200" style="margin-right: 50px; border: solid 1pt black ;" >Votre navigateur ne supporte pas les canvas (pour réaliser une signature).</canvas>
		<input id="effacer2" type="button" onclick="arraysign[1].effacer();  arraysign[1].check=false;" value="Effacer la signature" />
	</td>
	<td>
	<p> Le soussigné dispose d’un droit d’accès et de rectification aux informations portées sur sa fiche individuelle. Ces informations sont destinées au Club ACA Natation et ne peuvent pas être cédées à des partenaires commerciaux ou toute autre personne ou organisme.
Cependant le soussigné autorise le Club à utiliser son image sur tout support destiné à la promotion des activités du Club, à l’exclusion de toute utilisation à titre commercial. Cette autorisation est donnée à titre gracieux pour une durée de 4 ans et pour la France.
	<br>
	<strong><br>Signature du licencié (des parents ou du représentant légal si le licencié est mineur)
	<br>
	<label><input type="radio" id="accepte_lil" name="choix_lil" checked value="Autorisation"/>Accepte</label>
	<label><input type="radio" id="refuse_lil" name="choix_lil" value="Refus"/>N'accepte pas</label>
	<br>
   <label for="date_lil">Le : </label> 
     <input disabled type="text" name="date_lil" id="date_lil"  title="Date de la signature (jj/mm/aaaa)" value="<?php  echo $maintenant; ?>" required />
	<label for="ville_lil"> A : </label>
	<input  id="ville_lil" type="text" size="30" name="ville_lil" placeholder="" title="Ville" required />
   	</strong>
	</p>
	</td>
	</tr>
	</table>
	</fieldset>
	

 <fieldset hidden id="section8">
	<legend>Autorisations parentales pour les Nageurs Mineurs :</legend>	
	<table>
	<tr><td>
		<input type='hidden' name='myCanvas3' value='null'/>
		<canvas id="myCanvas3" class="signature" width="200" height="200" style="margin-right: 50px; border: solid 1pt black ;" >Votre navigateur ne supporte pas les canvas (pour réaliser une signature).</canvas>
		<input id="effacer3" type="button" onclick="arraysign[2].effacer(); arraysign[2].check=false;" value="Effacer la signature" />
    </td>
	<td>
	Je soussigné 
   <label for="nom_parent">Nom : </label> 
     <input type="text" name="nom_parent" id="nom_parent" onchange="remplissage_auto();" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Dupont" required />
   <label for="prenom_parent">Prénom :</label> 
     <input type="text" name="prenom_parent" id="prenom_parent" onchange="remplissage_auto();" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Eric" required />
	père / mère / tuteur légal autorise mon enfant
	   <label for="nom_enfant">Nom : </label> 
     <input type="text" name="nom_enfant" id="nom_enfant" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Dupont" required />
   <label for="prenom_enfant">Prénom :</label> 
     <input type="text" name="prenom_enfant" id="prenom_enfant" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Eric" required />
	<ul><li> A pratiquer des activités sportives au sein du club de l'ACA </li></ul>
	<ul><li> Au début de chaque séance, je m’engage à ne pas quitter la surveillance de mon enfant avant de m’être assuré (e) de la prise en charge effective de celui-ci par un responsable de l’association chargé de l’accueillir,</li></ul>
	<ul><li> A la fin de chaque séance, je m’engage à venir rechercher mon enfant aux horaires prévus pour la fin des activités et en cas de retard exceptionnel, à avertir immédiatement un responsable de l’association – tél. 02 47 30 04 78 ou 0642769044</li></ul>
	<ul><li> Autorise les dirigeants, entraîneurs et parents des nageurs, à véhiculer mon enfant lors des compétitions et des déplacements en cas d’incapacité de ma part à effectuer ce transport moi-même,</li></ul>
	<ul><li> En cas de blessures accidentelles et en cas d’urgence, j’autorise un médecin à pratiquer tous les examens médicaux nécessaires à l’établissement d’un diagnostic,</li></ul>
	<ul><li> J’autorise en cas d’extrême urgence, toute intervention médicale ou chirurgicale y compris avec la phase d’anesthésie réanimation que nécessiterait l’état de santé de mon enfant.</li></ul>
	<br>
	<strong><br>Signature du licencié (des parents ou du représentant légal si le licencié est mineur)
	<br>
   <label for="date_parent">Le : </label> 
     <input disabled type="text" name="date_parent" id="date_parent"  title="Date de la signature (jj/mm/aaaa)" value="<?php  echo $maintenant; ?>" required />
	<label for="ville_parent"> A : </label>
	<input  id="ville_parent" type="text" size="30" name="ville_parent" placeholder="" title="Ville" required />
   </strong>
   </td>
   </tr>
	</table>
</fieldset>


 <fieldset hidden id="section9">
	<legend>Autorisation pour rentrer seul </legend>	
	<table>
	<tr><td>
		<input type='hidden' name='myCanvas4' value='null'/>
		<canvas id="myCanvas4" class="signature" width="200" height="200" style="margin-right: 50px; border: solid 1pt black ;" >Votre navigateur ne supporte pas les canvas (pour réaliser une signature).</canvas>
		<input id="effacer4" type="button" onclick="arraysign[3].effacer(); arraysign[3].check=false;" value="Effacer la signature"/>
    </td>
	<td>
   <label for="nom_parent_seul">Nom : </label> 
     <input type="text" name="nom_parent_seul" id="nom_parent_seul" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Dupont" required />
   <label for="prenom_parent_seul">Prénom :</label> 
     <input type="text" name="prenom_parent_seul" id="prenom_parent_seul" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Eric" required />
	père / mère / tuteur légal autorise mon enfant
	   <label for="nom_enfant_seul">Nom : </label> 
     <input type="text" name="nom_enfant_seul" id="nom_enfant_seul" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Dupont" required />
   <label for="prenom_enfant_seul">Prénom :</label> 
     <input type="text" name="prenom_enfant_seul" id="prenom_enfant_seul" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Eric" required />
	<p> Licencié (e) à l'ACA à venir aux entraînements et à rentrer seul (e) par ses propres moyens. J’ai pris acte que la responsabilité du club commence à partir du moment où le nageur se présente aux responsables et s’arrête à la fin de l’entraînement.</p>
	<br>
	<strong><br>Signature du licencié (des parents ou du représentant légal si le licencié est mineur)
	<br>
	
   <label for="date_parent_seul">Le : </label> 
     <input disabled type="text" name="date_parent_seul" id="date_parent_seul"  title="Date de la signature (jj/mm/aaaa)" value="<?php  echo $maintenant; ?>" required />
	<label for="ville_parent_seul"> A : </label>
	<input  id="ville_parent_seul" type="text" size="30" name="ville_parent_seul" placeholder="" title="Ville" required />
     </strong>
	 </td>
	 </tr>
	 </table> 
</fieldset>


 <fieldset hidden id="section10">
	<legend>Activités du Club : pouvez-vous nous aider ? </legend>	
	<p> Chers parents, chers licenciés, vous pouvez nous aider au fonctionnement du club. Notre association sportive ne peut fonctionner que grâce aux bénévoles. La participation de chacun et chacune d’entre nous, 
	ne serait-ce qu’une seule fois dans la saison, nous permettra d’offrir à toutes et à tous la qualité d’encadrement que nous attendons. Cette zone vous appartient merci de noter vos idées, remarques afin de faire 
	que <strong><u>VOTRE</u></strong> club progresse :</p>
	<textarea cols="80" rows="6" maxlength ="500" id="commentaire" name="commentaire" > </textarea>
</fieldset>


 <fieldset hidden id="section11">
 	<legend>Tarification Saison <?php  echo   $annee."-".$anneesuivante; ?> :</legend>
	<table>
	<tr><td>Adhésion Pass'club + licence </td><th>160€</th><th></th></tr>
	<tr><td>Adhésion au club + licence </td><th>220€</th><th>(10% de remise pour les familles par adhérent à partir de 3 membres)</th></tr>
	<tr><td>Adhésion aquabike + licence </td><th>350€</th><td><strong>(280€ si l'adhérent est aussi inscrit dans une activité au club)</strong></td></tr>
	</table>
<p id="p_montant">
Calcul de votre cotisation : <span id="montant"> Groupe non attribué </span> €
</p>
<p>
Choisissez votre moyen de paiement :
<label><input type="radio" name="type_paiement" id="type_cheque" checked value="cheques" />Chèque(s)</label>
<label><input type="radio" name="type_paiement" id="type_espece" value="especes" >Espèces</label> 
</p>
<div id="div_cheques">
<input hidden id="montant_cotisation" type="text" name="montant_cotisation"/>
	
 		<label>Paiement en <output for="nb-media" id="output-nb">1</output> fois</label>
			<input type="range" min="1" max="4" value="1" name="nb" oninput="changeEvent()" id="nb-media" />
			
		
		<table>
		<thead><tr><th></th><th>Banque</th><th>N° de chèque</th><th>Montant (en euros)</th></tr>
		</thead>
		<tbody id="tbody_cheques">
		<tr>
		<td id="cheque">Chèque 1 :</td>
		<td><input type="text" name="banque[]" maxlength="30" title="Nom de la banque de ce chèque" placeholder= "Nom de la banque" required /></td>
		<td><input type="number" name="numero_banque[]" min="1" title="Numéro de chèque" placeholder= "123456789" required /></td>
		<td><input id="montant1" type="number" name="montant_cheque[]" min="40" max="350" title="Montant du chèque" placeholder= "220.00" required /></td>
		</tr>
		</tbody>
		</table>
</div>
</fieldset>


<table id="table_fin_page1">
<tr><td class="table_fin_page">
<input hidden id="precedent" type="button" onclick="" value="Page précédente" size="100" />
</td><td class="table_fin_page"><span id="pourcent">0 % </span></td>
<td class="table_fin_page"> 
<input id="suivant" type="button" value="Page suivante" size="100" />
</td></tr>
<tr><td class="table_fin_page"></td><td class="table_fin_page">
<img id="barre" src="images/barre.jpg" alt="Barre de progression" height="12" >
</td><td class="table_fin_page"></td></tr>
</table>
<div id="valider">
<input hidden id="section12" class="bouton" type="submit" name="soumet" value="Valider"/>
</div>
  </form> 
  </div>
 <script>
 var renouvellement_ok = "0";
 var renouvellement_auto = document.getElementById('renouvellement_auto');
 var type_licence1 = document.getElementById('type_licence1');
 type_licence1.onchange = function(){
 renouvellement_ok="0";
 old_club.hidden=true; 
 label_old_club.hidden=true; 
 iuf.hidden=true; 
 label_iuf.hidden=true; 
 lien_iuf.hidden=true; 
 lien_iuf2.hidden=true; 
 old_club.required=false; 
 iuf.required=false;}
 
 var type_licence2 = document.getElementById('type_licence2');
 type_licence2.onchange = function(){
 renouvellement_ok="0";
 old_club.hidden=false; 
 label_old_club.hidden=false; 
 iuf.hidden=false; 
 label_iuf.hidden=false; 
 lien_iuf.hidden=false; 
 old_club.required=true; 
 iuf.required=true;} 
  
var type_licence3 = document.getElementById('type_licence3');

type_licence3.onchange = function(){
	fonction_renouvellement();
}

var resultat = document.getElementById('resultat_groupe');
var affichage_resultat = document.getElementById('affichage_resultat_groupe');

function displayLines(line2, line3, line4, result){
	document.getElementById('ligne2').hidden=line2;
	document.getElementById('ligne3').hidden=line3;
	document.getElementById('ligne4').hidden=line4;
	document.getElementById('div_resultat').hidden=result;
}

var disciplines = {
    22:'type_discipline1',
    10: 'type_discipline2',
	14: 'type_discipline3',
	15: 'type_discipline4',
	21: 'type_seance_enfant1',
	5: 'type_seance_enfant2',
	9: 'type_seance_enfant3',
	12: 'type_seance_adulte1',
	13: 'type_seance_adulte2',
	11: 'type_seance_adulte3',
	6: 'type_ENF1',
	7: 'type_ENF2',
	8: 'type_ENF3',
	20: 'type_ENF4'
}
for (let key in disciplines){
    var radio = document.getElementById(disciplines[key]);
	radio.onchange = function(){ 
		switch (key) {
			case '22':
				var sexe = document.getElementById('sexe').value;
				var test_naissance = naissance.value;
				if ((sexe=="H" || sexe=="F") && test_naissance.length==10){
					var age = test_naissance.slice(6,10);		
					if (sexe=="H"){
						var age=annee_saison-age;
					}else{
						var age=annee_saison-age+1;
					}
					if (age >=18){
						displayLines(true, false, true, true);
					}else{
						displayLines(false, true, true, true);
					}
					return;
				}else{
					displayLines(true, true, true, false);
					key='0';
				}
				break;
			case '10':
			case '14':
			case '15':
				displayLines(true, true, true, false);
				break;
			case '21':
				displayLines(false, true, false, true);
				return;
			case '5':
				displayLines(false, true, true, false);
				break;
			case '9':
				displayLines(false, true, true, false);
				break;
			case '12':
			case '11':
				displayLines(true, false, true, false);
				break;
			case '13':
				displayLines(true, false, true, false);
				break;
			case '6':
			case '7':
			case '8':
				displayLines(false, true, false, false);
				break;
			case '20':
				var sexe = document.getElementById('sexe').value;
				var test_naissance = naissance.value;
				if ((sexe=="H" || sexe=="F") && test_naissance.length==10){
					var age = test_naissance.slice(6,10);		
					if (sexe=="H")
						var age=annee_saison-age;
					else
						var age=annee_saison-age+1;
					
					if (age <= 11)
						key='4';				
					else if (age >= 16)
						key='1';
					else if (age >= 12  && age <= 13 )			
						key='3';
					else
						key='2';
					displayLines(false, true, false, false);
				}else{
					displayLines(true, true, true, false);
					key='0';
				}
				break;
			default:
				break;
		}
		resultat.value=key;
		affichage_du_resultat();
	}
}


function affichage_du_resultat() {
switch (resultat.value) {  
	case '1':
		var groupe ="<?php 	echo fonction(1); ?>";
		break;
	case '2':
		var groupe ="<?php 	echo fonction(2); ?>";
		break;
	case '3':
		var groupe ="<?php 	echo fonction(3); ?>";
		break;
	case '4':
		var groupe ="<?php 	echo fonction(4); ?>";
		break;
	case '5':
		var groupe ="<?php 	echo fonction(5); ?>";
		break;
	case '6':
		var groupe ="<?php 	echo fonction(6); ?>";
		break;
	case '7':
		var groupe ="<?php 	echo fonction(7); ?>";
		break;
	case '8':
		var groupe ="<?php 	echo fonction(8); ?>";
		break;
	case '9':
		var groupe ="<?php 	echo fonction(9); ?>";
		break;
	case '10':
		var groupe ="<?php 	echo fonction(10); ?>";
		break;
	case '11':
		var groupe ="<?php 	echo fonction(11); ?>";
		break;
	case '12':
		var groupe ="<?php 	echo fonction(12); ?>";
		break;
	case '13':
		var groupe ="<?php 	echo fonction(13); ?>";
		break;
	case '14':
		var groupe ="<?php 	echo fonction(14); ?>";
		break
	case '15':
		var groupe ="<?php 	echo fonction(15); ?>";
		break;
	default:
		var groupe ="Pas assez d'informations (vérifiez les informations sexe et date de naissance)";
		return;
}
	affichage_resultat_groupe.value = groupe;
	montant = document.getElementById('montant');
	montant_cotisation =document.getElementById('montant_cotisation');
	if (groupe=='Aquabike'){
		montant.innerText = "350";
		montant_cotisation.value = "350";
	}
	else if (groupe=='Savoir Nager'){
		montant.innerText = "160";
		montant_cotisation.value = "160";
	}
	else{
		montant.innerText = "220";
		montant_cotisation.value = "220";
	}
	reduction=<?php echo reduction_cotisation(); ?>;
	if (reduction >=3){
		montant_reduit = parseInt(montant_cotisation.value)*0.9;
		montant_cotisation.value = montant_reduit.toString();
		montant.innerText = "(-10% ont été appliqués) : "+ montant_cotisation.value;
	}	
	montant1.placeholder=montant_cotisation.value;
}

function fonction_renouvellement(){
	renouvellement_ok = <?php echo distrib_renouvellement();?>;
	if (renouvellement_ok=="-1"){
		alert("Impossible ! Pour renouveler ou corriger un dossier, rendez-vous sur votre espace utilisateur et cliquez sur le lien du dossier que vous voulez renouveler.");
	}
	else{
	    type_de_licence="<?php echo affich('type_licence');?>"
		old_club.value="<?php echo affich('club_origine');?>";
		iuf.value="<?php echo affich('IUF');?>";
		nom.value="<?php echo affich('nom');?>";
		prenom.value="<?php echo affich('prenom');?>";
		nationalite.value="<?php echo affich('nationalite');?>";
		sexe.value="<?php echo affich('sexe');?>";
		naissance_bd="<?php echo affich('date_naissance');?>";
		naissance.value=(naissance_bd).slice(8,10)+"/"+(naissance_bd).slice(5,7)+"/"+(naissance_bd).slice(0,4)
		fonction_naissance();
		numero.value="<?php echo affich('numero_rue');?>";
		rue.value="<?php echo affich('rue');?>";
		cp.value="<?php echo affich('code_postal');?>";
		ville.value="<?php echo affich('ville');?>";
		mail1.value="<?php echo affich('mail');?>";
		mail2.value="<?php echo affich('mail2');?>";
		telephone1.value="<?php echo affich('tel1');?>";
		telephone2.value="<?php echo affich('tel2');?>";
		telephone3.value="<?php echo affich('tel3');?>";
		groupe_db="<?php echo affich('id_categorie');?>";
		var radio = document.getElementById(disciplines[groupe_db]);
		switch (groupe_db) {  
			case '12':
			case '13':
			case '11':
			case '5':
			case '9':
				type_discipline1.checked=true;
				type_discipline1.onchange();
				break;
			case '6':
			case '7':
			case '8':
			case '20':
				type_discipline1.checked=true;
				type_discipline1.onchange();
				type_seance_enfant1.checked=true;
				type_seance_enfant1.onchange();
				break;
			case '1':
			case '2':
			case '3':
			case '4':
				type_discipline1.checked=true;
				type_discipline1.onchange();
				type_seance_enfant1.checked=true;
				type_seance_enfant1.onchange();
				type_ENF4.checked=true;
				type_ENF4.onchange();
				break;
			default:
				break;
		}
		if (groupe_db>4){ 
		radio.checked=true;
		radio.onchange();}
		
	ville_adhesion.value="<?php echo affich('ville_signature');?>";
	nom_parent.value="<?php echo affich('nom_parent');?>";
	prenom_parent.value="<?php echo affich('prenom_parent');?>";
	commentaire.value="<?php echo affich('commentaire');?>";

	nom_enfant.value = nom.value;
	nom_enfant_seul.value = nom_enfant.value;
	nom_adhesion.value = nom.value;
	prenom_enfant.value = prenom.value;
	prenom_enfant_seul.value = prenom.value;
	prenom_adhesion.value = prenom.value;
	nom_parent_seul.value = nom_parent.value;
	prenom_parent_seul.value = prenom_parent.value;
	ville_parent.value = ville_adhesion.value;
	ville_parent_seul.value = ville_adhesion.value;
	ville_lil.value = ville_adhesion.value;

	var var_choix_lil = "<?php echo affich('accepte_lil');?>";
	if (var_choix_lil == "Refus"){
	accepte_lil.checked=false;
	refuse_lil.checked=true;
	}else{
	accepte_lil.checked=true;
	refuse_lil.checked=false;
	}

	var nombre_cheques = "<?php echo affich('nbcheques');?>";
	var tableau_cheques = <?php if (isset($_GET["id_dossier"])) echo json_encode(recup_cheques()); else echo '""'; ?>;

	if (nombre_cheques == "0"){
		type_cheque.checked=false;
		type_espece.checked=true;
		changePaiementType(false)
	}else{
		type_cheque.checked=true;
		type_espece.checked=false;
		document.getElementById("nb-media").value=parseInt(nombre_cheques);
		changeEvent();
		changePaiementType(true);
		var element = document.getElementsByName('banque[]'); 
		for (i=0;i<element.length;i++)
		element[i].value=tableau_cheques[i][0];
		var element = document.getElementsByName('numero_banque[]'); 
		for (i=0;i<element.length;i++)
		element[i].value=tableau_cheques[i][1];
		var element = document.getElementsByName('montant_cheque[]'); 
		for (i=0;i<element.length;i++)
		element[i].value=tableau_cheques[i][2];
	}

	if (erreur.value==""){
		old_club.hidden=true; 
	label_old_club.hidden=true; 
	iuf.hidden=false; 
	label_iuf.hidden=false; 
	lien_iuf.hidden=false;  
	lien_iuf2.hidden=false;  
	old_club.required=false; 
	iuf.required=true; 
	alert("Nous vous remercions de renouveler votre inscription.\n Vous allez retrouver toutes vos informations mais merci de :\n - signer à nouveau à tous les endroits qui vous sont demandés,\n - vérifier l''affectation de votre groupe\n - mettre à jour TOUTES les pièces jointes (si elles sont encore valables, merci de les télécharger et de les envoyer de nouveau)\n - communiquer les nouvelles données des chèques si vous payez par chèque(s)");
	}else if (type_de_licence=="Transfert") {	
	type_licence3.checked=false; 
	type_licence2.checked=true; 	
	type_licence2.onchange();
	alert("Nous vous remercions de corriger votre dossier.\n Vous allez retrouver toutes vos informations et pouvoir corriger les erreurs évoquées dans le mail mais merci de :\n - signer à nouveau à tous les endroits qui vous sont demandés,\n - vérifier l''affectation de votre groupe\n - mettre à jour TOUTES les pièces jointes (les télécharger et les envoyer de nouveau)\n - communiquer à nouveau les données des chèques si vous payez par chèque(s)");

	}else if (type_de_licence=="Renouvellement") {
	old_club.hidden=true; 
	label_old_club.hidden=true; 
	iuf.hidden=false; 
	label_iuf.hidden=false; 
	lien_iuf.hidden=false;  
	lien_iuf2.hidden=false;  
	old_club.required=false; 
	iuf.required=true; 
	alert("Nous vous remercions de corriger votre dossier.\n Vous allez retrouver toutes vos informations et pouvoir corriger les erreurs évoquées dans le mail mais merci de :\n - signer à nouveau à tous les endroits qui vous sont demandés,\n - vérifier l''affectation de votre groupe\n - mettre à jour TOUTES les pièces jointes (les télécharger et les envoyer de nouveau)\n - communiquer à nouveau les données des chèques si vous payez par chèque(s)");
	
	}else {	
	type_licence3.checked=false; 
	type_licence1.checked=true; 
	type_licence1.onchange();
	alert("Nous vous remercions de corriger votre dossier.\n Vous allez retrouver toutes vos informations et pouvoir corriger les erreurs évoquées dans le mail mais merci de :\n - signer à nouveau à tous les endroits qui vous sont demandés,\n - vérifier l''affectation de votre groupe\n - mettre à jour TOUTES les pièces jointes (les télécharger et les envoyer de nouveau)\n - communiquer à nouveau les données des chèques si vous payez par chèque(s)");
	}
}
}

var valuesChange = {
    nom: ['nom_adhesion','nom_enfant','nom_enfant_seul'],
    prenom: ['prenom_adhesion','prenom_enfant','prenom_enfant_seul'],
	nom_parent: ['nom_parent_seul'],
	prenom_parent: ['prenom_parent_seul'],
	ville_adhesion: ['ville_lil','ville_parent','ville_parent_seul']
}
function remplissage_auto(){
	for (let key in valuesChange){
		let maininput = document.getElementById(key);
		let array = valuesChange[key];
		for(let i of array){
			let input = document.getElementById(i);
			input.value = maininput.value;
		}
	}
}

var majorite=false;
var naissance = document.getElementById('naissance');
var annee_saison = "<?php echo $anneesuivante;?>";
naissance.onkeyup = function(){
	fonction_naissance();
}

function fonction_naissance(){
	// lecture entrées
	var jour = (naissance.value).slice(0,2);
	var sep = (naissance.value).slice(2,3);
	var mois = (naissance.value).slice(3,5);
	var sep2 =(naissance.value).slice(5,6);
	var annee = (naissance.value).slice(6,10);
	// traitement
	if (jour.length==2) {
		var day = jour.valueOf();
		if (day <1 || day >31)
			var jour = "**" ;
	}

	if (mois.length==2) {
		var month = mois.valueOf();
		if (month <1 || month>12)
			var mois = "**" ;
	}

	if (annee.length==4) {
		var year = annee.valueOf();
		if (year <annee_saison-100 || year>annee_saison-4)
			var annee = "****";
	}

	if (sep.length==1){
		if (sep != "/" && sep != "" )
		sep="/";
	}

	if (sep2.length==1){
		if (sep2 != "/" && sep2 != "" )
			sep2="/";
	}

	naissance.value = jour + sep + mois + sep2 + annee;
	var saisie=naissance.value;
	var etoile = saisie.indexOf("*");
	if (etoile!=-1 && saisie.length==10){
		alert("Date de naissance incorrecte");
		naissance.value = "";
	}
	if (saisie.length==10 && annee.indexOf("*")==-1){
		if (annee_saison-annee >18){                                       
			majorite=true;
		}else{                                 
			majorite=false;
		}
	}
}

class Signature {
   constructor(divsign) {
	   self=this;	
		this.divsign = divsign;
		this.ctx = divsign.getContext("2d");
		this.newX =0;
		this.newY =0;
		this.x =0;
		this.y =0;
		this.lastX=0;
		this.lastY=0;
		this.check = false;
	}


	setLastCoords(e){
        this.x= this.divsign.getBoundingClientRect().x;
        this.y= this.divsign.getBoundingClientRect().y;
		this.lastX = e.clientX - this.x;
        this.lastY = e.clientY - this.y;
    }

    freeForm(e) {
		var dataURL = this.divsign.toDataURL('image/png');
        document.getElementsByName(this.divsign.id)[0].value = dataURL;                     
        if (e.buttons !== 1) return; // left button is not pushed yet
        this.penTool(e);
    }

    penTool(e) {
		this.x= this.divsign.getBoundingClientRect().x;
        this.y= this.divsign.getBoundingClientRect().y;
		this.newX = e.clientX - this.x;
        this.newY = e.clientY - this.y;
        this.ctx.beginPath();
        this.ctx.lineWidth = 1;
        this.ctx.moveTo(this.lastX, this.lastY);
        this.ctx.lineTo(this.newX, this.newY);
        this.ctx.strokeStyle = 'black';
        this.ctx.stroke();
        this.ctx.closePath();

        this.lastX = this.newX;
        this.lastY = this.newY;
    }
	
	effacer(){
		this.ctx.clearRect(0, 0, this.divsign.width, this.divsign.height);
		var dataURL = this.divsign.toDataURL('image/png');
		document.getElementsByName(this.divsign.id)[0].value = dataURL;     
	}
}


function prg1(e,id){
	let sign = arraysign[id-1];
	sign.penTool(e);
	sign.check=true;}

function prg2(e,id){
	let sign = arraysign[id-1];
	sign.setLastCoords(e);
}

function prg3(e,id){
	let sign = arraysign[id-1];
	sign.freeForm(e);
}


var arraysign = [];
let signatures = document.getElementsByClassName("signature");
for (const sign of signatures) {
	let d = document.getElementById(sign.id);
	signature = new Signature(d);
	arraysign.push(signature);
	
	d.addEventListener("click", function(e){ id=e.currentTarget.id.split('myCanvas')[1]; prg1(e,id);} );
	d.addEventListener("mousedown", function(e){ id=e.currentTarget.id.split('myCanvas')[1]; prg2(e,id)} );
	d.addEventListener("mousemove", function(e){ id=e.currentTarget.id.split('myCanvas')[1]; prg3(e,id)} );

	//d.addEventListener("click", function(e){ id=e.toElement.id.split('myCanvas')[1]; prg1(e,id);} );
	//d.addEventListener("mousedown", function(e){ id=e.toElement.id.split('myCanvas')[1]; prg2(e,id)} );
	//d.addEventListener("mousemove", function(e){ id=e.toElement.id.split('myCanvas')[1]; prg3(e,id)} );

	}
   
  function changeEvent(){
		nbRange = document.getElementById('nb-media');
		document.getElementById('output-nb').value =  nbRange.value;
		var container = document.getElementById('tbody_cheques');

		
		while (container.hasChildNodes()) {
			container.removeChild(container.lastChild);
		}
		
		var inputConfig = {
			banque: ['text','banque[]','30','Nom de la banque de ce chèque','Nom de la banque'],
			numero: ['number','numero_banque[]','1','Numéro de chèque','123456789'],
			montant: ['number','montant_cheque[]','40','Montant du chèque',montant_cotisation.value,'350']
		}
		
		for (i=0;i<nbRange.value;i++){				
			var container = document.getElementById('tbody_cheques');
			var tr = document.createElement("tr");		
			container.appendChild(tr);		
			var container = container.lastChild;
			
			var td = document.createElement("td");	
			td.innerText="Chèque " + (i+1)+ ":";
			td.id = "cheque";
			container.appendChild(td);						
			
			for (let key in inputConfig){
				var td = document.createElement("td");			
				container.appendChild(td);	
				let array = inputConfig[key];
				var input = document.createElement("input");
				input.type = array[0];
				input.name = array[1];
				if(key == 'banque'){
					input.maxlength = array[2];
				}else{
					input.min = array[2];
					if(key == 'montant'){
						input.max = array[5];
					}
				}
				input.title=array[3]; 
				input.placeholder= array[4];
				input.required = true;
				td.appendChild(input);
			}
		}
	}
	
	
function changePaiementType(cheque){
	let banque = document.getElementsByName('banque[]'); 
	let numero_banque = document.getElementsByName('numero_banque[]'); 
	let montant_cheque = document.getElementsByName('montant_cheque[]'); 
	if(cheque)
		document.getElementById('div_cheques').hidden=false;
	else
		document.getElementById('div_cheques').hidden=true;
	for (i=0;i<banque.length;i++){	
		banque[i].required=cheque;
		numero_banque[i].required=cheque;
		montant_cheque[i].required=cheque;
	}
}

var type_espece = document.getElementById('type_espece');
type_espece.onchange = function(){ changePaiementType(false); }

var type_cheque = document.getElementById('type_cheque');
type_cheque.onchange = function(){ changePaiementType(true); }


var barre = document.getElementById('barre');
barre.width="0";
var suivant = document.getElementById('suivant');
var precedent = document.getElementById('precedent');
var pourcent = document.getElementById('pourcent');
var section = 1;
suivant.onclick = function(){
	if (renouvellement_ok!="-1"){
		sectionplus1=section+1;
		var sect0 = document.getElementById('section'+section.toString());
		if (section==7 && majorite==true){
			nom_parent.required=false;
			prenom_parent.required=false;
			nom_parent_seul.required=false;
			prenom_parent_seul.required=false;
			section=9;
			sectionplus1+=2;
			var sect = document.getElementById('section'+sectionplus1.toString());
		}else{
			var sect = document.getElementById('section'+sectionplus1.toString());
		}
		sect.hidden=false;
		sect0.hidden=true;
		precedent.hidden=false;
		section=section+1;
		barre.width=section*222/11;
		pourcent.innerHTML = parseInt(section*100/11) +" %";
		if (section == 11) {
			sectionplus1=sectionplus1+1
			var sect = document.getElementById('section'+sectionplus1.toString());
			sect.hidden=false;
			suivant.hidden=true;
		}	
	}
	else{
	alert("Impossible de continuer !");}
}

precedent.onclick = function(){
	sectionmoins1=section-1
	var sect0 = document.getElementById('section'+section.toString());
	if (section==10 && majorite==true){
		section=8;
		sectionmoins1-=2;
		var sect = document.getElementById('section'+sectionmoins1.toString());
	}else{
		var sect = document.getElementById('section'+sectionmoins1.toString());
	}
	sect.hidden=false;
	sect0.hidden=true;
	suivant.hidden=false;
	section=section-1;
	barre.width=section*222/11;
	pourcent.innerHTML = parseInt(section*100/11) +" %"
	document.getElementById('section12').hidden=true;

	if (section > 1){
		precedent.hidden=false;
	}else{
		precedent.hidden=true;
		barre.width="0";
		pourcent.innerHTML = "0 %"
	}
}

var section12 = document.getElementById('section12');
section12.onmousedown=function(){

if (document.getElementById('section1').hidden==true){
	alert("Vous allez voir le récapitulatif de votre dossier. Merci de corriger les erreurs s'il y en a. DANS TOUS LES CAS, IL  FAUDRA CLIQUER SUR VALIDER UNE NOUVELLE FOIS POUR ENVOYER VOTRE DOSSIER.");
}

for (let pas = 1; pas < 12; pas++){
var sect = document.getElementById('section'+pas.toString());
sect.hidden=false;}
if (majorite==true){
document.getElementById('section8').hidden=true;
document.getElementById('section9').hidden=true;
}

if (affichage_resultat_groupe.value =="0" || affichage_resultat_groupe.value ==0 || affichage_resultat_groupe.value.indexOf("Pas assez d'informations (vérifiez les informations sexe et date de naissance)")!=-1){
	alert("Vous avez mal renseigné le questionnaire pour la détermination de votre groupe");
}
var montant = parseInt(document.getElementById('montant').innerText);
var addition=document.getElementsByName('montant_cheque[]');
var somme=0;
var n = addition.length;

for (let nbcheque = 0; nbcheque < n; nbcheque++){
	somme+=parseInt(addition[nbcheque].value);
}
if (somme != montant && document.getElementById('type_espece').checked ==false){
	msg="Le total de vos chèques ( ".concat(somme); 
	msg=msg.concat(" € ) n'est pas égal au montant de votre cotisation ( ");
	msg=msg.concat(montant);
	msg=msg.concat(" € )");
	alert(msg);
}

if((majorite == false && !(arraysign[0].check && arraysign[1].check && arraysign[2].check && arraysign[3].check))||((majorite == true && !(arraysign[0].check && arraysign[1].check)))){
	alert("Il manque au moins une signature !");
}

}
if (renouvellement_auto.value != ""){
	type_licence3.checked=true;
	fonction_renouvellement();
}
</script>
 </div> 
</div>
 		
 <?php require('fragments/bas.php'); ?>
 </body>
 </html>