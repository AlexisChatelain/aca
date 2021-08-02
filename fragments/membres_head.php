<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<title><?php echo $title ?></title>
	<link rel="stylesheet" href="/aca/css/style.css">
	<?php if (isset($css) && isset($id)) {?>	
		<link rel="stylesheet" href="/aca/css/<?php echo $css;?>">	
	<?php } ?>
	<link rel="icon" type="image/x-icon" href="/aca/images/favicon.ico" />
	<script src="/aca/responsive.js"></script>

	<!-- Librairie Datatables pour le tableau dynamique-->
	<script src="/aca/lib/calendar/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
	<!-- FontAwesome qui ajoute des icones comme ceux de le tableau (logo edition)-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
	  $(function () {
		$('#membres').dataTable( {
			"language": {
				"sProcessing":     "Traitement en cours...",
				"sSearch":         "Rechercher&nbsp;:",
				"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
				"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix":    "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst":      "Premier",
					"sPrevious":   "Pr&eacute;c&eacute;dent",
					"sNext":       "Suivant",
					"sLast":       "Dernier"
				},
				"oAria": {
					"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				},
				"select": {
						"rows": {
							_: "%d lignes séléctionnées",
							0: "Aucune ligne séléctionnée",
							1: "1 ligne séléctionnée"
						} 
				}
			},
			rowReorder: {
				selector: 'td:nth-child(2)'
			},
			responsive: true
		} );
	  })
	</script>
	
	<?php if(!isset($id)){ ?>
	<script>
	function deleteUser(id, role){
		if(role == "Administrateur"){
			alert("Vous ne pouvez pas supprimer ce membre !")
			return;
		}else if(confirm("Voulez-vous vraiment supprimer ce membre ?")){
			var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && (this.status == 200 || this.status == 0)) {
					location.reload();
				}
			};
			xmlhttp.open("GET", "/aca/scripts/delete_membre.php?id="+id, true);
			xmlhttp.send();
		}
	}	
	function delete_dossier(id){
		if(confirm("Voulez-vous vraiment supprimer cet adhérent ?")){
			var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && (this.status == 200 || this.status == 0)) {
					location.reload();
				}
			};
			xmlhttp.open("GET", "/aca/scripts/delete_dossier.php?id="+id, true);
			xmlhttp.send();
		}
	}
	
	function valid_dossier(id){
			var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && (this.status == 200 || this.status == 0)) {
					location.reload();
				}
			};
			xmlhttp.open("GET", "/aca/scripts/valid_dossier.php?id="+id, true);
			xmlhttp.send();
	}
	</script>
	<?php }?>
</head>