<?php
	if($title != "Connexion" & $title != "Inscription"){
		require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/ConnexionClass.php");
		$login = new Login();
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="/aca/css/style.css">
	<?php if (isset($css)) {?>	
	<link rel="stylesheet" href="/aca/css/<?php echo $css;?>">	
	<?php } ?>
	<script src="/aca/responsive.js" ></script>
	<link rel="icon" type="image/x-icon" href="/aca/images/favicon.ico" />
	<?php if (isset($style_with_php)) echo $style_with_php; ?>
</head>