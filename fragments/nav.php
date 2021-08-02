<header id="header">
	<div class="container">
		<div class="logo"><a href="/aca/"><img src="/aca/images/logo.png" alt="Logo du club"></a></div>
		<nav id="nav" class="nav">
			<div class="open-menu">
				<a onclick="openNav()" href="#" class="nav-open"><span></span></a>
			</div>
			<a href=<?=$login->isUserLoggedIn() ? "/aca/espace-utilisateur.php" : "/aca/connexion.php" ?> class="btn btn-primary rounded"><?=$login->isUserLoggedIn() ? "Espace utilisateur" : "Connexion" ?></a>
			<div class="nav-drop">
				<ul>
					<li><a href="/aca/">Accueil</a></li>
					<li><a href="/aca/galerie.php">Galerie</a></li>
					<li><a href="/aca/calendrier.php">Calendrier</a></li>					
					<li><a href="/aca/planning.php">Planning</a></li>
					<li><a href="/aca/plan.php">Plan</a></li>
					<li><a href="/aca/contact.php">Contact</a></li>
				</ul>
			</div>
		</nav>
	</div>
</header>