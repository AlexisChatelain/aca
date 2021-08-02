<?php 
	$title = "Commandes";
	$css = "boutique.css";
	require('fragments/head.php'); 
?>

<body>
<div class="content">

<?php 
require('fragments/nav.php');
require('fragments/nav_img.php'); 

if(!$login->isUserLoggedIn()){	
	header('Location: /aca/connexion.php');
	die();
}

if(isset($_GET["id"])){
	$id=$_GET["id"];
	if ($_GET["id"]!=$_SESSION["id"]){
		header('Location: /aca/connexion.php');
		die();
	}
}else if(isset($_POST["id"])){
	if ($_POST["id"]!=$_SESSION["id"]){
		header('Location: /aca/connexion.php');
		die();
	}
}else{
	header('Location: /aca/connexion.php');
die();
}
$regles_commande=
"<p class='regles'>Règles concernant les commandes :<br>
- La commande en gros est effectuée par le club par rapport à celles qu'il reçoit de ses adhérents<br>
- Le délai entre la validation de votre commande et le passage de celle du club auprès de son fournisseur TYR peut donc être long<br>
- Toute commande est livrée au bureau du club à la piscine. Vous serez ultérieuresement informé de la date de livraison<br>
- Votre commande sera à régler quand elle sera arrivée au club contre récupération de cette dernière<br>
</p>";
$regles_commande_alt=
"Règles concernant les commandes :
- La commande en gros est effectuée par le club par rapport à celles qu'il reçoit de ses adhérents
- Le délai entre la validation de votre commande et le passage de celle du club auprès de son fournisseur TYR peut donc être long
- Toute commande est livrée au bureau du club à la piscine. Vous serez ultérieuresement informé de la date de livraison
- Votre commande sera à régler quand elle sera arrivée au club contre récupération de cette dernière
";

function affichage($db,$id, $bool_confirmation){
	$query = "SELECT reçue, id_commande, confirmation,	commande.id_boutique, commande.taille AS commande_taille, id_kit, id_utilisateur, titre, prix, boutique.taille  AS boutique_taille FROM commande, boutique WHERE commande.id_boutique=boutique.id_boutique and id_utilisateur={$id} and confirmation={$bool_confirmation} ORDER BY id_kit, id_commande, id_boutique;";
	$result = $db->query($query);
	$nb_produits=0;
	$total=0;
	if ($bool_confirmation==0){
		echo "<form action='{$_SERVER["PHP_SELF"]}' method='post' >";
		echo "<input type='hidden' name='id' value='{$id}' />";
		echo "<input type='hidden' id='valid_definitif' name='valid' value='0' />";
	}
	echo "<table class='table_commande' >";	
	$liste=array();
	$liste_produits=array();
	$old_titre_commande="";
	$old_titre_kit="";
	$old_kit="";
	$liste_ou="";
	$old_id_commande=0;
	$ok=0;        
	while ($row = $result->fetch_array()){	
		if ($row['reçue']==1)
			$reçue='Reçu : <br> oui';
		else
			$reçue='Reçu : <br> non';		
		$ok=1;		
		$id_commande=strval($row['id_commande']);
		$titre_commande=$row['titre'];
		$commande_taille=strval($row['commande_taille']);
		$id_boutique=$row["id_boutique"];
		$id_kit=$row["id_kit"];		
		if ($id_kit == null && $old_titre_kit!="Produits hors kit"){
			echo "<tr><th>Produits hors kit</th><th></th><th></th><th></th><th></th>";
			/*if (!$bool_confirmation)
				echo "<th></th>";*/
			echo "</tr>";
			$old_titre_kit="Produits hors kit";
		}else if ($id_kit != null){
			if ($id_kit!=$old_kit){
				$liste=array();
			}
			array_push($liste,$titre_commande);
			if ($old_kit!=$id_kit  || ($old_kit==$id_kit && count(array_keys($liste,$titre_commande)) > 1) ){
				array_push($liste_produits,array($id_kit, $nb_produits, $id_commande));
				if ($old_kit==$id_kit){				
					$liste=array();
					array_push($liste,$titre_commande);
				}
				$query2 = "SELECT titre_kit, prix_kit FROM kit WHERE id_kit={$id_kit};";			
				$resultat = $db->query($query2);			
				$kit = $resultat->fetch_object();
				echo "<tr><th>".strval($kit->titre_kit)."</th><th></th><th></th><th>Prix : ".strval($kit->prix_kit)."€</th>";
				if (!$bool_confirmation)
					echo "<th><input class='suppr' type='button' value='Supprimer' onclick='delete_kit(suppr{$id_commande})'  /></th>";
				else
					echo "<th>{$reçue}</th>";
				echo "</tr>" ;
				$total+=$kit->prix_kit;
				$old_kit=$id_kit;
			}
			

			
		}
		$old_titre_commande=$titre_commande;
		$old_id_commande=$id_commande;
		$old_id_boutique=$id_boutique;
		$id_boutique_query2=0;
		$compteur=0;
		$boucle=0;
		if ($id_kit != null){
			$requete="SELECT kit.id_boutique AS id_boutique, titre, operateur FROM kit,boutique WHERE kit.id_boutique=boutique.id_boutique and (operateur='ou' or operateur=' ') and titre_kit=(SELECT titre_kit FROM kit WHERE id_kit={$id_kit})";
			$resultat = $db->query($requete);
			
			while ($row2 = $resultat->fetch_array()){
				$compteur  =count($row2);
				$operateur =$row2["operateur"];
				$id_boutique_query2 = $row2["id_boutique"];
				$titre =$row2["titre"];
				if ($compteur!=0 ){
					if ($boucle==0){
							$liste_ou="<td>
							<label>Produit: <br>
							<select id='produit{$id_commande}' class='choix_maillot' onchange=\"changement_image(produit{$id_commande})\" name='produit[]'>
							<option class='scripts/get-img-boutique.php?id={$id_boutique_query2}' value='".$titre."' ";
							if ($titre==$titre_commande)
								$liste_ou.="selected";
							$liste_ou.=" >".$titre."</option>";
						}else if ($boucle==$compteur/3){
							$liste_ou.="<option class='scripts/get-img-boutique.php?id={$id_boutique_query2}' value='".$titre."' ";
							if ($titre==$titre_commande)
								$liste_ou.="selected";
							$liste_ou.=" >".$titre."</option>
										</select></label></td>";
						 }else{						
							$liste_ou.="<option class='scripts/get-img-boutique.php?id={$id_boutique_query2}' value='".$titre."' ";
							if ($titre==$titre_commande)
								$liste_ou.="selected";
							$liste_ou.=" >".$titre."</option>";
						}
					$boucle+=1;
				}
			}
		}
			echo "<tr>
			      <td><img id='img{$id_commande}' alt=\"Produit {$id_boutique}\" width=250  src=\"scripts/get-img-boutique.php?id={$id_boutique}\" ></td>";
			
		if ($boucle==0 || $id_boutique_query2!=$id_boutique+$boucle-1){
			echo "<td>{$row["titre"]}</td>";	
		}else if  ($boucle!=0 && $id_boutique_query2==$id_boutique+$boucle-1){
			echo $liste_ou;
			$liste_ou="";
		}
		if ($row['boutique_taille'] == NULL){
			echo "	
			<td><label>Taille : <br>
			  <select id='id{$id_commande}' name='taille[]' onchange='' >
			  <option value='5 ans' "; if ($commande_taille=='5 ans') echo "selected"; echo ">5 ans</option>
			  <option value='6 ans' "; if ($commande_taille=='6 ans') echo "selected"; echo ">6 ans</option>
			  <option value='7 ans' "; if ($commande_taille=='7 ans') echo "selected"; echo ">7 ans</option>
			  <option value='8 ans' "; if ($commande_taille=='8 ans') echo "selected"; echo ">8 ans</option>
			  <option value='9 ans' "; if ($commande_taille=='9 ans') echo "selected"; echo ">9 ans</option>
			  <option value='10 ans' "; if ($commande_taille=='10 ans') echo "selected"; echo ">10 ans</option>
			  <option value='12 ans' "; if ($commande_taille=='12 ans') echo "selected"; echo ">12 ans</option>
			  <option value='14 ans' "; if ($commande_taille=='14 ans') echo "selected"; echo ">14 ans</option>
			  <option value='16 ans' "; if ($commande_taille=='16 ans') echo "selected"; echo ">16 ans</option>
			  <option value='XS' "; if ($commande_taille=='XS') echo "selected"; echo ">XS</option>
			  <option value='S' "; if ($commande_taille=='S') echo "selected"; echo ">S</option>
			  <option value='M' "; if ($commande_taille=='M') echo "selected"; echo ">M</option>
			  <option value='L' "; if ($commande_taille=='L') echo "selected"; echo ">L</option>
			  <option value='XL' "; if ($commande_taille=='XL') echo "selected"; echo ">XL</option>
			  <option value='XXL' "; if ($commande_taille=='XXL') echo "selected"; echo ">XXL</option>
			 </select>  
			 </label></td>
			 ";	
		}else if ($row['boutique_taille'] == "pointure"){
			echo "	
			<td><label>Pointure : <br>
			  <select id=id{$id_commande} name='taille[]' onchange='' >
			  <option value=30 "; if ($commande_taille=='30') echo "selected"; echo ">30</option>
			  <option value=32 "; if ($commande_taille=='32') echo "selected"; echo ">32</option>
			  <option value=34 "; if ($commande_taille=='34') echo "selected"; echo ">34</option>
			  <option value=36 "; if ($commande_taille=='36') echo "selected"; echo ">36</option>
			  <option value=38 "; if ($commande_taille=='38') echo "selected"; echo ">38</option>
			  <option value=40 "; if ($commande_taille=='40') echo "selected"; echo ">40</option>
			  <option value=42 "; if ($commande_taille=='42') echo "selected"; echo ">42</option>
			  <option value=44 "; if ($commande_taille=='44') echo "selected"; echo ">44</option>
			  <option value=46 "; if ($commande_taille=='46') echo "selected"; echo ">46</option>
			  <option value=48 "; if ($commande_taille=='48') echo "selected"; echo ">48</option>
			  <option value=50 "; if ($commande_taille=='50') echo "selected"; echo ">50</option>
			  <option value=52 "; if ($commande_taille=='52') echo "selected"; echo ">52</option>
			  <option value=54 "; if ($commande_taille=='54') echo "selected"; echo ">54</option>
			 </select>  
			 </label></td>
			 ";
		}else if ($row['boutique_taille'] == "couleur"){
			echo "	
			<td><label>Couleur : <br>
			  <select id=id{$id_commande} class='taille' onchange=\"changement_image(id{$id_commande})\" name='taille[]'>
			  <option class='scripts/get-img-boutique.php?id={$id_boutique}' value='bleu' "; if ($commande_taille=='bleu') echo "selected"; echo ">Bleu</option>
			  <option class='scripts/get-img-boutique.php?id={$id_boutique}&nb=2' value='rose' "; if ($commande_taille=='rose') echo "selected"; echo ">Rose</option>
			 </select>  
			 </label></td>
			 ";
		 }else{
			echo "<td></td>";
		}
		if ($id_kit==null){
			echo "<td>Prix: {$row["prix"]}€</td>";
			if (!$bool_confirmation)
				echo "<td><input class='suppr' type='button' value='Supprimer' onclick='delete_produit({$id_commande})' /></td>";
			else
				echo "<td>{$reçue}</td>";
			$total+=$row["prix"];
		}else
			echo "<td></td><td></td>";
	$nb_produits+=1;
	echo "</tr>";
	}
	if (!$ok){
		if ($bool_confirmation){
			echo "<tr><td>Vous n'avez encore passé aucune commande</td></tr></table><br>";
		}else{
			echo "<tr><td>Votre panier est vide</td></tr></table><br>";
		}
	}else if(!$bool_confirmation){	
		if ($total!=0)
			echo "<tr><th>TOTAL ({$nb_produits} articles)</th><th></th><th></th><th>{$total}€</th><th></th></tr>";
		echo "</table><br>";
		echo "<table>
		<tr><td><input id='submit'  class='submit' type='submit' value='Enregistrer les modifications' /><br><br><br></td></tr>
		<tr><td><input id='submit2' class='submit' type='submit' value='Valider définitivement la commande' /><br><br><br></td></tr>
		</table>
		</form>";
	}else
		echo "</table><br>";
	
	foreach ($liste_produits as $key => $value){		
		if ($key==count($liste_produits)-1){
		$liste_produits[$key][1]=$nb_produits-$liste_produits[$key][1];
		}else{
		$key_plus1=$key+1;
		$liste_produits[$key][1]=$liste_produits[$key_plus1][1]-$liste_produits[$key][1];
		}
		echo "<input type='hidden' id='suppr{$liste_produits[$key][2]}' class='{$liste_produits[$key][1]}' value='{$liste_produits[$key][0]}' />";
	}
}

require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
if($_SERVER['REQUEST_METHOD'] =="POST"){
	foreach ($_POST as $key => $value){
		if (strstr($key, "produit")){
			$id_commande = preg_replace('`[^0-9]`', '', $key);
			$query = "UPDATE commande
					SET id_boutique = (SELECT id_boutique FROM boutique WHERE titre='{$_POST[$key][0]}') 
					WHERE id_commande={$id_commande} and id_utilisateur={$_SESSION["id"]} and confirmation=0";
			$db->query($query);
		}else if (strstr($key, "taille")){
			$id_commande = preg_replace('`[^0-9]`', '', $key);
			$query = "UPDATE commande
					SET taille = '{$_POST[$key][0]}'
					WHERE id_commande={$id_commande} and id_utilisateur={$_SESSION["id"]} and confirmation=0";		
			$db->query($query);
		}else{
		}
	}
	if (isset($_POST["valid"])){
		if ($_POST["valid"]==1){	
			$query = "UPDATE commande
					SET confirmation = 1
					WHERE id_utilisateur={$_SESSION["id"]} and confirmation=0";		
			$db->query($query);
			require('config/mail.php');
			$adress = array($_SESSION['email'], $_SESSION['prenom'], $_SESSION['nom']);
			$subject = 'Votre commande a été transmise au club';
			$body = '<html>
			<head>
			<title>Votre commande a été transmise au club</title>	   
			<meta charset="UTF-8" />
			</head>
			<body>	  
			<img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
			<p>Bonjour '.$_SESSION['prenom'].',<br>
			Ceci est un message automatique envoyé par votre club de natation.<br>
			Merci d\'avoir passé commande auprès de votre club.<br>
			Pour rappel, '.
			$regles_commande.'<br>
			A bientôt dans l\'eau, <br>
			Merci de ne pas répondre à ce mail :)<br>
			</p>
			</body>
			</html>';
			$alt='(logo du club) 
			Bonjour '.$_SESSION['prenom'].',
			Ceci est un message texte automatique envoyé par votre club de natation.
			Merci d\'avoir passé commande auprès de votre club.
			Pour rappel, '.
			$regles_commande_alt.			
			'A bientôt dans l\'eau,
			Merci de ne pas répondre à ce mail :)';
			sendMail($construction_mail, $adress, $subject, $body, $alt);			
			
			$adress2 = array($mail_from, "Club", "Natation");
			$subject = 'Nouvelle commande de '.$_SESSION["user"];
			$body = '<html>
			  <head>
			   <title>Nouvelle commande de '.$_SESSION["user"].'</title>	   
				<meta charset="UTF-8" />
			  </head>
			  <body>	  
			   <img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
			   <p>Bonjour,<br>
				Ceci est un message automatique envoyé par le ste web et s\'adresse à ses administrateurs.<br>
				Une nouvelle commande a été validée par l\'adhérent '.$_SESSION["user"].'<br>
				Pensez à bientôt passer une commande en gros auprès du fournisseur TYR<br>
				Merci de ne pas répondre à ce mail :)
				</p>
			  </body>
			 </html>';
			$alt='(logo du club) Bonjour,
			Ceci est un message texte automatique envoyé par le ste web et s\'adresse à ses administrateurs.
			Une nouvelle commande a été validée par l\'adhérent '.$_SESSION["user"].'
			Pensez à bientôt passer une commande en gros auprès du fournisseur TYR.
			Merci de ne pas répondre à ce mail :)<br>';
			sendMail($construction_mail, $adress2, $subject, $body, $alt);			
			}
	}
	header("Location: {$_SERVER["PHP_SELF"]}?id={$_SESSION["id"]}");
}elseif (isset($_GET["id_boutique"])){
	$id_boutique=$_GET["id_boutique"];
	$taille = (isset($_GET["taille"])) ? $_GET["taille"] : 'null';	
	if ($taille!='null')
		$taille="'{$taille}'";
	$query = "INSERT INTO commande(reçue, confirmation, id_boutique, taille, id_kit, id_utilisateur) 
	VALUES(false,false, {$id_boutique}, {$taille},null,{$id})";
	$result = $db->query($query);
	/*if(!$result)
		trigger_error($db->error);*/
	header("Location: {$_SERVER["PHP_SELF"]}?id={$id}");
	
}else if (isset($_GET["id_kit"])){
	$id_kit=$_GET["id_kit"];
	$old_operateur="";
	$query = "SELECT kit.id_boutique AS id_boutique, kit.id_kit AS id_kit, boutique.taille AS taille, operateur FROM kit,boutique WHERE titre_kit=(SELECT titre_kit FROM kit WHERE id_kit={$id_kit}) and boutique.id_boutique=kit.id_boutique";
	$result = $db->query($query);
	while ($row = $result->fetch_array()){
		if ($old_operateur!='ou'){
			if ($row['taille'] == NULL)
				$taille="'5 ans'";
			else if ($row['taille'] == "pointure")
				$taille="'30'";
			else if ($row['taille'] == "couleur")
				$taille="'bleu'";
			else
				$taille='null';
			$query = $db->query("INSERT INTO commande(reçue, confirmation, id_boutique, taille, id_kit, id_utilisateur) 
			VALUES(false,false, {$row['id_boutique']}, {$taille},{$id_kit},{$id})");
			/*if(!$query)
				trigger_error($db->error);*/
		}
		$old_operateur=$row['operateur'];
	}
	header("Location: {$_SERVER["PHP_SELF"]}?id={$id}");
	
}else{
	
	echo "<a href=\"boutique.php\"> Retour à la boutique </a>";
	echo $regles_commande;	
	echo "<h2 class='regles'>Attention, votre panier sera automatiquement vidé à 1h du matin</h2><br>";
	echo "<h1>Mon panier</h1>";
	affichage($db,$id, 0);
	echo "<h1>Mes commandes déjà validées</h1>";
	affichage($db,$id, 1);
	}
	?>
</div>
	<?php require('fragments/bas.php'); ?>
  <script>
if (typeof submit !== 'undefined') {
	  submit.onmousedown=function(){
		var liste = document.getElementsByTagName("select");
		for (i=0;i<liste.length;i++)
			liste[i].name=liste[i].id + liste[i].name;
	}
}
if (typeof submit2 !== 'undefined') {
	submit2.onmousedown=function(){
		if (valid_definitif.value==1){
			submit.onsubmit();
		}else{
			if(confirm("Etes-vous sûr(e) de vouloir valider définitivement votre commande ? Elle ne pourra plus être modifiée.")){
				alert("D'accord mais appuyez de nouveau sur le bouton \"Valider définivement la commande\" s'il vous plait"); 
				valid_definitif.value=1;	
			}
		}
	}
}
function delete_produit(id){
	if(confirm("Voulez-vous vraiment supprimer définitivement ce produit de votre panier ?")){
		var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && (this.status == 200 || this.status == 0)) {
				location.reload();
			}
		};
		xmlhttp.open("GET", "/aca/scripts/delete_commande.php?id="+id, true);
		xmlhttp.send();
	}
}
function delete_kit(id){
	if(confirm("Voulez-vous vraiment supprimer définitivement ce kit ?")){
		var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && (this.status == 200 || this.status == 0)) {
				location.reload();
			}
		};
		commande=id.id.substring(5);
		commande=parseInt(commande);
		xmlhttp.open("GET", "/aca/scripts/delete_commande.php?kit="+id.value+"&commande="+commande+"&limit="+id.className, true);
		xmlhttp.send();
	}
}
function changement_image(id_select){ 
	var lien = id_select.options[id_select.selectedIndex].className;
	var id_str=id_select.id;
	if (id_str.indexOf('produit')==-1)
		var id=id_str.substring(2);
	else
		var id=id_str.substring(7);
	document.getElementById("img"+id).src=lien;
}
 
liste_select=document.getElementsByTagName("select");
for (i=0;i<liste_select.length;i++)
	liste_select[i].onchange();
 </script>
 </body>
</html>
