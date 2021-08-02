<?php 
	$title = "Galerie";
	$css = "galerie.css";
?>
<?php require('fragments/head.php'); ?>

<body>	
<div class="content">

	<?php require('fragments/nav.php'); ?>
	<?php require('fragments/nav_img.php'); ?>

	<h1>Galerie de l'ACA</h1>
	<table class="table_galerie">
		<?php
			require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

			$query = $db->query("SELECT id_album, nom_album FROM album");
			$n=0;
			while($row = $query->fetch_array()){
				$id_album = $row[0];
				$nom_album = $row[1];
				$getpictureid = $db->query("SELECT id_photo FROM media WHERE id_album={$id_album} LIMIT 1");
				$picture_row = $getpictureid->fetch_object();
				$id_photo = $picture_row->id_photo;
				if ($n%2==0){
					echo "<tr><td class=\"case_galerie\"> 
					<a href=\"album.php?id={$id_album}\"><img alt=\"Album {$id_album}\" src=\"scripts/get-img.php?id={$id_photo}\" /></a>
					<a class=\"lien_galerie\" href=\"album.php?id={$id_album}\">{$nom_album}</a>
					</td>";
				} else {
					echo "<td class=\"case_galerie\"> 
					<a href=\"album.php?id={$id_album}\"><img alt=\"Album {$id_album}\" src=\"scripts/get-img.php?id={$id_photo}\" /></a>
					<a class=\"lien_galerie\" href=\"album.php?id={$id_album}\">{$nom_album}</a>
					</td></tr>";
				}
				$n++;
				$getpictureid->free();
			}
			$query->free();
			$db->close();
		?>
	</table>

	<?php if ($login->isUserLoggedIn()){ ?>	
	<button type="button" id="add-media" onclick="location.href='/aca/add-media.php'" class="btn">Ajouter un album</button>
	<?php }?>
	</div>

	<?php require('fragments/bas.php'); ?>
</body>
</html>	