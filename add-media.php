<?php 
	$title = "Ajouter un album";
	$css = "galerie.css";
	   
	$messages = array();
?>
<?php require('fragments/head.php'); ?>
<?php
	if (!$login->isUserLoggedIn()) {
		header('Location: /aca');
		die();
	}
		
	if(isset($_POST["nb"]) && !empty($_FILES) && count($_FILES)<=10){
		$nb = $_POST["nb"];
		$nom = htmlspecialchars($_POST["nom"]);

		require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");

		$create = true;
		
		for ($i = 1; $i <= $nb; $i++) {
			$imgSize = $_FILES['file']['size'][$i-1];
			if($imgSize > 3000000){
				$messages[] = "Une image est trop lourde (>4Mo).";
				$create = false;
				break;
			}
		}
		
		if($create){
			$result= $db->query("INSERT INTO album (nom_album, id_utilisateur) VALUES('{$nom}', {$_SESSION["id"]})");
			
			for ($i = 1; $i <= $nb; $i++) {
				$imgData = addslashes(file_get_contents($_FILES['file']['tmp_name'][$i-1]));
				$imageProperties = getimageSize($_FILES['file']['tmp_name'][$i-1]);
				$query = "INSERT INTO media(type ,file, id_album) VALUES('{$imageProperties['mime']}', '{$imgData}', (SELECT id_album FROM album WHERE nom_album='{$nom}'))";
				$result = $db->query($query);
			}
			header('Location: /aca/galerie.php');
		}
		$db->close();
	}
	

	
	?>
	
<body>
<div class="content">
	<?php require('fragments/nav.php'); ?>
	<?php require('fragments/nav_img.php'); ?>

	<h1>Ajouter un album</h1>
	<h3> Taille des images limitées à 4Mo </h3>
	<form action="<?=($_SERVER['PHP_SELF'])?>" enctype="multipart/form-data" method="post">
		
		<label>Nom de l'album
			<input type="text" maxlength="20" name="nom" placeholder="Mon Album" required>
		</label>
		<br>
		<label>Nombre d'images à ajouter :
			<input type="range" min="1" max="10" value="1" name="nb" oninput="changeEvent()" id="nb-media">
		</label>
		<output for="nb-media" id="output-nb">1</output>

		<div id="input-media-div">Image n°1<input type="file" name="file[]" accept=".png,.jpg,.jpeg" required></div>
		
		<input type="submit" id="add-media" class="btn" onclick="document.getElementById('loader').style.display='block'" value="Enregistrer">
		<span id="loader" style="display: none;"><img src="/aca/images/loader.gif" alt="loading" /></span>
		<?php
				if ($messages) {
					foreach ($messages as $error) {
						echo $error;
					}
				}
		?>
	</form>

</div>
	<?php require('fragments/bas.php'); ?>

<script>

	function changeEvent(){
		nbRange = document.getElementById('nb-media')
		document.getElementById('output-nb').value =  nbRange.value;
		var container = document.getElementById('input-media-div');

		
		while (container.hasChildNodes()) {
			container.removeChild(container.lastChild);
		}
		
		for (i=0;i<nbRange.value;i++){
			container.appendChild(document.createTextNode("Image n°" + (i+1)));
			var input = document.createElement("input");
			input.type = "file";
			input.name = "file[]";
			input.accept = ".png,.jpg,.jpeg,.gif";
			input.required = true;
			container.appendChild(input);
			container.appendChild(document.createElement("br"));
		}
	}
</script>
</body>
</html>	