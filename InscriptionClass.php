<?php
class Registration
{
    public $messages = array();

    public function __construct()
    {
        if (isset($_POST["register"])) {
            $this->registerNewUser();
        }
    }
    private function registerNewUser()
    {
        if (empty($_POST['nom'])) {
            $this->messages[] = "Nom incorrect.";
        } elseif (empty($_POST['prenom'])) {
            $this->messages[] = "Prenom incorrect.";
		} elseif (empty($_POST['mail']) || empty($_POST['confirm_mail'])) {
            $this->messages[] = "Adresse mail incorrecte";
        } elseif (empty($_POST['mdp']) || empty($_POST['confirm_mdp'])) {
            $this->messages[] = "Mot de passe incorrect.";
        } elseif ($_POST['mail'] !== $_POST['confirm_mail']) {
            $this->messages[] = "Les adresses mail ne sont pas les mêmes.";
        } elseif ($_POST['mdp'] !== $_POST['confirm_mdp']) {
            $this->messages[] = "Les mots de passes ne sont pas les mêmes.";
        } elseif (strlen($_POST['mdp']) < 8) {
            $this->messages[] = "La taille minimum du mot de passe est de 8 caractères.";
        } elseif (strlen($_POST['nom']) > 50 || strlen($_POST['nom']) < 2 || strlen($_POST['prenom']) > 50 || strlen($_POST['prenom']) < 2) {
            $this->messages[] = "La taille du nom et prénom va de 2 à 50 caractères.";
        } else {
		
			require('config/database.php');
            $mdp = $_POST['mdp'];
			
			//hash du mdp
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
						
			if (!$db->connect_errno) {

				/*protection caractères spéciaux, etc pour la requête
				htmlentities convertit en caractères éligibles html, ENT_QUOTES convertit "" en ''
				*/
                $nom = $db->real_escape_string(htmlentities($_POST['nom'], ENT_QUOTES));
				$prenom = $db->real_escape_string(htmlentities($_POST['prenom'], ENT_QUOTES));
                $email = $db->real_escape_string(htmlentities($_POST['mail'], ENT_QUOTES));

                $query_check_email = $db->query("SELECT * FROM utilisateur WHERE mail = '" . $email . "';");

                if ($query_check_email->num_rows == 1) {
                    $this->messages[] = "Email déja utilisé.";
                } else {
					$code="";
					for ($i=0; $i<15; $i++)
						$code.= chr(rand(65, 90));
					$query_new_user = $db->query("INSERT INTO utilisateur (confirmation, nom, prenom, mail, mdp, admin) VALUES('".$code."', '" . $nom . "', '" . $prenom . "', '" . $email . "', '" . $mdp_hash . "',False);");
					if ($query_new_user) {
						require('config/mail.php');
						$adress = array($email, $prenom, $nom);
						$subject = 'Confirmation de votre adresse mail et de votre inscription';
						$body = '<html>
						<head>
						<title>Confirmation de votre adresse mail</title>	   
						<meta charset="UTF-8" />
						</head>
						<body>	  
						<img src="http://'.$_SERVER['HTTP_HOST'].'/aca/images/logo.png" alt="(logo du club)">
						<p>Bonjour '.$prenom.',<br>
						Ceci est un message automatique envoyé par votre club de natation.<br>
						Voici le code pour confirmer votre inscription et vous assurer que votre adresse mail est correcte : '.$code.'<br>
						A bientôt dans l\'eau, <br>
						Merci de ne pas répondre à ce mail :)<br>
						</p>
						</body>
						</html>';
						$alt='(logo du club) 
						Bonjour '.$prenom.',
						Ceci est un message texte automatique envoyé par votre club de natation.
						Voici le code pour confirmer votre inscription et vous assurer que votre adresse mail est correcte : '.$code.
						'A bientôt dans l\'eau,
						Merci de ne pas répondre à ce mail :)';
						sendMail($construction_mail, $adress, $subject, $body, $alt);
						header('Location: /aca/confirmation_compte.php?mail='.$email);
					} else {						
						$this->messages[] = "Erreur interne, veuillez réessayer plus tard.";
					}
					$db->close();
                }

            } else {
                $this->messages[] = "Erreur interne, veuillez réessayer plus tard.";
            }
		}
	}
}