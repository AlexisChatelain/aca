<?php 
	$title = "Album";
	$css = "galerie.css";
	if(isset($_GET["id"])){
		$id = (isset($_GET["id"])) ? $_GET["id"] : NULL;
	}
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

	$query_nom = $db->query("SELECT id_album, nom_album, date_ajout FROM album WHERE id_album=".$id);
	if ($query_nom->num_rows == 0) {
		http_response_code(500);
		die();
	}
	$row = $query_nom->fetch_object();
	$id_album = $row->id_album;
	$date_ajout = $row->date_ajout;
	$nom_album = $row->nom_album;
	$nom_createur_query = $db->query("SELECT nom, prenom FROM utilisateur INNER JOIN album ON album.id_utilisateur = utilisateur.id_utilisateur WHERE id_album=".$id);
	$row2 = $nom_createur_query->fetch_object();
	$nom_createur = $row2->nom." ".$row2->prenom;
	
?>
<?php require('fragments/head.php'); ?>

<body>	
<div class="content">
	<?php require('fragments/nav.php'); ?>
	<?php require('fragments/nav_img.php'); ?>
	<h1><?php echo $nom_album ?></h1>
	<a class="retour-galerie" href="galerie.php">← Retour à la galerie</a>
	
	<p class="right"><?php echo "Crée le {$date_ajout} par {$nom_createur}" ?></p>
	<br>
	<?php if($login->isUserLoggedIn() && $login->isAdmin()) {?>
	<a class="right retour-galerie" href="#" onclick="deleteAlbum(<?php echo $id_album; ?>)" >Supprimer l'album</a>
	<?php } ?>
	<table class="table_galerie">
	
		<?php

			$query = $db->query("SELECT id_photo FROM media WHERE id_album=".$id);
			$n=0;
			while($row = $query->fetch_array()){
				$id_photo = $row[0];
				if ($n%2==0){
					echo "<tr><td class=\"case_galerie\"> <img width=\"250\" class=\"table-image\" alt=\"Image\" id=\"{$id_photo}\" onclick=\"showModal({$id_photo})\" src=\"scripts/get-img.php?id={$id_photo}\" /></td>";
				}else{
					echo "<td class=\"case_galerie\"> <img alt=\"Image\" class=\"table-image\" width=\"250\" id=\"{$id_photo}\" onclick=\"showModal({$id_photo})\" src=\"scripts/get-img.php?id={$id_photo}\" /></td></tr>";
				}
				$n++;
			}
			$query->free();
			$db->close();

		?>
	</table>
	
	<div id="modal" class="modal">
	  <span class="close">&times;</span>
	  <img class="modal-img" id="imgmodal" src="#" alt="Image zoom">
	</div>
</div>
	<?php require('fragments/bas.php'); ?>	

<?php if($login->isUserLoggedIn() && $login->isAdmin()) {?>
<script>
function deleteAlbum(id){
	if(confirm("Voulez-vous vraiment supprimer cet album ?")){
		var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && (this.status == 200 || this.status == 0)) {
				window.location = "/aca/galerie.php";
			}
		};
		xmlhttp.open("GET", "/aca/scripts/delete_album.php/?id="+id, true);
		xmlhttp.send();
	}
}
</script>
<?php }?>
<script>
function showModal(id){
	var modal = document.getElementById("modal");
	var img = document.getElementById(id);
	var modalImg = document.getElementById("imgmodal");
	modal.style.display = "block";
	modalImg.src = img.src;
	var span = document.getElementsByClassName("close")[0];
	span.onclick = function() { 
	  modal.style.display = "none";
	}
}
</script>
</body>
</html>