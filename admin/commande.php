<?php 
	$title = "Resumé commandes";
	$css = "boutique.css";
	require('../fragments/head.php'); 
?>

<body>
<div class="content">


<?php 
require('../fragments/nav.php');
require('../fragments/nav_img.php'); 

if(!$login->isUserLoggedIn() || !$login->isAdmin()){
	header('Location: /aca');
	die();
}

require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
if (isset($_GET["liste"])){
	$old="";
	$query = "SELECT utilisateur.id_utilisateur AS id_user, nom,prenom, titre, commande.taille AS taille, prix, count(commande.id_boutique) AS nb FROM commande, boutique, utilisateur WHERE commande.id_boutique=boutique.id_boutique and commande.id_utilisateur=utilisateur.id_utilisateur and commande.confirmation=1 and reçue=0 GROUP BY commande.id_utilisateur, commande.id_boutique,commande.taille ORDER BY nom, prenom";
	$result = $db->query($query);
	echo "<h1>Vue des commandes par adhérent</h1><br>";
	$entete="<table><tr><th>Nom de l'adhérent</th><th>Nom du produit</th><th>Taille demandée</th><th>Quantité</th><th></th></tr>";
	$contenu="";
	while ($row = $result->fetch_array()){
		if ($row['nom'].$row['prenom']!=$old)
			$bouton="<input type='button' value='Archiver' onclick='document.location.href=\"/aca/admin/commande.php?archive={$row['id_user']}\"' />";
		else
			$bouton="";
		$contenu.="<tr><td>{$row['nom']} {$row['prenom']} </td><td>{$row['titre']}</td><td>{$row['taille']}</td><td>{$row['nb']}</td><td>{$bouton}</td></tr>";
		$old=$row['nom'].$row['prenom'];
	}
	if ($contenu=="")
		echo "<table><tr><td>Aucun adhérent n'a encore validé de commande</td></tr></table>";
	else
		echo $entete.$contenu."</table>";
	$result->free();
}elseif (isset($_GET["archive"])){
	$db->query("UPDATE commande SET reçue=1 WHERE id_utilisateur={$_GET["archive"]} and confirmation=1;");
	header('Location: /aca/admin/commande.php?liste=1');
}else{	
	$query = "SELECT titre, commande.taille AS taille, prix, count(commande.id_boutique) AS nb FROM commande, boutique WHERE commande.id_boutique=boutique.id_boutique and confirmation=1 and reçue=0 GROUP BY commande.id_boutique,commande.taille";
	$result = $db->query($query);
	echo "<h1>Résumé des commandes validées des adhérents</h1><br>";
	$entete="<table><tr><th>Nom du produit</th><th>Taille demandée</th><th>Quantité</th></tr>";
	$contenu="";
	while ($row = $result->fetch_array())
		$contenu.="<tr><td>{$row['titre']}</td><td>{$row['taille']}</td><td>{$row['nb']}</td></tr>";
	if ($contenu=="")
		echo "<table><tr><td>Aucun adhérent n'a encore validé de commande</td></tr></table>";
	else
		echo $entete.$contenu."</table>";
	$result->free();
}
$db->close();
?>



</div>
<?php require('../fragments/bas.php'); ?>
</body>
</html>