<?php 
	$title = "Incompatibilité";
?>
<?php require('fragments/head.php'); ?>

<body>
<div class="content">
	
	<?php 
		require('fragments/nav.php'); 
		require('fragments/nav_img.php'); 
		echo "Nous sommes vraiment navrés, votre navigateur (Internet Explorer ou ancienne version de Microsoft Edge) est incompatible pour l'édition du dossier d'inscription.<br>
		Merci de réessayer sur un autre navigateur ( 
		<a href='https://www.mozilla.org/fr/firefox/new/'> Mozilla Firefox </a>
		, 
		<a href='https://www.google.fr/chrome'> Google Chrome </a>
		,
		<a href='https://www.microsoft.com/fr-fr/edge'> nouvelle version de Microsoft Edge </a>
		, etc.)";
	?>
</div>
<?php require('fragments/bas.php'); ?>
</body>
</html>	