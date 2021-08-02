<?php 
	//Titre de la page, utilisé dans fragments/head.php
	$title = "Aquatique Club Amboisien";
?>

<?php 
	//Importation de code HTML utilisé dans toutes les pages (ici l'en-tête avec le charset, titre, css ...) pour pouvoir le modifier plus facilement 
	
	require('fragments/head.php'); ?>

<body>
	<div class="content">
	<?php require('fragments/nav.php'); ?>

	<section class="main">
		<div class="container">
			<div class="text-block">
				<div class="heading-holder">
					<h1>Aquatique Club Amboisien</h1>	
					<p>
					<br>Site non officiel, valide html, développé par Alexis Chatelain, Gaetan Chevalier et Alexis Desaint-Denis, élèves de Polytech Tours.
					<br>juin 2020
					<!--<br>Dans un souci d'économie d'énergie, ce site est inaccessible tous les jours environ entre 2h00 et 7h30 (programmateur mécanique journalier).-->
					</p>					
				</div>
				
			</div>
		</div>
		<img src="/aca/images/background.jpg" alt="Fond d'écran" class="background-img" style="height:100%">
	</section>
	
	</div>

</body>
</html>