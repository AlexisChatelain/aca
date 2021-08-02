<?php 
	$title = "Membres";
	$css = "connexion.css";
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() || !$login->isAdmin()){
		header('Location: /aca');
		die();
	} else if(isset($_GET["edit"])){
		$id = $_GET["edit"];
	}
	
	require_once('../fragments/membres_head.php'); ?>

<body>
<div class="content">

	<?php require('../fragments/nav.php');
		require('../fragments/nav_img.php'); 
		if (isset($id)){
			require('edit_membre.php');
		} else {
			require('liste_membre.php');
		}
	 ?>


</div>
<?php require('../fragments/bas.php'); ?>

</body>
</html>