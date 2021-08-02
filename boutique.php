<?php 
	$title = "Boutique";
	$css = "boutique.css";

?>
<?php require('fragments/head.php'); ?>

<body>
<div class="content">

<?php 
require('fragments/nav.php');
require('fragments/nav_img.php'); 

if(!$login->isUserLoggedIn()){	
	header('Location: /aca/connexion.php');
	die();
}
require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
if (isset($_GET["id"])){
	$id=$_GET["id"];
	echo "<a href=\"boutique.php\"> Retour à la boutique </a>";	
	echo "<span id='valid_commande'><a href=\"commande.php?id={$_SESSION["id"]}\"> Voir le panier/Confirmer la commande </a></span>";	
	$query = $db->query("SELECT id_boutique, titre, prix, taille, type_image2 FROM boutique WHERE id_boutique=$id");
	while ($row = $query->fetch_array()){
	$titre=$row['titre'];
	echo "<h1>{$titre} </h1><table>";
	echo "<tr>
	<th rowspan='3'><a target='_blank' href='/aca/scripts/get-img-boutique.php?id={$id}'><img alt=\"Produit {$id}\" width=250  src=\"scripts/get-img-boutique.php?id={$id}\" ></a></th>";
	if ($row['type_image2'] != NULL)
	echo "<th rowspan='3'><a target='_blank' href='/aca/scripts/get-img-boutique.php?id={$id}'><img alt=\"2e image produit {$id}\" width=250  src=\"aca/scripts/get-img-boutique.php?id={$id}&nb=2\" ></a></th>";
	echo "<th>Prix : <br>".$row['prix']."€</th></tr>";
	if ($row['taille'] == NULL){
	echo "	
	<tr><th><label>Taille : 
	  <select id='taille' name='taille'>
	  <option value='5 ans' selected>5 ans</option>
	  <option value='6 ans'>6 ans</option>
	  <option value='7 ans'>7 ans</option>
	  <option value='8 ans'>8 ans</option>
	  <option value='9 ans'>9 ans</option>
	  <option value='10 ans'>10 ans</option>
	  <option value='12 ans'>12 ans</option>
	  <option value='14 ans'>14 ans</option>
	  <option value='16 ans'>16 ans</option>
	  <option value='XS'>XS</option>
	  <option value='S'>S</option>
	  <option value='M'>M</option>
	  <option value='L'>L</option>
	  <option value='XL'>XL</option>
	  <option value='XXL'>XXL</option>
     </select>  
	 </label></th></tr>
	 ";	
	}else if ($row['taille'] == "pointure"){
	echo "	
	<tr><th><label>Pointure : 
	  <select id='taille' name='taille'>
	  <option value=30 selected>30</option>
	  <option value=32>32</option>
	  <option value=34>34</option>
	  <option value=36>36</option>
	  <option value=38>38</option>
	  <option value=40>40</option>
	  <option value=42>42</option>
	  <option value=44>44</option>
	  <option value=46>46</option>
	  <option value=48>48</option>
	  <option value=50>50</option>
	  <option value=52>52</option>
	  <option value=54>54</option>
     </select>  
	 </label></th></tr>
	 ";
	}
	else if ($row['taille'] == "couleur")	
	echo "	
	<tr><th><label>Couleur : 
	  <select id='taille' name='taille'>
	  <option value='bleu' selected>Bleu</option>
	  <option value='rose'>Rose</option>
     </select>  
	 </label></th></tr>
	 ";	
	else
	echo "<tr><th></th></tr>";
	}
	echo "<tr><th><input type='button' name='produit' value='Ajouter le produit au panier' onclick='redirection({$_SESSION["id"]},{$id});' /></th></tr>";
	echo "</table><h1>Kits contenant {$titre} :</h1>";
	$query = $db->query("SELECT * FROM kit WHERE id_boutique={$id} or titre_kit IN (SELECT titre_kit FROM kit WHERE id_boutique={$id})" );
	$old="";
	echo "<table><tr><th></th>";
	while ($row = $query->fetch_array()){
	$id_boutique=$row["id_boutique"];	
	if ($row["titre_kit"]!=$old){
	echo "</tr></table><br><div class='kit'>".$row["titre_kit"]." : ".$row["prix_kit"]."€ <input type='submit' name='kit[]' value='Ajouter le kit au panier' onclick='document.location.href=\"commande.php?id={$_SESSION["id"]}&id_kit={$row["id_kit"]}\"' /></div><br><table><tr>";
	}
	echo "<th><img class='img' alt=\"Produit {$id_boutique}\" width=100  src=\"scripts/get-img-boutique.php?id={$id_boutique}\" ><th>".$row['operateur']."</th>";
	$old=$row["titre_kit"];
	}
	echo "</table>";
}else{
	echo "<span id='valid_commande'><a href=\"commande.php?id={$_SESSION["id"]}\"> Voir le panier </a></span>";
	echo "<h1>Boutique</h1><table>";
	$query = $db->query("SELECT id_boutique, titre, prix FROM boutique");
	while ($row = $query->fetch_array()){
	$id_boutique=$row['id_boutique'];
	$debut_lien="<a href=\"boutique.php?id={$row['id_boutique']}\">";	
	$fin_lien="</a>";
	echo "<tr>
	<th class='fin_produit' rowspan='2'>{$debut_lien}<img class='img' alt=\"Produit {$id_boutique}\" width=250  src=\"/aca/scripts/get-img-boutique.php?id={$id_boutique}\" >{$fin_lien}</th>
	<th>".$debut_lien.$row['titre'].$fin_lien."</th>
	</tr>
	<tr>
	<th class='prix'>".$debut_lien.$row['prix']."€".$fin_lien."</th>
	</tr>";
	}	
	echo "</table>";
	$query->free();
	$db->close();
}
	?>
</div>
	<?php require('fragments/bas.php'); ?>
 <script>
 function redirection(id,id_boutique){
 if (typeof(taille) !== 'undefined')
 document.location.href="commande.php?id="+id+"&id_boutique="+id_boutique+"&taille="+taille.value
 else
 document.location.href="commande.php?id="+id+"&id_boutique="+id_boutique
  }
 </script>
  </body>
 </html>
