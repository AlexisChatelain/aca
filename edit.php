<?php 
	$title = "Editer ses informations";
	$css = "connexion.css";
	require('fragments/head.php'); 
	
	if(!$login->isUserLoggedIn()){
		header('Location: /aca');
		die();
	} 
	$id = $_SESSION["id"];
	?>

<body>
<div class="content">
	<?php 
		require('fragments/nav.php');
		require('fragments/nav_img.php'); 
		
		if (isset($_GET['id']) && isset($id)){
			$id_dossier=$_GET['id'];			
			$maj = (isset($_GET["maj"])) ? $_GET["maj"] : NULL;
			require("{$_SERVER['DOCUMENT_ROOT']}/aca/admin/edit_dossier.php");
		}else if (isset($id)){
			require("{$_SERVER['DOCUMENT_ROOT']}/aca/admin/edit_membre.php");
		} else {
			header('Location: /aca/');
			die();
		}
	?>
	
	</div>
	<?php require('fragments/bas.php'); ?>

</body>
</html>