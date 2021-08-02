<?php
	require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/ConnexionClass.php");
	$login = new Login();
	if(!$login->isUserLoggedIn() || !$login->isAdmin()){
		header('Location: /aca');
		die();
	}
?>
<h1>Liste des membres</h1>
	
	<table id="membres">
		<thead>
			<tr>
			  <th>Id</th>
			  <th>Nom</th>
			  <th>Mail</th>
			  <th>Rôle</th>
			  <th>Date inscription</th>
			  <th>Dernière connexion</th>
			  <th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php
				require_once("{$_SERVER['DOCUMENT_ROOT']}/aca/config/database.php");
				
				$query = $db->query("SELECT id_utilisateur, nom, prenom, mail, admin, date_inscription, derniere_connexion FROM utilisateur;");
				while($row = $query->fetch_array()){
					$id_utilisateur = $row[0];
					$nom_prenom = $row[1]." ".$row[2];
					$mail = $row[3];
					$admin = $row[4] == 1 ? "Administrateur" : "Utilisateur";
					$date_inscription = $row[5];
					$derniere_connexion = $row[6];
					echo "<tr>
					<td>".$id_utilisateur."</td>
					<td>".$nom_prenom."</td>
					<td>".$mail."</td>
					<td>".$admin."</td>
					<td>".$date_inscription."</td>
					<td>".$derniere_connexion."</td>
					<td><a class=\"delete btn\" onclick=\"deleteUser(".$id_utilisateur.",'".$admin."')\"><i class=\"fa fa-close\"></i></a>
					<a href=\"?edit=".$id_utilisateur."\" class=\"edit btn\"><i class=\"fa fa-edit\"></i></a>
					</td>
					</tr>";
				}
			?>
		</tbody>
		</table>