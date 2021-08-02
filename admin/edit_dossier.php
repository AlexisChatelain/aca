<?php 
	require('scripts/saison.php');
	if ($login->isAdmin())
	$admin=true;
	else
	$admin=false;
		
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
	$requete="SELECT 
	type_licence, nationalite, sexe, date_naissance, id_categorie, accepte_lil,
	nbcheques,
	club_origine, IUF, nom, prenom,
	numero_rue, rue, code_postal, ville, mail,
	mail2, tel1, tel2, tel3, nom_parent, prenom_parent 
	FROM dossier WHERE id_dossier={$id_dossier} ";
	
	if (!$admin)
	$requete=$requete."and id_utilisateur={$id}";
	
	$getUser = $db->query($requete);
	
	if ($getUser->num_rows == 1) {
		$result_row = $getUser->fetch_object();
		$old_club = $result_row->club_origine;
		$IUF = $result_row->IUF;
		$nom = $result_row->nom;
		$prenom = $result_row->prenom;
		$numero_rue = $result_row->numero_rue;
		$rue = $result_row->rue;
		$code_postal = $result_row->code_postal;
		$ville = $result_row->ville;
		$mail = $result_row->mail;
		$mail2 = $result_row->mail2;
		$tel1 = $result_row->tel1;
		$tel2 = $result_row->tel2;
		$tel3 = $result_row->tel3;
		$nom_parent = $result_row->nom_parent;
		$prenom_parent = $result_row->prenom_parent;
		
		$type_licence = $result_row->type_licence;
		$nationalite = $result_row->nationalite;
		$sexe = $result_row->sexe;
		$date_naissance = $result_row->date_naissance;
		$id_categorie = $result_row->id_categorie;
		$accepte_lil = $result_row->accepte_lil;	

		$nbcheques = $result_row->nbcheques;		
	} else {
		header('Location: /');
	}
	$cheques=array();
	$requete="SELECT id_cheque,banque,numero,montant FROM cheques WHERE id_dossier={$id_dossier} ";
	$result= $db->query($requete);	
	while($row=$result->fetch_assoc())
	array_push ($cheques,array($row["id_cheque"],$row["banque"],$row["numero"],$row["montant"]));
	
?>


<h1>Edition du dossier <?php if ($login->isAdmin())echo $id_dossier;?> de <?php echo $prenom." ".$nom;?> :</h1>


<form action="/aca/scripts/edit_dossier.php" method="post">

   <input type="hidden" name="id" value="<?php echo $id;?>">	
   <input type="hidden" name="id_dossier" value="<?php echo $id_dossier;?>">
   <input type="hidden" name="maj" value="<?php if (isset($_GET["maj"])) echo $_GET["maj"];?>">
	
   <label>Nom :   
	<input type="text" name="nom" value="<?php echo $nom;?>" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Dupont" required />
   </label> 
   
   <label>Prénom :   
     <input type="text" name="prenom" value="<?php echo $prenom;?>" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" placeholder= "Eric" required />
   </label>  
   
   <div <?php if (!$admin) echo "hidden"; ?> >
   
   	<label>Type de licence : 
     <select name="type_licence" class="selected" >
	  <option value="Nouvellle licence" <?php if ($type_licence=="Nouvellle licence") echo "selected"; ?> >Nouvellle licence</option>
	  <option value="Transfert" <?php if ($type_licence=="Transfert") echo "selected"; ?>>Transfert</option>
	  <option value="Renouvellement" <?php if ($type_licence=="Renouvellement") echo "selected"; ?> >Renouvellement</option>
	  </select>
	  </label>
	  
	 <label>Nationalité :
	<datalist id="datalist_nationalites">
		<option value="Française"></option>
	</datalist>	
	 <input type="text" name="nationalite" maxlength="30" title="Nationalité" list="datalist_nationalites" placeholder="Saisir ou choisir..."  value="<?php echo $nationalite; ?>" required />
    </label> 
	
	<label>Sexe :
	 <select name="sexe" class="selected">
	  <option value="H" <?php if ($sexe=="H") echo "selected"; ?> >H</option>
	  <option value="F" <?php if ($sexe=="F") echo "selected"; ?> >F</option>
     </select>
	 </label>
	 
	<label>Date de naissance : 
	 <input type="text" name="naissance" id="naissance" title="Date de naissance (jj/mm/aaaa)" maxlength="10" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}"  value="<?php echo substr($date_naissance,-2)."/".substr($date_naissance,5,2)."/".substr($date_naissance,0,4); ?>" placeholder= "jj/mm/aaaa" required />
    </label>
	
	<label>Groupe :
	 <select name="id_categorie" class="selected">
	 <?php
	 
		$query = $db->query("SELECT * FROM categorie");
		while($row=$query->fetch_assoc()){
		if ($id_categorie==$row["id_categorie"])
		$selected="selected";
		else
		$selected="";
		echo "<option value=".$row["id_categorie"]." ".$selected." >".$row["nom"]."</option>";	
		}
	?>
     </select>
	 </label>
	
	
	</div>	  
	 
   <label>Nom du club précédent (en cas de transfert):
     <input type="text" name="old_club" value="<?php echo $old_club;?>" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre ancien club : Première lettre en majuscule et pas d'espaces à la fin"  <?php if ($old_club!="") echo "required"; ?> />
   </label> 
   
   <label><a href="https://ffn.extranat.fr/webffn/nat_recherche.php" target="_blank">IUF :</a> 
     <input type="text" name="iuf" value="<?php echo $IUF;?>" maxlength="7" pattern="[0-9]{6-7}" title="Votre IUF (Identifiant Unique Fédéral) : numéro de licence FFN à 7 chiffres"  <?php if ($IUF!="") echo "required"; ?>/>
   </label>
   
   <label> N° de voie :
     <input type="number" name="numero" min="0" value="<?php echo $numero_rue;?>" placeholder="3" required />
   </label>
	 
   <label> Rue : 
	<input type="text" name="rue" value="<?php echo $rue;?>" placeholder="Rue du Clos des Gardes" title="Rue" required />  
   </label>
		
   <label> Code postal : 
	<input type="text" name="cp" maxlength="5" value="<?php echo $code_postal;?>"  pattern="[0-9]{5-5}" placeholder="37400" title="Code postal (5 chiffres)" required />
	</label>
	
   <label> Ville : 
	<input type="text" name="ville" placeholder="Amboise" value="<?php echo $ville;?>"  title="Ville" required />
   </label>
   
   <label>E-mail 1 (obligatoire) :
     <input type="email" name="mail1" value="<?php echo $mail;?>"  pattern="^[a-zA-Z0-9-\.]+@[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,6}$" placeholder= "exemple@mail.fr" required />
   </label>
   
   <label>E-mail 2 :
     <input type="email" name="mail2" value="<?php echo $mail2;?>"  pattern="^[a-zA-Z0-9-\.]+@[a-zA-Z0-9-\.]+\.[a-zA-Z]{2,6}$" placeholder= "exemple@mail.fr" />
   </label>	 
   
   <label>Téléphone 1 (obligatoire) :
     <input type="tel" name="telephone1" value="<?php echo $tel1;?>"  maxlength="14" placeholder="0x xx xx xx xx" pattern="0[1-9]([ ]?[0-9]{2}){4}" required />   
   </label>	 
   
   <label>Téléphone 2 :
     <input type="tel" name="telephone2" value="<?php echo $tel2;?>"  maxlength="14" placeholder="0x xx xx xx xx" pattern="0[1-9]([ ]?[0-9]{2}){4}" />   
   </label>	 
   
   <label>Téléphone 3 :
     <input type="tel" name="telephone3" value="<?php echo $tel3;?>" maxlength="14" placeholder="0x xx xx xx xx" pattern="0[1-9]([ ]?[0-9]{2}){4}" />   
   </label>	 
   
   <label>Droit à l'image :
	 <select name="accepte_lil" class="selected">
	  <option value="1" <?php if ($accepte_lil=="1" || $accepte_lil==1) echo "selected";  ?> >Oui</option>
	  <option value="0" <?php if ($accepte_lil=="0" || $accepte_lil==0) echo "selected"; ?> >Non</option>
     </select>
	 </label>
   
   <label>Nom du parent :
     <input type="text" name="nom_parent" value="<?php echo $nom_parent;?>" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre nom : Première lettre en majuscule et pas d'espaces à la fin" <?php if ($nom_parent!="") echo "required"; ?> />
   </label>  
   
   <label>Prénom du parent :
     <input type="text" name="prenom_parent" value="<?php echo $prenom_parent;?>" maxlength="30" pattern="^[A-Z]{1}.*[\S]{1}$" title="Votre prénom : Première lettre en majuscule et pas d'espaces à la fin" <?php if ($prenom_parent!="") echo "required"; ?> />
   </label>	
   
   	<div <?php if (!$admin) echo "hidden"; ?> >
	<?php
	
	if ($nbcheques == "0" || $nbcheques == 0){
	echo "<label>Type de paiement :
		 <input disabled type='text' value='Espèces' />		
		  </label>";
	}else{
	echo "<label>Type de paiement :
		 <input disabled type='text' value='".$nbcheques." Chèques' />		
		  </label>
		  <table style='margin: 0 auto;'>
		  <thead><tr><th></th><th>Banque</th><th>N° de chèque</th><th>Montant (en euros)</th></tr></thead>
		  <tbody>";
	for ($i=0;$i<count($cheques);$i++){
	echo '<tr>
		<td>Chèque n° '.$cheques[$i][0].' :<input type="hidden" name="id_cheque[]" value="'.$cheques[$i][0].'" /></td>
		<td><input type="text" name="banque[]" maxlength="30" title="Nom de la banque de ce chèque" placeholder= "Nom de la banque" value="'.$cheques[$i][1].'" required /></td>
		<td><input type="number" name="numero_banque[]" min="1" title="Numéro de chèque" placeholder= "123456789" value="'.$cheques[$i][2].'" required /></td>
		<td><input type="number" name="montant_cheque[]" min="40" max="350" title="Montant du chèque" placeholder= "220.00" value="'.$cheques[$i][3].'" required /></td>
		</tr>';
	}
	echo "
		</tbody>
		</table>";	
	}

	?>
	</div>
	
	<input class="i_bouton" type="submit" value="Enregistrer"/>

</form>
<script>
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
}
</script>